<?php
  // session_start(); // Start the session (important for checking session variables)
  include '../admin.php';
  $admin = new Admin();
  // $admin->handleRedirect(); // Call handleRedirect to check login status and redirect

  // Removing this causes website to proceed to index.php despite isLoggedIn from admin.php returning false
  if(!$admin->isLoggedIn()) {
      header("Location: ../login.php");
      exit();
  }
  if($admin->isLoggedIn() && $admin->session_role == 'admin') {
      header("Location: ../admin/admindashboard.php");
      exit();
  }

  // // Retrieve the users_id from the session
  // $users_id = $_SESSION['user_id'];

  // // Fetch the houses_id from the tenants table using the users_id
  // $sql_houses_id = "SELECT house_id FROM tenants WHERE users_id = '$users_id'";
  // $result_houses_id = $admin->conn->query($sql_houses_id);

  // // Check if a house was found
  // if ($result_houses_id && $result_houses_id->num_rows > 0) {
  //   $row_houses_id = $result_houses_id->fetch_assoc();
  //   $houses_id = $row_houses_id['house_id'];
  // } else {
  //   // Handle the case where no house was found
  //   $houses_id = null;
  // }

  // Retrieve the users_id from the session
  $users_id = $_SESSION['user_id'];

  // Fetch the houses_id from the houseaccounts table based on the house_id from tenants table
  $sql_houses_id = "SELECT houseaccounts.houses_id 
  FROM tenants 
  INNER JOIN houseaccounts ON tenants.house_id = houseaccounts.houses_id 
  WHERE tenants.users_id = '$users_id'";
  $result_houses_id = $admin->conn->query($sql_houses_id);

  // Check if a house was found
  if ($result_houses_id && $result_houses_id->num_rows > 0) {
    $row_houses_id = $result_houses_id->fetch_assoc();
    $houses_id = $row_houses_id['houses_id'];
  } else {
    // Handle the case where no house was found
    $houses_id = null;
  }


  // Check if the form is submitted for adding a new payment
  if(isset($_POST['add_payment'])) {
    // Get the payment data from the form
    $name = trim(htmlspecialchars($_POST['name']));
    $amount = trim(htmlspecialchars($_POST['amount']));
    $paymentDate = htmlspecialchars($_POST['payment_date']);

    // File upload handling
    $file = $_FILES['payment_file']; // Get the uploaded file
    $fileName = $file['name']; // Get the file name
    $fileTmpName = $file['tmp_name']; // Get the temporary file path
    $fileError = $file['error']; // Get any errors
    $fileDestination = '../uploads/' . $fileName; // Set the destination folder
      

    // Check for upload errors
    if ($fileError === UPLOAD_ERR_OK) {
      $uploadDir = '../uploads/';
      // Ensure the upload directory exists
      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
      }

      // $fileDestination = $uploadDir . basename($fileName); // Set the destination folder

      // Generate a unique identifier (using uniqid) to append to the file name
      $uniqueIdentifier = uniqid();

      // Extract file extension
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      
      // Generate a new unique file name with the original file name and extension
      $newFileName = basename($fileName, '.' . $fileExtension) . '_' . $uniqueIdentifier . '.' . $fileExtension;


      $fileDestination = $uploadDir . $newFileName; // Set the destination folder

      // Move the uploaded file to the destination folder
      if (move_uploaded_file($fileTmpName, $fileDestination)) {
          // Call the addPayment method to add the new payment with file path
          $added = $admin->addPayment($name, $amount, $houses_id, $paymentDate, $fileDestination);
          if ($added) {
            // Payment added successfully, you can display a success message here if needed
            header("Location: payments.php?payment_added=1");
            exit();
          } else {
            // Error occurred while adding payment, display an error message or handle as needed
            $_SESSION['error_message'] = "User not on Tenant List";
            header("Location: payments.php?payment_added=1");
            echo "Error occurred while adding payment.";
            exit();
          }
      } else {
          // Handle file move errors
          echo "File upload failed. Unable to move the file to the target directory.";
      }
    } else {
      // Handle file upload errors
      echo "File upload failed with error code: " . $fileError;
    }
  }

  $sql = "
  SELECT payments.* 
  FROM payments
  INNER JOIN tenants ON payments.tenants_id = tenants.id
  WHERE tenants.users_id = '$admin->session_id'
  ";
  $result = $admin->conn->query($sql);

  $sql_name = "SELECT * FROM users WHERE id = '$admin->session_id'";
  $result_name = $admin->conn->query($sql_name);

  if ($result_name && $result_name->num_rows > 0) {
    // Fetch the data from the result set
    $row_name = $result_name->fetch_assoc();
    $Firstname = $row_name['firstname'];
    $Middlename = $row_name['middlename'];
    $Lastname = $row_name['lastname'];
  } else {
    // Handle the case where no user with the specified ID is found
    echo "No user found with the specified ID.";
  }

  // Get the tenant information
  $sql_tenant = "SELECT t.date_start, t.date_preferred, t.date_end, t.house_id, t.id AS tenant_id, h.price 
  FROM tenants t
  INNER JOIN houses h ON t.house_id = h.id 
  WHERE t.users_id = '$users_id'";
  $result_tenant = $admin->conn->query($sql_tenant);

  if ($result_tenant->num_rows > 0) {
    $row_tenant = $result_tenant->fetch_assoc();
    $date_start = $row_tenant['date_start'];
    $date_preferred = $row_tenant['date_preferred'];
    $date_end = $row_tenant['date_end'];
    $house_id = $row_tenant['house_id'];
    $tenant_id = $row_tenant['tenant_id'];
    $price = $row_tenant['price'];

    // Determine the base date for calculation
    $baseDate = $date_preferred ? new DateTime($date_preferred) : new DateTime($date_start);

    // Calculate the interval to the end date if it exists, otherwise to the current date
    if ($date_end) {
    $endDate = new DateTime($date_end);
    $interval = $baseDate->diff($endDate);
    } else {
    $currentDate = new DateTime();
    $interval = $baseDate->diff($currentDate);
    }

    // Calculate the number of months passed
    $monthsPassed = $interval->y * 12 + $interval->m;

    // Calculate the total rent due
    $totalRentDue = $monthsPassed * $price;

    // Fetch payment amounts for the specific tenant, considering only payments on or after the base date
    $baseDateString = $baseDate->format('Y-m-d');
    $sql_payments = "SELECT SUM(amount) AS total_payments
          FROM payments
          WHERE tenants_id = $tenant_id
            AND houses_id = $house_id
            AND date_payment >= '$baseDateString'
            AND approval = 'true'";
    $result_payments = $admin->conn->query($sql_payments);
    $row_payments = $result_payments->fetch_assoc();
    $totalPayments = $row_payments['total_payments'];

    // Count the number of tenants sharing the same house
    $sql_tenants_count = "SELECT COUNT(*) AS tenants_count
              FROM tenants
              WHERE house_id = $house_id";
    $result_tenants_count = $admin->conn->query($sql_tenants_count);
    $row_tenants_count = $result_tenants_count->fetch_assoc();
    $tenants_count = $row_tenants_count['tenants_count'];

    // Calculate the rent due per tenant
    $rentDuePerTenant = $totalRentDue / $tenants_count;

    // Calculate the current balance for the current tenant
    $balance = $rentDuePerTenant - $totalPayments;

    // Display the balance
    // echo "Total Balance: $" . number_format($balance, 2);
    // echo "<br>Total Rent Due: $" . number_format($rentDuePerTenant, 2);
    // echo "<br>Total Payments: $" . number_format($totalPayments, 2);

    // Monthly Balance Calculation
    $currentMonthStart = (new DateTime('first day of this month'))->format('Y-m-d');
    $currentMonthEnd = (new DateTime('last day of this month'))->format('Y-m-d');

    // Calculate the monthly rent due for the tenant
    $monthlyRentDue = $price;

    // Fetch approved payment amounts for the current month
    $sql_monthly_payments = "SELECT SUM(amount) AS total_monthly_payments
      FROM payments
      WHERE tenants_id = $tenant_id
        AND houses_id = $house_id
        AND date_payment >= '$currentMonthStart'
        AND date_payment <= '$currentMonthEnd'
        AND approval = 'true'";
    $result_monthly_payments = $admin->conn->query($sql_monthly_payments);
    $row_monthly_payments = $result_monthly_payments->fetch_assoc();
    $totalMonthlyPayments = $row_monthly_payments['total_monthly_payments'];

    // Calculate the balance for the current month
    $monthlyBalance = $monthlyRentDue - $totalMonthlyPayments;

    // Ensure the balance does not go below 0
    $monthlyBalance = max(0, $monthlyBalance);

    // Display the monthly balance
    // echo "<br>Monthly Balance: $" . number_format($monthlyBalance, 2);
    // echo "<br>Monthly Rent Due: $" . number_format($monthlyRentDue, 2);
    // echo "<br>Total Monthly Payments: $" . number_format($totalMonthlyPayments, 2);

  } else {
    echo "No tenant information found for the current user.";
  }





  $pageTitle = 'Payments Page'; 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <?php if ($pageTitle == 'Home Page' || $pageTitle == 'Chat Page'): ?>
        <link rel="stylesheet" href="asset/user.css">
        <link rel="icon" type="image/x-icon" href="asset/Renttrack pro no word.png">
    <?php else: ?>
        <link rel="stylesheet" href="../asset/user.css">
        <link rel="icon" type="image/x-icon" href="../asset/Renttrack pro no word.png">
    <?php endif; ?>
    <!-- <link rel="stylesheet" href= "../asset/user.css"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <title><?php echo isset($pageTitle) ? $pageTitle : 'Default Title'; ?></title>
  </head>
  <body>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    <nav class="navbar navbar-expand-lg navbar-light flex-column py-0" id="navbar" style="background-color: #3A583C;">
    <!-- <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../asset/Renttrack pro no word.png" class="img-fluid" alt="..." width="120" height="96">
        </a>
    </div> -->

    <div class="container-fluid mb-3 mt-3" id="navbarbar">
    <div class="row mx-auto w-65 d-flex align-items-center">
    
    




        <!-- Left-aligned image -->
        <div class="col d-flex align-items-center">
            <a class="navbar-brand py-0" href="../index.php">
                <img src="../asset/Renttrack pro no word_2.png" id="userpiclogo" class="img-fluid" alt="..." style="height: 50px;">
            </a>
        </div>
        
        <!-- Right-aligned navigation links and icons -->
        <div class="col d-flex justify-content-end" id="navnav">
        <button class="navbar-toggler" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
        </button>


        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="payments.php">Payment</a>
        <a class="dropdown-item" href="../info.php">Info</a>
        <a class="dropdown-item" href="../profile_user.php">Profile</a>
        <a class="dropdown-item" href="../chat_user.php">Chat</a>
        <a class="dropdown-item" href="../logout.php">Log Out</a>
    </div>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="payments.php">
                            <p class="mb-0">Payment</p>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="../info.php">
                            <p class="mb-0">Info</p>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="../profile_user.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="../chat_user.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext"href="../logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

</nav>


<div class="row mx-auto w-65 d-flex align-items-center col main content" id="paymentwhole">
    <div class="mx-auto">
    <div class="card-header text-center" style="font-size: 1.2rem; font-weight: bold;">Payment</div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="row">
                  <div class="col-sm-6">
                    <?php 
                    echo "<p class='fw-bolder'>Monthly Balance: &#8369;" . number_format($monthlyBalance, 2) . "</p>";
                    echo "<p class='fw-bolder'>Monthly Rent Due: &#8369;" . number_format($monthlyRentDue, 2) . "</p>";
                    echo "<p class='fw-bolder'>Total Payments: &#8369;" . number_format($totalPayments, 2) . "</p>";
                    ?>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-sm-end align-items-sm-end">
                    <button type="button" class="btn receipt" id="new_payment" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fa fa-plus"></i> Create Receipt
                    </button>
                  </div>
                </div>
            </div>
            <div class="table-container">
            <div class="table-responsive"">
                <table class="table table-striped table-bordered border border-5">
                    <thead class="">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Image</th>
                            <th scope="col">Approved</th>
                            <th scope="col">Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Check if there are any rows in the result set
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["name"] . "</td>"; // actual column name from your database
                                echo "<td>" . $row["amount"] . "</td>"; // actual column name from your database
                                echo "<td><img src='" . $row["filepath"] . "' alt='Receipt' class='img-fluid' style='max-width: 150px; height: 150px;'></td>";
                                echo "<td>" . ($row["approval"] == "true" ? "APPROVED" : "UNAPPROVED") . "</td>";
                                echo "<td>" . $row["date_payment"] . "</td>"; // actual column name from your database
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No payments found</td></tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Create Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="paymentForm" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <?php
            if ($houses_id == null) {
              $_SESSION['error_message'] = "User/Tenant has no House, Contact Admin";
              echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
              unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
              echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
              // Unset the error message after displaying it
              echo '<script>var paymentModal = new bootstrap.Modal(document.getElementById("paymentModal"), { keyboard: false });paymentModal.show();</script>';
              unset($_SESSION['error_message']);
            } else if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
              echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
              echo '<script>var paymentModal = new bootstrap.Modal(document.getElementById("paymentModal"), { keyboard: false });paymentModal.show();</script>';
              unset($_SESSION['success_message']);
            }
          ?>
          <div class="mb-3">
            <!-- <input type='hidden' name='housesid' value="<?php echo htmlspecialchars($houses_id); ?>">
            <label for="paymentName" class="form-label">Name</label>
            <input type="text" class="form-control" id="paymentName" name="name"> -->
            <label for="housesId" class="form-label">House ID</label>
            <input type="text" class="form-control" id="housesId" name="housesid" value="<?php echo htmlspecialchars($houses_id); ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="paymentName" class="form-label">Name</label>
            <input type="text" class="form-control" id="paymentName" name="name" value="<?php echo $Firstname . " " . $Middlename . " " . $Lastname ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="paymentAmount" class="form-label">Amount</label>
            <input type="text" class="form-control" id="paymentAmount" name="amount">
          </div>
          <div class="mb-3">
            <label for="paymentDate" class="form-label">Payment Date</label>
            <input type="date" class="form-control" id="paymentDate" name="payment_date">
          </div>
          <div class="mb-3">
            <label for="paymentFile" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="paymentFile" name="payment_file">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submitBtn" name="add_payment">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
          </div>
<!-- Include jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const paymentForm = document.getElementById("paymentForm");

    paymentForm.addEventListener("submit", function(event) {
      // No need to prevent default form submission
      // No need for AJAX submission

      // Form will be submitted traditionally
    });
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var housesIdInput = document.getElementById('housesId');
    var submitBtn = document.getElementById('submitBtn');

    // Function to check the value of housesId and enable/disable the submit button
    function checkHousesId() {
        if (!housesIdInput.value.trim()) {
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }

    // Initial check when the page loads
    checkHousesId();

    // Add an event listener to check the value whenever it changes
    housesIdInput.addEventListener('input', checkHousesId);
});
</script>

<!-- <script>
  // JavaScript code to handle form submission
  document.addEventListener("DOMContentLoaded", function() {
    // Get the payment form
    const paymentForm = document.getElementById("paymentForm");

    // Add event listener for form submission
    paymentForm.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent default form submission

      // Perform form validation if needed

      // Submit the form via AJAX
      const formData = new FormData(paymentForm); // Create FormData object
      fetch("payments.php", { // Send form data to payments.php
        method: "POST",
        body: formData
      })
      .then(response => {
        if (response.ok) {
          // Handle successful form submission
          // For example, close the modal and reload the page
          $('#paymentModal').modal('hide');
          location.reload();
        } else {
          // Handle errors if any
          console.error("Error:", response.statusText);
        }
      })
      .catch(error => {
        console.error("Error:", error);
      });
    });
  });
</script> -->


<?php include '../regular/includes/footer.php'; ?>
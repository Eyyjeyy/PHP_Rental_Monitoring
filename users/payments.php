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
            AND date_payment >= '$baseDateString'";
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
    echo "Current Balance: $" . number_format($balance, 2);
    echo "<br>Total Rent Due: $" . number_format($rentDuePerTenant, 2);
    echo "<br>Total Payments: $" . number_format($totalPayments, 2);
  } else {
    echo "No tenant information found for the current user.";
  }





    
?>

<?php include '../regular/includes/header_user.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light flex-column py-0" style="background-color: #527853;">
    <!-- <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../asset/Renttrack pro no word.png" class="img-fluid" alt="..." width="120" height="96">
        </a>
    </div> -->
    <div class="container-fluid">
        <a class="navbar-brand py-0" href="#">
            <img src="../asset/Renttrack pro no word_2.png" class="img-fluid" alt="..." style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse align-self-stretch" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">
                        <p class="mb-0">HOME</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <p class="mb-0">PAYMENTS</p>
                    </a>
                </li>
                <li class="d-none nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="col main content px-lg-5">
    <div class="card mt-5">
        <div class="card-header">Header</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-12">
                  <button type="button" class="btn btn-primary float-end" id="new_payment" data-bs-toggle="modal" data-bs-target="#paymentModal">
                      <i class="fa fa-plus"></i> Create Receipt
                  </button>
                  <p>Balance: </p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered border-1">
                    <thead class="">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Image</th>
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
                                echo "<td><img src='" . $row["filepath"] . "' alt='Receipt' class='img-fluid' style='max-width: 200px; height: auto;'></td>";
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
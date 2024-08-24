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
    if($admin->isLoggedIn() && $admin->session_role == 'user') {
        header("Location: ../index.php");
        exit();
    }  

    // Check if the form is submitted for adding a new tenant
    if(isset($_POST['add_tenant'])) {
        // Get the tenant data from the form
        list($houseid, $housename) = explode('|', $_POST['house_name']);
        list($users_id, $users_username) = explode('|', $_POST['user_name']);
        $preferreddate = ($_POST['preferreddate']);
        // $firstname = htmlspecialchars($_POST['firstname']);
        // $middlename = htmlspecialchars($_POST['middlename']);
        // $lastname = htmlspecialchars($_POST['lastname']);
        $contactno = htmlspecialchars($_POST['contactno']);
        // $users_username = htmlspecialchars($_POST['user_name']);
        // $housename = htmlspecialchars($_POST['house_name']);
        // $houseid = htmlspecialchars($_POST['house_id']);
        $registerdate = htmlspecialchars($_POST['registerdate']);
        // Validate contact number length
        if (preg_match('/^\d{10,11}$/', $contactno)) {
            // Call the addTenant method to add the new tenant
            $added = $admin->addTenant($contactno, $users_id, $users_username, $houseid, $housename, $registerdate, $preferreddate);
            if ($added["success"]) {
                // Tenant added successfully, you can display a success message here if needed
                header("Location: admintenants.php?tenant_added=1");
                exit();
            } else {
                // Error occurred while adding tenant, store the error message in a session variable
                $_SESSION['error_message'] = $added["message"];
                header("Location: admintenants.php?error=add");
                exit();
            }
        } else {
            // Invalid contact number length, store the error message in a session variable
            $_SESSION['error_message'] = "Contact number must be 10-11 digits long.";
            header("Location: admintenants.php?error=invalid_contactno");
            exit();
        }
    }
    // Check if there's an error message stored in the session
    if(isset($_SESSION['error_message'])) {
        // Display the error message as an alert
        echo '<div class="alert alert-danger mb-0" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        // Unset the session variable to clear the error message
        unset($_SESSION['error_message']);
    }



    // Check if the form is submitted for editing a tenant
    if (isset($_POST['edit_tenant'])) {
        list($houseid, $housecategory) = explode('|', $_POST['house_data']);
        $tenant_id = htmlspecialchars($_POST['tenant_id']);
        $firstname = htmlspecialchars($_POST['firstname']);
        $middlename = htmlspecialchars($_POST['middlename']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $contactno = htmlspecialchars($_POST['contactno']);
        // $houseid = htmlspecialchars($_POST['category_id']);
        // $housecategory = htmlspecialchars($_POST['category_id']);
        $registerdate = htmlspecialchars($_POST['registerdate']);

        // Validate contact number length
        if (preg_match('/^\d{10,11}$/', $contactno)) {
            // Call the updateTenant method to update the tenant
            $updated = $admin->updateTenant($tenant_id, $firstname, $middlename, $lastname, $contactno, $houseid, $housecategory, $registerdate);
            if ($updated) {
                // Tenant updated successfully, redirect with success message
                header("Location: admintenants.php?tenant_updated=1");
                exit();
            } else {
                // Error occurred while updating tenant, display an error message or handle as needed
                echo "Error occurred while updating tenant.";
            }
        } else {
            // Invalid contact number length, display an error message or handle as needed
            $_SESSION['error_message'] = "Contact number must be 10-11 digits long.";
            header("Location: admintenants.php?error=update");
            exit();
        }
    }

    // Check if the form is submitted for deleting a tenant
    if(isset($_POST['delete_tenant'])) {
        // Get the tenant ID to be deleted
        $tenantid = $_POST['tenantid'];
        // Call the deleteTenant method to delete the tenant
        $deleted = $admin->deleteTenant($tenantid);
        if($deleted) {
            // Tenant deleted successfully, you can display a success message here if needed
            // echo "Tenant deleted successfully.";
            header("Location: admintenants.php?tenant_deleted=1");
        } else {
            // Error occurred while deleting tenant, display an error message or handle as needed
            echo "Error occurred while deleting tenant.";
        }
    }
    
    // $sql = "SELECT * FROM tenants";
    // $sql = "SELECT tenants.*
    // FROM tenants
    // INNER JOIN houses 
    // INNER JOIN categories ON houses.category_id = categories.id
    // ";



    // Get sort column and direction from query parameters
    $sortColumn = isset($_GET['column']) ? $_GET['column'] : 'id';
    $sortDirection = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';

    // Ensure the sort column is one of the allowed columns to prevent SQL injection
    $allowedColumns = ['id', 'fname', 'mname', 'lname', 'users_username', 'house_category', 'date_start'];
    if (!in_array($sortColumn, $allowedColumns)) {
        $sortColumn = 'id';
    }

    // Determine the next sort direction
    $nextSortDirection = $sortDirection === 'ASC' ? 'desc' : 'asc';

    // Determine the arrow symbol based on the current sort direction
    $arrow = $sortDirection === 'ASC' ? '↑' : '↓';

    $sql = "SELECT tenants.*, houses.house_name AS house_name
            FROM tenants
            LEFT JOIN houses ON tenants.house_id = houses.id
            ORDER BY $sortColumn $sortDirection;";

    $result = $admin->conn->query($sql);
    // $sql_option = "SELECT houses.id, houses.house_number, categories.name AS category_name FROM houses 
    // INNER JOIN categories ON categories.id = houses.category_id 
    // ORDER BY houses.id ASC";
    // $sql_option = "SELECT houses.id, houses.house_number, houses.category_id AS houses_id FROM houses ORDER BY houses.id ASC";
    
    // $sql_option = "SELECT houses.id, houses.house_number, houses.category_id AS house_catid, categories.name AS category_name FROM houses
    // INNER JOIN categories ON categories.id = houses.category_id
    // ORDER BY houses.id ASC";
    // $sql_option = "SELECT houses.*, categories.name AS house_name FROM houses INNER JOIN categories ON categories.id = houses.category_id";
    // $result_option = $admin->conn->query($sql_option);

    $sql_option = "SELECT houses.*, categories.name AS category_name FROM houses 
    INNER JOIN categories ON categories.id = houses.category_id";
    $result_option = $admin->conn->query($sql_option);

    $user_option = "SELECT * FROM users WHERE role = 'user'";
    $user_result = $admin->conn->query($user_option);


    $sql_option2 = "SELECT houses.*, categories.name AS category_name FROM houses INNER JOIN categories ON categories.id = houses.category_id";
    $result_option2 = $admin->conn->query($sql_option);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "admintenants";
?>

  
    <div class="container-fluid">
        <div class="row">
        <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" id="tableheader"> 
                            <button class="btn btn-primary float-end table-buttons-update" id="new_tenant"><i class="fa fa-plus"></i> New Tenant</button>
                        </div>
                    </div>
                    <div class="table-responsive"  id="tablelimiter">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">
                                        <a href="?column=fname&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Firstname
                                            <?php echo $sortColumn === 'fname' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=mname&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Middlename
                                            <?php echo $sortColumn === 'mname' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=lname&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Lastname
                                            <?php echo $sortColumn === 'lname' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">
                                        <a href="?column=users_username&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Username
                                            <?php echo $sortColumn === 'users_username' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=house_category&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            House
                                            <?php echo $sortColumn === 'house_category' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=date_start&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Date
                                            <?php echo $sortColumn === 'date_start' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['mname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['users_username']) . "</td>";
                                        echo "<td>Category: " . htmlspecialchars($row['house_category']) . "<br>House Name: " . htmlspecialchars($row['house_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['date_start']) . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a delete button for each record
                                        echo "<form method='POST' action='admintenants.php' class='float-xl-end align-items-center' style='height:100%;'>";
                                        echo "<input type='hidden' name='tenantid' value='" . $row['id'] . "'>";
                                        echo "<button type='submit' name='delete_tenant' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a update button for each record
                                        echo "<input type='hidden' name='tenantid' value='" . $row['id'] . "'>";
                                        echo "<button type='button' class='btn btn-primary update-tenant-btn float-xl-start table-buttons-update' data-id='" . $row['id'] . "' data-tenantname='" . htmlspecialchars($row['fname']) . "' data-middlename= '" . htmlspecialchars($row['mname']) . "' data-lastname= '" . htmlspecialchars($row['lname']) . "' data-contactno= '" . htmlspecialchars($row['contact']) . "' data-registerdate= '" . htmlspecialchars($row['date_start']) . "' style='width: 80px;'>Update</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No tenants found</td></tr>";
                                }
                                $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- New Tenants Modal -->
                <div class="modal fade" id="newTenantModal" tabindex="-1" aria-labelledby="newTenantModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newtenantModalLabel">New Tenant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newTenantForm" class="d-flex flex-row" method="POST" action="admintenants.php">
                                <div class="row">
                                    <!-- <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middlename" name="middlename" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                                        </div>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Contact #</label>
                                            <input type="text" class="form-control" id="contactno" name="contactno" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4"> -->
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Apartment Name, Price, Category</label>
                                            <!-- <select class="form-select" id="house" name="house_name" required onchange="updateHouseId()"> -->
                                            <select class="form-select" id="house" name="house_name" required>
                                                <!-- <option value="3">3</option> -->
                                                <?php
                                                    // Fetch houses from the database
                                                    

                                                    // Check if houses exist
                                                    if ($result_option->num_rows > 0) {
                                                        // Output options for each category
                                                        while ($row_option = $result_option->fetch_assoc()) {
                                                            // echo "<option value='" . $row_option['id'] . "'>" . $row_option['category_name'] . "</option>";

                                                            $house_info = $row_option['id'] . "|" . $row_option['category_name'];
                                                            echo "<option value='" . htmlspecialchars($house_info) . "'>" . htmlspecialchars($row_option['house_name']) . " " . htmlspecialchars($row_option['price']) . " " . htmlspecialchars($row_option['category_name']) . "</option>";
                                                            
                                                            // echo "<option value='" . htmlspecialchars($row_option['house_name']) . "' data-house-id='" . $row_option['id'] . "'>" . htmlspecialchars($row_option['house_number']) . " " . htmlspecialchars($row_option['category_name']) . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No houses found</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Users</label>
                                            <!-- <select class="form-select" id="house" name="house_name" required onchange="updateHouseId()"> -->
                                            <select class="form-select" id="user" name="user_name" required>
                                                <!-- <option value="3">3</option> -->
                                                <?php
                                                    // Fetch houses from the database
                                                    

                                                    // Check if houses exist
                                                    if ($user_result->num_rows > 0) {
                                                        // Output options for each category
                                                        while ($row_users = $user_result->fetch_assoc()) {
                                                            // echo "<option value='" . $row_option['id'] . "'>" . $row_option['category_name'] . "</option>";

                                                            $user_info = $row_users['id'] . "|" . $row_users['username'];
                                                            echo "<option value='" . htmlspecialchars($user_info) . "'>" . "ID: " . htmlspecialchars($row_users['id']) . " " . htmlspecialchars($row_users['username']) . "</option>";
                                                            
                                                            // echo "<option value='" . htmlspecialchars($row_option['house_name']) . "' data-house-id='" . $row_option['id'] . "'>" . htmlspecialchars($row_option['house_number']) . " " . htmlspecialchars($row_option['category_name']) . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No houses found</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <input type="hidden" id="house_id" name="house_id" value=""> -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <?php
                                                date_default_timezone_set('Asia/Manila');

                                                // Get the current date in the Philippines time zone
                                                $currentDate = date('Y-m-d');
                                            ?>
                                            <label for="username" class="form-label">Registration Date</label>
                                            <input type="date" class="form-control" id="registerdate" name="registerdate" value="<?php echo $currentDate; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="preferreddate" class="form-label">Date of Payment (Rent is applied to Balance 1 month from this)</label>
                                            <input type="date" class="form-control" id="preferreddate" name="preferreddate">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="add_tenant" class="btn btn-primary table-buttons-update">Add Tenant</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
                            function updateHouseId() {
                                var select = document.getElementById('house');
                                var selectedOption = select.options[select.selectedIndex];
                                var houseId = selectedOption.getAttribute('data-house-id');
                                document.getElementById('house_id').value = houseId;
                            }
                        </script>
                        </div>
                    </div>
                </div>
                <!-- Update House Modal -->
                <div class="modal fade" id="updateTenantModal" tabindex="-1" aria-labelledby="updateTenantModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateTenantModalLabel">Update Tenant</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateTenantForm" method="POST" action="admintenants.php">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="hidden" id="updateTenantId" name="tenant_id">
                                            <div class="mb-3">
                                                <label for="updateTenantNumber" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="updateTenantNumber" name="firstname" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateMiddlename" class="form-label">Middlename</label>
                                                <input type="text" class="form-control" id="updateMiddlename" name="middlename" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateLastname" class="form-label">Lastname</label>
                                                <input type="text" class="form-control" id="updateLastname" name="lastname" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateContactno" class="form-label">Contact</label>
                                                <input type="text" class="form-control" id="updateContactno" name="contactno" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="edit_registerdate" class="form-label">Register Date</label>
                                                <input type="date" class="form-control" id="edit_registerdate" name="registerdate" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateCategoryId" class="form-label">House</label>
                                                <select class="form-select" id="updateCategoryId" name="house_data" required>
                                                    <?php
                                                        // Fetch categories from the database
                                                        

                                                        // Check if categories exist
                                                        if ($result_option2->num_rows > 0) {
                                                            // Output options for each category
                                                            while ($row_option2 = $result_option2->fetch_assoc()) {
                                                                // echo "<option value='" . $row_option['category_id'] . "'></option>";
                                                                $house_info2 = $row_option2['id']. "|" . $row_option2['category_name'];
                                                                echo "<option value='" . htmlspecialchars($house_info2) . "'>" . $row_option2['id'] . " " . $row_option2['category_name'] . "</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>No categories found</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="edit_tenant" class="btn btn-primary table-buttons-update">Update User</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById('new_tenant').addEventListener('click', function () {
                        var newTenantModal = new bootstrap.Modal(document.getElementById('newTenantModal'), {
                            keyboard: false
                        });
                        newTenantModal.show();
                    });
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var updateButtons = document.querySelectorAll('.update-tenant-btn');
                        updateButtons.forEach(function (button) {
                            button.addEventListener('click', function () {
                                var tenantId = button.getAttribute('data-id');
                                var firstname = button.getAttribute('data-tenantname');
                                var middlename = button.getAttribute('data-middlename');
                                var lastname = button.getAttribute('data-lastname');
                                var contactno = button.getAttribute('data-contactno');
                                var registerDate = button.getAttribute('data-registerdate');
                                
                                // Fill the modal with the user's current data
                                document.getElementById('updateTenantId').value = tenantId;
                                document.getElementById('updateTenantNumber').value = firstname;
                                document.getElementById('updateMiddlename').value = middlename;
                                document.getElementById('updateLastname').value = lastname;
                                document.getElementById('updateContactno').value = contactno;
                                document.getElementById('edit_registerdate').value = registerDate;
                                var updateTenantModal = new bootstrap.Modal(document.getElementById('updateTenantModal'), {
                                    keyboard: false
                                });
                                updateTenantModal.show();
                            });
                        });
                    });
                </script>
                <p>Home</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

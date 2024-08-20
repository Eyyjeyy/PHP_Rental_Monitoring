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

    $sql = "SELECT tenants.*, houses.house_name AS house_name
            FROM tenants
            LEFT JOIN houses ON tenants.house_id = houses.id;";

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
    $page = "";
?>

    <?php include 'includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col sidebar text-white">
                <nav class="navbar navbar-expand navbar-dark sidebar">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav d-flex flex-column">
                            <a class="nav-link" href="admindashboard.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                                </svg>
                                <p>Dashboard</p>
                            </a>
                            <a class="nav-link" href="adminusers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-file-person" viewBox="0 0 16 16">
                                    <path d="M12 1a1 1 0 0 1 1 1v10.755S12 11 8 11s-5 1.755-5 1.755V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                </svg>
                                <p>Users</p>
                            </a>
                            <a class="nav-link" href="adminhouses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-building-fill" viewBox="0 0 16 16">
                                    <path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <p>Apartments</p>
                            </a>
                            <a class="nav-link" href="admincategories.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-list-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                                </svg>
                                <p>Categories</p>
                            </a>
                            <a class="nav-link active" aria-current="page" href="admintenants.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-person-standing" viewBox="0 0 16 16">
                                    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0"/>
                                </svg>
                                <p>Tenants</p>
                            </a>
                            <a class="nav-link" href="adminpayments.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-bank2" viewBox="0 0 16 16">
                                    <path d="M8.277.084a.5.5 0 0 0-.554 0l-7.5 5A.5.5 0 0 0 .5 6h1.875v7H1.5a.5.5 0 0 0 0 1h13a.5.5 0 1 0 0-1h-.875V6H15.5a.5.5 0 0 0 .277-.916zM12.375 6v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zM8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2M.5 15a.5.5 0 0 0 0 1h15a.5.5 0 1 0 0-1z"/>
                                </svg>
                                <p>Payments</p>
                                <?php
                                    $unapproved_payments = $admin->countPendingApprovals();
                                    echo "<p class= fw-bold' style='color: #F28543;'>" . $unapproved_payments . "</p>";
                                ?>
                            </a>
                            <a class="nav-link" href="adminpapers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
                                    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                                    <path d="M3.5 1h.585A1.5 1.5 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5q-.001-.264-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1"/>
                                </svg>
                                <p>Papers</p>
                            </a>
                            <a class="nav-link" href="adminexpenses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-wallet-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z"/>
                                    <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z"/>
                                </svg>
                                <p>Expenses</p>
                            </a>
                            <a class="nav-link" href="../chat.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-chat-left-text-fill" viewBox="0 0 16 16">
                                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                                </svg>
                                <p>Chat</p>
                            </a>
                            <a class="nav-link" href="adminhistory.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <p>History</p>
                            </a>
                        </ul>
                        <ul class="navbar-nav d-flex flex-column">
                            <a class="nav-link" href="../logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                    <path d="M7.5 1v7h1V1z"/>
                                    <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
                                </svg>
                                <p>Logout</p>
                            </a>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col main content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary float-end table-buttons-update" id="new_tenant"><i class="fa fa-plus"></i> New Tenant</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Firstname</th>
                                    <th scope="col">Middlename</th>
                                    <th scope="col">Lastname</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">House</th>
                                    <th scope="col">Date</th>
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
                                        echo "<td>House ID: " . htmlspecialchars($row['house_id']) . "<br>Category: " . htmlspecialchars($row['house_category']) . "<br>House Name: " . htmlspecialchars($row['house_name']) . "</td>";
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

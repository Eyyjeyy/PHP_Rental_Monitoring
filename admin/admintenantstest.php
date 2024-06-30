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
        $firstname = htmlspecialchars($_POST['firstname']);
        $middlename = htmlspecialchars($_POST['middlename']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $contactno = htmlspecialchars($_POST['contactno']);
        $houseid = htmlspecialchars($_POST['house_name']);
        $registerdate = htmlspecialchars($_POST['registerdate']);
        // Call the addTenant method to add the new tenant
        $added = $admin->addTenant($firstname, $middlename, $lastname, $contactno, $houseid, $registerdate);
        if($added) {
            // Tenant added successfully, you can display a success message here if needed
            // echo "Tenant added successfully.";
            header("Location: admintenants.php?tenant_added=1");
            exit();
        } else {
            // Error occurred while adding tenant, display an error message or handle as needed
            echo "Error occurred while adding tenant.";
        }
    }
    
    // $sql = "SELECT * FROM tenants";

    $sql = "SELECT tenants.*, categories.name AS house_name
    FROM tenants
    INNER JOIN houses ON tenants.house_id = houses.id
    INNER JOIN categories ON houses.category_id = categories.id;
    ";
    
    $result = $admin->conn->query($sql);
    // $sql_option = "SELECT houses.id, houses.house_number, categories.name AS category_name FROM houses 
    // INNER JOIN categories ON categories.id = houses.category_id 
    // ORDER BY houses.id ASC";
    // $sql_option = "SELECT houses.id, houses.house_number, houses.category_id AS houses_id FROM houses ORDER BY houses.id ASC";
    $sql_option = "SELECT houses.id, houses.house_number, houses.category_id AS house_catid, categories.name AS category_name FROM houses
    INNER JOIN categories ON categories.id = houses.category_id
    ORDER BY houses.id ASC";
    $result_option = $admin->conn->query($sql_option);
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
                                <p>Houses</p>
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
                            <button class="btn btn-primary float-end" id="new_tenant"><i class="fa fa-plus"></i> New Tenant</button>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Firstname</th>
                                <th scope="col">Middlename</th>
                                <th scope="col">Lastname</th>
                                <th scope="col">Contact</th>
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
                                    echo "<td>" . htmlspecialchars($row['house_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date_start']) . "</td>";
                                    echo "<td class='d-flex'>";
                                    // Add a form with a delete button for each record
                                    echo "<form method='POST' action='admintenants.php'>";
                                    echo "<input type='hidden' name='tenantid' value='" . $row['id'] . "'>";
                                    echo "<button type='submit' name='delete_tenant' class='btn btn-danger' style='width: 80px;'>Delete</button>";
                                    echo "</form>";
                                    // Add a form with a update button for each record
                                    echo "<input type='hidden' name='tenantid' value='" . $row['id'] . "'>";
                                    echo "<button type='button' class='btn btn-primary update-tenant-btn' data-id='" . $row['id'] . "' data-tenantname='" . htmlspecialchars($row['fname']) . "' style='width: 80px;'>Update</button>";
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
                                    <div class="col-md-4">
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
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Contact #</label>
                                            <input type="text" class="form-control" id="contactno" name="contactno" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">House</label>
                                            <select class="form-select" id="role" name="house_name" required>
                                                <!-- <option value="3">3</option> -->
                                                <?php
                                                    // Fetch houses from the database
                                                    

                                                    // Check if houses exist
                                                    if ($result_option->num_rows > 0) {
                                                        // Output options for each category
                                                        while ($row_option = $result_option->fetch_assoc()) {
                                                            // echo "<option value='" . $row_option['id'] . "'>" . $row_option['category_name'] . "</option>";
                                                            echo "<option value='" . $row_option['id'] . "'>" . $row_option['house_number'] . " " . $row_option['category_name'] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No houses found</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Registration Date</label>
                                            <input type="date" class="form-control" id="registerdate" name="registerdate" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="add_tenant" class="btn btn-primary">Add Tenant</button>
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
                <p>Home</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

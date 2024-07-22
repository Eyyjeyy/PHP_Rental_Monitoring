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

    // // Check if the form is submitted for adding a new user
    // if(isset($_POST['add_house'])) {
    //     // Get the user data from the form
    //     $housenumber = trim(htmlspecialchars($_POST['housenumber']));
    //     $price = trim(htmlspecialchars($_POST['price']));
    //     $category = htmlspecialchars($_POST['category']);

    //     // Validate the house number to ensure it contains only numerical characters
    //     if (!ctype_digit($housenumber)) {
    //         // Set an error message in the session and redirect back to the form
    //         $_SESSION['error_message'] = "House number should only contain numerical characters.";
    //         header("Location: adminhouses.php?error=add");
    //         exit();
    //     }

    //     // Validate the price to ensure it contains only numerical characters and a single optional period
    //     if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
    //         // Set an error message in the session and redirect back to the form
    //         $_SESSION['error_message'] = "Price should only contain numerical characters and a single optional period.";
    //         header("Location: adminhouses.php?error=add");
    //         exit();
    //     }

    //     // Call the addUser method to add the new user
    //     $added = $admin->addHouse($housenumber, $price, $category);
    //     if($added) {
    //         // User added successfully, you can display a success message here if needed
    //         // echo "User added successfully.";
    //         header("Location: adminhouses.php?house_added=1");
    //         exit();
    //     } else {
    //         // Error occurred while adding user, display an error message or handle as needed
    //         echo "Error occurred while adding user.";
    //     }
    // }

    if (isset($_POST['housenumber']) && isset($_POST['price']) && isset($_POST['category']) && isset($_POST['e_accountnum'])
        && isset($_POST['e_accountname'])) {
        // Get the user data from the form
        $housenumber = trim(htmlspecialchars($_POST['housenumber']));
        $price = trim(htmlspecialchars($_POST['price']));
        $category = htmlspecialchars($_POST['category']);
        $e_accountnum = trim(htmlspecialchars($_POST['e_accountnum']));
        $e_accountname = trim(htmlspecialchars($_POST['e_accountname']));
        $w_accountnum = trim(htmlspecialchars($_POST['w_accountnum']));
        $w_accountname = trim(htmlspecialchars($_POST['w_accountname']));
    
        // Validate the house number to ensure it contains only numerical characters
        if (ctype_digit($housenumber)) {
            $_SESSION['error_message'] = "Should only be alphabetical characters";
            header("Location: adminhouses.php");
            exit();
        }

        if (!ctype_digit($e_accountnum)) {
            $_SESSION['error_message'] = "Should only be numerical characters";
            header("Location: adminhouses.php");
            exit();
        }
    
        // Validate the price to ensure it contains only numerical characters and a single optional period
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
            header("Location: adminhouses.php");
            exit();
        }
    
        // Create an instance of your Admin class
        // $admin1 = new Admin($conn);
        // Call the addHouse method to add the new house
        $added = $admin->addHouse($housenumber, $price, $category, $e_accountname, $e_accountnum, $w_accountname, $w_accountnum);
        if ($added) {
            $_SESSION['success_message'] = "Success";
            header("Location: adminhouses.php");
            exit();
        } else {
            echo "Error occurred while updating user.";
        }
    }
    
    // echo json_encode($response);

    // Check if there's an error message stored in the session
    if (isset($_SESSION['error_message'])) {
        // Display the error message as an alert
        // echo '<div class="alert alert-danger mb-0" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        // // Unset the session variable to clear the error message
        // unset($_SESSION['error_message']);
    }

    if(isset($_POST['edit_house'])) {
        $housenumber = htmlspecialchars($_POST['house_number']);
        $price = htmlspecialchars($_POST['price']);
        $category_id = htmlspecialchars($_POST['category_id']);
        $house_id = $_POST['house_id'];
        $meralco_accnum = $_POST['meralco_accnum'];
        $meralco_accname = $_POST['meralco_accname'];
        $maynilad_accnum = $_POST['maynilad_accnum'];
        $maynilad_accname = $_POST['maynilad_accname'];
        $updated = $admin->updateHouse($house_id, $housenumber, $price, $category_id, $meralco_accnum, $meralco_accname, $maynilad_accnum, $maynilad_accname);
        if($updated) {
            header("Location: adminhouses.php");
            exit();
        } else {
            echo "Error occurred while updating user.";
        }
    }

    // Check if the form is submitted for deleting a house
    if(isset($_POST['delete_house'])) {
        // Get the house ID to be deleted
        $house_id = $_POST['house_id'];
        // Call the deleteHouse method to delete the house
        $deleted = $admin->deleteHouse($house_id);
        if($deleted) {
            // House deleted successfully, you can display a success message here if needed
            // echo "User deleted successfully.";
            header("Location: adminhouses.php?house_deleted=1");
        } else {
            // Error occurred while deleting user, display an error message or handle as needed
            echo "Error occurred while deleting user.";
        }
    }
    
    // $sql = "SELECT * FROM houses";
    // $sql = "SELECT houses.*, categories.name AS category_name FROM houses INNER JOIN categories ON categories.id = houses.category_id ORDER BY houses.id ASC";
    $sql = "SELECT houses.*, categories.name AS category_name, houseaccounts.elec_accnum, houseaccounts.elec_accname, houseaccounts.water_accname, houseaccounts.water_accnum
    FROM houses
    INNER JOIN categories ON categories.id = houses.category_id
    LEFT JOIN houseaccounts ON houses.id = houseaccounts.houses_id
    ORDER BY houses.id ASC";
    $result = $admin->conn->query($sql);
    $sql_option = "SELECT * FROM categories";
    $result_option = $admin->conn->query($sql_option);
    $sql2 = "SELECT * FROM categories";
    $sql2_option = $admin->conn->query($sql2);


    // $sql3 = "SELECT ha.* FROM houseaccounts ha INNER JOIN houses h ON ha.houses_id = h.id";
    // $result3 = $admin->conn->query($sql3);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
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
                            <a class="nav-link active" aria-current="page" href="adminhouses.php">
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
                            <a class="nav-link" href="admintenants.php">
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
                            </a>
                            <a class="nav-link" href="adminpapers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
                                    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                                    <path d="M3.5 1h.585A1.5 1.5 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5q-.001-.264-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1"/>
                                </svg>
                                <p>Papers</p>
                            </a>
                            <a class="nav-link" href="../chat.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-chat-left-text-fill" viewBox="0 0 16 16">
                                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                                </svg>
                                <p>Chat</p>
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
                            <button class="btn btn-primary float-end" id="new_house"><i class="fa fa-plus"></i> New House</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Apartment Name</th>
                                    <th scope="col">Rent</th>
                                    <th scope="col">Apartment Type</th>
                                    <th scope="col" style="max-width: 80px;">Meralco #</th>
                                    <th scope="col">Meralco Account Name</th>
                                    <th scope="col">Maynilad #</th>
                                    <th scope="col">Maynilad Account Name</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="house_data">
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['house_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                                        // echo "<td>" . htmlspecialchars($row['category_id']) . " <br> " . $row['category_name'] . " </td>";
                                        echo "<td>" . $row['category_name'] . " </td>";
                                        echo "<td>" . $row['elec_accnum'] . "</td>";
                                        echo "<td>" . $row['elec_accname'] . "</td>";
                                        echo "<td>" . $row['water_accnum'] . "</td>";
                                        echo "<td>" . $row['water_accname'] . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                        echo "<div class='col-xxl-6 px-2'>";
                                        // Add a form with a delete button for each record
                                        echo "<form method='POST' action='adminhouses.php' class='float-xxl-end align-items-center'>";
                                        echo "<input type='hidden' name='house_id' value='" . $row['id'] . "'>";
                                        echo "<button type='submit' name='delete_house' class='btn btn-danger' style='width: 80px;'>Delete</button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "<div class='col-xxl-6 px-2'>";
                                        // Add a form with a update button for each record
                                        echo "<input type='hidden' name='house_id' value='" . $row['id'] . "'>";
                                        echo "<button type='button' class='btn btn-primary update-house-btn float-xxl-start' data-id='" . $row['id'] . "' data-housenumber='" . htmlspecialchars($row['house_name']) . "' data-price='" . htmlspecialchars($row['price']) . "' data-categoryid='" . htmlspecialchars($row['category_id']) . "' data-meralconum='" . htmlspecialchars($row['elec_accnum']) . "' data-meralconame='" . htmlspecialchars($row['elec_accname']) . "' data-mayniladnum='" . htmlspecialchars($row['water_accnum']) . "' data-mayniladname='" . htmlspecialchars($row['water_accname']) . "' style='width: 80px;'>Update</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                // $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- New House Modal -->
                <div class="modal fade" id="newHouseModal_house" tabindex="-1" aria-labelledby="newHouseModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newhouseModalLabel">New House</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                                if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                                    // Unset the error message after displaying it
                                    echo '<script>var newHouseModal = new bootstrap.Modal(document.getElementById("newHouseModal_house"), { keyboard: false });newHouseModal.show();</script>';
                                    unset($_SESSION['error_message']);
                                } else if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                                    echo '<script>var newHouseModal = new bootstrap.Modal(document.getElementById("newHouseModal_house"), { keyboard: false });newHouseModal.show();</script>';
                                    unset($_SESSION['success_message']);
                                }
                            ?>
                            <form id="newHouseForm" method="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">House Name</label>
                                            <input type="text" class="form-control" id="username" name="housenumber" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control" id="price" name="price" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Category Name</label>
                                            <select class="form-select" id="role" name="category" required>
                                                <!-- <option value="3">3</option> -->
                                                <?php
                                                    // Fetch categories from the database
                                                    

                                                    // Check if categories exist
                                                    if ($result_option->num_rows > 0) {
                                                        // Output options for each category
                                                        while ($row_option = $result_option->fetch_assoc()) {
                                                            // echo "<option value='" . $row_option['category_id'] . "'></option>";
                                                            echo "<option value='" . $row_option['id'] . "'>" . $row_option['name'] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No categories found</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="e_accountnum" class="form-label">Meralco Account #</label>
                                            <input type="number" class="form-control" id="e_accountnum" name="e_accountnum" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="e_accountname" class="form-label">Meralco Account Name</label>
                                            <input type="text" class="form-control" id="e_accountname" name="e_accountname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="w_accountnum" class="form-label">Maynilad Account #</label>
                                            <input type="number" class="form-control" id="w_accountnum" name="w_accountnum" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="w_accountname" class="form-label">Maynilad Account Name</label>
                                            <input type="text" class="form-control" id="w_accountname" name="w_accountname" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" name="add_house" class="btn btn-primary">Add House</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Update House Modal -->
                <div class="modal fade" id="updateHouseModal" tabindex="-1" aria-labelledby="updateHouseModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateHouseModalLabel">Update House</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateHouseForm" method="POST" action="adminhouses.php">
                                    <div class="row">
                                        <input type="hidden" id="updateHouseId" name="house_id">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateHouseNumber" class="form-label">House Name</label>
                                                <input type="text" class="form-control" id="updateHouseNumber" name="house_number" required>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updatePrice" class="form-label">Price</label>
                                                <input type="text" class="form-control" id="updatePrice" name="price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateCategoryId" class="form-label">Category Name</label>
                                                <select class="form-select" id="updateCategoryId" name="category_id" required>
                                                    <?php
                                                        // Fetch categories from the database
                                                        

                                                        // Check if categories exist
                                                        if ($sql2_option->num_rows > 0) {
                                                            // Output options for each category
                                                            while ($row_option2 = $sql2_option->fetch_assoc()) {
                                                                // echo "<option value='" . $row_option['category_id'] . "'></option>";
                                                                echo "<option value='" . $row_option2['id'] . "'>" . $row_option2['name'] . "</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>No categories found</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="update_meralco_accnum" class="form-label">Meralco Account #</label>
                                                <input type="number" class="form-control" id="update_meralco_accnum" name="meralco_accnum">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="update_meralco_accname" class="form-label">Meralco Account Name</label>
                                                <input type="text" class="form-control" id="update_meralco_accname" name="meralco_accname">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="update_maynilad_accnum" class="form-label">Maynilad Account #</label>
                                                <input type="number" class="form-control" id="update_maynilad_accnum" name="maynilad_accnum">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="update_maynilad_accname" class="form-label">Maynilad Account Name</label>
                                                <input type="text" class="form-control" id="update_maynilad_accname" name="maynilad_accname">
                                            </div>
                                        </div>
                                        <button type="submit" name="edit_house" class="btn btn-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <!-- <script>
                    $(document).ready(function() {
                        $('#newHouseForm').on('submit', function(e) {
                            e.preventDefault(); // Prevent the form from submitting the traditional way

                            $.ajax({
                                url: 'adminhouses.php', // The PHP script that processes the form
                                type: 'POST',
                                data: $(this).serialize(), // Serialize the form data
                                dataType: 'json',
                                success: function(response) {
                                    console.log(response); // Log the response to the console

                                    // Handle the response from the server
                                    if (response.success) {
                                        alert('House added successfully!');
                                        // Optionally, close the modal and refresh the house list
                                        $('#newHouseModal_house').modal('hide');
                                        // Refresh the house list or update the UI as needed
                                    } else {
                                        // Display the error message
                                        alert(response.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(response);
                                    // Handle any errors that occurred during the AJAX request
                                    alert('An error occurred: ' + error);
                                }
                            });
                        });
                    });
                </script> -->
                <script>
                    document.getElementById('new_house').addEventListener('click', function () {
                        var newHouseModal_house = new bootstrap.Modal(document.getElementById('newHouseModal_house'), {
                            keyboard: false
                        });
                        newHouseModal_house.show();
                    });
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var updateButtons = document.querySelectorAll('.update-house-btn');
                        updateButtons.forEach(function (button) {
                            button.addEventListener('click', function () {
                                var userId = button.getAttribute('data-id');
                                var username = button.getAttribute('data-housenumber');
                                var password = button.getAttribute('data-price');
                                var role = button.getAttribute('data-categoryid');
                                var meralcoNum = button.getAttribute('data-meralconum');
                                var meralcoNam = button.getAttribute('data-meralconame');
                                var mayniladNum = button.getAttribute('data-mayniladnum');
                                var mayniladNam = button.getAttribute('data-mayniladname');
                                
                                // Fill the modal with the user's current data
                                document.getElementById('updateHouseId').value = userId;
                                document.getElementById('updateHouseNumber').value = username;
                                document.getElementById('updatePrice').value = password;
                                document.getElementById('updateCategoryId').value = role;
                                document.getElementById('update_meralco_accnum').value = meralcoNum;
                                document.getElementById('update_meralco_accname').value = meralcoNam;
                                document.getElementById('update_maynilad_accnum').value = mayniladNum;
                                document.getElementById('update_maynilad_accname').value = mayniladNam;
                                
                                var updateHouseModal = new bootstrap.Modal(document.getElementById('updateHouseModal'), {
                                    keyboard: false
                                });
                                updateHouseModal.show();
                            });
                        });
                    });
                </script>
                <p>Home</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

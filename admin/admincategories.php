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

    // Check if the form is submitted for adding a new category
    if(isset($_POST['add_category'])) {
        // Get the category data from the form
        $categoryname = htmlspecialchars($_POST['categoryname']);
        // Call the addCategory method to add the new category
        $added = $admin->addCategory($categoryname);
        if($added) {
            // Category added successfully, you can display a success message here if needed
            // echo "Category added successfully.";
            header("Location: admincategories.php?category_added=1");
            exit();
        } else {
            // Error occurred while adding category, display an error message or handle as needed
            echo "Error occurred while adding Category.";
        }
    }

    if(isset($_POST['edit_category'])) {
        $categoryname = htmlspecialchars($_POST['categoryname']);
        $categoryid = $_POST['categoryid'];
        $updated = $admin->updateCategory($categoryid, $categoryname);
        if($updated) {
            header("Location: admincategories.php");
            exit();
        } else {
            echo "Error occurred while updating user.";
        }
    }

    // Check if the form is submitted for deleting a category
    if(isset($_POST['delete_category'])) {
        // Get the category ID to be deleted
        $categoryid = $_POST['categoryid'];
        // Call the deleteCategory method to delete the category
        $deleted = $admin->deleteCategory($categoryid);
        if($deleted) {
            // Category deleted successfully, you can display a success message here if needed
            header("Location: admincategories.php?category_deleted=1");
        } else {
            // Error occurred while deleting category, display an error message or handle as needed
            echo "Error occurred while deleting category.";
        }
    }
    
    $sql = "SELECT * FROM categories";
    $result = $admin->conn->query($sql);

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
                            <a class="nav-link" href="adminhouses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-building-fill" viewBox="0 0 16 16">
                                    <path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <p>Apartments</p>
                            </a>
                            <a class="nav-link active" aria-current="page" href="admincategories.php">
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
                            <button class="btn btn-primary float-end" id="new_category"><i class="fa fa-plus"></i> New Category</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Apartment Type</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a delete button for each record
                                        echo "<form method='POST' action='admincategories.php' class='float-xl-end align-items-center'>";
                                        echo "<input type='hidden' name='categoryid' value='" . $row['id'] . "'>";
                                        echo "<button type='submit' name='delete_category' class='btn btn-danger' style='width: 80px;'>Delete</button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a update button for each record
                                        echo "<input type='hidden' name='categoryid' value='" . $row['id'] . "'>";
                                        echo "<button type='button' class='btn btn-primary update-category-btn float-xl-start' data-id='" . $row['id'] . "' data-categoryname='" . htmlspecialchars($row['name']) . "' style='width: 80px;'>Update</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No categories found</td></tr>";
                                }
                                $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- New Category Modal -->
                <div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newcategoryModalLabel">New Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newCategoryForm" method="POST" action="admincategories.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="username" name="categoryname" required>
                            </div>
                            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Update Category Modal -->
                <div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateCategoryModalLabel">Update Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateCategoryForm" method="POST" action="admincategories.php">
                                    <input type="hidden" id="updateCategoryId" name="categoryid">
                                    <div class="mb-3">
                                        <label for="updateCategoryname" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" id="updateCategoryname" name="categoryname" required>
                                    </div>
                                    <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById('new_category').addEventListener('click', function () {
                        var newCategoryModal = new bootstrap.Modal(document.getElementById('newCategoryModal'), {
                            keyboard: false
                        });
                        newCategoryModal.show();
                    });
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var updateButtons = document.querySelectorAll('.update-category-btn');
                        updateButtons.forEach(function (button) {
                            button.addEventListener('click', function () {
                                var userId = button.getAttribute('data-id');
                                var username = button.getAttribute('data-categoryname');
                                
                                // Fill the modal with the user's current data
                                document.getElementById('updateCategoryId').value = userId;
                                document.getElementById('updateCategoryname').value = username;
                                
                                var updateCategoryModal = new bootstrap.Modal(document.getElementById('updateCategoryModal'), {
                                    keyboard: false
                                });
                                updateCategoryModal.show();
                            });
                        });
                    });
                </script>
                <p>Home</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

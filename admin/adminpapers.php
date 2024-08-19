<?php
    include '../admin.php';
    $admin = new Admin();

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
        $categorynamepapers = htmlspecialchars($_POST['categoryname']);
        // Call the addCategory method to add the new category
        $added = $admin->addCategoryPapers($categorynamepapers);
        if($added) {
            // Category added successfully, you can display a success message here if needed
            // echo "Category added successfully.";
            echo 'success';
            // header("Location: adminpapers.php?category_added=1");
            exit();
        } else {
            // Error occurred while adding category, display an error message or handle as needed
            echo 'error';
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoryname'])) {
        // Get the category name from the form
        $categorynamepapers = htmlspecialchars($_POST['categoryname']);

        // Call the addCategoryPapers method to add the new category
        $added = $admin->addCategoryPapers($categorynamepapers);

        if ($added) {
            // Category added successfully, return success response
            echo 'success2';
        } else {
            // Error occurred while adding category, return error response
            echo 'error';
        }

        exit(); // Stop further execution after handling AJAX request
    }
    // Check if the form is submitted for deleting a category
    if(isset($_POST['delete_category'])) {
        // Get the category ID to be deleted
        $categoryid = $_POST['categoryid'];
        // Call the deleteCategory method to delete the category
        $deleted = $admin->deleteCategoryPapers($categoryid);
        if($deleted) {
            // Category deleted successfully, you can display a success message here if needed
            // header("Location: admincategories.php?category_deleted=1");
            echo 'success';
        } else {
            // Error occurred while deleting category, display an error message or handle as needed
            // echo "Error occurred while deleting category.";
            echo 'error';
        }
    }

    // Handle form submission for adding papers
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'])) {
        $categoryId = $_POST['category_id'];
        $paperName = htmlspecialchars($_POST['paper_name']);

        // File upload handling
        // $uploadDir = '../uploads/'; // Specify your upload directory
        // $fileName = basename($_FILES['paper_file']['name']);
        // $targetPath = $uploadDir . $fileName;

        $uploadDir = '../uploads/'; // Specify your upload directory
        $originalFileName = basename($_FILES['paper_file']['name']);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
        $uniqueFileName = $fileNameWithoutExtension . '_' . uniqid() . '.' . $fileExtension;
        $targetPath = $uploadDir . $uniqueFileName;


        if (move_uploaded_file($_FILES['paper_file']['tmp_name'], $targetPath)) {
            // File uploaded successfully, insert into database
            $added = $admin->addPaper($categoryId, $paperName, $uniqueFileName, $targetPath);
            if ($added) {
                // echo 'success';
                header("Location: adminpapers.php");
            } else {
                // echo 'error';
            }
        } else {
            echo 'error';
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete_file']) && $_POST['delete_file'] == 'true') {
            $fileId = intval($_POST['fileid']);
            if ($admin->deletePaper($fileId)) {
                echo 'success';
            } else {
                echo 'error';
            }
            exit;
        }
        // ... handle other POST requests
    }

    // Pagination settings
    $limit = 5; // Number of records per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Fetch total number of records
    $sql_count = "SELECT COUNT(*) AS count FROM paper_files";
    $result_count = $admin->conn->query($sql_count);
    $total_records = $result_count->fetch_assoc()['count'];
    $total_pages = ceil($total_records / $limit);

    // Fetch records for current page
    $sql_paper = "SELECT * FROM paper_files LIMIT $start, $limit";
    $result_paper = $admin->conn->query($sql_paper);



    $sql = "SELECT * FROM paper_categories";
    $result = $admin->conn->query($sql);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "adminpapers";
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
                            <a class="nav-link" aria-current="page" href="admincategories.php">
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
                                <?php
                                    $users_notTenants = $admin->countUsersNotInTenants();
                                    echo "<p class='notifs fw-bold position-absolute' style='color: #F28543;'>" . $users_notTenants . "</p>";
                                ?>
                            </a>
                            <a class="nav-link" href="adminpayments.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-bank2" viewBox="0 0 16 16">
                                    <path d="M8.277.084a.5.5 0 0 0-.554 0l-7.5 5A.5.5 0 0 0 .5 6h1.875v7H1.5a.5.5 0 0 0 0 1h13a.5.5 0 1 0 0-1h-.875V6H15.5a.5.5 0 0 0 .277-.916zM12.375 6v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zM8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2M.5 15a.5.5 0 0 0 0 1h15a.5.5 0 1 0 0-1z"/>
                                </svg>
                                <p>Payments</p>
                                <?php
                                    $unapproved_payments = $admin->countPendingApprovals();
                                    echo "<p class='notifs fw-bold position-absolute' style='color: #F28543;'>" . $unapproved_payments . "</p>";
                                ?>
                            </a>
                            <a class="nav-link active" href="adminpapers.php">
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
                    <!-- <div class="row">
                        <form id="newPapersForm" action="adminpapers.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="paperCategory" class="form-label">Select Category</label>
                                <select class="form-select" id="paperCategory" name="category_id" required>
                                    <?php
                                    // Fetch categories from database
                                    $sql = "SELECT * FROM paper_categories";
                                    $result = $admin->conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paperName" class="form-label">Paper Name</label>
                                <input type="text" class="form-control" id="paperName" name="paper_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="paperFile" class="form-label">Upload File</label>
                                <input type="file" class="form-control" id="paperFile" name="paper_file" required>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="add_papers" class="btn btn-primary float-end" style="padding: 6px 20px;">
                                    <i class="fa fa-plus">
                                    </i>
                                    Add Papers
                                </button>
                            </div>
                        </form>
                    </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary float-end" id="new_papers"><i class="fa fa-plus"></i> New Papers</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Paper Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="papertable">
                                <?php
                                $sql_paper = "SELECT * FROM paper_files";
                                $result_paper = $admin->conn->query($sql_paper);
                                if ($result_paper->num_rows > 0) {
                                    while ($row_paper = $result_paper->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row_paper['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row_paper['file_name']) . "</td>";
                                        echo "<td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- <div id="pagination-controls">
                            <button id="prev-page" onclick="prevPage()">Previous</button>
                            <span id="page-info"></span>
                            <button id="next-page" onclick="nextPage()">Next</button>
                        </div> -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination-controls"></ul>
                        </nav>
                    </div>
                </div>
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
                                    <th scope="col">Paper Type</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="result">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
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
                            <form id="newCategoryForm" method="POST" action="adminpapers.php">
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
                <!-- New Papers Modal -->
                <div class="modal fade" id="newPapersModal" tabindex="-1" aria-labelledby="newCategoryPapersLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newpapersModalLabel">New Papers</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="newPaperFilesForm" method="POST" action="adminpapers.php">
                                    <!-- <div class="mb-3">
                                        <label for="username" class="form-label">Paper Name</label>
                                        <input type="text" class="form-control" id="username" name="categoryname" required>
                                    </div> -->
                                    <div class="mb-3">
                                        <label for="paperCategory" class="form-label">Select Category</label>
                                        <select class="form-select" id="paperCategory" name="category_id" required>
                                            <?php
                                            // Fetch categories from database
                                            $sql = "SELECT * FROM paper_categories";
                                            $result = $admin->conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paperName" class="form-label">Paper Name</label>
                                        <input type="text" class="form-control" id="paperName" name="paper_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paperFile" class="form-label">Upload File</label>
                                        <input type="file" class="form-control" id="paperFile" name="paper_file" required>
                                    </div>
                                    <button type="submit" name="add_category" class="btn btn-primary">Add Papers</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // $(document).ready(function(){
        //     $('#newCategoryForm').on('submit', function(e){
        //         e.preventDefault();

        //         var formData = new FormData(this);
        //         $.ajax({
        //             url: 'adminpapers.php',
        //             type: 'GET',
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             success: function(response) {
        //                 console.log('Form submitted successfully:', response);
        //                 // $('#message-input').val('');
        //                 // $('#image-input').val('');
        //                 // fetchMessages();
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 console.error('Error submitting form:', textStatus, errorThrown);
        //             }
        //         });
        //     });
        // });
        document.getElementById('newCategoryForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var form = event.target;
            var formData = new FormData(form);

            fetch('adminpapers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response from server:', data);

                var messageDiv = document.getElementById('result');
                if (data.includes('success')) {
                    messageDiv.innerHTML = '<p class="text-success">Category added successfully.</p>';
                    
                    // Update the dropdown with the new category
                    var paperCategorySelect = document.getElementById('paperCategory');
                    var newOption = document.createElement('option');
                    newOption.value = data.newCategoryId; // Assuming the server returns the new category ID
                    newOption.text = formData.get('categoryname');
                    paperCategorySelect.appendChild(newOption);

                    // Optionally, clear the form fields after successful submission
                    form.reset();
                } else {
                    messageDiv.innerHTML = '<p class="text-danger">Failed to add category.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        });
    </script>
    <script>
        // document.getElementById('new_category').addEventListener('click', function () {
        //     var newCategoryModal = new bootstrap.Modal(document.getElementById('newCategoryModal'), {
        //         keyboard: false
        //     });
        //     newCategoryModal.show();
        // });

        document.getElementById('new_category').addEventListener('click', function () {
            var newCategoryModal = new bootstrap.Modal(document.getElementById('newCategoryModal'), {
                keyboard: false
            });
            newCategoryModal.show();
        });

        // document.getElementById('newPapersForm').addEventListener('submit', function(event) {
        //     event.preventDefault();
            
        //     var form = event.target;
        //     var formData = new FormData(form);

        //     fetch('adminpapers.php', {
        //         method: 'POST',
        //         body: formData
        //     })
        //     .then(response => response.text())
        //     .then(data => {
        //         console.log('Response from server:', data);
        //         if (data.includes('success')) {
        //             // Handle successful paper addition
        //             // window.location.reload(); // Reload the page to see the new paper
        //             form.reset();
        //         } else {
        //             // Handle error in paper addition
        //             console.error('Failed to add paper');
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //     });
        // });
    </script>
    <script>
        document.getElementById('new_papers').addEventListener('click', function () {
            var newCategoryModal = new bootstrap.Modal(document.getElementById('newPapersModal'), {
                keyboard: false
            });
            newCategoryModal.show();
        });

        document.getElementById('newPaperFilesForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            var form = event.target;
            var formData = new FormData(form);

            fetch('adminpapers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response from server:', data);
                if (data.includes('success')) {
                    // Handle successful paper addition
                    // window.location.reload(); // Reload the page to see the new paper
                    form.reset();
                } else {
                    // Handle error in paper addition
                    console.error('Failed to add paper');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
    <script>
        var source = new EventSource("../fetch_papers.php");
        source.onmessage = function(event) {
            // document.getElementById("result").innerHTML += event.data + "<br>";
            var arrayData = JSON.parse(event.data);
            console.log(arrayData);
            var dataContainer = document.querySelector('tbody#result')
            dataContainer.innerHTML = ''
            arrayData.forEach(e => {
                dataContainer.innerHTML +=`
                <tr>
                    <td>${e.id}</td>
                    <td>${e.name}</td>
                    <td>${e.created_at}</td>
                    <td>
                        <button class="btn btn-danger btn-delete table-buttons-delete" name="delete_category" data-id="${e.id}">Delete</button>
                    </td>
                </tr>
                `;
            });

            var paperCategorySelect = document.getElementById('paperCategory');
            paperCategorySelect.innerHTML = ''; // Clear existing options

            // Update the category dropdown
            arrayData.forEach(function(category) {
                var option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                paperCategorySelect.appendChild(option);
            });
        }
    </script>

    <script>
        var allDataFiles = [];
        var currentPage = 1;
        var itemsPerPage = 5;

        var source_files = new EventSource("../fetch_papers_files.php");
        source_files.onmessage = function(event_files) {
            // allDataFiles = JSON.parse(event_files.data);
            // currentPage = 1; // Reset to the first page whenever new data is fetched
            // renderTable();
            // updatePaginationControls();
            var newDataFiles = JSON.parse(event_files.data);
            
            if (JSON.stringify(allDataFiles) !== JSON.stringify(newDataFiles)) {
                allDataFiles = newDataFiles;
                console.log("Updated allDataFiles:", allDataFiles);
                renderTable();
                updatePaginationControls();
            }
        };

        function renderTable() {
            var dataContainer_files = document.querySelector('tbody#papertable');
            dataContainer_files.innerHTML = '';
            var startIndex = (currentPage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            var paginatedData = allDataFiles.slice(startIndex, endIndex);

            paginatedData.forEach(e_files => {
                dataContainer_files.innerHTML += `
                    <tr>
                        <td>${e_files.file_id}</td>
                        <td>${e_files.file_name}</td>
                        <td>${e_files.category_name}</td>
                        <td>${e_files.uploaded_at}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="row m-0">
                                    <div class="col d-flex justify-content-center px-2">
                                        <button class="btn btn-danger btn-delete table-buttons-delete" name="delete_file" data-id="${e_files.file_id}" style="width: 100px;">Delete</button>
                                    </div>
                                    <div class="col d-flex justify-content-center px-2">
                                        <a href="${e_files.file_url}" class="btn btn-primary btn-download table-buttons-update" download="${e_files.file_name}" style="width: 100px; text-align: center;">Download</a>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                        </td>
                    </tr>
                `;
            });
            // Add event listeners for delete buttons
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    deleteFile(id);
                });
            });
        }

        function deleteFile(id) {
            var formData = new FormData();
            formData.append('delete_file', 'true');
            formData.append('fileid', id);

            fetch('adminpapers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response from server:', data);

                var message_filesDiv = document.getElementById('papertable');
                if (data.includes('success')) {
                    message_filesDiv.innerHTML += '<p class="text-success">File deleted successfully.</p>';
                    // Optionally, remove the deleted file row from the table
                    document.querySelector('button[data-id="' + id + '"]').closest('tr').remove();
                    // Refresh the data to reflect changes
                    allDataFiles = allDataFiles.filter(file => file.file_id != id);
                    renderTable();
                    updatePaginationControls();
                } else {
                    message_filesDiv.innerHTML += '<p class="text-danger">Failed to delete file.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        }

        function updatePaginationControls() {
            // var totalPages = Math.ceil(allDataFiles.length / itemsPerPage);
            // document.getElementById('page-info').textContent = `Page ${currentPage} of ${totalPages}`;

            // document.getElementById('prev-page').disabled = currentPage === 1;
            // document.getElementById('next-page').disabled = currentPage === totalPages;
            var totalPages = Math.ceil(allDataFiles.length / itemsPerPage);
            var paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = '';

            for (var i = 1; i <= totalPages; i++) {
                var li = document.createElement('li');
                li.classList.add('page-item');
                if (i === currentPage) {
                    li.classList.add('active');
                }
                
                var button = document.createElement('button');
                button.textContent = i;
                button.classList.add('page-link');
                button.addEventListener('click', function() {
                    currentPage = parseInt(this.textContent);
                    renderTable();
                    updatePaginationControls();
                });

                li.appendChild(button);
                paginationControls.appendChild(li);
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
                updatePaginationControls();
            }
        }

        function nextPage() {
            var totalPages = Math.ceil(allDataFiles.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
                updatePaginationControls();
            }
        }
    </script>
    
    <!-- Without Pagination for papertable -->
    <!-- <script>
        var soruce_files = new EventSource("../fetch_papers_files.php");
        soruce_files.onmessage = function(event_files) {
            // document.getElementById("result").innerHTML += event.data + "<br>";
            var arrayData_files = JSON.parse(event_files.data);
            console.log(arrayData_files);
            var dataContainer_files = document.querySelector('tbody#papertable')
            dataContainer_files.innerHTML = ''
            arrayData_files.forEach(e_files => {
                dataContainer_files.innerHTML +=`
                <tr>
                    <td>${e_files.id}</td>
                    <td>${e_files.file_name}</td>
                    <td>${e_files.uploaded_at}</td>
                    <td>
                        <button class="btn btn-danger btn-delete" name="delete_category" data-id="${e_files.id}">Delete</button>
                    </td>
                </tr>
                `;
            });
        }
    </script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('tbody#result').addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-delete')) {
                    var categoryId = event.target.getAttribute('data-id');
                    deleteCategory(categoryId);
                }
            });
        });

        function deleteCategory(categoryId) {
            var formData = new FormData();
            formData.append('delete_category', true);
            formData.append('categoryid', categoryId);

            fetch('adminpapers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response from server:', data);

                var messageDiv = document.getElementById('result');
                if (data.includes('success')) {
                    messageDiv.innerHTML += '<p class="text-success">Category deleted successfully.</p>';
                    // Optionally, remove the deleted category row from the table
                    document.querySelector('button[data-id="' + categoryId + '"]').closest('tr').remove();
                } else {
                    messageDiv.innerHTML += '<p class="text-danger">Failed to delete category.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        }
    </script>

    <?php include 'includes/footer.php'; ?>
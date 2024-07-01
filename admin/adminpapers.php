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
        $uploadDir = '../uploads/'; // Specify your upload directory
        $fileName = basename($_FILES['paper_file']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['paper_file']['tmp_name'], $targetPath)) {
            // File uploaded successfully, insert into database
            $added = $admin->addPaper($categoryId, $paperName, $fileName, $targetPath);
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

    $sql = "SELECT * FROM paper_categories";
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
                            </a>
                            <a class="nav-link active" href="adminpapers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
                                    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                                    <path d="M3.5 1h.585A1.5 1.5 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5q-.001-.264-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1"/>
                                </svg>
                                <p>Papers</p>
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
                                <button type="submit" name="add_papers" class="btn btn-primary float-end">
                                    <i class="fa fa-plus">
                                    </i>
                                    Add Papers
                                </button>
                            </div>
                        </form>
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

        document.getElementById('newPapersForm').addEventListener('submit', function(event) {
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
            var dataContainer = document.querySelector('tbody')
            dataContainer.innerHTML = ''
            arrayData.forEach(e => {
                dataContainer.innerHTML +=`
                <tr>
                    <td>${e.id}</td>
                    <td>${e.name}</td>
                    <td>${e.created_at}</td>
                    <td>
                        <button class="btn btn-danger btn-delete" name="delete_category" data-id="${e.id}">Delete</button>
                    </td>
                </tr>
                `;
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('tbody').addEventListener('click', function(event) {
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
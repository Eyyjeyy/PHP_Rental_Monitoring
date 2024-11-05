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
    
    // Check if the form is submitted for adding a new user
    if(isset($_POST['add_user'])) {
        // Get the user data from the form
        $username = trim(htmlspecialchars($_POST['username']));
        $firstname = trim(htmlspecialchars($_POST['firstname']));
        $middlename = trim(htmlspecialchars($_POST['middlename']));
        $lastname = trim(htmlspecialchars($_POST['lastname']));
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);

        if(!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
            $_SESSION['error_message'] = "Firstname should only have letters and spaces.";
            header("Location: adminusers.php?error=add");
            exit();
        }

        // Call the addUser method to add the new user
        $added = $admin->addUser($username, $firstname, $middlename, $lastname, $password, $role);
        if($added) {
            // User added successfully, you can display a success message here if needed
            // echo "User added successfully.";
            header("Location: adminusers.php?user_added=1");
            exit();
        } else {
            // Error occurred while adding user, display an error message or handle as needed
            // echo "Error occurred while adding user.";

            $_SESSION['error_message'] = "Addition Failed due to an error";
            header("Location: adminusers.php?error=add");
            exit();
        }
    }

    // Check if there's an error message stored in the session
    if (isset($_SESSION['error_message'])) {
        // Display the error message as an alert
        echo '<div class="alert alert-danger mb-0" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        // Unset the session variable to clear the error message
        unset($_SESSION['error_message']);
    }

    if(isset($_POST['edit_user'])) {
        $username = htmlspecialchars($_POST['username']);
        $firstname = htmlspecialchars($_POST['firstname']);
        $middlename = htmlspecialchars($_POST['middlename']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);
        $user_id = $_POST['user_id'];
        $updated = $admin->updateUser($user_id, $username, $firstname, $middlename, $lastname, $password, $role);
        if($updated) {
            header("Location: adminusers.php");
            exit();
        } else {
            echo "Error occurred while updating user.";
        }
    }

    // Check if the form is submitted for deleting a user
    if(isset($_POST['delete_user'])) {
        // Get the user ID to be deleted
        $user_id = $_POST['user_id'];
        // Call the deleteUser method to delete the user
        $deleted = $admin->deleteUser($user_id);
        if($deleted) {
            // User deleted successfully, you can display a success message here if needed
            header("Location: adminusers.php?user_deleted=1");
        } else {
            // Error occurred while deleting user, display an error message or handle as needed
            echo "Error occurred while deleting user.";
        }
    }
    
    // Get sort column and direction from query parameters
    $sortColumn = isset($_GET['column']) ? $_GET['column'] : 'id';
    $sortDirection = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';

    // Ensure the sort column is one of the allowed columns to prevent SQL injection
    $allowedColumns = ['id', 'username', 'firstname', 'middlename', 'lastname', 'password', 'role'];
    if (!in_array($sortColumn, $allowedColumns)) {
        $sortColumn = 'id';
    }

    // Determine the next sort direction
    $nextSortDirection = $sortDirection === 'ASC' ? 'desc' : 'asc';

    // Determine the arrow symbol based on the current sort direction
    $arrow = $sortDirection === 'ASC' ? '↑' : '↓';

    // Retrieve users data from the database
    $sql = "SELECT * FROM users ORDER BY $sortColumn $sortDirection";
    $result = $admin->conn->query($sql);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "adminuser";
?>

    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body"  id="userbody">
                    <div class="row">
                        <div class="col-lg-12" id="tableheader">
                            <button class="btn btn-primary float-end table-buttons-update" id="new_user"><i class="fa fa-plus"></i> New User</button>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <a href="?column=id&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            #
                                            <?php echo $sortColumn === 'id' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=username&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Username
                                            <?php echo $sortColumn === 'username' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=firstname&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Firstname
                                            <?php echo $sortColumn === 'firstname' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=middlename&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Middlename
                                            <?php echo $sortColumn === 'middlename' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=lastname&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Lastname
                                            <?php echo $sortColumn === 'lastname' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=password&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Password
                                            <?php echo $sortColumn === 'password' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=role&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Role
                                            <?php echo $sortColumn === 'role' ? $arrow : ''; ?>
                                        </a>
                                    </th>
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
                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['middlename']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['password'] ? str_repeat('*', strlen($row['password'])) : 'N/A') . "</td>";
                                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a delete button for each record
                                        echo "<form method='POST' action='adminusers.php' class='float-xl-end align-items-center'>";
                                        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                        echo "<button type='submit' name='delete_user' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "<div class='col-xl-6 px-2'>";
                                        // Add a form with a update button for each record
                                        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                        echo "<button type='button' class='btn btn-primary update-user-btn float-xl-start table-buttons-update' data-id='" . $row['id'] . "' data-username='" . htmlspecialchars($row['username']) . "' data-firstname= '" . htmlspecialchars($row['firstname']) . "' data-middlename= '" . htmlspecialchars($row['middlename']) . "' data-lastname= '" . htmlspecialchars($row['lastname']) . "' data-password='" . htmlspecialchars($row['password']) . "' data-role='" . htmlspecialchars($row['role']) . "' style='width: 80px;'>Update</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- New User Modal -->
                <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header" style="background-color: #527853;">
                            <h5 class="modal-title text-white" id="newUserModalLabel">New User</h5>
                            <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="newUserForm" method="POST" action="adminusers.php">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Firstname</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="middlename" class="form-label">Middlename</label>
                                            <input type="text" class="form-control" id="middlename" name="middlename" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="lastname" class="form-label">Lastname</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-select" id="role" name="role" required>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="add_user" class="btn btn-primary table-buttons-update">Add User</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Update User Modal -->
                <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #527853;">
                                <h5 class="modal-title text-white" id="updateUserModalLabel">Update User</h5>
                                <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="updateUserForm" method="POST" action="adminusers.php">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="hidden" id="updateUserId" name="user_id">
                                            <div class="mb-3">
                                                <label for="updateUsername" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="updateUsername" name="username" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateFirstname" class="form-label">Firstname</label>
                                                <input type="text" class="form-control" id="updateFirstname" name="firstname" required>
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
                                                <label for="updatePassword" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="updatePassword" name="password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="updateRole" class="form-label">Role</label>
                                                <select class="form-select" id="updateRole" name="role" required>
                                                    <option value="admin">Admin</option>
                                                    <option value="user">User</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="edit_user" class="btn btn-primary table-buttons-update">Update User</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById('new_user').addEventListener('click', function () {
                        var newUserModal = new bootstrap.Modal(document.getElementById('newUserModal'), {
                            keyboard: false
                        });
                        newUserModal.show();
                    });
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var updateButtons = document.querySelectorAll('.update-user-btn');
                        updateButtons.forEach(function (button) {
                            button.addEventListener('click', function () {
                                var userId = button.getAttribute('data-id');
                                var username = button.getAttribute('data-username');
                                var firstname = button.getAttribute('data-firstname');
                                var middlename = button.getAttribute('data-middlename');
                                var lastname = button.getAttribute('data-lastname');
                                var password = button.getAttribute('data-password');
                                var role = button.getAttribute('data-role');
                                
                                // Fill the modal with the user's current data
                                document.getElementById('updateUserId').value = userId;
                                document.getElementById('updateUsername').value = username;
                                document.getElementById('updateFirstname').value = firstname;
                                document.getElementById('updateMiddlename').value = middlename;
                                document.getElementById('updateLastname').value = lastname;
                                document.getElementById('updatePassword').value = password;
                                document.getElementById('updateRole').value = role;
                                
                                var updateUserModal = new bootstrap.Modal(document.getElementById('updateUserModal'), {
                                    keyboard: false
                                });
                                updateUserModal.show();
                            });
                        });
                    });
                </script>
                <!-- Include jQuery library -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function fetchUnreadMessages() {
                        $.ajax({
                        url: '../fetch_unread_count.php',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data && data.unread_messages !== undefined) {
                            $('#unseenChatLabel').text(data.unread_messages);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error fetching unread messages:", textStatus, errorThrown);
                        }
                        });
                    }

                    // Run once on page load
                    fetchUnreadMessages();

                    // Poll every 3 seconds
                    setInterval(fetchUnreadMessages, 3000);
                </script>
                <script>
                    // Function to create and set the favicon
                    function setFavicon(iconURL) {
                    // Create a new link element
                    const favicon = document.createElement('link');
                    favicon.rel = 'icon';
                    favicon.type = 'image/x-icon';
                    favicon.href = iconURL;

                    // Remove any existing favicons
                    const existingIcons = document.querySelectorAll('link[rel="icon"]');
                    existingIcons.forEach(icon => icon.remove());

                    // Append the new favicon to the head
                    document.head.appendChild(favicon);
                    }

                    // Example usage: set the favicon on page load
                    document.addEventListener('DOMContentLoaded', () => {
                    setFavicon('../asset/Renttrack pro no word.png'); // Change to your favicon path
                    });
                </script>
                <!-- <p>Home</p> -->
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

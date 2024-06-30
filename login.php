<?php 
    // Include or require the file containing the Admin class definition
    include 'admin.php';
    $admin = new Admin();
    // $admin->handleRedirect();

    // Now you can instantiate the Admin class
    // if($admin->isLoggedIn()) {
    //     header("Location: index.php");
    //     exit();
    // }
    // if($admin->isLoggedIn() && $admin->session_role == 'admin') {
    //     header("Location: admin/admindashboard.php");
    //     exit();
    // }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        if ($admin->login($username, $password)) {
            if ($admin->session_role == 'admin') {
                header("Location: admin/admindashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['message'] = "Invalid credentials. Please try again.";
            header("Location: login.php"); // Redirect to clear the POST data
            exit();
        }
    }

    if ($admin->isLoggedIn()) {
        if ($admin->session_role == 'admin') {
            header("Location: admin/admindashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }

    // Set the title for this page
    $pageTitle = "Login Page"; // Change this according to the current page
?>

    <?php include 'regular/includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row" style="min-height: 100vh;">
            <div class="col-7">

            </div>
            <div class="d-flex flex-column justify-content-center align-items-center col-5 position-relative mx-auto">
                    <div class="d-flex justify-content-center position-absolute" style="margin-bottom: 70vh;">
                        <img src="asset/Renttrack pro no word.png" class="img-fluid" alt="...">
                    </div>
                <div class="login-panel panel panel-primary flex-column justify-content-center align-self-center mx-auto">
                    <div class="d-flex justify-content-center">
                        <p class="fs-3 fw-bold">
                            Log In
                        </p>
                    </div>
                    <div class="d-flex panel-body justify-content-center align-self-center">
                        <form method="POST" action="login.php" class="d-flex flex-column align-items-center">
                            <div class="form-group userform mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" style="color: #9b9b9b75;" width="20" height="20" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                </svg>
                                <input class="form-control user" placeholder="Username" type="text" name="username">
                            </div>
                            <div class="form-group userform mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" style="color: #9b9b9b75;" width="20" height="20" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                    <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                                <input class="form-control" placeholder="Password" type="password" name="password">
                            </div>
                            <div class="row w-100">
                                <div class="col-sm-12 col-md-6 p-0 text-center">
                                    <button type="submit" name="login" class="btn btn-mb btn-primary btn-block" style="min-width: 83px;">
                                        <span class="glyphicon glyphicon-log-in">
                                        </span>Login
                                    </button>
                                </div>
                                <div class="col-sm-12 col-md-6 text-center p-0" style="min-width: 83px;">
                                    <button type="submit" name="register" class="btn btn-mb btn-primary btn-block" style="min-width: 83px;">
                                        <span class="glyphicon glyphicon-log-in">
                                        </span>Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Script Code prevents form resubmission when refreshing -->
                <!-- login page after submitting wrong credentials -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    });
                </script>

                <?php
                    if(isset($_SESSION['message'])){
                        ?>
                            <div class="alert alert-info text-center">
                                <?php echo $_SESSION['message']; ?>
                            </div>
                        <?php

                        unset($_SESSION['message']);
                    }
                ?>
            </div>
        </div>
    </div>

    <?php include 'regular/includes/footer.php'; ?>
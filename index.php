<?php
    // session_start(); // Start the session (important for checking session variables)
    include 'admin.php';
    $admin = new Admin();
    // $admin->handleRedirect(); // Call handleRedirect to check login status and redirect

    // Removing this causes website to proceed to index.php despite isLoggedIn from admin.php returning false
    if(!$admin->isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    if($admin->isLoggedIn() && $admin->session_role == 'admin') {
        header("Location: admin/admindashboard.php");
        exit();
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        Welcome
    </title>
</head>
<body>
    <div>
        <p>Index.php</p>
        <?php
        if ($admin->isLoggedIn()) {
            // User is logged in
            echo "Welcome, admin!".$admin->session_uname;
            echo " User ID:     $admin->session_id";
            echo "      $admin->session_role";
        } else {
            // User is not logged in
            echo "You are not logged in.";
        }
        ?>
    </div>
    <div>
        <form method="POST" action="logout.php">
            <button type="submit" name="logout" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span>Logout</button>
        </form>
    </div>
    <div>
        <form method="POST" action="users.php">

        </form>
    </div>
</body>
</html>
<?php 

    include 'admin.php';
    $admin = new Admin();

    if ($admin->isLoggedIn()) {
        if ($admin->session_role == 'admin') {
            header("Location: admin/admindashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $result = $admin->registerUser($firstname, $middlename, $lastname, $password, $email, $role);
        if($result) {
            header("Location: register.php");
            exit();
        } else {
            echo "Registration Failed";
        }
    }

?>
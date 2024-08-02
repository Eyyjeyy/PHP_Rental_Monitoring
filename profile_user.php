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

    $pageTitle = 'Index Page';
?>

<?php include 'regular/includes/header_user.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light flex-column py-0" style="background-color: #527853;">
    <!-- <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../asset/Renttrack pro no word.png" class="img-fluid" alt="..." width="120" height="96">
        </a>
    </div> -->
    <div class="container-fluid mb-3 mt-3">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center align-self-stretch" id="navbarSupportedContent">
            <a class="navbar-brand py-0" href="#">
                <img src="asset/Renttrack pro no word_2.png" class="img-fluid" alt="..." style="height: 50px;">
            </a>
            <ul class="navbar-nav mb-2 mb-lg-0" style="margin-left: 50%;">
                <li class="nav-item mx-1">
                    <a class="nav-link h-100 d-flex align-items-center" aria-current="page" href="#">
                        <p class="mb-0 text-center">Contact</p>
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link h-100 d-flex align-items-center" href="users/payments.php">
                        <p class="mb-0">Payment</p>
                    </a>
                </li>
                <li class="nav-item mx-1">
                  <a class="nav-link h-100 d-flex align-items-center" href="#">
                    <p class="mb-0">Info</p>
                  </a>
                </li>
                <li class="nav-item mx-1">
                  <a class="nav-link h-100 d-flex align-items-center" href="profile_user.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                  </a>
                </li>
                <li class="nav-item mx-1">
                  <a class="nav-link h-100 d-flex align-items-center" href="chat_user.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                        <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                  </a>
                </li>
                <li class="d-none nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <li class="d-none nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-12 col-md-6 col-xxl-4 mx-md-auto">
            <div class="card">
                <div class="card-header">
                    <p class="fs-5 mb-0 text-center">Profile</p>
                </div>
                <div class="card-body" style="background-color: #F9E8D9;">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">First Name</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Middle Name</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Last Name</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Contact Number</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Email</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Password</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Confirm Password</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                    </div>
                    <div class="row justify-content-center justify-content-md-end">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-3 px-4" style="background-color: #527853; border-color: #527853;">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
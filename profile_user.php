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

<div class="container-fluid" style="margin-top: 100px; margin-bottom: 125px;">
    
    <div class="row mt-5 mb-5">
    <div class="row mx-auto w-65 d-flex align-items-center m-0 p-0">
        <div class="col-12">
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
                        <div class="col-12 col-md-6 mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">
                                <p class="fs-5 fw-bold mb-0">Contact Number</p>
                            </label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="col-12 col-md-6 mb-3 mt-3">
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
                            <button type="submit" class="btn btn-primary mb-3 mt-3 px-4" style="background-color: #527853; border-color: #527853;">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
    </div>
</div>

<?php include 'regular/includes/footer.php'; ?>
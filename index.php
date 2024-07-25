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
    <div class="container-fluid">
        <a class="navbar-brand py-0" href="#">
            <img src="asset/Renttrack pro no word_2.png" class="img-fluid" alt="..." style="height: 50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse align-self-stretch" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 w-100">
                <li class="ms-auto nav-item mx-1">
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
        <div class="col-12 col-lg-6 d-flex flex-column justify-content-center">
            <p class="fs-1 fw-bolder inter-google text-center mb-lg-5">Welcome to RentTrack Pro</p>
            <p class="fs-5 inter-google mx-sm-4 text-center d-lg-none">
                RentTrack Pro is a rental monitoring system, designed to cater to the needs of the landlord/s
                for a system for managing house rentals efficiently, leveraging the power of web technologies to streamline
                the processing and storage of tenant, payments and house information.
            </p>
            <p class="fs-5 inter-google mx-sm-4 text-center w-75 d-none d-lg-block align-self-center">
                RentTrack Pro is a rental monitoring system, designed to cater to the needs of the landlord/s
                for a system for managing house rentals efficiently, leveraging the power of web technologies to streamline
                the processing and storage of tenant, payments and house information.
            </p>
        </div>
        <div class="col-12 col-lg-6">
            <div class="row m-0 h-100 justify-content-center">
                <div id="carouselExampleControls" class="carousel slide align-self-center" data-bs-ride="">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="asset/Chalet-04633e05326048b3a8765fc6a646ca74.jpg" style="object-fit: cover; width: 100%; height: 600px;" class="mx-auto d-block" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/blackbasin-warm-5987.jpg" style="object-fit: cover; width: 100%; height: 600px;" class="mx-auto d-block" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/photo-1484931627545-f6d9b3aaa6eb.jfif" style="object-fit: cover; width: 100%; height: 600px;" class="mx-auto d-block" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
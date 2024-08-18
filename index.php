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

    $pageTitle = 'Home Page';
?>

<?php include 'regular/includes/header_user.php'; ?>



<div class="container-fluid p-0">
    <div class="row mx-auto w-65 d-flex align-items-center p-0" style="margin-bottom: 50px;" id="textindex">
        <div class="row mt-5 m-0 p-0">
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-center p-0" style="margin-right: 0px;">
            <p class="fs-1 fw-bolder inter-google text-center mb-lg-3 m-0 p-0" style="color: #3A583C;">Welcome</p>
                <p class="fs-5 inter-google mx-sm-4 text-center d-lg-none m-0 p-0">
                    RentTrack Pro is a rental monitoring system, designed to cater to the needs of the landlord/s
                    for a system for managing house rentals efficiently, leveraging the power of web technologies to streamline
                    the processing and storage of tenant, payments and house information.
                </p>
                <p class="fs-10 inter-google mx-sm-4 text-center w-80 d-none d-lg-block align-self-center m-0 p-0">
                    RentTrack Pro is a rental monitoring system, designed to cater to the needs of the landlord/s
                    for a system for managing house rentals efficiently, leveraging the power of web technologies to streamline
                    the processing and storage of tenant, payments and house information.
                </p>
            </div>
            <div class="col-12 col-lg-6 m-0 p-0" id="carousellpics">
                <div class="row m-0 h-100 justify-content-center p-0">
                    <div id="carouselExampleControls" class="carousel slide align-self-center m-0 p-0" data-bs-ride="">
                        <div class="carousel-inner m-0 p-0">
                            <div class="carousel-item active m-0 p-0">
                                <img src="asset/Chalet-04633e05326048b3a8765fc6a646ca74.jpg" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
                            </div>
                            <div class="carousel-item m-0 p-0">
                                <img src="asset/blackbasin-warm-5987.jpg" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
                            </div>
                            <div class="carousel-item m-0 p-0">
                                <img src="asset/photo-1484931627545-f6d9b3aaa6eb.jfif" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
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
    <div class="row mx-auto w-65 d-flex align-items-center p-0" style="margin-bottom: 100px;">

        <div class="row mt-5 m-0 p-0">
         
            <div class="col-12 col-lg-6 m-0 p-0" id="carousellpics">
                <div class="row m-0 h-100 justify-content-center p-0">
                    <div id="carouselExampleControls" class="carousel slide align-self-center m-0 p-0" data-bs-ride="">
                        <div class="carousel-inner m-0 p-0">
                            <div class="carousel-item active m-0 p-0">
                                <img src="asset/Chalet-04633e05326048b3a8765fc6a646ca74.jpg" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
                            </div>
                            <div class="carousel-item m-0 p-0">
                                <img src="asset/blackbasin-warm-5987.jpg" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
                            </div>
                            <div class="carousel-item m-0 p-0">
                                <img src="asset/photo-1484931627545-f6d9b3aaa6eb.jfif" style="object-fit: cover; width: 100%; height: 300px;" class="mx-auto d-block" alt="...">
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
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-center m-0 p-0" id="textindex-1">
                <p class="fs-1 fw-bolder inter-google text-center mb-lg-3 m-0 p-0" style="color: #F28543;">Goals</p>
                <p class="fs-5 inter-google mx-sm-4 text-center d-lg-none m-0 p-0">
                  RentTrack Pro's goal is to streamline rental management for landlords by optimizing tenant data, simplifying payment processes, 
                    and accurately tracking property information. Through advanced web technologies, it aims to enhance efficiency and ease of use 
                    in property management.
                </p>
                <p class="fs-10 inter-google mx-sm-4 text-center w-80 d-none d-lg-block align-self-center m-0 p-0">
                    RentTrack Pro's goal is to streamline rental management for landlords by optimizing tenant data, simplifying payment processes, 
                    and accurately tracking property information. Through advanced web technologies, it aims to enhance efficiency and ease of use 
                    in property management.
                </p>
            </div>
        </div>
    </div>
</div>


<?php include 'regular/includes/footer.php'; ?>
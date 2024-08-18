<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <?php if ($pageTitle == 'Index Page' || $pageTitle == 'Chat Page' || $pageTitle == 'Info Page'): ?>
        <link rel="stylesheet" href="asset/user.css">
        <link rel="icon" type="image/x-icon" href="asset/Renttrack pro no word.png">
    <?php else: ?>
        <link rel="stylesheet" href="../asset/user.css">
        <link rel="icon" type="image/x-icon" href="../asset/Renttrack pro no word.png">
    <?php endif; ?>
    <!-- <link rel="stylesheet" href= "../asset/user.css"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <title><?php echo isset($pageTitle) ? $pageTitle : 'Default Title'; ?></title>
  </head>
  <body>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->



     <!-- Start NavBar -->


    <nav class="navbar navbar-expand-lg navbar-light flex-column py-0" id="navbar" style="background-color: #3A583C;">
    <!-- <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../asset/Renttrack pro no word.png" class="img-fluid" alt="..." width="120" height="96">
        </a>
    </div> -->

    <div class="container-fluid mb-3 mt-3" id="navbarbar">
    <div class="row mx-auto w-65 d-flex align-items-center">
    
    




        <!-- Left-aligned image -->
        <div class="col d-flex align-items-center">
            <a class="navbar-brand py-0" href="index.php">
                <img src="asset/Renttrack pro no word_2.png" id="userpiclogo" class="img-fluid" alt="..." style="height: 50px;">
            </a>
        </div>
        
        <!-- Right-aligned navigation links and icons -->
        <div class="col d-flex justify-content-end" id="navnav">
        <button class="navbar-toggler" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
        </button>


        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="users/payments.php">Payment</a>
        <a class="dropdown-item" href="info.php">Info</a>
        <a class="dropdown-item" href="profile_user.php">Profile</a>
        <a class="dropdown-item" href="chat_user.php">Chat</a>
        <a class="dropdown-item" href="logout.php">Log Out</a>
    </div>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="users/payments.php">
                            <p class="mb-0">Payment</p>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="info.php">
                            <p class="mb-0">Info</p>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="profile_user.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext" href="chat_user.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link h-100 d-flex align-items-center" id="icontext"href="logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

</nav>

 <!-- End of Navbar -->



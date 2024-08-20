<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href= "../asset/admin.css"> -->
    <?php if ($pageTitle == 'Chat Page'): ?>
        <link rel="stylesheet" href="asset/admin.css">
    <?php else: ?>
        <link rel="stylesheet" href="../asset/admin.css">
    <?php endif; ?>
    
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->



    <title><?php echo isset($pageTitle) ? $pageTitle : 'Default Title'; ?></title>
    <?php
      if ($page == 'adminexpenses') {
        echo 
        '<style>
          .notifs {
            right: 40%;
          }
          @media (max-width: 748px) {
            a.nav-link {
              justify-content: start;
            }
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 737px) {
            a.nav-link {
              justify-content: center;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      } else if ($page == 'admindashboard') {
        echo 
        '<style>
          .notifs {
            right: 40%;
          }
          @media (max-width: 1198px) {
            div.sidebar {
              padding: 0;
              min-width: 100% !important;
              max-width: none;
              width: 100%;
              min-height: 20vh;
            }
            .navbar-expand .navbar-toggler {
              display: inline-block;
            }
            .navbar-expand .navbar-collapse {
            }
          }
          @media (max-width: 748px) {
            a.nav-link {
              justify-content: center;
            }
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 737px) {
            a.nav-link {
              justify-content: center;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      } else if ($page == 'admincategories') {
        echo 
        '<style>
          .notifs {
            right: 40%;
          }
          @media (max-width: 748px) {
            div.sidebar {
              min-height: 100vh;
            }
            a.nav-link {
              justify-content: start;
            }
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 737px) {
            
          }
          @media (max-width: 555px) {
            div.sidebar {
              min-height: 20vh;
            }
            a.nav-link {
              justify-content: center;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
            .nav-link {
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      } else if ($page == 'adminhouses') {
        echo 
        '<style>
          .notifs {
            right: 40%;
          }
          @media (max-width: 1074px) {
            div.sidebar {
              padding: 0;
              min-width: 250px !important;
              max-width: none;
              width: 100%;
              min-height: 20vh;
            }
            .nav-link {
              justify-content: center;
            }
            a p {
              margin-left: 0px;
              width: 90px;
              padding-left: 10px;
            }
            .notifs {
              right: 30%;
            }
          }
          @media (max-width: 748px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      } else if ($page == 'adminhistory') {
        echo 
        '<style>
          .notifs {
            right: 40%;
          }
          @media (max-width: 838px) {
            div.sidebar {
              padding: 0;
              min-width: 250px !important;
              max-width: none;
              width: 100%;
              min-height: 20vh;
            }
            .nav-link {
              justify-content: center;
            }
            a p {
              margin-left: 0px;
              width: 90px;
              padding-left: 10px;
            }
          }
          @media (max-width: 748px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      } else if ($page == "adminpapers") {
        echo 
        '<style>
          .notifs {
            right: 30%;
          }
          @media (max-width: 882px) {
            div.sidebar {
              padding: 0;
              min-width: 250px !important;
              max-width: none;
              width: 100%;
              min-height: 20vh;
            }
            .nav-link {
              justify-content: center;
            }
            a p {
              margin-left: 0px;
              width: 90px;
              padding-left: 10px;
            }
          }
          @media (max-width: 748px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 737px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 550px) {
            .notifs {
              right: 20%;
            }
          }
          @media (max-width: 480px) {
            .notifs {
              right: 10%;
            }
            .nav-link {
            }
          }
          @media (max-width: 380px) {
            .notifs {
              right: 2%;
            }
            .nav-link {
            }
          }
          @media (max-width: 300px) {
            .notifs {
              right: -5%;
            }
            .nav-link {
            }
          }
        </style>';
      }
    ?>
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

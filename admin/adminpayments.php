<?php

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

    if(isset($_POST['approve_payment'])) {
        // Get the payment ID to be approved
        $paymentsid = $_POST['paymentsid'];

        $approved = $admin->approvePayment($paymentsid);
        if($approved) {

            header("Location: adminpayments.php?payment=approved");
        } else {

            echo "Error occurred while approving a payment.";
        }
    }

    if(isset($_POST['decline_payment'])) {
        // Get the payment ID to be approved
        $paymentsid = $_POST['paymentsid'];

        $declined = $admin->declinePayment($paymentsid);
        if($declined) {
            header("Location: adminpayments.php?payment=declined");
        } else {
            echo "Error occurred while approving a payment.";
        }
    }

    // Get sort column and direction from query parameters
    $sortColumn = isset($_GET['column']) ? $_GET['column'] : 'date_payment';
    $sortDirection = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';

    // Ensure the sort column is one of the allowed columns to prevent SQL injection
    $allowedColumns = ['id', 'name', 'amount', 'date_payment'];
    if (!in_array($sortColumn, $allowedColumns)) {
        $sortColumn = 'date_payment';
    }

    // Determine the next sort direction
    $nextSortDirection = $sortDirection === 'ASC' ? 'desc' : 'asc';

    // Determine the arrow symbol based on the current sort direction
    $arrow = $sortDirection === 'ASC' ? '↑' : '↓';

    $sql = "SELECT * FROM payments ORDER BY $sortColumn $sortDirection";
    $result = $admin->conn->query($sql);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "adminpayments";
?>


    <div class="container-fluid">
        <div class="row">
        <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body" style="margin-top: 12px;">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- <input type="text" id="searchBar" placeholder="Search..." class="form-control mb-3" /> -->
                            <!-- <button class="btn btn-primary float-end" id="new_category"><i class="fa fa-plus"></i> New Category</button> -->
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="searchBar" placeholder="Search..." class="form-control mb-3 " style="max-width: 180px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive"  id="tablelimiter">
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
                                        <a href="?column=name&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529;">
                                            Tenant
                                            <?php echo $sortColumn === 'name' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="?column=amount&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none d-inline-block" style="color: #212529; min-width: 100px;">
                                            Amount <?php echo $sortColumn === 'amount' ? $arrow : ''; ?>
                                        </a>
                                    </th>
                                    <th scope="col">Receipt</th>
                                    <th scope="col">
                                        <a href="?column=date_payment&direction=<?php echo $nextSortDirection; ?>" class="text-decoration-none" style="color: #212529;">
                                            Date <?php echo $sortColumn === 'date_payment' ? $arrow : ''; ?>
                                        </a>
                                    </th>

                                    <!-- <th scope="col">
                                        <a href="#" id="sortPaymentDate" data-sort="asc" class="text-decoration-none" style="color: #212529;">Payment Date <span id="sortIndicator">▲</span></a>
                                    </th> -->
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="paymentTableBody">
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                        // echo "<td><img src='" . $row["filepath"] . "' alt='Receipt' class='img-fluid' style='max-width: 100px; height: auto;'></td>";

                                        echo "
                                        <td>
                                            <a href='#' data-bs-toggle='modal' data-bs-target='#imageModal" . $row["id"] . "'>
                                                <img src='" . $row["filepath"] . "' alt='Receipt' class='img-fluid' style='max-width: 100px; height: auto;'>
                                            </a>
                                        </td>
                                        <div class='modal fade' id='imageModal" . $row["id"] . "' tabindex='-1' aria-labelledby='imageModalLabel" . $row["id"] . "' aria-hidden='true'>
                                            <div class='modal-dialog modal-dialog-centered'>
                                                <div class='modal-content'>
                                                    <div class='modal-header' style='background-color: #527853;'>
                                                        <h5 class='modal-title text-white' id='imageModalLabel" . $row["id"] . "'>Receipt Preview</h5>
                                                        <button type='button' class='btn-svg p-0' data-bs-dismiss='modal' aria-label='Close' style='width: 24px; height: 24px;'>
                                                            <svg xmlns='http://www.w3.org/2000/svg' fill='currentColor' class='bi bi-x-lg w-100' viewBox='0 0 16 16'>
                                                                <path d='M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z'/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <img src='" . $row["filepath"] . "' alt='Receipt' class='img-fluid'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";

                                        echo "<td>" . htmlspecialchars($row['date_payment']) . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                        echo "<div class='col-xxl-6 px-2'>";
                                        // Add a form with a delete button for each record
                                        echo "<form method='POST' action='adminpayments.php' class='float-xxl-end align-items-center'>";
                                        echo "<input type='hidden' name='paymentsid' value='" . $row['id'] . "'>";
                                        $approval = $row['approval'] === 'true' ? 'disabled' : '';
                                        echo "<button type='submit' name='approve_payment' class='btn btn-primary d-flex table-buttons-update' style='width: 120px;' $approval>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-check align-self-center' viewBox='0 0 16 16'>
                                            <path d='M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z'/>
                                        </svg>
                                        Approve
                                        </button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "<div class='col-xxl-6 d-flex justify-content-center justify-content-xxl-start px-2'>";
                                        // Add a form with a update button for each record
                                        echo "<form method='POST' action='adminpayments.php' class='align-items-center'>";
                                        echo "<input type='hidden' name='paymentsid' value='" . $row['id'] . "'>";
                                        $decline = $row['approval'] === 'false' ? 'disabled' : '';
                                        echo "<button type='submit' name='decline_payment' class='btn btn-danger update-category-btn float-xxl-start d-flex table-buttons-delete' data-id='" . $row['id'] . "' data-paymentname='" . htmlspecialchars($row['name']) . "' style='width: 120px;' $decline>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-ban-fill align-self-center me-2' viewBox='0 0 16 16'>
                                            <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M2.71 12.584q.328.378.706.707l9.875-9.875a7 7 0 0 0-.707-.707l-9.875 9.875Z'/>
                                        </svg>
                                        Decline
                                        </button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No categories found</td></tr>";
                                }
                                $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    $(document).ready(function() {
                        $('#searchBar').on('input', function() {
                            var searchQuery = $(this).val();

                            $.ajax({
                                url: 'search/search_payments.php', // PHP script to perform search
                                type: 'POST',
                                data: { query: searchQuery },
                                success: function(response) {
                                    $('tbody').html(response); // Replace table body with new data
                                }
                            });
                        });
                    });
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
            </div>
        </div>
    </div>
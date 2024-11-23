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

    if (isset($_POST['send_reminder'])) {
        $reminder_tenant_id = trim(htmlspecialchars($_POST['tenantsid']));
        $reminder_missing_months = trim(htmlspecialchars($_POST['missing_months']));
        $reminder_missed_months_dates = htmlspecialchars($_POST['missed_months_dates']);

        $send_reminder = $admin->delinquencySendReminder($reminder_tenant_id, $reminder_missing_months, $reminder_missed_months_dates);
        if($send_reminder) {
            header("Location: admindelinquency.php?reminder_sent=1");
            exit();
        } else {
            if(empty($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "Reminder Failed due to an error";
            }
            header("Location: admindelinquency.php?error=reminder");
            exit();
        }
    }

    // Check if there's an error message stored in the session
    if (isset($_SESSION['error_message'])) {
        // Display the error message as an alert
        echo '<div class="alert alert-danger mb-0" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        // Unset the session variable to clear the error message
        unset($_SESSION['error_message']);
    }

    // $sql = "
    // SELECT 
    //     tenants.*, 
    //     houses.*,
    //     GROUP_CONCAT(payments.amount ORDER BY payments.date_payment DESC) AS payment_amounts,
    //     GROUP_CONCAT(payments.date_payment ORDER BY payments.date_payment DESC) AS date_payment
    // FROM tenants 
    // LEFT JOIN payments ON tenants.id = payments.tenants_id
    // LEFT JOIN houses ON tenants.house_id = houses.id
    // GROUP BY tenants.id
    // ";
    // $result = $admin->conn->query($sql);
    $sql = "
    SELECT 
        tenants.id AS tenant_id,
        tenants.*, 
        houses.*,
        GROUP_CONCAT(payments.amount ORDER BY payments.date_payment DESC) AS payment_amounts,
        GROUP_CONCAT(payments.date_payment ORDER BY payments.date_payment DESC) AS date_payment,
        MAX(payments.date_payment) AS last_payment_date  -- Get the last payment date
    FROM tenants 
    LEFT JOIN payments ON tenants.id = payments.tenants_id
    LEFT JOIN houses ON tenants.house_id = houses.id
    GROUP BY tenants.id
    ";
    $result = $admin->conn->query($sql);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "admindelinquency";
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/header.php'; ?>
        <div class="col main content" style="padding-top: 12px; padding-bottom: 12px;">
            <div class="card-body" style="margin-top: 0; height:100%; max-height: 100%; overflow-y: auto;">
                <div class="row">
                    <div class="col-lg-12" id="tableheader">
                        <div class="row">
                            <div class="col-6">
                                <input type="text" id="searchBar" placeholder="Search..." class="form-control mb-3 " style="max-width: 180px;" />
                            </div>
                            <!-- <div class="col-6">
                                <button class="btn btn-primary float-end table-buttons-update" id="new_contract"><i class="fa fa-plus"></i> New Contract</button>
                            </div> -->
                        </div>                            
                    </div>
                </div>
                <div class="table-responsive"  id="tablelimiter">
                    <table class="table table-striped table-bordered" id="contractTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" data-column="tenantname" class="sortable-column" data-column="tenantname" style="cursor: pointer;">
                                    Tenant
                                    <span class="sort-arrow" data-column="tenantname"></span>
                                </th>
                                <th scope="col" data-column="tenantapproval" class="sortable-column">
                                    House
                                    <span id="tenantapprovalSortArrow"></span>
                                </th>
                                <th scope="col" data-column="datestart" class="sortable-column">
                                    Months Missed
                                    <span id="datestartSortArrow"></span>
                                </th>
                                <th scope="col" data-column="expirationdate" class="sortable-column">
                                    # of Missed Payments
                                    <span id="expirationdateSortArrow"></span>
                                </th>
                                <th scope="col" data-column="expirationdate" class="sortable-column">
                                    Amount
                                    <span id="expirationdateSortArrow"></span>
                                </th>
                                <th scope="col">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="contractTableBody">
                            <?php
                            $hasData = false;
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while($row = $result->fetch_assoc()) {
                                    $tenant_id = $row['id']; // Unknown ID, probably house id
                                    $date_preferred = $row['date_preferred'];
                                    $last_payment_date = $row['last_payment_date'];

                                    // If no payment has been made, set the last payment date to the current date
                                    if (!$last_payment_date) {
                                        $last_payment_date = date('Y-m-d');
                                    }

                                    // Convert dates to timestamps for easier date manipulation
                                    $date_preferred_timestamp = strtotime($date_preferred);
                                    $current_date_timestamp = strtotime(date('Y-m-d'));  // Today's date

                                    // Offset date_preferred by 1 month
                                    $start_date_timestamp = strtotime("+1 month", $date_preferred_timestamp);

                                    // Calculate the number of months between start_date and today
                                    $months_difference = (date('Y', $current_date_timestamp) - date('Y', $start_date_timestamp)) * 12 
                                                        + date('m', $current_date_timestamp) - date('m', $start_date_timestamp);

                                    // Initialize variables
                                    $missing_months = 0;
                                    $missed_months_dates = []; // Array to store missed months

                                    // Loop through all months from start_date to today
                                    for ($i = 0; $i <= $months_difference; $i++) {
                                        $current_month = date('Y-m', strtotime("+$i months", $start_date_timestamp));
                                        $payment_found = false;

                                        // Check if the current month has any payment
                                        foreach (explode(',', $row['date_payment']) as $payment_date) {
                                            if (substr($payment_date, 0, 7) == $current_month) {  // Check year-month format
                                                $payment_found = true;
                                                break;
                                            }
                                        }

                                        // If no payment is found for this month, increment missing_months
                                        if (!$payment_found) {
                                            $missing_months++;
                                            $missed_months_dates[] = $current_month; // Add to missed months list
                                        }
                                    }

                                    // Only display the tenant in delinquency if his/her missing months of payments are 2 or more months
                                    if ($missing_months >= 2 ) {
                                        $hasData = true; // Mark that at least one row is displayed
                                        echo "<tr>";
                                            echo "<th scope='row'>" . $row['tenant_id'] . "</th>";
                                            echo "<td>" . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['mname']) . " " . htmlspecialchars($row['lname']) . "</td>";
                                            echo "<td class='text-center'>" . ($row['house_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars(implode(', ', $missed_months_dates)) . "</td>";
                                            echo "<td>" . htmlspecialchars($missing_months) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['payment_amounts']) . "</td>";
                                            echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                                echo "<div class='row justify-content-center m-0'>";
                                                    echo "<div class='col-xxl-4 px-2'>";
                                                        // Add a form with a delete button for each record
                                                        // echo "<form method='POST' action='adminpayments.php' class='float-xxl-end align-items-center'>";
                                                        echo "<form method='POST' action='admindelinquency.php' class='align-items-center'>";
                                                            echo "<input type='hidden' name='tenantsid' value='" . $row['tenant_id'] . "'>";
                                                            echo "<input type='hidden' name='missing_months' value='" . $missing_months . "'>";
                                                            echo "<input type='hidden' name='missed_months_dates' value='" . htmlspecialchars(implode(', ', $missed_months_dates)) . "'>";
                                                            echo "<button type='submit' name='send_reminder' class='btn btn-primary d-flex table-buttons-update justify-content-center' style='width: 160px;'>
                                                                Send Reminder
                                                            </button>";
                                                        echo "</form>";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</td>";
                                        echo "</tr>";
                                    }

                                    // echo "<tr>";
                                    //     echo "<th scope='row'>" . $row['id'] . "</th>";
                                    //     echo "<td>" . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['mname']) . " " . htmlspecialchars($row['lname']) . "</td>";
                                    //     echo "<td class='text-center'>" . ($row['house_name']) . "</td>";
                                    //     echo "<td>" . htmlspecialchars($missing_months) . "</td>";
                                    //     echo "<td>" . htmlspecialchars($row['expirationdate']) . "</td>";
                                    //     echo "<td>" . htmlspecialchars($row['expirationdate']) . "</td>";
                                    //     echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                    //         echo "<div class='row justify-content-center m-0'>";
                                    //             echo "<div class='col-xl-6 px-2'>";
                                    //                 // Add a form with a delete button for each record
                                    //                 echo "<form method='POST' action='admin_contract_template.php' class='float-xl-end align-items-center' style='height:100%;'>";
                                    //                     echo "<input type='hidden' name='contractid' value='" . $row['id'] . "'>";
                                    //                     echo "<button type='submit' name='delete_contract' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                    //                 echo "</form>";
                                    //             echo "</div>";
                                    //             echo "<div class='col-xl-6 px-2'>";
                                    //                 if (!empty($row['fileurl'])) { // Ensure fileurl is not empty
                                    //                     echo "<a href='". '..' . htmlspecialchars($row['fileurl']) . "' download class='btn btn-success table-buttons-download justify-content-center table-buttons-update' style='width: 120px;'>Download</a>";
                                    //                 } else {
                                    //                     echo "<span>No file available</span>";
                                    //                 }
                                    //             echo "</div>";
                                    //         echo "</div>";
                                    //     echo "</td>";
                                    // echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No contracts found</td></tr>";
                            }
                            // If no rows matched the condition
                            if (!$hasData) {
                                echo "<tr><td colspan='7' class='text-center'>No tenants with 2 or more missed months.</td></tr>";
                            }
                            $admin->conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
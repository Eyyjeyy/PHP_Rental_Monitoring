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


    $sql = "SELECT date_payment, 
               COUNT(*) AS total_payments, 
               SUM(p.amount) AS total_amount_payment,
               (SELECT SUM(e.amount) FROM expenses e WHERE e.date = p.date_payment) AS total_amount_expense,
               (SELECT COUNT(*) FROM expenses e WHERE e.date = p.date_payment) AS total_expenses
            FROM payments p
            GROUP BY date_payment";
    $result = $admin->conn->query($sql);


    // $sql_2 = "SELECT 
    //     tenants.house_id,
    //     houses.house_name,
    //     GROUP_CONCAT(tenants.id) AS tenant_ids,
    //     GROUP_CONCAT(DISTINCT tenants.fname, ' ', tenants.mname, ' ', tenants.lname SEPARATOR ', ') AS tenant_names,
    //     COUNT(tenants.id) AS total_tenants,
    //     (SELECT COUNT(*) FROM payments WHERE payments.houses_id = tenants.house_id) as total_number_payments,
    //     (SELECT SUM(payments.amount) FROM payments WHERE payments.houses_id = tenants.house_id) AS total_amount_payment,
    //     (SELECT COUNT(*) FROM expenses WHERE expenses.house_id = tenants.house_id) as total_number_expenses,
    //     (SELECT SUM(expenses.amount) FROM expenses WHERE expenses.house_id = tenants.house_id) AS total_amount_expenses,
    //     payments.date_payment AS payment_date
    // FROM tenants
    // LEFT JOIN houses ON tenants.house_id = houses.id
    // LEFT JOIN payments ON tenants.house_id = payments.houses_id
    // GROUP BY tenants.house_id";
    $sql_2 = "
        SELECT 
            tenants.house_id,
            houses.house_name,
            GROUP_CONCAT(DISTINCT tenants.id) AS tenant_ids,
            GROUP_CONCAT(DISTINCT CONCAT(tenants.fname, ' ', tenants.mname, ' ', tenants.lname) SEPARATOR ', ') AS tenant_names,
            COUNT(DISTINCT tenants.id) AS total_tenants,
            COUNT(DISTINCT payments.id) AS total_number_payments,
            SUM(payments.amount) AS total_amount_payment,
            (
                SELECT COUNT(*) 
                FROM expenses 
                WHERE expenses.house_id = tenants.house_id AND expenses.date = payments.date_payment
            ) AS total_number_expenses,
            (
                SELECT SUM(expenses.amount) 
                FROM expenses 
                WHERE expenses.house_id = tenants.house_id AND expenses.date = payments.date_payment
            ) AS total_amount_expenses,
            payments.date_payment AS payment_date
        FROM tenants
        LEFT JOIN houses ON tenants.house_id = houses.id
        LEFT JOIN payments ON tenants.house_id = payments.houses_id
        GROUP BY tenants.house_id, payments.date_payment
        ORDER BY tenants.house_id, payments.date_payment
    ";
    $result_2 = $admin->conn->query($sql_2);



    // $sql_3 = "SELECT house_name
    //     FROM houses h
    //     WHERE NOT EXISTS (
    //         SELECT 1 
    //         FROM tenants t 
    //         WHERE t.house_id = h.id
    //     )
    // ";
    $sql_3 = "
    SELECT h.id, h.house_name, 
           IFNULL(MAX(t.date_end), CURDATE()) AS vacant_until
    FROM houses h
    LEFT JOIN tenants t ON h.id = t.house_id
    GROUP BY h.id
    HAVING MAX(t.date_end) < CURDATE() OR MAX(t.date_end) IS NULL
    ";
    $result_3 = $admin->conn->query($sql_3);


    $sql_4 = "
    SELECT 
        DATE(t.date_start) AS date, 
        COUNT(DISTINCT t.id) AS number_of_tenants, 
        COUNT(DISTINCT t.house_id) AS number_of_apartments
    FROM tenants t
    WHERE t.date_start IS NOT NULL
    GROUP BY DATE(t.date_start)
    ORDER BY DATE(t.date_start)
    ";

    $result_4 = $admin->conn->query($sql_4);


    $sql_5 = "
    SELECT 
        tenants.id AS tenant_id,
        tenants.*, 
        houses.*,
        eviction_popup.users_id AS eviction_users_id,
        eviction_popup.file_path,
        SUM(payments.amount) AS total_amount_payment,
        GROUP_CONCAT(payments.amount ORDER BY payments.date_payment DESC) AS payment_amounts,
        GROUP_CONCAT(payments.date_payment ORDER BY payments.date_payment DESC) AS date_payment,
        MAX(payments.date_payment) AS last_payment_date  -- Get the last payment date
    FROM tenants 
    LEFT JOIN payments ON tenants.id = payments.tenants_id AND payments.approval = 'true' -- Include only approved payments
    LEFT JOIN houses ON tenants.house_id = houses.id
    LEFT JOIN (
        SELECT 
            users_id, 
            file_path 
        FROM eviction_popup 
        WHERE id = (
            SELECT MAX(id) 
            FROM eviction_popup AS ep_sub 
            WHERE ep_sub.users_id = eviction_popup.users_id
        )
    ) AS eviction_popup ON tenants.users_id = eviction_popup.users_id
    GROUP BY tenants.id
    ";
    $result_5 = $admin->conn->query($sql_5);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "adminreports";
?>

    <div class="container-fluid">
        <div class="row">
        <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body"  id="userbody" style="margin-top: 0; height: 100%; max-height: 100%;overflow-y: auto;display: flex;flex-direction: column;">
                    <div class="row">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex h-100 align-items-end justify-content-start">
                                        <h4 class="fw-bold mb-0">General Income</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary table-buttons-update" id="general_income_filter"><i class="fa fa-plus"></i> Filter</button>
                                        <button class="btn btn-primary table-buttons-update ms-2" id="new_user"><i class="fa fa-plus"></i> Monthly</button>
                                        <button class="btn btn-primary table-buttons-update mx-2" id="new_user"><i class="fa fa-plus"></i> Quarterly</button>
                                        <button class="btn btn-primary table-buttons-update me-2" id="new_user"><i class="fa fa-plus"></i> Yearly</button>
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter" style="max-height: 100%;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="id">ID</th> -->
                                    <th scope="col" class="sortable-column" data-column="username">Number of Payments</th>
                                    <th scope="col" class="sortable-column" data-column="firstname">Total Amount of Payments</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Number of Expenses</th>
                                    <th scope="col" class="sortable-column" data-column="lastname">Total Amount of Expenses</th>
                                    <th scope="col" class="sortable-column" data-column="phonenumber">Total Income</th>
                                    <th scope="col" class="sortable-column" data-column="email">Dates</th>
                                </tr>
                            </thead>
                            <tbody id="general_income_tbody">
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        $totalPayment = $row['total_amount_payment'];
                                        $totalExpense = $row['total_amount_expense'];
                                        $netAmount = $totalPayment - $totalExpense;
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row['total_payments'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row['total_amount_payment']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['total_amount_expense']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['total_expenses']) . "</td>";
                                        echo "<td>" . htmlspecialchars($netAmount) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['date_payment']) . "</td>";
                                        // echo "<td>" . htmlspecialchars($row['phonenumber'] ? $row['phonenumber'] : 'N/A') . "</td>";
                                        // echo "<td>" . htmlspecialchars($row['email'] ? $row['email'] : 'N/A') . "</td>";
                                        // echo "<td>" . htmlspecialchars($row['password'] ? str_repeat('*', strlen($row['password'])) : 'N/A') . "</td>";
                                        // echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                        // echo "<td class='justify-content-center text-center align-middle'>";
                                        // echo "<div class='row justify-content-center m-0'>";
                                        // echo "<div class='col-xl-6 px-2'>";
                                        // // Add a form with a delete button for each record
                                        // echo "<form method='POST' action='adminusers.php' class='float-xl-end align-items-center'>";
                                        // echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                        // echo "<button type='submit' name='delete_user' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                        // echo "</form>";
                                        // echo "</div>";
                                        // echo "<div class='col-xl-6 px-2'>";
                                        // // Add a form with a update button for each record
                                        // echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                        // echo "<button type='button' class='btn btn-primary update-user-btn float-xl-start table-buttons-update' data-id='" . $row['id'] . "' data-username='" . htmlspecialchars($row['username']) . "' data-firstname= '" . htmlspecialchars($row['firstname']) . "' data-middlename= '" . htmlspecialchars($row['middlename']) . "' data-lastname= '" . htmlspecialchars($row['lastname']) . "' data-password='" . htmlspecialchars($row['password']) . "' data-role='" . htmlspecialchars($row['role']) . "'data-email='" . htmlspecialchars($row['email']) . "'data-number='" . htmlspecialchars($row['phonenumber']) . "' style='width: 80px;'>Update</button>";
                                        // echo "</div>";
                                        // echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>



                    <div class="row mt-5">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex h-100 align-items-end justify-content-start">
                                        <h4 class="fw-bold mb-0">Income per Tenant/Apartment</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Filter</button>
                                        <button class="btn btn-primary table-buttons-update ms-2" id="new_user"><i class="fa fa-plus"></i> Monthly</button>
                                        <button class="btn btn-primary table-buttons-update mx-2" id="new_user"><i class="fa fa-plus"></i> Quarterly</button>
                                        <button class="btn btn-primary table-buttons-update me-2" id="new_user"><i class="fa fa-plus"></i> Yearly</button>
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter" style="max-height: 100%;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="id">ID</th> -->
                                    <th scope="col" class="sortable-column" data-column="username">Tenant</th>
                                    <th scope="col" class="sortable-column" data-column="firstname">Apartment</th>
                                    <th scope="col" class="sortable-column" data-column="middlename"># of Payments</th>
                                    <th scope="col" class="sortable-column" data-column="lastname">Total Amount of Payments</th>
                                    <th scope="col" class="sortable-column" data-column="phonenumber">Number of Expenses</th>
                                    <th scope="col" class="sortable-column" data-column="email">Total Income</th>
                                    <th scope="col" class="sortable-column" data-column="email">Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_2->num_rows > 0) {
                                    // Output data of each row
                                    while($row_2 = $result_2->fetch_assoc()) {
                                        $IncomePerTenantTable_totalPayment = $row_2['total_amount_payment'];
                                        $IncomePerTenantTable_totalExpense = $row_2['total_amount_expenses'];
                                        $IncomePerTenantTable_netAmount = $IncomePerTenantTable_totalPayment - $IncomePerTenantTable_totalExpense;
                                        echo "<tr>";
                                        echo "<th scope='row' style='max-width: 100px;'>" . $row_2['tenant_names'] . "</th>";
                                        echo "<td style='width: 150px;'>" . htmlspecialchars($row_2['house_name']) . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_2['total_number_payments']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_2['total_amount_payment']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_2['total_number_expenses']) . "</td>";
                                        echo "<td>" . htmlspecialchars($IncomePerTenantTable_netAmount) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_2['payment_date']) . "</td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>



                    <div class="row mt-5">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex h-100 align-items-end justify-content-start">
                                        <h4 class="fw-bold mb-0">Vacancies</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Filter</button>
                                        <button class="btn btn-primary table-buttons-update ms-2" id="new_user"><i class="fa fa-plus"></i> Monthly</button>
                                        <button class="btn btn-primary table-buttons-update mx-2" id="new_user"><i class="fa fa-plus"></i> Quarterly</button>
                                        <button class="btn btn-primary table-buttons-update me-2" id="new_user"><i class="fa fa-plus"></i> Yearly</button>
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter" style="max-height: 100%;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="id">ID</th> -->
                                    <th scope="col" class="sortable-column" data-column="username">Vacant Apartment</th>
                                    <th scope="col" class="sortable-column" data-column="firstname">Dates Vacant</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_3->num_rows > 0) {
                                    // Output data of each row
                                    while($row_3 = $result_3->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row' style='width: 100px;'>" . $row_3['house_name'] . "</th>";
                                        echo "<td style='width: 150px;'>" . htmlspecialchars($row_3['vacant_until']) . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_3['vacant_until']) . "</td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>




                    <div class="row mt-5">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex h-100 align-items-end justify-content-start">
                                        <h4 class="fw-bold mb-0">Tenant and Apartment Count</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Filter</button>
                                        <button class="btn btn-primary table-buttons-update ms-2" id="new_user"><i class="fa fa-plus"></i> Monthly</button>
                                        <button class="btn btn-primary table-buttons-update mx-2" id="new_user"><i class="fa fa-plus"></i> Quarterly</button>
                                        <button class="btn btn-primary table-buttons-update me-2" id="new_user"><i class="fa fa-plus"></i> Yearly</button>
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter" style="max-height: 100%;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="id">ID</th> -->
                                    <th scope="col" class="sortable-column" data-column="username">Tenant Count</th>
                                    <th scope="col" class="sortable-column" data-column="firstname">Apartment Count</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_4->num_rows > 0) {
                                    // Output data of each row
                                    while($row_4 = $result_4->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row' style='width: 100px;'>" . $row_4['number_of_tenants'] . "</th>";
                                        echo "<td style='width: 150px;'>" . htmlspecialchars($row_4['number_of_apartments']) . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_4['date']) . "</td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>





                    <div class="row mt-5">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex h-100 align-items-end justify-content-start">
                                        <h4 class="fw-bold mb-0">Summary of Delinquencies</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Filter</button>
                                        <button class="btn btn-primary table-buttons-update ms-2" id="new_user"><i class="fa fa-plus"></i> Monthly</button>
                                        <button class="btn btn-primary table-buttons-update mx-2" id="new_user"><i class="fa fa-plus"></i> Quarterly</button>
                                        <button class="btn btn-primary table-buttons-update me-2" id="new_user"><i class="fa fa-plus"></i> Yearly</button>
                                        <button class="btn btn-primary table-buttons-update" id="new_user"><i class="fa fa-plus"></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="tablelimiter" style="max-height: 100%;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="id">ID</th> -->
                                    <th scope="col" class="sortable-column" data-column="username">Tenant</th>
                                    <th scope="col" class="sortable-column" data-column="firstname">Apartment</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Number of Missed Payments</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Total Amount of Missed Payments</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Total Amount Paid</th>
                                    <th scope="col" class="sortable-column" data-column="middlename">Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_5->num_rows > 0) {
                                    // Output data of each row
                                    while($row_5 = $result_5->fetch_assoc()) {
                                        $tenant_id = $row_5['id']; // Unknown ID, probably house id
                                        $date_preferred = $row_5['date_preferred'];
                                        $last_payment_date = $row_5['last_payment_date'];
                                        $monthly_rent = $row_5['price'];  // Use the price from the houses table as the monthly rent

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
                                        $missing_payment_total = 0; // To track the total amount of missing payments
                                        $paid_total = 0; // To sum the amounts paid for missed months

                                        // Loop through all months from start_date to today
                                        for ($i = 0; $i <= $months_difference; $i++) {
                                            $current_month = date('Y-m', strtotime("+$i months", $start_date_timestamp));
                                            $payment_found = false;

                                            // Check if the current month has any payment
                                            foreach (explode(',', $row_5['date_payment']) as $key => $payment_date) {
                                                if (substr($payment_date, 0, 7) == $current_month) {  // Check year-month format
                                                    $payment_found = true;

                                                    // Add the corresponding amount paid to the monthly tracker
                                                    $amounts = explode(',', $row_5['payment_amounts']);
                                                    $monthly_paid = isset($amounts[$key]) ? (float)$amounts[$key] : 0;
                                                    $paid_total += $monthly_paid;
                                                    break;
                                                }
                                            }

                                            // If no payment is found for this month, increment missing months
                                            if (!$payment_found) {
                                                $missing_months++;
                                                $missed_months_dates[] = $current_month; // Add to missed months list
                                                
                                                // Add the monthly rent to the total missing payment amount
                                                $missing_payment_total += $monthly_rent; // Add the house price (monthly rent) to the total
                                            }
                                        }

                                        // Deduct total paid from total missing payment amount
                                        // $missing_payment_total -= $paid_total;

                                        // Ensure missing_payment_total does not go below 0
                                        $missing_payment_total = max(0, $missing_payment_total);


                                        echo "<tr>";
                                        echo "<th scope='row' style='width: 100px;'>" . $row_5['fname'] . "</th>";
                                        echo "<td style='width: 150px;'>" . htmlspecialchars($row_5['house_name']) . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_5['date_payment']) . "</td>";
                                        echo "<td style='width: 100px;'>" . $missing_months . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_5['total_amount_payment']) . "</td>";
                                        echo "<td style='width: 100px;'>" . htmlspecialchars($row_5['date_start']) . "</td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>


                <!-- General Income Filter Modal -->
                <div class="modal fade" id="generalIncomeModal" tabindex="-1" aria-labelledby="generalIncomeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #527853;">
                                <h5 class="modal-title text-white" id="generalIncomeModalLabel">Filter - General Income</h5>
                                <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="generalIncomeFilterForm" method="POST" action="adminreports.php">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" id="updateUserId" name="user_id">
                                            <div class="mb-3">
                                                <label for="startdate" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" id="updateUserId" name="user_id">
                                            <div class="mb-3">
                                                <label for="enddate" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" id="general_income_filter_submit" name="general_income_filter_submit" class="btn btn-primary table-buttons-update">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('general_income_filter').addEventListener('click', function () {
                        var generalIncomeModal = new bootstrap.Modal(document.getElementById('generalIncomeModal'), {
                            keyboard: false
                        });
                        generalIncomeModal.show();
                    });
                </script>

                <!-- Include jQuery library -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    $(document).ready(function() {
                        // Attach event listener to the button
                        $('#general_income_filter_submit').on('click', function (e) {
                            e.preventDefault(); // Prevent default button behavior

                            // Collect form data
                            var formData = $('#generalIncomeFilterForm').serialize(); // Serialize form data

                            // Send AJAX request
                            $.ajax({
                                url: 'search/filter_reports.php', // Replace with your PHP script path
                                type: 'POST',
                                data: formData, // Send serialized form data
                                success: function (response) {
                                    // Handle success response
                                    $('#general_income_tbody').html(response); // Update table body with response data
                                    $('#generalIncomeModal').modal('hide'); // Hide the modal
                                },
                                error: function (xhr, status, error) {
                                    // Handle error
                                    console.error('Error:', error);
                                    alert('An error occurred while processing your request.');
                                }
                            });
                        });
                    });
                </script>
                             
                <!-- <p>Home</p> -->
            </div>
        </div>
    </div>
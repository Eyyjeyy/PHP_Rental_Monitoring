<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    if ($startDate && $endDate) {
        $query = "SELECT 
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
        WHERE tenants.date_start BETWEEN ? AND ? -- Filter results by date range
        GROUP BY tenants.id";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            $tenant_id = $row['id']; // Unknown ID, probably house id
            $date_preferred = $row['date_preferred'];
            $last_payment_date = $row['last_payment_date'];
            $monthly_rent = $row['price'];  // Use the price from the houses table as the monthly rent

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
                foreach (explode(',', $row['date_payment']) as $key => $payment_date) {
                    if (substr($payment_date, 0, 7) == $current_month) {  // Check year-month format
                        $payment_found = true;

                        // Add the corresponding amount paid to the monthly tracker
                        $amounts = explode(',', $row['payment_amounts']);
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

            // Ensure missing_payment_total does not go below 0
            $missing_payment_total = max(0, $missing_payment_total);

            echo "<tr>";
            echo "<th scope='row' style='width: 100px;'>" . $row['fname'] . "</th>";
            echo "<td style='width: 150px;'>" . htmlspecialchars($row['house_name']) . "</td>";
            echo "<td style='width: 100px;'>" . $missing_months . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars($missing_payment_total) . "</td>"; // Display the total missing payment
            echo "<td style='width: 100px;'>" . htmlspecialchars($row['total_amount_payment']) . "</td>";
            // echo "<td style='width: 100px;'>" . htmlspecialchars($row['date_start']) . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars(implode(', ', $missed_months_dates)) . "</td>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        $query = "SELECT 
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
        GROUP BY tenants.id";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            $tenant_id = $row['id']; // Unknown ID, probably house id
            $date_preferred = $row['date_preferred'];
            $last_payment_date = $row['last_payment_date'];
            $monthly_rent = $row['price'];  // Use the price from the houses table as the monthly rent

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
                foreach (explode(',', $row['date_payment']) as $key => $payment_date) {
                    if (substr($payment_date, 0, 7) == $current_month) {  // Check year-month format
                        $payment_found = true;

                        // Add the corresponding amount paid to the monthly tracker
                        $amounts = explode(',', $row['payment_amounts']);
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

            // Ensure missing_payment_total does not go below 0
            $missing_payment_total = max(0, $missing_payment_total);

            echo "<tr>";
            echo "<th scope='row' style='width: 100px;'>" . $row['fname'] . "</th>";
            echo "<td style='width: 150px;'>" . htmlspecialchars($row['house_name']) . "</td>";
            echo "<td style='width: 100px;'>" . $missing_months . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars($missing_payment_total) . "</td>"; // Display the total missing payment
            echo "<td style='width: 100px;'>" . htmlspecialchars($row['total_amount_payment']) . "</td>";
            // echo "<td style='width: 100px;'>" . htmlspecialchars($row['date_start']) . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars(implode(', ', $missed_months_dates)) . "</td>";
            echo "</td>";
            echo "</tr>";
        }
    }
}
?>
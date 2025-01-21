<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    if ($startDate && $endDate) {
        $query = "SELECT 
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
                WHERE expenses.house_id = tenants.house_id 
                AND expenses.date = payments.date_payment
            ) AS total_number_expenses,
            (
                SELECT SUM(expenses.amount) 
                FROM expenses 
                WHERE expenses.house_id = tenants.house_id 
                AND expenses.date = payments.date_payment
            ) AS total_amount_expenses,
            payments.date_payment AS payment_date
        FROM tenants
        LEFT JOIN houses ON tenants.house_id = houses.id
        LEFT JOIN payments ON tenants.house_id = payments.houses_id
        WHERE payments.date_payment BETWEEN ? AND ?
        GROUP BY tenants.house_id, payments.date_payment
        ORDER BY tenants.house_id, payments.date_payment";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            $IncomePerTenantTable_totalPayment = $row['total_amount_payment'];
            $IncomePerTenantTable_totalExpense = $row['total_amount_expenses'];
            $IncomePerTenantTable_netAmount = $IncomePerTenantTable_totalPayment - $IncomePerTenantTable_totalExpense;
            echo "<tr>";
            echo "<th scope='row' style='max-width: 100px;'>" . $row['tenant_names'] . "</th>";
            echo "<td style='width: 150px;'>" . htmlspecialchars($row['house_name']) . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars($row['total_number_payments']) . "</td>";
            echo "<td>" . htmlspecialchars($row['total_amount_payment']) . "</td>";
            echo "<td>" . htmlspecialchars($row['total_number_expenses']) . "</td>";
            echo "<td>" . htmlspecialchars($IncomePerTenantTable_netAmount) . "</td>";
            echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        $query = "SELECT 
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
        ORDER BY tenants.house_id, payments.date_payment";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>
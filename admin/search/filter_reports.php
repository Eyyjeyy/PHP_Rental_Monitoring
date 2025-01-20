<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    // Validate input
    if ($startDate && $endDate) {
        // Fetch data based on date range
        $query = "SELECT date_payment, 
               COUNT(*) AS total_payments, 
               SUM(p.amount) AS total_amount_payment,
               (SELECT SUM(e.amount) FROM expenses e WHERE e.date = p.date_payment) AS total_amount_expense,
               (SELECT COUNT(*) FROM expenses e WHERE e.date = p.date_payment) AS total_expenses
            FROM payments p WHERE date_payment BETWEEN ? AND ?
            GROUP BY date_payment";
        // $query = "SELECT * FROM payments WHERE date_payment BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Generate table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['total_payments']} testing</td>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                  </tr>";
        }
    } else {
        // Fetch all data if no filters are provided
        $query = "SELECT * FROM payments";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['date_payment']}</td>
                  </tr>";
        }
    }
}
?>
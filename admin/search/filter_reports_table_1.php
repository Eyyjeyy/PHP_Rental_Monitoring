<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    $monthly = $_POST['month'] ?? null;
    $quarterly = $_POST['quarterly'] ?? null;
    $yearly = $_POST['yearly'] ?? null;

    // MONTHLY BUTTON
    if($monthly) {
        $query = "SELECT date_payment, 
               COUNT(*) AS total_payments, 
               SUM(p.amount) AS total_amount_payment,
               (SELECT SUM(e.amount) FROM expenses e WHERE DATE_FORMAT(e.date, '%Y-%m') = DATE_FORMAT(p.date_payment, '%Y-%m')) AS total_amount_expense,
               (SELECT COUNT(*) FROM expenses e WHERE DATE_FORMAT(e.date, '%Y-%m') = DATE_FORMAT(p.date_payment, '%Y-%m')) AS total_expenses
            FROM payments p
            GROUP BY YEAR(date_payment), MONTH(date_payment)
            ORDER BY YEAR(date_payment), MONTH(date_payment)";
        // $query = "SELECT * FROM payments";
        $result = $conn->query($query);

        // Generate table rows
        while ($row = $result->fetch_assoc()) {
            $totalPayment = $row['total_amount_payment'];
            $totalExpense = $row['total_amount_expense'];
            $netAmount = $totalPayment - $totalExpense;
            echo "<tr>
                    <th scope='row'>{$row['total_payments']}</th>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                    <td>{$row['total_expenses']}</td>
                    <td>{$netAmount}</td>
                    <td>{$row['date_payment']}</td>
                  </tr>";
        }
    } else if ($quarterly) {
        $query = "SELECT 
                CONCAT(YEAR(date_payment), '-Q', QUARTER(date_payment)) AS quarter, -- Format year and quarter
                COUNT(*) AS total_payments,                                       -- Total number of payments
                SUM(p.amount) AS total_amount_payment,                            -- Total amount of payments
                (SELECT SUM(e.amount) 
                FROM expenses e 
                WHERE YEAR(e.date) = YEAR(p.date_payment) 
                AND QUARTER(e.date) = QUARTER(p.date_payment)
                ) AS total_amount_expense,                                        -- Total expense amount for the quarter
                (SELECT COUNT(*) 
                FROM expenses e 
                WHERE YEAR(e.date) = YEAR(p.date_payment) 
                AND QUARTER(e.date) = QUARTER(p.date_payment)
                ) AS total_expenses                                               -- Total number of expenses for the quarter
            FROM payments p
            GROUP BY YEAR(date_payment), QUARTER(date_payment)                   -- Group by year and quarter
            ORDER BY YEAR(date_payment), QUARTER(date_payment)";
        $result = $conn->query($query);

        // Generate table rows
        while ($row = $result->fetch_assoc()) {
            $totalPayment = $row['total_amount_payment'];
            $totalExpense = $row['total_amount_expense'];
            $netAmount = $totalPayment - $totalExpense;
            echo "<tr>
                    <th scope='row'>{$row['total_payments']}</th>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                    <td>{$row['total_expenses']}</td>
                    <td>{$netAmount}</td>
                    <td>{$row['quarter']}</td>
                  </tr>";
        }
    } else if ($yearly) {
        $query = "SELECT 
                YEAR(date_payment) AS year,
                COUNT(*) AS total_payments,
                SUM(p.amount) AS total_amount_payment,
                (SELECT SUM(e.amount) 
                FROM expenses e 
                WHERE YEAR(e.date) = YEAR(p.date_payment)
                ) AS total_amount_expense,
                (SELECT COUNT(*) 
                FROM expenses e 
                WHERE YEAR(e.date) = YEAR(p.date_payment)
                ) AS total_expenses
            FROM payments p
            GROUP BY YEAR(date_payment)
            ORDER BY YEAR(date_payment)";

        // Execute the query
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $totalPayment = $row['total_amount_payment'];
            $totalExpense = $row['total_amount_expense'];
            $netAmount = $totalPayment - $totalExpense;
            echo "<tr>
                    <th scope='row'>{$row['total_payments']}</th>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                    <td>{$row['total_expenses']}</td>
                    <td>{$netAmount}</td>
                    <td>{$row['year']}</td>
                  </tr>";
        }

    } else if ($startDate && $endDate) {
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
            $totalPayment = $row['total_amount_payment'];
            $totalExpense = $row['total_amount_expense'];
            $netAmount = $totalPayment - $totalExpense;
            echo "<tr>
                    <th scope='row'>{$row['total_payments']}</th>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                    <td>{$row['total_expenses']}</td>
                    <td>{$netAmount}</td>
                    <td>{$row['date_payment']}</td>
                  </tr>";
        }
    } else {
        // Fetch all data if no filters are provided
        $query = "SELECT date_payment, 
               COUNT(*) AS total_payments, 
               SUM(p.amount) AS total_amount_payment,
               (SELECT SUM(e.amount) FROM expenses e WHERE e.date = p.date_payment) AS total_amount_expense,
               (SELECT COUNT(*) FROM expenses e WHERE e.date = p.date_payment) AS total_expenses
            FROM payments p
            GROUP BY date_payment";
        // $query = "SELECT * FROM payments";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $totalPayment = $row['total_amount_payment'];
            $totalExpense = $row['total_amount_expense'];
            $netAmount = $totalPayment - $totalExpense;
            echo "<tr>
                    <th scope='row'>{$row['total_payments']}</th>
                    <td>{$row['total_amount_payment']}</td>
                    <td>{$row['total_amount_expense']}</td>
                    <td>{$row['total_expenses']}</td>
                    <td>{$netAmount}</td>
                    <td>{$row['date_payment']}</td>
                  </tr>";
        }
    }
}
?>
<?php
include '../../db_connect.php'; // Include your database connection

// Get the search query and trim any leading/trailing whitespace
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Build the SQL query
$sql = "SELECT expenses.*, houses.house_name, houses.id AS housingid
            FROM expenses
            LEFT JOIN houses ON expenses.house_id = houses.id";

// Add the WHERE clause only if there’s a search query
if (!empty($query)) {
    $sql .= " WHERE 
        expenses.name LIKE '%$query%' OR
        expenses.info LIKE '%$query%' OR
        houses.house_name LIKE '%$query%'
    ";
}


$result = $conn->query($sql);


// Generate table rows based on search results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row; // Collect data for the JavaScript array
        echo "<tr>";
        echo "<th scope='row'>" . $row['id'] . "</th>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['info']) . "</td>";
        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['house_name']) . "</td>";
        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
        echo "<div class='row justify-content-center m-0'>";
        echo "<div class='col-xl-6 px-2'>";
        // Add a form with a delete button for each record
        echo "<form method='POST' action='adminexpenses.php' class='float-xl-end align-items-center'>";
        echo "<input type='hidden' name='expensesid' value='" . $row['id'] . "'>";
        echo "<button type='submit' name='delete_expenses' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "<div class='col-xl-6 px-2'>";
        // Add a form with a update button for each record
        echo "<input type='hidden' name='expensesid' value='" . $row['id'] . "'>";
        echo "<button type='button' class='btn btn-primary update-expenses-btn float-xl-start table-buttons-update' data-id='" . $row['id'] . "' data-expensesname='" . htmlspecialchars($row['name']) . "'data-expensesinfo='" . htmlspecialchars($row['info']) . "'data-expensesamount='" . htmlspecialchars($row['amount']) .  "' style='width: 80px;'>Update</button>";
        echo "</div>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
    echo "<script>var arrayData = " . json_encode($expenses) . ";</script>"; // Pass the data to JavaScript
} else {
    echo "<tr><td colspan='10'>No Payments found</td></tr>";
}

$conn->close();
?>

<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    if ($startDate && $endDate) {
        $query = "SELECT h.id, h.house_name, 
           IFNULL(MAX(t.date_end), CURDATE()) AS vacant_until
        FROM houses h
        LEFT JOIN tenants t ON h.id = t.house_id
        GROUP BY h.id
        HAVING (MAX(t.date_end) IS NULL OR MAX(t.date_end) BETWEEN ? AND ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<th scope='row' style='max-width: 100px;'>" . $row['house_name'] . "</th>";
            echo "<td style='width: 150px;'>" . htmlspecialchars($row['vacant_until']) . "</td>";
            echo "<td style='width: 100px;'>" . htmlspecialchars($row['date_end']) . "</td>";
            echo "</tr>";
        }
    } else {

    }
}
?>
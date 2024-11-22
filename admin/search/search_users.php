<?php
include '../../db_connect.php';

// Get search, pagination, and sorting parameters
$query = isset($_POST['query']) ? $_POST['query'] : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$sort_column = isset($_POST['sort_column']) ? $_POST['sort_column'] : 'id';
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'ASC';
$records_per_page = 10; // Adjust as needed

// Calculate the offset for pagination
$offset = ($page - 1) * $records_per_page;

// Get the total number of matching records for pagination
$total_sql = "
    SELECT COUNT(*) as total FROM users 
    WHERE 
        username LIKE '%$query%' OR 
        firstname LIKE '%$query%' OR 
        middlename LIKE '%$query%' OR 
        lastname LIKE '%$query%' OR 
        phonenumber LIKE '%$query%' OR 
        email LIKE '%$query%' OR 
        role LIKE '%$query%'
";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page);

// Fetch paginated, sorted records
$sql = "
    SELECT * FROM users
    WHERE 
        username LIKE '%$query%' OR 
        firstname LIKE '%$query%' OR 
        middlename LIKE '%$query%' OR 
        lastname LIKE '%$query%' OR 
        phonenumber LIKE '%$query%' OR 
        email LIKE '%$query%' OR 
        role LIKE '%$query%'
    ORDER BY $sort_column $sort_order
    LIMIT $offset, $records_per_page
";
$result = $conn->query($sql);

// Generate table rows based on search results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<th scope='row'>" . $row['id'] . "</th>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['middlename']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phonenumber'] ? $row['phonenumber'] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['email'] ? $row['email'] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['password'] ? str_repeat('*', strlen($row['password'])) : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
        echo "<td class='justify-content-center text-center align-middle'>";
        echo "<div class='row justify-content-center m-0'>";
        echo "<div class='col-xl-6 px-2'>";
        echo "<form method='POST' action='adminusers.php' class='float-xl-end align-items-center'>";
        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
        echo "<button type='submit' name='delete_user' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "<div class='col-xl-6 px-2'>";
        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
        echo "<button type='button' class='btn btn-primary update-user-btn float-xl-start table-buttons-update' data-id='" . $row['id'] . "' data-username='" . htmlspecialchars($row['username']) . "' data-firstname= '" . htmlspecialchars($row['firstname']) . "' data-middlename= '" . htmlspecialchars($row['middlename']) . "' data-lastname= '" . htmlspecialchars($row['lastname']) . "' data-password='" . htmlspecialchars($row['password']) . "' data-role='" . htmlspecialchars($row['role']) . "' style='width: 80px;'>Update</button>";
        echo "</div>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No users found</td></tr>";
}

// Output pagination buttons
echo "<tr><td colspan='10' class='text-center'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<button class='btn btn-secondary pagination-btn' data-page='$i'>$i</button> ";
}
echo "</td></tr>";

$conn->close();
?>

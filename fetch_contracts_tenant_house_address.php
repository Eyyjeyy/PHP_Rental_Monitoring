<?php
include 'admin.php';
$admin = new Admin();
include("../../db_connect.php"); // Include your database connection file

if (isset($_POST['tenant_id'])) {
    $tenant_id = intval($_POST['tenant_id']); // Sanitize the input
    $query = "SELECT * FROM tenants
              LEFT  
              WHERE t.users_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin->session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'address' => $row['address']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Address not found.']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

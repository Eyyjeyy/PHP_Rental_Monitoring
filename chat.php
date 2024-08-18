<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    
    include 'admin.php';
    $admin = new Admin();

    if(!$admin->isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    if($admin->isLoggedIn() && $admin->session_role == 'user') {
        header("Location: index.php");
        exit();
    }

    $user_id = $admin->session_id;
    $chat_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $_SESSION['chat_user_id'] = $chat_user_id;


    $users = $admin->getAllUsers();
    // echo $chat_user_id . "<br>";
    // echo $_SESSION['chat_user_id'] . "<br>";
    // echo $admin->session_id . "<br>";
    // echo $user_id . "<br>";

    // Check if the request is an AJAX request to fetch messages
    if (isset($_GET['fetch_messages']) && $_GET['fetch_messages'] == 1) {
        $user_id = $admin->session_id;
        // echo "AJ";
        if ($chat_user_id) {
            $sql = "SELECT m.*, 
                        u1.username AS sender_username, 
                        u2.username AS receiver_username
                    FROM messages m
                    LEFT JOIN users u1 ON m.sender_id = u1.id
                    LEFT JOIN users u2 ON m.receiver_id = u2.id
                    WHERE (m.sender_id = '$user_id' AND m.receiver_id = '$chat_user_id') 
                    OR (m.sender_id = '$chat_user_id' AND m.receiver_id = '$user_id')
                    ORDER BY m.timestamp";
            $result = $admin->conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $message = htmlspecialchars($row['message']);
                    $timestamp = $row['timestamp'];
                    $sender_id = $row['sender_id'];
                    $receiver_id = $row['receiver_id'];
                    $sender_username = htmlspecialchars($row['sender_username']);
                    $receiver_username = htmlspecialchars($row['receiver_username']);
                    $sender = ($sender_id == $user_id) ? 'You' : $sender_username;
                    $class = ($sender_id == $user_id) ? 'message-right' : 'message-left';
                    $image_path = $row['image_path'];
                    $message_id = $row['id']; // Unique ID for each message
                    ?>
                    <div class="message <?php echo $class; ?>">
                        <p><strong><?php echo $sender; ?>:</strong> <?php echo $message; ?></p>
                        <?php if ($image_path): ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $message_id; ?>">
                                <img src="<?php echo $image_path; ?>" alt="Image">
                            </a>
                            <div class="modal fade" id="imageModal<?php echo $message_id; ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo $message_id; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel<?php echo $message_id; ?>">Image Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body mx-auto">
                                            <img src="<?php echo $image_path; ?>" alt="Image" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <span class="timestamp"><?php echo $timestamp; ?></span>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No messages yet.</p>";
            }
        } else {
            echo "<p>Select a user to start chatting.</p>";
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $message = $_POST['message'] ?? '';
        $image_path = null;
    
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    
            if (in_array($file_extension, $allowed_extensions)) {
                $upload_file_dir = './uploads/';
                $dest_path = $upload_file_dir . uniqid() . '.' . $file_extension;
    
                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    $image_path = $dest_path;
                } else {
                    echo "File could not be uploaded.";
                }
            } else {
                echo "Invalid file extension.";
            }
        }
    
        if (!empty($message) || $image_path) {
            $chat_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
            $admin->sendMessage($user_id, $chat_user_id, $message, $image_path);
        }
    }

    // if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    //     $message = $_POST['message'];
    //     if (!empty($message)) {
    //         $admin->sendMessage($user_id, $chat_user_id, $message);
    //     }
    // }

    // $sql = "SELECT * FROM messages 
    //         WHERE (sender_id = $user_id AND receiver_id = $chat_user_id) 
    //         OR (sender_id = $chat_user_id AND receiver_id = $user_id) 
    //         ORDER BY timestamp";
    // $sql = "SELECT m.*, u1.username AS sender_name, u2.username AS receiver_name
    //     FROM messages m
    //     INNER JOIN users u1 ON m.sender_id = u1.id
    //     INNER JOIN users u2 ON m.receiver_id = u2.id
    //     WHERE (m.sender_id = (SELECT id FROM users WHERE users_id = $user_id) AND m.receiver_id = (SELECT id FROM users WHERE users_id = $chat_user_id))
    //     OR (m.sender_id = (SELECT id FROM users WHERE users_id = $chat_user_id) AND m.receiver_id = (SELECT id FROM users WHERE users_id = $user_id))
    //     ORDER BY m.timestamp";

    $sql = "SELECT m.*, 
               u1.username AS sender_username, 
               u2.username AS receiver_username
        FROM messages m
        LEFT JOIN users u1 ON m.sender_id = u1.id
        LEFT JOIN users u2 ON m.receiver_id = u2.id
        WHERE m.receiver_id = '$user_id' OR m.sender_id = '$user_id'
        ORDER BY m.timestamp";
    $result = $admin->conn->query($sql);

    $sql_debug = "SELECT m.*, 
               u1.username AS sender_username, 
               u2.username AS receiver_username
        FROM messages m
        LEFT JOIN users u1 ON m.sender_id = u1.id
        LEFT JOIN users u2 ON m.receiver_id = u2.id
        WHERE m.receiver_id = '$user_id' OR m.sender_id = '$user_id'
        ORDER BY m.timestamp";
    $result_debug = $admin->conn->query($sql);

    // if ($result_debug) {
    //     if ($result_debug->num_rows > 0) {
    //         echo "Debug Results:<br>";
    //         while ($row_debug = $result_debug->fetch_assoc()) {
    //             echo "Logged in User's ID: " . $admin->session_id . "<br>";
    //             echo "Message ID: " . $row_debug['id'] . "<br>";
    //             echo "Sender ID: " . $row_debug['sender_id'] . "<br>";
    //             echo "Receiver ID: " . $row_debug['receiver_id'] . "<br>";
    //             echo "Sender Username: " . $row_debug['sender_username'] . "<br>";
    //             echo "Receiver Username: " . $row_debug['receiver_username'] . "<br>";
    //             echo "Message: " . $row_debug['message'] . "<br>";
    //             echo "Timestamp: " . $row_debug['timestamp'] . "<br>";
    //             echo "<hr>";
    //         }
    //     } else {
    //         echo "<p>No debug messages found for user ID: $user_id</p>";
    //     }
    // } else {
    //     echo "Debug Query Error: " . $admin->conn->error;
    // }

    // Debug output: print SQL query
    // echo "<p>SQL Query: $sql</p>";

    if ($result === false) {
        // Query execution failed
        echo "Query failed: " . $admin->conn->error;
        exit;
    }

    $pageTitle = "Chat Page"; // Change this according to the current page
    $page = "";
    // Determine the base URL
    $base_url = dirname($_SERVER['SCRIPT_NAME']) . '/';
?>

<?php include 'admin/includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col sidebar text-white">
                <nav class="navbar navbar-expand navbar-dark sidebar">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav d-flex flex-column">
                            <a class="nav-link" href="admin/admindashboard.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                                </svg>
                                <p>Dashboard</p>
                            </a>
                            <a class="nav-link" href="admin/adminusers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-file-person" viewBox="0 0 16 16">
                                    <path d="M12 1a1 1 0 0 1 1 1v10.755S12 11 8 11s-5 1.755-5 1.755V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                </svg>
                                <p>Users</p>
                            </a>
                            <a class="nav-link" aria-current="page" href="admin/adminhouses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-building-fill" viewBox="0 0 16 16">
                                    <path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <p>Apartments</p>
                            </a>
                            <a class="nav-link" href="admin/admincategories.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-list-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                                </svg>
                                <p>Categories</p>
                            </a>
                            <a class="nav-link" href="admin/admintenants.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-person-standing" viewBox="0 0 16 16">
                                    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0"/>
                                </svg>
                                <p>Tenants</p>
                            </a>
                            <a class="nav-link" href="admin/adminpayments.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-bank2" viewBox="0 0 16 16">
                                    <path d="M8.277.084a.5.5 0 0 0-.554 0l-7.5 5A.5.5 0 0 0 .5 6h1.875v7H1.5a.5.5 0 0 0 0 1h13a.5.5 0 1 0 0-1h-.875V6H15.5a.5.5 0 0 0 .277-.916zM12.375 6v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zm-2.5 0v7h-1.25V6zM8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2M.5 15a.5.5 0 0 0 0 1h15a.5.5 0 1 0 0-1z"/>
                                </svg>
                                <p>Payments</p>
                                <?php
                                    $unapproved_payments = $admin->countPendingApprovals();
                                    echo "<p class= fw-bold' style='color: #F28543;'>" . $unapproved_payments . "</p>";
                                ?>
                            </a>
                            <a class="nav-link" href="admin/adminpapers.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
                                    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                                    <path d="M3.5 1h.585A1.5 1.5 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5q-.001-.264-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1"/>
                                </svg>
                                <p>Papers</p>
                            </a>
                            <a class="nav-link" href="admin/adminexpenses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-wallet-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z"/>
                                    <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z"/>
                                </svg>
                                <p>Expenses</p>
                            </a>
                            <a class="nav-link active" href="chat.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-chat-left-text-fill" viewBox="0 0 16 16">
                                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                                </svg>
                                <p>Chat</p>
                            </a>
                            <a class="nav-link" href="admin/adminhistory.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <p>History</p>
                            </a>
                        </ul>
                        <ul class="navbar-nav d-flex flex-column">
                            <a class="nav-link" href="<?php echo $base_url; ?>logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: auto; margin-bottom: auto;" width="24" height="24" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                    <path d="M7.5 1v7h1V1z"/>
                                    <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
                                </svg>
                                <p>Logout</p>
                            </a>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- <div class="col main content">
                <div class="card-body">
                    <?php
                        echo "AJ";
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $message = $row['message'];
                                $timestamp = $row['timestamp'];
                                $sender_id = $row['sender_id'];
                                $receiver_id = $row['receiver_id'];
                                $sender_username = htmlspecialchars($row['sender_username']);
                                $receiver_username = htmlspecialchars($row['receiver_username']);
                                // Determine the message sender
                                $sender = ($row['sender_id'] == $user_id) ? 'You' : $sender_username;
                                // Determine the CSS class for message alignment
                                $message_class = ($sender_id == $user_id) ? 'message-right' : 'message-left';
                                ?>
                                <div class="<?php echo $message_class; ?>">
                                    <p><strong><?php echo $sender; ?>:</strong> <?php echo $message; ?></p>
                                    <span class="timestamp"><?php echo $timestamp; ?></span>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>No messages yet.</p>";
                        }
                    ?>
                </div>
                <form method="post" action="">
                    <textarea name="message" required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div> -->

            <div class="col chatflex">
                <!-- User List -->
                <div class="user-list">
                    <div class="list">
                        <h2>Users</h2>
                        <ul>
                            <?php foreach ($users as $user): ?>
                                <li><a href="chat.php?user_id=<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Chat Section -->
                <div class="chat-section">
                    <div class="messages">
                        <?php
                        if ($chat_user_id) {
                            // Fetch messages between the logged-in user and the selected user
                            $sql = "SELECT m.*, 
                                        u1.username AS sender_username, 
                                        u2.username AS receiver_username
                                    FROM messages m
                                    LEFT JOIN users u1 ON m.sender_id = u1.id
                                    LEFT JOIN users u2 ON m.receiver_id = u2.id
                                    WHERE (m.sender_id = '$user_id' AND m.receiver_id = '$chat_user_id') 
                                    OR (m.sender_id = '$chat_user_id' AND m.receiver_id = '$user_id')
                                    ORDER BY m.timestamp";
                            $result = $admin->conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $message = htmlspecialchars($row['message']);
                                    $timestamp = $row['timestamp'];
                                    $sender_id = $row['sender_id'];
                                    $receiver_id = $row['receiver_id'];
                                    $sender_username = htmlspecialchars($row['sender_username']);
                                    $receiver_username = htmlspecialchars($row['receiver_username']);
                                    $sender = ($sender_id == $user_id) ? 'You' : $sender_username;
                                    $class = ($sender_id == $user_id) ? 'message-right' : 'message-left';
                                    $image_path = $row['image_path'];
                                    ?>
                                    <div class="message <?php echo $class; ?>">
                                        <p><strong><?php echo $sender; ?>:</strong> <?php echo $message; ?></p>
                                        <?php if ($image_path): ?>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $message_id; ?>">
                                                <img src="<?php echo $image_path; ?>" alt="Image">
                                            </a>
                                            <div class="modal fade" id="imageModal<?php echo $message_id; ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo $message_id; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="imageModalLabel<?php echo $message_id; ?>">Image Preview</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body mx-auto">
                                                            <img src="<?php echo $image_path; ?>" alt="Image" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <span class="timestamp"><?php echo $timestamp; ?></span>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p>No messages yet.</p>";
                            }
                        } else {
                            echo "<p>Select a user to start chatting.</p>";
                        }
                        ?>
                    </div>
                    
                    <!-- Send Message Form -->
                    <?php if ($chat_user_id): ?>
                        <form id="message-form" action="chat.php?user_id=<?php echo $chat_user_id; ?>" method="POST" enctype="multipart/form-data">
                            <textarea name="message" id="message-input" placeholder="Type your message here..." required></textarea>
                            <input type="file" name="image" id="image-input" accept="image/*">
                            <button type="submit">Send</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Fetch messages initially
            function fetchMessages() {
                var userId = <?php echo $chat_user_id; ?>; // Assuming $chat_user_id is set in PHP
                $.ajax({
                    url: 'chat.php?fetch_messages=1&user_id=' + userId,
                    method: 'GET',
                    success: function(data) {
                        $('.messages').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching messages:', textStatus, errorThrown);
                    }
                });
            }

            fetchMessages();

            // Handle form submission
            $('#message-form').on('submit', function(e){
                e.preventDefault();

                var formData = new FormData(this);
                var userId2 = <?php echo $chat_user_id; ?>; // Assuming $chat_user_id is set in PHP
                $.ajax({
                    url: 'chat.php?user_id=' + userId2, // Same PHP file
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Form submitted successfully:', response);
                        $('#message-input').val('');
                        $('#image-input').val('');
                        fetchMessages();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error submitting form:', textStatus, errorThrown);
                    }
                });
            });
        });
    </script>










    <!-- <script>
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messages = document.querySelector('.messages');

            fetch('chat.php?user_id=<?php echo $chat_user_id; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Clear input fields
                document.getElementById('message-input').value = '';
                document.getElementById('image-input').value = '';

                // Add message to the chat
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', 'message-right');
                messageDiv.innerHTML = `
                    <p><strong>You:</strong> ${data.message}</p>
                    ${data.image ? `<img src="${data.image}" alt="Image">` : ''}
                    <span class="timestamp">${new Date(data.timestamp).toLocaleString()}</span>
                `;
                messages.appendChild(messageDiv);

                // Scroll to the bottom of the messages
                messages.scrollTop = messages.scrollHeight;
            })
            .catch(error => console.error('Error:', error));
        });
    </script> -->
    
<?php include 'admin/includes/footer.php'; ?>
<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    
    include 'admin.php';
    $admin = new Admin();

    if(!$admin->isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    if($admin->isLoggedIn() && $admin->session_role == 'admin') {
        header("Location: admin/admindashboard.php");
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
                    ?>
                    <div class="message <?php echo $class; ?>">
                        <p><strong><?php echo $sender; ?>:</strong> <?php echo $message; ?></p>
                        <?php if ($image_path): ?>
                            <img src="<?php echo $image_path; ?>" alt="Image">
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

    $pageTitle = "UserChat Page"; // Change this according to the current page
    // Determine the base URL
    $base_url = dirname($_SERVER['SCRIPT_NAME']) . '/';
?>

<?php include 'regular/includes/header_user.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light py-0" style="background-color: #527853;">
  <div class="container-fluid">
    <a class="navbar-brand py-0" href="#">
        <img src="asset/Renttrack pro no word_2.png" class="img-fluid" alt="..." style="height: 50px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-self-stretch" id="navbarSupportedContent">
      <ul class="navbar-nav mb-2 mb-lg-0 w-100" style="min-height: 100%;">
        <li class="ms-auto nav-item mx-1">
          <a class="nav-link h-100 d-flex align-items-center" aria-current="page" href="#">
            <p class="mb-0 text-center text-white" style="font-weight: 600;">Contact</p>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link h-100 d-flex align-items-center" href="#">
            <p class="mb-0 text-center text-white" style="font-weight: 600;">Payment</p>
          </a>
        </li>
        <li class="nav-item mx-1">
            <a class="nav-link h-100 d-flex align-items-center" href="#">
                <p class="mb-0 text-center text-white" style="font-weight: 600;">Info</p>
            </a>
        </li>
      </ul>
      <form class="d-none">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
    <div class="row mt-5 mx-auto w-75" style="height: 80vh;">
        <div class="col-12 col-md-4 pe-0 h-100">
            <div class="card h-100">
                <div class="card-header" style="background-color: #EE7214;">
                    <h5 class="text-center mb-0">Users</h5>
                </div>
                <div class="card-body bg-white" style="overflow-y: auto;">
                    <ul class="ps-0" style="list-style: none;">
                        <?php foreach ($users as $user): ?>
                            <li>
                                <a href="chat_user.php?user_id=<?php echo $user['id']; ?>" class="text-decoration-none" style="color: #2C3E50;">
                                    <p class="fs-5 mb-2">
                                        <?php echo htmlspecialchars($user['username']); ?>
                                    </p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 ps-0 h-100">            
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="text-center mb-0">Chat</h5>
                </div>
                <div class="card-body bg-white h-100" style="overflow-y: auto;">
                    <div class="messages" style="max-height: 100%;">
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
                                                <img src="<?php echo $image_path; ?>" class="img-fluid" alt="Image">
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
                </div>
                <!-- Send Message Form -->
                <?php if ($chat_user_id): ?>
                    <form id="message-form" action="chat_user.php?user_id=<?php echo $chat_user_id; ?>" method="POST" enctype="multipart/form-data" style="height: 15%;">
                        <div class="row h-100 m-0">
                            <div class="col-7 p-0 pt-2">
                                <div class="row px-3 m-0">
                                    <textarea class="w-100" name="message" id="message-input" placeholder="Type your message here..." required></textarea>
                                </div>
                            </div>
                            <div class="col-5 p-0 pt-2">
                                <input class="w-100" type="file" name="image" id="image-input" accept="image/*">
                                <button class="btn btn-primary" style="border-style: none; border-radius: 4px;" type="submit">Send</button>
                            </div>
                        </div>
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
                    url: 'chat_user.php?fetch_messages=1&user_id=' + userId,
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
                    url: 'chat_user.php?user_id=' + userId2, // Same PHP file
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
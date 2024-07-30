<?php

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
Class Admin {
    public $conn; // Declare the connection variable
    // public $loggedIn = 3; // Define the loggedIn property
    public $session_uname;
    public $session_pword;
    public $session_id;
    public $session_role;
    private $session_timeout = 3600; // Session expires after 30  minutes


  public function __construct() {
    if(!isset($_SESSION)) {
      session_start();
    }
    $this->checkSessionTimeout();

    // Run the setup script
    include 'db_setup.php';

    include 'db_connect.php';
    $this->conn = $conn;
  }

  public function isLoggedIn() {
    if (isset($_SESSION["username"]) && isset($_SESSION['user_id'])) {
      $this->session_uname = $_SESSION["username"];
      $this->session_role = $_SESSION['role'];
      $this->session_id = $_SESSION['user_id'];
      return true;
    }
    return false;
  }

  // Check if the session has timed out and logout if necessary
  private function checkSessionTimeout() {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $this->session_timeout)) {
        // Session timed out, log out the user
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        // Construct the redirect URL relative to the current directory
        // $redirectUrl = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\') . '/../login.php';
        // header("Location: $redirectUrl"); // Redirect to the login page

        // header("Location: ../login.php"); // Redirect to the login page
        
        // Determine the protocol
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";

        // Get the project's root directory
        $projectRoot = str_replace('\\', '/', dirname(__FILE__)); // For PHP 5.3+

        // Construct the redirect URL for login.php at the project's root level
        $redirectUrl = $protocol . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', $projectRoot) . '/login.php';

        // Redirect to the login page
        header("Location: $redirectUrl");
        exit();
    } else if ($this->isLoggedIn()) {
        // Update last activity time if user is logged in
        $_SESSION['LAST_ACTIVITY'] = time();
    }
  }

  public function login($username, $password) {
    if(isset($_SESSION['user_id'])) {
      return;
    }
    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE BINARY username = ? AND BINARY password = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();

      // Verify the password
      // Directly compare plain text passwords
      if ($password === $row['password']) {
        session_regenerate_id(true);
        $_SESSION["username"] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['id'];
        $this->session_uname = $username;
        $this->session_role = $row['role'];
        return true;
      }
    }
    return false;
  }

  // Function to register a user
  public function registerUser($firstname, $middlename, $lastname, $password, $email, $role) {
    // Validate input
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
      return "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return "Invalid email format.";
    }
    
    // Prepare the SQL statement to insert the new user
    $stmt = $this->conn->prepare("INSERT INTO users (username, firstname, middlename, lastname, password, email, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstname, $lastname, $email, $password);

    // Execute the statement and check if the user was added successfully
    if ($stmt->execute()) {
      return false;
    } else {
      return false;
    }

    // Close the statement
    $stmt->close();
  }

  // Function to delete a user by ID
  public function deleteUser($user_id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }
  
  // // Function to update a user's username, password, and role
  // public function updateUser($user_id, $username, $firstname, $middlename, $lastname, $password, $role) {
  //   $sql = "UPDATE users SET username = ?, firstname = ?, middlename = ?, lastname = ?, password = ?, role = ? WHERE id = ?";
  //   $stmt = $this->conn->prepare($sql);
  //   $stmt->bind_param("ssssssi", $username, $firstname, $middlename, $lastname, $password, $role, $user_id);
  //   $stmt->execute();
  //   if ($stmt->affected_rows > 0) {
  //       return true; // Update successful
  //   } else {
  //       return false; // Update failed
  //   }
  // }

  // Function to update a user's username, password, and role
  public function updateUser($user_id, $username, $firstname, $middlename, $lastname, $password, $role) {
    $this->conn->begin_transaction(); // Start transaction

    // Update the users table
    $sql_users = "UPDATE users SET username = ?, firstname = ?, middlename = ?, lastname = ?, password = ?, role = ? WHERE id = ?";
    $stmt_users = $this->conn->prepare($sql_users);
    $stmt_users->bind_param("ssssssi", $username, $firstname, $middlename, $lastname, $password, $role, $user_id);
    $stmt_users->execute();

    // Check if the users table update was executed successfully
    if ($stmt_users->errno) {
      $this->conn->rollback(); // Rollback transaction
      error_log("Error updating users table: " . $stmt_users->error);
      return false; // Update failed in users table
    }

    // Update the tenants table
    $sql_tenants = "UPDATE tenants SET fname = ?, mname = ?, lname = ? WHERE users_id = ?";
    $stmt_tenants = $this->conn->prepare($sql_tenants);
    $stmt_tenants->bind_param("sssi", $firstname, $middlename, $lastname, $user_id);
    $stmt_tenants->execute();

    // Check if the tenants table update was executed successfully
    if ($stmt_tenants->errno) {
      $this->conn->rollback(); // Rollback transaction
      error_log("Error updating tenants table: " . $stmt_tenants->error);
      return false; // Update failed in tenants table
    }

    // If execution reaches here, it means there were no errors
    $this->conn->commit(); // Commit transaction
    return true; // Update successful
  }

  // Function to add a new user
  public function addUser($username, $firstname, $middlename, $lastname, $password, $role) {
    $sql = "INSERT INTO users (username, firstname, middlename, lastname, password, role) VALUES (?, ?, ?, ? ,?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $firstname, $middlename, $lastname, $password, $role);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Insertion successful
    } else {
        return false; // Insertion failed
    }
  }

  // Function to delete a house by ID
  public function deleteHouse($house_id) {
    $sql = "DELETE FROM houses WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $house_id);
    $stmt->execute();

    $sql_2 = "DELETE FROM houseaccounts WHERE houses_id = ?";
    $stmt_2 = $this->conn->prepare($sql_2);
    $stmt_2->bind_param("i", $house_id);
    $stmt_2->execute();
    if ($stmt->affected_rows > 0) {
      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }

  public function updateHouse($house_id, $housenumber, $price, $category, $meralco_accnum = null, $meralco_accname = null, $maynilad_accnum = null, $maynilad_accname = null) {
    $sql = "UPDATE houses SET house_name = ?, price = ?, category_id = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdii", $housenumber, $price, $category, $house_id);
    $stmt->execute();
    
    // Check if the house update was successful
    $houseUpdated = $stmt->affected_rows > 0;
    
    // Check if we need to update the houseaccounts table
    if ($meralco_accnum !== null) {
      $sqlHouseAcc = "UPDATE houseaccounts SET elec_accname = ?, elec_accnum = ?, water_accname = ?, water_accnum = ? WHERE houses_id = ?";
      $stmtHouseAcc = $this->conn->prepare($sqlHouseAcc);
      $stmtHouseAcc->bind_param("sisii", $meralco_accname, $meralco_accnum, $maynilad_accname, $maynilad_accnum, $house_id);
      $stmtHouseAcc->execute();
      
      // Check if the houseaccounts update was successful
      $houseAccUpdated = $stmtHouseAcc->affected_rows > 0;
      
      // Return true if either the house or houseaccounts update was successful
      if ($houseUpdated || $houseAccUpdated) {
        return true; // Update successful
      } else if($stmtHouseAcc->error) {
        return false; // Update failed
      } else {
        return true;
      }
    } else {
      // Return true if the house update was successful
      return $houseUpdated;
    }
  }

  // Function to add a new house
  public function addHouse($housenumber, $price, $category, $e_accountname, $e_accountnum, $w_accountname, $w_accountnum) {
    $sql = "INSERT INTO houses (house_name, price, category_id) VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdi", $housenumber, $price, $category);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Get the auto-generated ID of the inserted house
      $houseId = $stmt->insert_id;
        
      // Prepare and execute the query to insert into the housesaccounts table
      $sqlAccounts = "INSERT INTO houseaccounts (houses_id, elec_accname, elec_accnum, water_accname, water_accnum) VALUES (?, ?, ?, ?, ?)";
      $stmtAccounts = $this->conn->prepare($sqlAccounts);
      $stmtAccounts->bind_param("isisi", $houseId, $e_accountname, $e_accountnum, $w_accountname, $w_accountnum);
      $stmtAccounts->execute();

      // Check if insertion into housesaccounts table was successful
      if ($stmtAccounts->affected_rows > 0) {
          return true; // Insertion successful
      } else {
          return false; // Insertion into housesaccounts failed
      }
    } else {
      return false; // Insertion failed
    }
  }

  public function deleteCategory($categoryid) {
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }

  public function updateCategory($categoryid, $categoryname) {
    $sql = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $categoryname, $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Update successful
    } else {
        return false; // Update failed
    }
  }

  public function addCategory($categoryname) {
    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $categoryname);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Insertion successful
    } else {
        return false; // Insertion failed
    }
  }

  // public function addTenant($firstname, $middlename, $lastname, $contactno, $houseid, $registerdate) {
  //   $sql = "INSERT INTO tenants (fname, mname, lname, contact, house_id, date_start) VALUES (?, ?, ?, ?, ?, ?)";
  //   $stmt = $this->conn->prepare($sql);
  //   $stmt->bind_param("ssssis", $firstname, $middlename, $lastname, $contactno, $houseid, $registerdate);
  //   $stmt->execute();
  //   if ($stmt->affected_rows > 0) {
  //       return true; // Insertion successful
  //   } else {
  //       return false; // Insertion failed
  //   }
  // }

  public function deleteTenant($tenantid) {
    $sql = "DELETE FROM tenants WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $tenantid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }

  public function updateTenant($tenant_id, $firstname, $middlename, $lastname, $contactno, $houseid, $housecategory, $registerdate) {
    $sql = "UPDATE tenants SET fname = ?, mname = ?, lname = ?, contact = ?, house_id = ?, house_category = ?, date_start = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssssssi", $firstname, $middlename, $lastname, $contactno, $houseid, $housecategory, $registerdate, $tenant_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Update successful
    } else {
        return false; // Update failed
    }
  }

  public function addTenant($contactno, $users_id, $users_username, $houseid, $housename, $registerdate, $preferreddate = null) {
    // Check if the users_username already exists
    $checkSql = "SELECT * FROM tenants WHERE users_id = ?";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->bind_param("s", $users_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
      // Username already exists, return a specific error message
      return array("success" => false, "message" => "Username already exists.");
    } else {
      // Fetch user details from the users table
      $userSql = "SELECT firstname, middlename, lastname FROM users WHERE id = ?";
      $userStmt = $this->conn->prepare($userSql);
      $userStmt->bind_param("s", $users_id);
      $userStmt->execute();
      $userResult = $userStmt->get_result();

      if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $firstname = $userRow['firstname'];
        $middlename = $userRow['middlename'];
        $lastname = $userRow['lastname'];

        // Determine the appropriate SQL statement based on whether preferreddate is provided
        if ($preferreddate) {
          // Insert with date_preferred
          $insertSql = "INSERT INTO tenants (fname, mname, lname, contact, users_id, users_username, house_id, house_category, date_start, date_preferred) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $insertStmt = $this->conn->prepare($insertSql);
          $insertStmt->bind_param("ssssssssss", $firstname, $middlename, $lastname, $contactno, $users_id, $users_username, $houseid, $housename, $registerdate, $preferreddate);
        } else {
          // Insert without date_preferred
          $insertSql = "INSERT INTO tenants (fname, mname, lname, contact, users_id, users_username, house_id, house_category, date_start) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $insertStmt = $this->conn->prepare($insertSql);
          $insertStmt->bind_param("sssssssss", $firstname, $middlename, $lastname, $contactno, $users_id, $users_username, $houseid, $housename, $registerdate);
        }

        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            // Insertion successful
            return array("success" => true);
        } else {
            // Insertion failed
            return array("success" => false, "message" => "Error occurred while adding tenant.");
        }
      } else {
          // User not found in the users table
          return array("success" => false, "message" => "User not found.");
      }
    }

    // $sql = "INSERT INTO tenants (fname, mname, lname, contact, users_username, house_id, house_category, date_start) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    // $stmt = $this->conn->prepare($sql);
    // $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $contactno, $users_username,$houseid, $housename, $registerdate);
    // $stmt->execute();
    // if ($stmt->affected_rows > 0) {
    //     return true; // Insertion successful
    // } else {
    //     return false; // Insertion failed
    // }
  }

  public function addPayment($name, $amount, $houses_id, $paymentDate, $filePath) {
    // Sanitize input data
    $name = $this->conn->real_escape_string($name);
    $amount = (float)$amount;
    $paymentDate = $this->conn->real_escape_string($paymentDate);
    $filePath = $this->conn->real_escape_string($filePath);

    // Fetch the tenants_id based on the session user_id
    $tenantIdSql = "SELECT id FROM tenants WHERE users_id = {$this->session_id}";
    $tenantIdResult = $this->conn->query($tenantIdSql);

    if ($tenantIdResult && $tenantIdResult->num_rows > 0) {
        $row = $tenantIdResult->fetch_assoc();
        $tenantId = $row['id'];

        // SQL query to insert the payment into the database
        $sql = "INSERT INTO payments (name, amount, tenants_id, houses_id, date_payment, filepath) VALUES ('$name', $amount, $tenantId, $houses_id, '$paymentDate', '$filePath')";

        // Execute the query
        $result = $this->conn->query($sql);
        if ($result === TRUE) {
          // If the query is successful, return true
          return true;
        } else {
          // If an error occurred, display the error message
          echo "Error: " . $this->conn->error;
          return false;
        }
    } else {
        // If the tenant ID couldn't be fetched, return false
        return false;
    }
  }

  public function getAllUsers() {
    $sql = "SELECT id, username FROM users";
    $result = $this->conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
  }
  
  public function sendMessage($sender_id, $receiver_id, $message, $image_path = null) {
    $sql = "INSERT INTO messages (sender_id, receiver_id, users_id, message, timestamp, image_path) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiiss", $sender_id, $receiver_id, $sender_id, $message, $image_path); // Assuming users_id is the same as sender_id
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
  }

  public function getMessages($user_id, $chat_user_id) {
    $messages = array();

    // Example SQL query to fetch messages from a hypothetical database table
    $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $chat_user_id, $chat_user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    return $messages;
  }

  public function addCategoryPapers($categorynamepapers) {
    $sql = "INSERT INTO paper_categories (name) VALUES (?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $categorynamepapers);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Insertion successful
    } else {
        return false; // Insertion failed
    }
  }

  public function deleteCategoryPapers($categoryid) {
    $sql = "DELETE FROM paper_categories WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }

  public function addPaper($categoryId, $paperName, $fileName, $targetPath) {
    // Prepare the SQL statement to fetch the category name
    $stmt = $this->conn->prepare("SELECT name FROM paper_categories WHERE id = ?");
    // Bind the parameter
    $stmt->bind_param("i", $categoryId);
    // Execute the statement
    $stmt->execute();
    // Bind the result to a variable
    $stmt->bind_result($categoryName);
    // Fetch the result
    $stmt->fetch();
    // Close the statement
    $stmt->close();

    // Check if the category name was found
    if (!$categoryName) {
        return false; // Category not found
    }

    // Prepare the SQL statement to insert a new paper record
    $stmt = $this->conn->prepare("INSERT INTO paper_files (category_id, category_name, file_name, file_url, uploaded_at) VALUES (?, ?, ?, ?, NOW())");
    // Bind the parameters
    $stmt->bind_param("isss", $categoryId, $categoryName, $fileName, $targetPath);

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();

    // Return the result of the execution
    return $result;
  }

  public function deletePaper($categoryid) {
    $sql = "DELETE FROM paper_files WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }

  public function approvePayment($paymentsid) {
    $sql = "UPDATE payments SET approval = 'true' WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $paymentsid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Approval successful
    } else {
        return false; // Approval failed
    }
  }

  public function declinePayment($paymentsid) {
    $sql = "UPDATE payments SET approval = 'false' WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $paymentsid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      return true; // Approval successful
    } else {
      return false; // Approval failed
    }
  }










  public function sendEmail($to, $subject, $body, $attachmentPath = null) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'your_email@gmail.com'; // SMTP username
        $mail->Password = 'your_password'; // SMTP password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Mailer');
        $mail->addAddress($to); // Add a recipient

        // Attachments
        if ($attachmentPath) {
            $mail->addAttachment($attachmentPath); // Add attachments
        }

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body); // Optional: plain text version for non-HTML email clients

        $mail->send();
        return true;
    } catch (PHPMailer\PHPMailer\Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
  }

  public function sendMonthlyPaymentNotifications() {
    // Current date
    $today = new DateTime();
    $todayString = $today->format('Y-m-d');
    
    // SQL query to get all tenants and house price
    $sql = "SELECT t.*, u.email, h.price
    FROM tenants t
    INNER JOIN users u ON t.users_id = u.id
    INNER JOIN houses h ON t.house_id = h.id";
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      while ($tenant = $result->fetch_assoc()) {
        $to = $tenant['email']; // Email from the users table
        $datePreferred = $tenant['date_preferred'];
        $dateStart = $tenant['date_start'];
        $price = $tenant['price'];

        // Determine the base date for notification
        $baseDate = !empty($datePreferred) ? new DateTime($datePreferred) : new DateTime($dateStart);

        // Calculate the months difference
        $interval = $today->diff($baseDate);
        $monthsDiff = $interval->y * 12 + $interval->m;

        // Send email if months difference is positive and it's the exact day of the month
        if ($monthsDiff > 0 && $baseDate->format('d') == $today->format('d')) {
          // Calculate the number of months passed
          $monthsPassed = $interval->y * 12 + $interval->m;

          // Calculate the total rent due
          // $totalRentDue = $monthsPassed * $price;

          // Fetch payment amounts for the specific tenant, considering only payments on or after the base date
          $baseDateString = $baseDate->format('Y-m-d');
          $sql_payments = "SELECT SUM(amount) AS total_payments
                FROM payments
                WHERE tenants_id = {$tenant['id']}
                  AND houses_id = {$tenant['house_id']}
                  AND date_payment >= '$baseDateString'";
          $result_payments = $this->conn->query($sql_payments);
          $row_payments = $result_payments->fetch_assoc();
          $totalPayments = $row_payments['total_payments'];

          // Count the number of tenants sharing the same house
          $sql_tenants_count = "SELECT COUNT(*) AS tenants_count
                    FROM tenants
                    WHERE house_id = {$tenant['house_id']}";
          $result_tenants_count = $this->conn->query($sql_tenants_count);
          $row_tenants_count = $result_tenants_count->fetch_assoc();
          $tenants_count = $row_tenants_count['tenants_count'];

          // Calculate the rent due per tenant
          // $rentDuePerTenant = $totalRentDue / $tenants_count;
          $rentDuePerTenant = $price / $tenants_count;

          // Calculate the current balance for the current tenant
          $balance = $rentDuePerTenant - $totalPayments;

          // Create email content
          $subject = "Monthly Payment Reminder";
          $body = "Dear " . $tenant['fname'] . " " . $tenant['lname'] . ",<br><br>";
          $body .= "This is a reminder that your monthly payment is due.<br>";
          $body .= "Amount Due: $" . number_format($balance, 2) . "<br><br>";
          $body .= "Best regards,<br>Your Company Name";

          // Send email
          if ($this->sendEmail($to, $subject, $body)) {
              echo "Email sent to " . $to . "\n";
          } else {
              echo "Failed to send email to " . $to . "\n";
          }
        }
      }
    } else {
        echo "No tenants to notify today.\n";
    }
  }

  
}

// $test = new Admin();
// $test->sendEmail("email@gmail.com", "Lupercal", "TESTING BODY 123___");
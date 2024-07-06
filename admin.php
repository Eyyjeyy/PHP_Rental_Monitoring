<?php
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
    if ($stmt->affected_rows > 0) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
  }

  public function updateHouse($house_id, $housenumber, $price, $category) {
    $sql = "UPDATE houses SET house_name = ?, price = ?, category_id = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdii", $housenumber, $price, $category, $house_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true; // Update successful
    } else {
        return false; // Update failed
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
    // Prepare the SQL statement to insert a new paper record
    $stmt = $this->conn->prepare("INSERT INTO paper_files (category_id, file_name, file_url, uploaded_at) VALUES (?, ?, ?, NOW())");
    // Bind the parameters
    $stmt->bind_param("iss", $categoryId, $fileName, $targetPath);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Success, return true
        // $stmt->close(); // Close the statement before returning
        return true;
    } else {
        // Error, return false
        // $stmt->close(); // Close the statement before returning
        return false;
    }
    // Close the statement
    $stmt->close();
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

}


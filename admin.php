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
    if (isset($_SESSION['dbmessage'])) {
      echo '<div class="alert alert-success">' . $_SESSION['dbmessage'] . '</div>';
      unset($_SESSION['dbmessage']);
    }

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
  public function registerUser($username, $firstname, $middlename, $lastname, $password, $email, $phonenumber) {
    // Validate input
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($phonenumber)) {
      $_SESSION['message'] = "Fill up all fields";
      return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['message'] = "Invalid Email";
      return false;
    }
    if (strlen($password) < 7) {
      $_SESSION['message'] = "Password must be at least 7 characters long";
      return false;
    }
    $digitCount = preg_match_all('/\d/', (string)$phonenumber);
    if ($digitCount < 11 || $digitCount > 12) {
      $_SESSION['message'] = "Phone number must be 11 or 12 digits long";
      return false;
    }

    // Check if the username already exists
    $checkStmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    // If a row was found, the username already exists
    if ($checkStmt->num_rows > 0) {
      $_SESSION['message'] = "Username already taken";
      $checkStmt->close();
      return false;
    }
    $checkStmt->close();
    
    $role = 'user';

    // Prepare the SQL statement to insert the new user
    $stmt = $this->conn->prepare("INSERT INTO users (username, firstname, middlename, lastname, password, email, role, phonenumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $phonenumberStr = (string)$phonenumber;
    $stmt->bind_param("ssssssss", $username, $firstname, $middlename, $lastname, $password, $email, $role, $phonenumberStr);

    // Execute the statement and check if the user was added successfully
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }

    // Close the statement
    $stmt->close();
  }

  // Function to delete a user by ID
  public function deleteUser($user_id) {
    $retrievesql = "SELECT * FROM users WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $user_id);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();
    
    $retrieveRow = $retrieveResult->fetch_assoc();
    $delete_username = $retrieveRow['username'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        // Log the action
        $this->History($this->session_id, 'Delete', 'Deleted User, ID: ' . $user_id . '<br> Username: ' . $delete_username);
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

    // Retrieve Old Values for History Logs
    $oldvalsql = "SELECT * FROM users WHERE id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql);
    $oldvalstmt->bind_param("i", $user_id);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_username = $oldvalRow['username'];

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

    $retrievesql = "SELECT * FROM users WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $user_id);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();
    
    $retrieveRow = $retrieveResult->fetch_assoc();
    $update_username = $retrieveRow['username'];
    $this->History($this->session_id, 'Update', 'Updated User, ID: ' . $user_id . '<br> New Username: ' . $update_username . '<br> Old Username: ' . $oldval_username);

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
      // Get the ID of the newly inserted record
      $newUserId = $stmt->insert_id;

      $retrievesql = "SELECT * FROM users WHERE id = ?";
      $retrievestmt = $this->conn->prepare($retrievesql);
      $retrievestmt->bind_param("i", $newUserId);
      $retrievestmt->execute();
      $retrieveResult = $retrievestmt->get_result();

      // Fetch the user record as an associative array
      $userRecord = $retrieveResult->fetch_assoc();
      $added_username = $userRecord['username'];

      $this->History($this->session_id, 'Add', 'Added User, ID: ' . $newUserId . '<br> Username: ' . $added_username);

      return true; // Insertion successful
    } else {
      return false; // Insertion failed
    }
  }

  // Function to delete a house by ID
  public function deleteHouse($house_id) {
    $retrievesql = "SELECT houses.*, 
                      categories.name AS category_name,
                      houseaccounts.*
                    FROM houses
                    INNER JOIN categories ON categories.id = houses.category_id
                    LEFT JOIN houseaccounts ON houses.id = houseaccounts.houses_id
                    WHERE houses.id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $house_id);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();
    
    $retrieveRow = $retrieveResult->fetch_assoc();
    $delete_housename = $retrieveRow['house_name'];
    $delete_categoryname = $retrieveRow['category_name'];
    $delete_price = $retrieveRow['price'];

    $sql = "DELETE FROM houses WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $house_id);
    $stmt->execute();

    $sql_2 = "DELETE FROM houseaccounts WHERE houses_id = ?";
    $stmt_2 = $this->conn->prepare($sql_2);
    $stmt_2->bind_param("i", $house_id);
    $stmt_2->execute();
    if ($stmt->affected_rows > 0) {
      // Log the action
      $this->History($this->session_id, 'Delete', 'Deleted House, ID: ' . $house_id . '<br> Housename: ' . $delete_housename . '<br> Category: ' . $delete_categoryname
      . '<br> Price: ' . $delete_price);
      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }

  public function updateHouse($house_id, $housenumber, $price, $category, $meralco_accnum = null, $meralco_accname = null, $maynilad_accnum = null, $maynilad_accname = null) {
    $oldvalsql  = "SELECT houses.*, 
                      categories.name AS category_name,
                      houseaccounts.*
                    FROM houses
                    INNER JOIN categories ON categories.id = houses.category_id
                    LEFT JOIN houseaccounts ON houses.id = houseaccounts.houses_id
                    WHERE houses.id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql );
    $oldvalstmt->bind_param("i", $house_id);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_housename = $oldvalRow['house_name'];
    $oldval_categoryname = $oldvalRow['category_name'];
    $oldval_price = $oldvalRow['price'];

    $newCatSql = "SELECT name FROM categories WHERE id = ?";
    $newCatStmt = $this->conn->prepare($newCatSql);
    $newCatStmt->bind_param("i", $category);
    $newCatStmt->execute();
    $newCatResult = $newCatStmt->get_result();
    $newCatRow = $newCatResult->fetch_assoc();
    $new_categoryname = $newCatRow['name'];

    $changes = [];

    if ($oldval_housename !== $housenumber) {
      $changes[] = 'Housename: ' . $oldval_housename . ' -> ' . $housenumber;
    }

    if ($oldval_categoryname !== $new_categoryname) {
      $changes[] = 'Category: ' . $oldval_categoryname . ' -> ' . $new_categoryname;
    }

    if ($oldval_price !== $price) {
      $changes[] = 'Price: ' . $oldval_price . ' -> ' . $price;
    }

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

      // Log additional changes if needed
      if ($oldvalRow['elec_accnum'] != $meralco_accnum) {
        $changes[] = 'Electric Account Number: ' . $oldvalRow['elec_accnum'] . ' -> ' . $meralco_accnum;
      }
      if ($oldvalRow['elec_accname'] !== $meralco_accname) {
        $changes[] = 'Electric Account Name: ' . $oldvalRow['elec_accname'] . ' -> ' . $meralco_accname;
      }
      if ($oldvalRow['water_accnum'] != $maynilad_accnum) {
        $changes[] = 'Water Account Number: ' . $oldvalRow['water_accnum'] . ' -> ' . $maynilad_accnum;
      }
      if ($oldvalRow['water_accname'] !== $maynilad_accname) {
        $changes[] = 'Water Account Name: ' . $oldvalRow['water_accname'] . ' -> ' . $maynilad_accname;
      }
      
      // Return true if either the house or houseaccounts update was successful
      if ($houseUpdated || $houseAccUpdated) {
        // Combine all changes into a single log message
        $logMessage = 'Updated House, ID: ' . $house_id . '<br>' . implode('<br>', $changes);

        // Log the action
        $this->History($this->session_id, 'Update', $logMessage);

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
        // Log the action
        $this->History($this->session_id, 'Add', 'Added House, ID: ' . $houseId . '<br> Housename: ' . $housenumber . '<br> Category: ' . $category
        . '<br> Price: ' . $price . '<br> Electric Account: ' . $e_accountname . ' (' . $e_accountnum . ')<br> Water Account: ' . $w_accountname . ' (' . $w_accountnum . ')');

        return true; // Insertion successful
      } else {
        return false; // Insertion into housesaccounts failed
      }
    } else {
      return false; // Insertion failed
    }
  }

  public function deleteCategory($categoryid) {
    $retrievesql = "SELECT * FROM categories WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $categoryid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();
    
    $retrieveRow = $retrieveResult->fetch_assoc();
    $delete_category = $retrieveRow['name'];

    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Log the action
      $this->History($this->session_id, 'Delete', 'Deleted Category, ID: ' . $categoryid . '<br> Username: ' . $delete_category);

      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }

  public function updateCategory($categoryid, $categoryname) {
    $oldvalsql = "SELECT * FROM categories WHERE id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql);
    $oldvalstmt->bind_param("i", $categoryid);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_category = $oldvalRow['name'];

    $changes = [];

    if ($oldval_category !== $categoryname) {
      $changes[] = 'Housename: ' . $oldval_category . ' -> ' . $categoryname;
    }

    $sql = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $categoryname, $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Combine all changes into a single log message
      $logMessage = 'Updated Category, ID: ' . $categoryid . '<br>' . implode('<br>', $changes);

      // Log the action
      $this->History($this->session_id, 'Update', $logMessage);

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
      $newCategoryId = $stmt->insert_id;

      $retrievesql = "SELECT * FROM categories WHERE id = ?";
      $retrievestmt = $this->conn->prepare($retrievesql);
      $retrievestmt->bind_param("i", $newCategoryId);
      $retrievestmt->execute();
      $retrieveResult = $retrievestmt->get_result();

      // Fetch the category record as an associative array
      $categoryRecord = $retrieveResult->fetch_assoc();
      $added_category = $categoryRecord['name'];

      $this->History($this->session_id, 'Add', 'Added Category, ID: ' . $newCategoryId . '<br> Username: ' . $added_category);

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
    $retrievesql = "SELECT * FROM tenants where id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $tenantid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();
    
    $retrieveRow = $retrieveResult->fetch_assoc();
    $delete_fname = $retrieveRow['fname'];
    $delete_mname = $retrieveRow['mname'];
    $delete_lname = $retrieveRow['lname'];
    $delete_usersid = $retrieveRow['users_id'];
    $delete_users_username = $retrieveRow['users_username'];
    $delete_house_id = $retrieveRow['house_id'];

    $sql = "DELETE FROM tenants WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $tenantid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Log the action
      $this->History($this->session_id, 'Delete', 'Deleted Tenant, ID: ' . $tenantid . '<br> Firstname: ' . $delete_fname . '<br> Middlename: ' . $delete_mname
      . '<br> Lastname: ' . $delete_lname . '<br> User ID: ' . $delete_usersid . '<br> Username: ' . $delete_users_username . '<br> House ID: ' .$delete_house_id);

      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }

  public function updateTenant($tenant_id, $firstname, $middlename, $lastname, $contactno, $houseid, $housecategory, $registerdate) {
    $oldvalsql = "SELECT * FROM tenants WHERE id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql);
    $oldvalstmt->bind_param("i", $tenant_id);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_fname = $oldvalRow['fname'];
    $oldval_mname = $oldvalRow['mname'];
    $oldval_lname = $oldvalRow['lname'];
    $oldval_contact = $oldvalRow['contact'];
    $oldval_datestart = $oldvalRow['date_start'];
    $oldval_houseid = $oldvalRow['house_id'];
    $oldval_housecategory = $oldvalRow['house_category'];

    $changes = [];

    if ($oldval_fname != $firstname) {
      $changes[] = 'Firstname: ' . $oldval_fname . ' -> ' . $firstname;
    }

    if ($oldval_mname != $middlename) {
      $changes[] = 'Middlename: ' . $oldval_mname . ' -> ' . $middlename;
    }

    if ($oldval_lname != $lastname) {
      $changes[] = 'Lastname: ' . $oldval_lname . ' -> ' . $lastname;
    }

    if ($oldval_contact != $contactno) {
      $changes[] = 'Contact: ' . $oldval_contact . ' -> ' . $contactno;
    }

    if ($oldval_houseid != $houseid) {
      $changes[] = 'House ID: ' . $oldval_houseid . ' -> ' . $houseid;
    }

    if ($oldval_housecategory != $housecategory) {
      $changes[] = 'House Category: ' . $oldval_housecategory . ' -> ' . $housecategory;
    }

    if ($oldval_datestart != $registerdate) {
      $changes[] = 'Registration Date: ' . $oldval_datestart . ' -> ' . $registerdate;
    }

    $sql = "UPDATE tenants SET fname = ?, mname = ?, lname = ?, contact = ?, house_id = ?, house_category = ?, date_start = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssssssi", $firstname, $middlename, $lastname, $contactno, $houseid, $housecategory, $registerdate, $tenant_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Combine all changes into a single log message
      $logMessage = 'Updated Tenant, ID: ' . $tenant_id . '<br>' . implode('<br>', $changes);

      // Log the action
      $this->History($this->session_id, 'Update', $logMessage);

      return true; // Update successful
    } else if ($stmt->errno) {
      return false; // Update failed
    } else {
      return true; // No changes were made, but query executed without error
    }
  }

  public function addTenant($users_id, $users_username, $houseid, $housename, $registerdate, $preferreddate = null) {
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
          $insertSql = "INSERT INTO tenants (fname, mname, lname, users_id, users_username, house_id, house_category, date_start, date_preferred) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $insertStmt = $this->conn->prepare($insertSql);
          $insertStmt->bind_param("sssssssss", $firstname, $middlename, $lastname, $users_id, $users_username, $houseid, $housename, $registerdate, $preferreddate);
        } else {
          // Insert without date_preferred
          $insertSql = "INSERT INTO tenants (fname, mname, lname, users_id, users_username, house_id, house_category, date_start) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
          $insertStmt = $this->conn->prepare($insertSql);
          $insertStmt->bind_param("sssssssss", $firstname, $middlename, $lastname, $users_id, $users_username, $houseid, $housename, $registerdate);
        }

        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
          $newTenantId = $insertStmt->insert_id;

          $retrievesql = "SELECT * FROM tenants WHERE id = ?";
          $retrievestmt = $this->conn->prepare($retrievesql);
          $retrievestmt->bind_param("i", $newTenantId);
          $retrievestmt->execute();
          $retrieveResult = $retrievestmt->get_result();

          $categoryRecord = $retrieveResult->fetch_assoc();
          $added_tenantfn = $categoryRecord['fname'];
          $added_tenantmn = $categoryRecord['mname'];
          $added_tenantln = $categoryRecord['lname'];
          $added_tenantcontact = $categoryRecord['contact'];
          $added_tenantusersid = $categoryRecord['users_id'];
          $added_tenantusersusername = $categoryRecord['users_username'];
          $added_houseid = $categoryRecord['house_id'];
          $added_housecategory = $categoryRecord['house_category'];

          $logMessage = 
          'Added Tenant, ID: ' . $newTenantId . '<br>' .
          'First Name: ' . $added_tenantfn . '<br>' .
          'Middle Name: ' . $added_tenantmn . '<br>' .
          'Last Name: ' . $added_tenantln . '<br>' .
          'Contact: ' . $added_tenantcontact . '<br>' .
          'User ID: ' . $added_tenantusersid . '<br>' .
          'Username: ' . $added_tenantusersusername . '<br>' .
          'House ID: ' . $added_houseid . '<br>' .
          'House Category: ' . $added_housecategory;

          $this->History($this->session_id, 'Add', $logMessage);

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
    $retrieveusername = $this->conn->prepare("SELECT firstname, middlename, lastname FROM users WHERE id = ?");
    $retrieveusername->bind_param("i", $this->session_id);
    $retrieveusername->execute();
    $retrieveresult = $retrieveusername->get_result();

    if ($retrieverow = $retrieveresult->fetch_assoc()) {
      // Concatenate the firstname, middlename, and lastname with a space separator
      $name = trim($retrieverow['firstname']) . ' ' . trim($retrieverow['middlename']) . ' ' . trim($retrieverow['lastname']);
    }

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
    // $sql = "SELECT id, username FROM users";
    $sql = "SELECT u.id, u.username, (SELECT COUNT(*) FROM messages WHERE receiver_id = u.id AND seen = 0) AS unread_count FROM users u";
    $result = $this->conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
  }

  public function getAdminOnly() {
    $sql = "SELECT u.id, u.username FROM users u WHERE role = 'admin'";
    $result = $this->conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $users[] = $row;
      }
    }
    return $users;
  }
  
  public function sendMessage($sender_id, $receiver_id, $message, $media_path = null) {
    $sql = "INSERT INTO messages (sender_id, receiver_id, users_id, message, timestamp, image_path) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiiss", $sender_id, $receiver_id, $sender_id, $message, $media_path); // Assuming users_id is the same as sender_id

    $retrievesql = "SELECT * FROM users WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $this->session_id);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    $userRecord = $retrieveResult->fetch_assoc();
    $user_role = $userRecord['role'];

    $receiversql = "SELECT * FROM users WHERE id = ?";
    $receiverstmt = $this->conn->prepare($receiversql);
    $receiverstmt->bind_param("i", $receiver_id);
    $receiverstmt->execute();
    $receiverResult = $receiverstmt->get_result();

    $receiverRecord = $receiverResult->fetch_assoc();
    $receiver_username = $receiverRecord['username'];

    if ($stmt->execute()) {
      $newMessageId = $stmt->insert_id;
      
      if($retrieveResult->num_rows > 0 && $user_role == 'admin') {
        $logMessage = 
        'Message, ID: ' . $newMessageId . '<br>' .
        'Receiver, : ' . $receiver_username . '<br>';

        $this->History($this->session_id, 'Message', $logMessage);
      }

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
      $newPaperCategoryId = $stmt->insert_id;

      $retrievesql = "SELECT * FROM paper_categories WHERE id = ?";
      $retrievestmt = $this->conn->prepare($retrievesql);
      $retrievestmt->bind_param("i", $newPaperCategoryId);
      $retrievestmt->execute();
      $retrieveResult = $retrievestmt->get_result();

      // Fetch the papercategory record as an associative array
      $papercategoryRecord = $retrieveResult->fetch_assoc();
      $added_papercategory = $papercategoryRecord['name'];

      $logMessage = 
      'Added Paper Category, ID: ' . $newPaperCategoryId . '<br>' .
      'Category Name, : ' . $added_papercategory . '<br>';

      $this->History($this->session_id, 'Add', $logMessage);

      return true; // Insertion successful
    } else {
      return false; // Insertion failed
    }
  }

  public function deleteCategoryPapers($categoryid) {
    $retrievesql = "SELECT * FROM paper_categories WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $categoryid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    // Fetch the papercategory record as an associative array
    $papercategoryRecord = $retrieveResult->fetch_assoc();
    $deleted_papercategory = $papercategoryRecord['name'];

    $sql = "DELETE FROM paper_categories WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {

      $logMessage = 
      'Deleted Paper Category, ID: ' . $categoryid . '<br>' .
      'Category Name, : ' . $deleted_papercategory . '<br>';

      $this->History($this->session_id, 'Delete', $logMessage);

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

    if ($stmt->affected_rows > 0) {
      $newPaperId = $stmt->insert_id;

      $retrievesql = "SELECT * FROM paper_files WHERE id = ?";
      $retrievestmt = $this->conn->prepare($retrievesql);
      $retrievestmt->bind_param("i", $newPaperId);
      $retrievestmt->execute();
      $retrieveResult = $retrievestmt->get_result();

      // Fetch the category record as an associative array
      $categoryRecord = $retrieveResult->fetch_assoc();
      $added_paper = $categoryRecord['category_name'];
      $added_paperfilename = $categoryRecord['file_name'];

      $logMessage = 
      'Added Paper, ID: ' . $newPaperId . '<br>' .
      'Category Name : ' . $added_paper . '<br>' .
      'File Name : ' . $added_paperfilename . '<br>';

      $this->History($this->session_id, 'Add', $logMessage);
    }

    // Close the statement
    $stmt->close();

    // Return the result of the execution
    return $result;
  }

  public function deletePaper($categoryid) {
    $retrievesql = "SELECT * FROM paper_files WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $categoryid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    // Fetch the papercategory record as an associative array
    $papercategoryRecord = $retrieveResult->fetch_assoc();
    $deleted_paper = $papercategoryRecord['file_name'];

    $sql = "DELETE FROM paper_files WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $categoryid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      
      $logMessage = 
      'Deleted Paper, ID: ' . $categoryid . '<br>' .
      'Paper Name, : ' . $deleted_paper . '<br>';

      $this->History($this->session_id, 'Delete', $logMessage);

      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }

  public function approvePayment($paymentsid) {
    $retrievesql = "SELECT * FROM payments WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $paymentsid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    // Fetch the papercategory record as an associative array
    $approvePaymentRecord = $retrieveResult->fetch_assoc();
    $approve_paymentname = $approvePaymentRecord['name'];
    $approve_paymentamount = $approvePaymentRecord['amount'];

    // Log additional changes if needed
    if ($approvePaymentRecord['approval'] != 'true') {
      $changes[] = 'Approval: ' . ($approvePaymentRecord['approval'] == null ? 'Pending' : 'Declined') . ' -> ' . 'Accepted';
    }

    $sql = "UPDATE payments SET approval = 'true' WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $paymentsid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {

      // Combine all changes into a single log message
      $logMessage = 'Payment Approved, ID: ' . $paymentsid . '<br>' . implode('<br>', $changes);

      $this->History($this->session_id, 'Approve', $logMessage);

      return true; // Approval successful
    } else {
      return false; // Approval failed
    }
  }

  public function declinePayment($paymentsid) {
    $retrievesql = "SELECT * FROM payments WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $paymentsid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    // Fetch the papercategory record as an associative array
    $approvePaymentRecord = $retrieveResult->fetch_assoc();

    // Payment record not found
    if (!$approvePaymentRecord) {
      return false; 
    }

    $approve_paymentname = $approvePaymentRecord['name'];
    $approve_paymentamount = $approvePaymentRecord['amount'];

    $changes = [];

    // Log additional changes if needed
    if ($approvePaymentRecord['approval'] != 'false') {
      $changes[] = 'Approval: ' . ($approvePaymentRecord['approval'] == null ? 'Pending' : 'Accepted') . ' -> ' . 'Declined';
    }

    $sql = "UPDATE payments SET approval = 'false' WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $paymentsid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {

      // Combine all changes into a single log message
      $logMessage = 'Payment Declined, ID: ' . $paymentsid . '<br>' . implode('<br>', $changes);

      $this->History($this->session_id, 'Declined', $logMessage);

      return true; // Approval successful
    } else {
      return false; // Approval failed
    }
  }


  public function addExpenses($expensesname, $infodata, $expensesamount, $house) {
    if (!empty($house)) {
      $sql = "INSERT INTO expenses (name, info, amount, house_id) VALUES (?, ?, ?, ?)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("ssdi", $expensesname, $infodata, $expensesamount, $house);
    } else {
      $sql = "INSERT INTO expenses (name, info, amount) VALUES (?, ?, ?)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("ssd", $expensesname, $infodata, $expensesamount);
    }

    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      $newExpensesId = $stmt->insert_id;

      $retrievesql = "SELECT * FROM expenses WHERE id = ?";
      $retrievestmt = $this->conn->prepare($retrievesql);
      $retrievestmt->bind_param("i", $newExpensesId);
      $retrievestmt->execute();
      $retrieveResult = $retrievestmt->get_result();

      // Fetch the expenses record as an associative array
      $expensesRecord = $retrieveResult->fetch_assoc();
      $added_expensesname = $expensesRecord['name'];
      $added_infodata = $expensesRecord['info'];
      $added_amount = $expensesRecord['amount'];

      $logMessage = 
      'Added Expenses, ID: ' . $newExpensesId . '<br>' .
      'Expenses Name : ' . $added_expensesname . '<br>' .
      'Expenses Info : ' . $added_infodata . '<br>' .
      'Expenses Amount : ' . $added_amount . '<br>';

      // Add house ID to log if applicable
      if (!empty($house)) {
        $logMessage .= 'House ID: ' . $house . '<br>';
      }

      $this->History($this->session_id, 'Add', $logMessage);

      return true; // Insertion successful
    } else {
      return false; // Insertion failed
    }
  }

  public function updateExpenses($expensesname, $expensesinfo, $expensesid, $expensesamount) {
    $oldvalsql = "SELECT * FROM expenses WHERE id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql);
    $oldvalstmt->bind_param("i", $expensesid);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_expensesname = $oldvalRow['name'];
    $oldval_expensesinfo = $oldvalRow['info'];
    $oldval_expensesamount = $oldvalRow['amount'];

    $changes = [];

    if ($oldval_expensesname !== $expensesname) {
      $changes[] = 'Expense Name: ' . $oldval_expensesname . ' -> ' . $expensesname;
    }
    if ($oldval_expensesinfo !== $expensesinfo) {
      $changes[] = 'Expenses Info: ' . $oldval_expensesinfo . ' -> ' . $expensesinfo;
    }
    if ($oldval_expensesamount !== $expensesamount) {
      $changes[] = 'Expenses Amount: ' . $oldval_expensesamount . ' -> ' . $expensesamount;
    }

    $sql = "UPDATE expenses SET name = ?, info = ?, amount = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssdi", $expensesname, $expensesinfo, $expensesamount, $expensesid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      // Combine all changes into a single log message
      $logMessage = 'Updated Expense, ID: ' . $expensesid . '<br>' . implode('<br>', $changes);

      // Log the action
      $this->History($this->session_id, 'Update', $logMessage);

      return true; // Update successful
    } else if ($stmt->error) {
      return false; // Update failed
    } else {
      return true;
    }
  }

  public function deleteExpenses($expensesid) {
    $retrievesql = "SELECT * FROM expenses WHERE id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $expensesid);
    $retrievestmt->execute();
    $retrieveResult = $retrievestmt->get_result();

    // Fetch the expenses record as an associative array
    $expensesRecord = $retrieveResult->fetch_assoc();
    $deleted_expenses = $expensesRecord['name'];
    $deleted_expensesinfo = $expensesRecord['info'];

    $sql = "DELETE FROM expenses WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $expensesid);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      
      $logMessage = 
      'Deleted Expense, ID: ' . $expensesid . '<br>' .
      'Expense Name : ' . $deleted_expenses . '<br>' .
      'Expense Info : ' . $deleted_expensesinfo . '<br>';

      $this->History($this->session_id, 'Delete', $logMessage);

      return true; // Deletion successful
    } else {
      return false; // Deletion failed
    }
  }








  public function sendOTP($email) {
    $userStmt = $this->conn->prepare("SELECT username FROM users WHERE email = ?");

    // Bind and execute the statement
    $userStmt->bind_param("s", $email);
    $userStmt->execute();
    $userStmt->bind_result($username);
    $userStmt->fetch();
    $userStmt->close();

    // Prepare the SQL statement to check if the email exists for a user role
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND role = 'user'");
    
    if (!$stmt) {
      return "Failed to prepare statement: " . $this->conn->error;
    }

    // Bind the email parameter as a string
    $stmt->bind_param("s", $email);

    // Execute the statement and check if it was successful
    if (!$stmt->execute()) {
      $stmt->close();
      return "Failed to execute statement: " . $stmt->error;
    }

    $stmt->bind_result($emailCount);
    
    // Fetch the result
    $stmt->fetch();
    
    // Close the statement
    $stmt->close();

    // If the email does not exist, return an error message
    if ($emailCount === 0) {
      return false;
    }

    // Generate a random 6-digit OTP
    $otp = random_int(100000, 999999);

    // Optionally, store OTP in a session or database if verification is needed later
    $_SESSION['otp'] = $otp;

    // Update the OTP in the users table for the corresponding email
    $updateStmt = $this->conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
    
    if (!$updateStmt) {
      return "Failed to prepare OTP update: " . $this->conn->error;
    }

    // Bind parameters and execute the statement
    $updateStmt->bind_param("is", $otp, $email);
    
    if (!$updateStmt->execute()) {
      $updateStmt->close();
      return "Failed to update OTP: " . $updateStmt->error;
    }

    // Close the update statement
    $updateStmt->close();

    // Set up the email subject and body
    $subject = "Your OTP Code";
    $body = "Your username: $username<br>Your OTP code is: <strong>$otp</strong><br>Please enter this code to verify your identity.";

    // Call the sendEmail function to send the OTP via email
    $emailSent = $this->sendEmail($email, $subject, $body);

    // Now, send OTP via SMS
    // Fetch the user's phone number
    $phoneStmt = $this->conn->prepare("SELECT phonenumber FROM users WHERE email = ?");
    $phoneStmt->bind_param("s", $email);
    $phoneStmt->execute();
    $phoneStmt->bind_result($phoneNumber);
    $phoneStmt->fetch();
    $phoneStmt->close();

    // Prepare SMS parameters
    $smsSent = false;
    if ($phoneNumber) {
      // Prepare the message
      $smsMessage = "Your username: $username\nYour OTP code is: $otp. Please enter this code to verify your identity.";

      // Set up the cURL request to send SMS
      $ch = curl_init();
      $parameters = array(
        'apikey' => '', // Replace with your actual API key
        'number' => $phoneNumber,  // Recipient's number
        'message' => $smsMessage,
        'sendername' => 'Thesis' // Replace with your registered sender name
      );

      // Set cURL options for the request
      curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Execute the cURL request and get the response
      $output = curl_exec($ch);
      if ($output) {
        $smsSent = true; // SMS sent successfully
      }

      // Close the cURL session
      curl_close($ch);
    }

    // Check if both email and SMS were sent successfully
    if ($emailSent && $smsSent) {
      return "OTP sent successfully via email and SMS.";
    } else {
      return "Failed to send OTP via email or SMS.";
    }
  }

  public function resetPassword($otp, $newPassword) {
    // Verify the OTP
    $stmt = $this->conn->prepare("SELECT email FROM users WHERE otp = ?");
    $stmt->bind_param("i", $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return false; // OTP is invalid or has expired
    }

    // Fetch the user email associated with the OTP
    $user = $result->fetch_assoc();
    $email = $user['email'];

    // Step 3: Update the user's password in the database
    $updateStmt = $this->conn->prepare("UPDATE users SET password = ?, otp = NULL WHERE email = ?");
    $updateStmt->bind_param("ss", $newPassword, $email);
    
    if ($updateStmt->execute()) {
      return true; // Password reset successfully
    } else {
      return false; // Failed to update the password
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
        $mail->Username = 'renttrackpro@gmail.com'; // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('renttrackpro@gmail.com', 'Mailer');
        $mail->addAddress($to); // Add a recipient

        // Attachments
        if ($attachmentPath) {
            // $mail->addAttachment($attachmentPath); // Add attachments
            $cid = 'renttrack_image'; // Content ID for referencing the image
            $mail->addEmbeddedImage($attachmentPath, $cid);
        }

        // Reference the image in the email body
        $body .= '<img src="cid:' . $cid . '" alt="Renttrack Logo" style="width: 200px; height: auto;"><br>';

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

    $notifications = [];
    
    // SQL query to get all tenants and house price
    $sql = "SELECT t.*, u.email, u.phonenumber, h.price
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

          // Prepare notification data (email + balance)
          $notifications[] = [
            'email' => $tenant['email'],
            'fname' => $tenant['fname'],
            'lname' => $tenant['lname'],
            'phonenumber' => $tenant['phonenumber'],
            'balance' => $balance
          ];

          // Create email content
          $subject = "Monthly Rent Payment Reminder";
          $body = '<p style="font-size: 18px; color: #004c00; font-family: Helvetica;">Dear <strong>' . $tenant['fname'] . ' ' . $tenant['lname'] . '</strong>,</p>';
          $body .= '<p style="font-size: 16px; color: #414141;">';
          $body .= 'This is a reminder that your monthly payment is due.<br>';
          $body .= 'Amount Due: <strong>₱' . number_format($balance, 2) . '</strong><br><br>';
          $body .= 'You can pay through the following: <br>Gcash: <br>Bank: <br><br>';
          $body .= 'Upload your proof of payment: <br>Link: https://www.renttrackpro.online/ <br><br>';
          $body .= 'Best regards,<br>Renttrack Pro<br></p>';
          // $imagePath = 'asset/Renttrack pro.png';
          $imagePath = __DIR__ . '/asset/Renttrack pro.png';

          // Send email
          if ($this->sendEmail($to, $subject, $body, $imagePath)) {
              echo "Email sent to " . $to . "\n";
          } else {
              echo "Failed to send email to " . $to . "\n";
          }
        }
      }
    } else {
        echo "No tenants to notify today.\n";
    }
    return $notifications;
  }

  public function getTenantCountByApartment() {
    $sql = "SELECT h.house_name AS house_name, COUNT(t.id) AS tenant_count 
        FROM houses h 
        LEFT JOIN tenants t ON h.id = t.house_id 
        GROUP BY h.house_name";
            
    $result = $this->conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $data[] = $row;
      }
    }

    return json_encode($data);
  }

  public function getMonthlyIncome() {
    $sql = "SELECT 
                DATE_FORMAT(date_payment, '%Y-%m') AS month, 
                SUM(amount) AS total_income,
                approval
            FROM payments 
            GROUP BY DATE_FORMAT(date_payment, '%Y-%m'), approval
            ORDER BY date_payment ASC";

    $result = $this->conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }

    return json_encode($data);
  }

  public function getUserRolePercentage() {
    $sql = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
    $result = $this->conn->query($sql);
    $data = [];

    // Get the total number of users
    $total = 0;
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $total += $row['count'];
          $data[] = $row;
      }
    }

    // Calculate the percentage
    foreach ($data as &$item) {
      $item['percentage'] = round(($item['count'] / $total) * 100, 2);
    }

    return json_encode($data);
  }

  public function getIncomeExpensesData() {
    $sql = "SELECT 
            DATE_FORMAT(p.date_payment, '%Y-%m') AS month,
            SUM(p.amount) AS total_income,
            (
                SELECT SUM(e.amount) 
                FROM expenses e 
                WHERE DATE_FORMAT(e.date, '%Y-%m') = DATE_FORMAT(p.date_payment, '%Y-%m')
            ) AS total_expenses
            FROM payments p
            GROUP BY month";

    $sql = "SELECT month,
        SUM(total_income) AS total_income,
        SUM(total_expenses) AS total_expenses
        FROM (SELECT DATE_FORMAT(p.date_payment, '%Y-%m') AS month, SUM(p.amount) AS total_income, 0 AS total_expenses FROM payments p GROUP BY month UNION ALL
          SELECT DATE_FORMAT(e.date, '%Y-%m') AS month, 0 AS total_income, SUM(e.amount) AS total_expenses FROM expenses e GROUP BY month) AS combined
        GROUP BY month
        ORDER BY month
    ";

$sql = "SELECT 
month,
house_id,
SUM(total_income) AS total_income,
SUM(total_expenses) AS total_expenses
FROM (
SELECT 
    DATE_FORMAT(p.date_payment, '%Y-%m') AS month,
    p.houses_id AS house_id,
    SUM(p.amount) AS total_income,
    0 AS total_expenses
FROM payments p
GROUP BY month, p.houses_id

UNION ALL

SELECT 
    DATE_FORMAT(e.date, '%Y-%m') AS month,
    e.house_id AS house_id,
    0 AS total_income,
    SUM(e.amount) AS total_expenses
FROM expenses e
GROUP BY month, e.house_id
) AS combined
GROUP BY month, house_id
ORDER BY month, house_id";

$sql = "SELECT 
        combined.month,
        combined.houses_id AS house_id,
        h.house_name,
        COALESCE(SUM(combined.total_income), 0) AS total_income,
        COALESCE(SUM(combined.total_expenses), 0) AS total_expenses
        FROM (SELECT DATE_FORMAT(p.date_payment, '%Y-%m') AS month, p.houses_id, SUM(p.amount) AS total_income, 0 AS total_expenses FROM payments p WHERE p.approval = 'true' GROUP BY month, p.houses_id
        UNION ALL
        SELECT DATE_FORMAT(e.date, '%Y-%m') AS month, e.house_id AS houses_id, 0 AS total_income, SUM(e.amount) AS total_expenses FROM expenses e GROUP BY month, e.house_id) 
        AS combined
        LEFT JOIN houses h ON combined.houses_id = h.id
        GROUP BY combined.month, combined.houses_id, h.house_name
        ORDER BY combined.month, combined.houses_id";

    $result = $this->conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }

    return json_encode($data);
  }

  public function getIncomeExpensesDataFiltered() {
    $sql = "SELECT 
                month,
                house_id,
                SUM(total_income) AS total_income,
                SUM(total_expenses) AS total_expenses
            FROM (
                SELECT 
                    DATE_FORMAT(p.date_payment, '%Y-%m') AS month,
                    p.houses_id AS house_id,
                    SUM(p.amount) AS total_income,
                    0 AS total_expenses
                FROM payments p
                GROUP BY month, p.houses_id
                
                UNION ALL
                
                SELECT 
                    DATE_FORMAT(e.date, '%Y-%m') AS month,
                    e.house_id AS house_id,
                    0 AS total_income,
                    SUM(e.amount) AS total_expenses
                FROM expenses e
                GROUP BY month, e.house_id
            ) AS combined
            GROUP BY month, house_id
            ORDER BY month, house_id";

    $result = $this->conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }

    return json_encode($data);
  }


  public function getExpensesPerApartmentData() {
    $sql = "SELECT 
            h.house_name,
            SUM(e.amount) AS total_expenses
            FROM expenses e
            LEFT JOIN houses h ON e.house_id = h.id
            GROUP BY h.house_name";

    $result = $this->conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }

    return json_encode($data);
  }

  public function getYearlyIncomeData($year = null) {
    if ($year === null) {
      $year = date('Y'); // Default to the current year if no year is provided
    }

    $sql = "SELECT 
                YEAR(date_payment) as year, 
                SUM(amount) as total_income 
            FROM payments 
            WHERE approval = 'true'
            GROUP BY YEAR(date_payment) 
            ORDER BY YEAR(date_payment) ASC";

    $result = $this->conn->query($sql);

    $incomeData = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $incomeData[] = [
            'year' => $row['year'],
            'total_income' => $row['total_income']
          ];
      }
    }

    return json_encode($incomeData);

  }

  public function countPendingApprovals() {
    // SQL query to count records where the approval is not true or false
    $sql = "SELECT COUNT(*) as count FROM payments WHERE approval NOT IN ('true', 'false')";
    
    // Execute the query
    $result = $this->conn->query($sql);
    
    // Fetch the result
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['count'];
    } else {
      return 0; // No records found
    }
  }

  function countUsersNotInTenants() {
    // SQL query to count users whose id is not in the tenants table
    $sql = "SELECT COUNT(*) as count FROM users WHERE id NOT IN (SELECT users_id FROM tenants) AND role = 'user'";
    
    // Execute the query
    $result = $this->conn->query($sql);
    
    // Fetch the result
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['count'];
    } else {
      return 0; // No records found
    }
  }

  public function getUserProfile($userId) {
    $userId = (int)$userId;
    $sql = "SELECT users.*, tenants.* 
            FROM users 
            LEFT JOIN tenants ON users.id = tenants.users_id 
            WHERE users.id = $userId";
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      return $result->fetch_assoc();
    } else {
      return null;
    }
  }

  public function updateUserProfile($userId, $firstName = null, $middleName = null, $lastName = null, $contactNumber = null, $email = null, $password = null) {
    $oldvalsql = "SELECT users.*, tenants.* 
                  FROM users 
                  LEFT JOIN tenants ON users.id = tenants.users_id 
                  WHERE users.id = ?";
    $oldvalstmt = $this->conn->prepare($oldvalsql);
    $oldvalstmt->bind_param("i", $userId);
    $oldvalstmt->execute();
    $oldvalResult = $oldvalstmt->get_result();
    
    $oldvalRow = $oldvalResult->fetch_assoc();
    $oldval_firstname = $oldvalRow['firstname'];
    $oldval_middlename = $oldvalRow['middlename'];
    $oldval_lastname = $oldvalRow['lastname'];
    $oldval_contact = $oldvalRow['contact'];
    $oldval_email = $oldvalRow['email'];
    $oldval_password = $oldvalRow['password'];

    $changes = [];

    if ($oldval_firstname !== $firstName) {
      $changes[] = 'Firstname: ' . $oldval_firstname . ' -> ' . $firstName;
    }
    if ($oldval_middlename !== $middleName) {
      $changes[] = 'Middlename : ' . $oldval_middlename . ' -> ' . $middleName;
    }
    if ($oldval_lastname !== $lastName) {
      $changes[] = 'Lastname : ' . $oldval_lastname . ' -> ' . $lastName;
    }
    if ($oldval_contact !== $contactNumber) {
      $changes[] = 'Contact : ' . $oldval_contact . ' -> ' . $contactNumber;
    }
    if ($oldval_email !== $email) {
      $changes[] = 'Email : ' . $oldval_email . ' -> ' . $email;
    }
    if ($oldval_password !== $password) {
      $changes[] = 'Password : ' . $oldval_password . ' -> ' . $password;
    }

    // Retrieve current user data
    $currentUser = $this->getUserProfile($userId);

    // Start building the SQL query for the users table
    $sql = "UPDATE users SET ";
    $sqlParts = [];
    $params = [];
    $types = "";

    // Compare and add fields to the SQL query if they have changed
    if ($firstName !== null && $firstName !== $currentUser['firstname']) {
      $sqlParts[] = "firstname = ?";
      $params[] = $firstName;
      $types .= "s";
    }
    if ($middleName !== null && $middleName !== $currentUser['middlename']) {
      $sqlParts[] = "middlename = ?";
      $params[] = $middleName;
      $types .= "s";
    }
    if ($lastName !== null && $lastName !== $currentUser['lastname']) {
      $sqlParts[] = "lastname = ?";
      $params[] = $lastName;
      $types .= "s";
    }
    if ($password !== null && $password !== $currentUser['password']) {
      $sqlParts[] = "password = ?";
      $params[] = $password;
      $types .= "s";
    }
    if ($email !== null && $email !== $currentUser['email']) {
      $sqlParts[] = "email = ?";
      $params[] = $email;
      $types .= "s";
    }

    if (empty($sqlParts)) {
      // No fields to update
      $_SESSION['success_message'] = "No Changes!";
      return true;
    }

    // Join SQL parts with commas and append WHERE clause
    $sql .= implode(", ", $sqlParts) . " WHERE id = ?";
    $params[] = $userId;
    $types .= "i"; // 'i' for integer userId

    // Prepare the statement
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      die("Error preparing statement: " . $this->conn->error);
    }

    // Bind the parameters
    $stmt->bind_param($types, ...$params);

    // Execute the statement
    $result = $stmt->execute();

    if ($stmt->affected_rows > 0) {
      // Combine all changes into a single log message
      $logMessage = 'Updated User ID: ' . $userId . '<br>' . implode('<br>', $changes);

      // Log the action
      $this->History($this->session_id, 'Update', $logMessage);

      // Update the tenants table with the same userId
      $tenantUpdateResult = $this->updateTenantProfile($userId, $firstName, $middleName, $lastName, $contactNumber);

      // Update the payments table if tenant information was updated
      if ($tenantUpdateResult) {
        $this->updatePaymentsTable($userId, $firstName, $middleName, $lastName);
      }

      $stmt->close();
      return true; // Update successful
    } else if ($stmt->error) {
      $stmt->close();
      return false; // Update failed
    } else {
      $stmt->close();
      return true;
    }

    return $result;
  }

  private function updateTenantProfile($userId, $firstName = null, $middleName = null, $lastName = null, $contactNumber = null) {
    $retrievesql = "SELECT fname, mname, lname, contact FROM tenants WHERE users_id = ?";
    $retrievestmt = $this->conn->prepare($retrievesql);
    $retrievestmt->bind_param("i", $userId);
    $retrievestmt->execute();
    $retrieveresult = $retrievestmt->get_result();
    $retrievetenant = $retrieveresult->fetch_assoc();
    $retrievestmt->close();

    // Start building the SQL query for the tenants table
    $sql = "UPDATE tenants SET ";
    $sqlParts = [];
    $params = [];
    $types = "";

    // Compare and add fields to the SQL query if they have changed
    if ($firstName !== null && $firstName !== $retrievetenant['fname']) {
      $sqlParts[] = "fname = ?";
      $params[] = $firstName;
      $types .= "s";
    }
    if ($middleName !== null && $middleName !== $retrievetenant['mname']) {
      $sqlParts[] = "mname = ?";
      $params[] = $middleName;
      $types .= "s";
    }
    if ($lastName !== null && $lastName !== $retrievetenant['lname']) {
      $sqlParts[] = "lname = ?";
      $params[] = $lastName;
      $types .= "s";
    }
    if ($contactNumber !== null && $contactNumber !== $retrievetenant['contact']) {
      $sqlParts[] = "contact = ?";
      $params[] = $contactNumber;
      $types .= "s";
    }

    if (empty($sqlParts)) {
      // No fields to update
      return false;
    }

    // Join SQL parts with commas and append WHERE clause
    $sql .= implode(", ", $sqlParts) . " WHERE users_id = ?";
    $params[] = $userId;
    $types .= "i"; // 'i' for integer userId

    // Prepare the statement
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      die("Error preparing statement: " . $this->conn->error);
    }

    // Bind the parameters
    $stmt->bind_param($types, ...$params);

    // Execute the statement
    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }

  private function updatePaymentsTable($userId, $firstName = null, $middleName = null, $lastName = null) {
    // Start building the SQL query for the payments table
    $sql = "UPDATE payments p
            JOIN tenants t ON p.tenants_id = t.id
            SET p.name = CONCAT(?, ' ', ?, ' ', ?)
            WHERE t.users_id = ?";

    // Prepare the statement
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      die("Error preparing statement: " . $this->conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("sssi", $firstName, $middleName, $lastName, $userId);

    // Execute the statement
    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }




  public function History($admin_id, $action, $details) {
    $stmt = $this->conn->prepare("INSERT INTO history (admin_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $admin_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }

  
}

// $test = new Admin();
// $test->sendEmail("email@gmail.com", "Lupercal", "TESTING BODY 123___");
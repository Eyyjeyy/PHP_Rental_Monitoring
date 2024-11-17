<?php
    // session_start(); // Start the session (important for checking session variables)
    include '../admin.php';
    $admin = new Admin();
    // $admin->handleRedirect(); // Call handleRedirect to check login status and redirect

    // Removing this causes website to proceed to index.php despite isLoggedIn from admin.php returning false
    if(!$admin->isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
    if($admin->isLoggedIn() && $admin->session_role == 'user') {
        header("Location: ../index.php");
        exit();
    }

    if (isset($_POST['add_contract'])) {
        $userId = trim(htmlspecialchars($_POST['adminuserid']));
        $tenantId = trim(htmlspecialchars($_POST['tenantid']));
        $lessorwitness = trim(htmlspecialchars($_POST['lessorwitness']));
        // $tenantaddressinput = trim(htmlspecialchars($_POST['tenantaddress-input']));
        $apartmentaddressinput = trim(htmlspecialchars($_POST['apartmentaddress-input']));
        $datestart = trim(htmlspecialchars($_POST['datestart']));
        $expirationdate = trim(htmlspecialchars($_POST['expirationdate']));
        $signatureData = $_POST['signature'];
        $signatureData2 = $_POST['signature2'];
        $deposit = trim(htmlspecialchars($_POST['deposit']));
        $rentprice = trim(htmlspecialchars($_POST['rentprice']));

        // Validate that deposit is a number (no letters or special characters)
        if (!preg_match("/^\d+$/", $deposit)) {
            $_SESSION['error_message'] = "Deposit must be a valid number.";
            header("Location: admin_contract_template.php?error=invalid_deposit");
            exit();
        }
        if (!preg_match("/^\d+$/", $rentprice)) {
            $_SESSION['error_message'] = "Rent Price must be a valid number.";
            header("Location: admin_contract_template.php?error=invalid_rentprice");
            exit();
        }

        // Convert the date to a DateTime object for reliable parsing
        $date = DateTime::createFromFormat('Y-m-d', $datestart);
        
        if ($date) {
            // Extract the day
            $day = $date->format('j');
        
            // Function to get the ordinal suffix (st, nd, rd, th)
            function getDayWithSuffix($day) {
                if (!in_array(($day % 100), [11, 12, 13])) {
                    switch ($day % 10) {
                        case 1: return $day . 'st';
                        case 2: return $day . 'nd';
                        case 3: return $day . 'rd';
                    }
                }
                return $day . 'th';
            }
        
            // Get the formatted day with suffix
            $formattedDay = getDayWithSuffix($day);
        }

        // Query the users table to retrieve first, middle, and last names
        $query = "SELECT firstname, middlename, lastname FROM users WHERE id = ? AND role = 'admin'";
        $stmt = $admin->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($firstname, $middlename, $lastname);
        $stmt->fetch();
        $stmt->close();

        // Concatenate names to create the full adminusername
        $adminusername = trim("$firstname $middlename $lastname");

        // Query the tenants table to retrieve first, middle, and last names
        $tenantquery = "SELECT fname, mname, lname FROM tenants WHERE id = ?";
        $tenantstmt = $admin->conn->prepare($tenantquery);
        $tenantstmt->bind_param("i", $tenantId);
        $tenantstmt->execute();
        $tenantstmt->bind_result($tenantfirstname, $tenantmiddlename, $tenantlastname);
        $tenantstmt->fetch();
        $tenantstmt->close();

        // Concatenate names to create the full tenantusername
        $tenantusername = trim("$tenantfirstname $tenantmiddlename $tenantlastname");

        // Validate username to allow only letters, numbers, underscores, and apostrophes
        if (!preg_match("/^[a-zA-Z0-9_' ]+$/", $adminusername) || !preg_match("/^[a-zA-Z0-9_' ]+$/", $tenantusername)) {
            // Invalid tenantname
            $_SESSION['error_message'] = "Input can only contain letters, numbers, underscores, and spaces.";
            header("Location: admin_contract_template.php?error=invalid_username");
            exit();
        }

        // Allow up to 30 characters
        if (!preg_match('/^.{1,70}$/', $apartmentaddressinput)) {
            // Address is valid, process it
            $_SESSION['error_message'] = "Address can only have up to 70 characters";
            header("Location: admin_contract_template.php?error=invalid_address");
            exit();
        }

        $added = $admin->addContract($adminusername, $lessorwitness, $tenantusername, $signatureData, $signatureData2, $datestart, $expirationdate, $formattedDay, $deposit, $tenantId,  
        $apartmentaddressinput, $rentprice);
        if($added) {
            // Contract added successfully, you can display a success message here if needed
            // echo "Contract added successfully.";
            header("Location: admin_contract_template.php?contract_added=1");
            exit();
        } else {
            // Error occurred while adding contract, display an error message or handle as needed
            // echo "Error occurred while adding contract.";
            if(empty($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "Addition Failed due to an error";
            }
            header("Location: admin_contract_template.php?error=add");
            exit();
        }
    }

    if (isset($_POST['delete_contract'])) {
        // Get the contract ID owning the contract to be deleted
        $contractid = $_POST['contractid'];
        // Call the deleteContract method to delete the contract
        $deleted = $admin->deleteContract($contractid);
        if($deleted) {
            header("Location: admin_contract_template.php?contract_deleted=1");
            exit();
        } else {
            if(empty($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "Deletion Failed due to an error";
            }
            header("Location: admin_contract_template.php?error=delete");
            exit();
        }
    }

    if (isset($_POST['upload_contract_file'])) {
        $uploadtenantid = $_POST['uploadtenantid'];
        $uploaddatestart = $_POST['uploaddatestart'];
        $uploadexpirationdate = $_POST['uploadexpirationdate'];
        
        // Check if a file was uploaded
        if (isset($_FILES['contractFile']) && $_FILES['contractFile']['error'] == 0) {
            $fileData = [
                'tmp_name' => $_FILES['contractFile']['tmp_name'],
                'name' => $_FILES['contractFile']['name'],
                'size' => $_FILES['contractFile']['size'],
                'type' => $_FILES['contractFile']['type'],
                'error' => $_FILES['contractFile']['error']
            ];

            // Pass the file data and other parameters to the function
            $uploaded = $admin->uploadContract($uploadtenantid, $uploaddatestart, $uploadexpirationdate, $fileData);

            if ($uploaded) {
                header("Location: admin_contract_template.php?contract_upload=1");
                exit();
            } else {
                if(empty($_SESSION['error_message'])) {
                    $_SESSION['error_message'] = "Upload Failed due to an error.";
                }
            }
        } else {
            if(empty($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "No file uploaded or file upload error.";
            }
        }
        // header("Location: admin_contract_template.php?error=upload");
        // exit();
    }

    // Physical Contracts
    if (isset($_POST['delete_physicalcontract'])) {
        // Get the contract ID owning the contract to be deleted
        $physicalcontractid = $_POST['physicalcontractid'];
        // Call the deleteContract method to delete the contract
        // $deleted = $admin->deletePhysicalContract($physicalcontractid);
        if($deleted) {
            header("Location: admin_contract_template.php?physicalcontract_deleted=1");
            exit();
        } else {
            if(empty($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "Deletion Failed due to an error";
            }
            header("Location: admin_contract_template.php?error=delete");
            exit();
        }
    }

    // Check if there's an error message stored in the session
    if (isset($_SESSION['error_message'])) {
        // Display the error message as an alert
        echo '<div class="alert alert-danger mb-0" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        // Unset the session variable to clear the error message
        unset($_SESSION['error_message']);
    }

    $sql = "SELECT * FROM users WHERE role = 'admin'";
    $result = $admin->conn->query($sql);

    $sql_upload = "SELECT * FROM users WHERE role = 'admin'";
    $result_upload = $admin->conn->query($sql_upload);

    $sql_tenant = "SELECT * FROM tenants";
    $result_tenant = $admin->conn->query($sql_tenant);

    $sql_tenant_upload = "SELECT * FROM tenants";
    $result_tenant_upload = $admin->conn->query($sql_tenant_upload);

    $sql_physical = "SELECT physical_contracts.*, CONCAT(tenants.fname, ' ', tenants.mname, ' ', tenants.lname) AS full_name
    FROM physical_contracts
    JOIN tenants ON physical_contracts.tenantid = tenants.id";
    $result_physical = $admin->conn->query($sql_physical);

    $sql_tenant_table = "SELECT * FROM contracts";
    $result_tenant_table = $admin->conn->query($sql_tenant_table);

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "admincontracts";
?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/header.php'; ?>
            <style>
                /* .wrapper {
                    position: relative;
                    width: 400px;
                    height: 200px;
                    -moz-user-select: none;
                    -webkit-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    border: solid 1px #ddd;
                    margin: 10px auto;
                }
                .signature-pad {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width:400px;
                    height:200px;
                } */
                .wrapper {min-height:200px;border: 1px solid #000;}
                .signature-pad {position: absolute;left: 0;top: 0;width: 100%;height: 100%}
            </style>
            <div class="col main content">
                <div class="card-body" style="margin-top: 12px;">
                    <div class="row">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="searchBar" placeholder="Search..." class="form-control mb-3 " style="max-width: 180px;" />
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-primary float-end table-buttons-update ms-2" id="upload_contract"><i class="fa fa-plus"></i> Upload Contract</button>
                                    <button class="btn btn-primary float-end table-buttons-update" id="new_contract"><i class="fa fa-plus"></i> New Contract</button>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="table-responsive"  id="tablelimiter">
                        <table class="table table-striped table-bordered" id="contractTable">
                            <thead>
                                <tr>
                                    <!-- <th scope="col" class="sortable-column" data-column="house_name" onclick="sortTable('house_name')" style="cursor: pointer;">Apartment Name <span class="sort-arrow" data-column="house_name"></span></th> -->
                                    <th scope="col">#</th>
                                    <th scope="col" data-column="tenantname" class="sortable-column" data-column="tenantname" style="cursor: pointer;">
                                        Tenant
                                        <span class="sort-arrow" data-column="tenantname"></span>
                                    </th>
                                    <th scope="col" data-column="tenantapproval" class="sortable-column">
                                        Status
                                        <span id="tenantapprovalSortArrow"></span>
                                    </th>
                                    <th scope="col" data-column="datestart" class="sortable-column">
                                        Contract Start
                                        <span id="datestartSortArrow"></span>
                                    </th>
                                    <th scope="col" data-column="expirationdate" class="sortable-column">
                                        Contract Expiry
                                        <span id="expirationdateSortArrow"></span>
                                    </th>
                                    <th scope="col">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="contractTableBody">
                                <?php
                                if ($result_tenant_table->num_rows > 0) {
                                    // Output data of each row
                                    while($row_tenant_table = $result_tenant_table->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row_tenant_table['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row_tenant_table['tenantname']) . "</td>";
                                        echo "<td class='text-center'>" . ($row_tenant_table["tenantapproval"] === "true" ? "APPROVED" : ($row_tenant_table["tenantapproval"] === "false" ? "REJECTED" : "PENDING")) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_tenant_table['datestart']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_tenant_table['expirationdate']) . "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                            echo "<div class='col-xl-6 px-2'>";
                                                // Add a form with a delete button for each record
                                                echo "<form method='POST' action='admin_contract_template.php' class='float-xl-end align-items-center' style='height:100%;'>";
                                                    echo "<input type='hidden' name='contractid' value='" . $row_tenant_table['id'] . "'>";
                                                    echo "<button type='submit' name='delete_contract' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                                echo "</form>";
                                            echo "</div>";
                                            echo "<div class='col-xl-6 px-2'>";
                                                if (!empty($row_tenant_table['fileurl'])) { // Ensure fileurl is not empty
                                                    echo "<a href='". '..' . htmlspecialchars($row_tenant_table['fileurl']) . "' download class='btn btn-success table-buttons-download justify-content-center' style='width: 120px;'>Download</a>";
                                                } else {
                                                    echo "<span>No file available</span>";
                                                }
                                            echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No contracts found</td></tr>";
                                }
                                $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-lg-12" id="tableheader">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="physical_contract_searchBar" placeholder="Search..." class="form-control mb-3 " style="max-width: 180px;" />
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="table-responsive"  id="tablelimiter">
                        <table class="table table-striped table-bordered" id="physicalcontractsTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" data-column="full_name" class="sortable-column" data-column="full_name" style="cursor: pointer;">
                                        Tenant
                                        <span class="sort-arrow" data-column="full_name"></span>
                                    </th>
                                    <th scope="col" data-column="datestart" class="sortable-column">
                                        Contract Start
                                        <span id="datestartSortArrow"></span>
                                    </th>
                                    <th scope="col" data-column="expirationdate" class="sortable-column">
                                        Contract Expiry
                                        <span id="expirationdateSortArrow"></span>
                                    </th>
                                    <th scope="col" data-column="fileurl" class="sortable-column">
                                        Contract Picture
                                        <!-- <span id="expirationdateSortArrow"></span> -->
                                    </th>
                                    <th scope="col">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="physicalcontractsTableBody">
                                <?php
                                if ($result_tenant_table->num_rows > 0) {
                                    // Output data of each row
                                    while($row_physical = $result_physical->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th scope='row'>" . $row_physical['id'] . "</th>";
                                        echo "<td>" . htmlspecialchars($row_physical['full_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_physical['datestart']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_physical['expirationdate']) . "</td>";
                                        echo "<td>";
                                            if (!empty($row_physical['fileurl'])) {
                                                $fileUrl = '../asset/physical_contracts/' . htmlspecialchars($row_physical['fileurl']);
                                                echo "<a href='#' data-bs-toggle='modal' data-bs-target='#imagePreviewModal' onclick=\"showImageModal('$fileUrl')\">";
                                                echo "<img src='$fileUrl' alt='Tenant Picture' class='img-fluid' style='width: 150px; height: 150px; object-fit: cover;'>";
                                                echo "</a>";
                                            } else {
                                                echo "<img src='../asset/physical_contracts/default.png' alt='Default Picture' class='img-fluid' style='width: 150px; height: 150px; object-fit: cover;'>";
                                            }
                                        echo "</td>";
                                        echo "<td class='justify-content-center text-center align-middle' style='height: 100%;'>";
                                        echo "<div class='row justify-content-center m-0'>";
                                            echo "<div class='col-xl-6 px-2'>";
                                                // Add a form with a delete button for each record
                                                echo "<form method='POST' action='admin_contract_template.php' class='float-xl-end align-items-center' style='height:100%;'>";
                                                    echo "<input type='hidden' name='physicalcontractid' value='" . $row_physical['id'] . "'>";
                                                    echo "<button type='submit' name='delete_physicalcontract' class='btn btn-danger table-buttons-delete' style='width: 80px;'>Delete</button>";
                                                echo "</form>";
                                            echo "</div>";
                                            echo "<div class='col-xl-6 px-2'>";
                                                if (!empty($row_physical['fileurl'])) { // Ensure fileurl is not empty
                                                    echo "<a href='". '../asset/physical_contracts/' . htmlspecialchars($row_physical['fileurl']) . "' download class='btn btn-success table-buttons-download justify-content-center' style='width: 120px;'>Download</a>";
                                                } else {
                                                    echo "<span>No file available</span>";
                                                }
                                            echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No contracts found</td></tr>";
                                }
                                // $admin->conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- New Contract Modal -->
                <div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #527853;">
                                <h5 class="modal-title text-white" id="newcategoryModalLabel">New Contract</h5>
                                <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                        </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="newContractForm" method="POST" action="admin_contract_template.php">
                                    <div class="mb-3">
                                        <!-- <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required> -->
                                        <input type="hidden" id="signature" name="signature">
                                        <input type="hidden" id="signature2" name="signature2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminuserid" class="form-label">Lessor</label>
                                        <select class="form-select" id="adminuserid" name="adminuserid" required>
                                            <?php
                                                if ($result->num_rows > 0) {
                                                    // Output options for each category
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['middlename']) . " " . htmlspecialchars($row['lastname']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No admin found</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tenantid" class="form-label">Lessee</label>
                                        <select class="form-select" id="tenantid" name="tenantid" required>
                                            <?php
                                                if ($result_tenant->num_rows > 0) {
                                                    // Output options for each category
                                                    while ($row_tenant = $result_tenant->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row_tenant['id']) . "'>" . htmlspecialchars($row_tenant['fname']) . " " . htmlspecialchars($row_tenant['mname']) . " " . htmlspecialchars($row_tenant['lname']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No tenant found</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="tenantaddress-input" class="form-label">Lessee Previous Address</label>
                                        <textarea name="tenantaddress-input" id="tenantaddress-input" class="d-block w-100" required></textarea>
                                    </div> -->
                                    <div class="mb-3">
                                        <label for="apartmentaddress-input" class="form-label">Apartment Address</label>
                                        <textarea name="apartmentaddress-input" id="apartmentaddress-input" class="d-block w-100" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="datestart" class="form-label">Date Start</label>
                                        <input type="date" class="form-control" id="datestart" name="datestart" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="expirationdate" class="form-label">Expiration Date</label>
                                        <input type="date" class="form-control" id="expirationdate" name="expirationdate" value="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deposit" class="form-label">Deposit</label>
                                        <input type="text" class="form-control" id="deposit" name="deposit" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rentprice" class="form-label">Rent Amount</label>
                                        <input type="text" class="form-control" id="rentprice" name="rentprice" required>
                                    </div>
                                    <!-- <div class="mb-3 position-relative d-inline-block" style="max-width: 200px; min-height: 150px; flex: 1;"> -->
                                    <div class="mb-3 position-relative d-inline-block" style="min-height: 150px; flex: 1;">
                                        <!-- <canvas id="signature-pad" width="400" height="200" class="position-absolute" style="border: 1px solid #000; width: 100%; height: 100%;"></canvas> -->
                                        <label for="signature-pad" class="form-label">Lessor Signature</label>
                                        <div class="wrapper">
                                            <canvas id="signature-pad" class="signature-pad"></canvas>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lessorwitness" class="form-label">Lessor Witness</label>
                                        <input type="text" class="form-control" id="lessorwitness" name="lessorwitness" required>
                                    </div>
                                    <div class="mb-3 position-relative d-inline-block" style="min-height: 150px; flex: 1;">
                                        <label for="signature-pad-2" class="form-label">Lessor Witness's Signature</label>
                                        <div class="wrapper">
                                            <canvas id="signature-pad-2" class="signature-pad"></canvas>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button id="clear">Clear</button>
                                        <!-- <button id="save">Save Signature</button> -->
                                    </div>
                                    <button type="submit" name="add_contract" class="btn btn-primary table-buttons-update">Add Contract</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Upload Contract Modal -->
                <div class="modal fade" id="uploadContractModal" tabindex="-1" aria-labelledby="uploadContractModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #527853;">
                                <h5 class="modal-title text-white" id="uploadContractModalLabel">Upload Contract</h5>
                                <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                        </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="uploadContractForm" method="POST" action="admin_contract_template.php" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <!-- <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required> -->
                                        <input type="hidden" id="signature" name="signature">
                                        <input type="hidden" id="signature2" name="signature2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="uploadadminuserid" class="form-label">Lessor</label>
                                        <select class="form-select" id="uploadadminuserid" readonly>
                                            <?php
                                                if ($result_upload->num_rows > 0) {
                                                    // Output options for each category
                                                    while ($row_upload = $result_upload->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row_upload['id']) . "'>" . htmlspecialchars($row_upload['firstname']) . " " . htmlspecialchars($row_upload['middlename']) . " " . htmlspecialchars($row_upload['lastname']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No admin found</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="uploadtenantid" class="form-label">Lessee</label>
                                        <select class="form-select" id="uploadtenantid" name="uploadtenantid" required>
                                            <?php
                                                if ($result_tenant->num_rows > 0) {
                                                    // Output options for each category
                                                    while ($row_tenant_upload = $result_tenant_upload->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row_tenant_upload['id']) . "'>" . htmlspecialchars($row_tenant_upload['fname']) . " " . htmlspecialchars($row_tenant_upload['mname']) . " " . htmlspecialchars($row_tenant_upload['lname']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No tenant found</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="uploaddatestart" class="form-label">Date Start</label>
                                        <input type="date" class="form-control" id="uploaddatestart" name="uploaddatestart" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="uploadexpirationdate" class="form-label">Expiration Date</label>
                                        <input type="date" class="form-control" id="uploadexpirationdate" name="uploadexpirationdate" value="" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="inputGroupFile04" name="contractFile" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                    </div>
                                    <button type="submit" name="upload_contract_file" class="btn btn-primary table-buttons-update">Upload Contract</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Image Preview Modal -->
                <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imagePreviewLabel">Image Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modalImage" src="" alt="Preview" class="w-100 img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('new_contract').addEventListener('click', function () {
                        var newContractModal = new bootstrap.Modal(document.getElementById('newContractModal'), {
                            keyboard: false
                        });
                        newContractModal.show();
                    });
                    document.getElementById('upload_contract').addEventListener('click', function () {
                        var uploadContractModal = new bootstrap.Modal(document.getElementById('uploadContractModal'), {
                            keyboard: false
                        });
                        uploadContractModal.show();
                    });
                </script>

                <!-- Include jQuery library -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    // $(document).ready(function() {
                    //     $('#searchBar').on('input', function() {
                    //         var searchQuery = $(this).val(); // Get the value of the search input

                    //         $.ajax({
                    //             url: 'search/search_contract.php', // Path to your search PHP script
                    //             type: 'POST',
                    //             data: { query: searchQuery }, // Send the search query via POST
                    //             success: function(response) {
                    //                 $('tbody#contractTableBody').html(response); // Replace the table body with the new data
                    //             },
                    //             error: function() {
                    //                 alert("Error in search!"); // Show an error message in case of failure
                    //             }
                    //         });
                    //     });
                    // });

                    // $(document).ready(function() {
                    //     let currentSortColumn = 'id';
                    //     let currentSortOrder = 'ASC';

                    //     function fetchUsers(page = 1, query = '', sortColumn = currentSortColumn, sortOrder = currentSortOrder) {
                    //         $.ajax({
                    //             url: 'search/search_contract.php',
                    //             type: 'POST',
                    //             data: { 
                    //                 page: page, 
                    //                 query: query, 
                    //                 sort_column: sortColumn, 
                    //                 sort_order: sortOrder 
                    //             },
                    //             success: function(response) {
                    //                 $('tbody#contractTableBody').html(response); // Update table body with data
                    //             }
                    //         });
                    //     }

                    //     // Initial fetch on page load
                    //     fetchUsers();

                    //     // Search bar event
                    //     $('#searchBar').on('input', function() {
                    //         var searchQuery = $(this).val();
                    //         fetchUsers(1, searchQuery);
                    //     });

                    //     // Pagination button event
                    //     $(document).on('click', '.pagination-btn', function() {
                    //         var page = $(this).data('page');
                    //         var searchQuery = $('#searchBar').val();
                    //         fetchUsers(page, searchQuery);
                    //     });

                    //     // Column header sorting event
                    //     $('.sortable-column').on('click', function() {
                    //         let column = $(this).data('column');
                    //         currentSortOrder = (currentSortColumn === column && currentSortOrder === 'ASC') ? 'DESC' : 'ASC';
                    //         currentSortColumn = column;

                    //         // Toggle the arrow indicator directly in the column header
                    //         $('.sortable-column').each(function() {
                    //             // Check if the column header contains an arrow (↑ or ↓) and remove it
                    //             let text = $(this).text().trim();
                    //             if (text.endsWith('↑') || text.endsWith('↓')) {
                    //                 $(this).text(text.slice(0, -2));  // Remove the last two characters (arrow)
                    //             }
                    //         });

                    //         // Add the appropriate arrow to the clicked column header
                    //         let arrow = currentSortOrder === 'ASC' ? ' ↑' : ' ↓';
                    //         $(this).append(arrow);  // Append the arrow directly to the text

                    //         let searchQuery = $('#searchBar').val();
                    //         fetchUsers(1, searchQuery, currentSortColumn, currentSortOrder);
                    //     });
                    // });



                    $(document).ready(function () {
                        // Common function to fetch data for any table
                        function fetchTableData(tableId, url, page = 1, query = '', sortColumn = 'id', sortOrder = 'ASC') {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    page: page,
                                    query: query,
                                    sort_column: sortColumn,
                                    sort_order: sortOrder,
                                },
                                success: function (response) {
                                    $(`#${tableId} tbody`).html(response); // Update the corresponding table body
                                },
                            });
                        }

                        // Table 1: Contracts Table
                        let table1SortColumn = 'id';
                        let table1SortOrder = 'ASC';
                        $('#searchBar').on('input', function () {
                            const query = $(this).val();
                            fetchTableData('contractTable', 'search/search_contract.php', 1, query, table1SortColumn, table1SortOrder);
                        });
                        $(document).on('click', '#contractTable .pagination-btn', function () {
                            const page = $(this).data('page');
                            const query = $('#searchBar').val();
                            fetchTableData('contractTable', 'search/search_contract.php', page, query, table1SortColumn, table1SortOrder);
                        });
                        $('#contractTable .sortable-column').on('click', function () {
                            const column = $(this).data('column');
                            table1SortOrder = (table1SortColumn === column && table1SortOrder === 'ASC') ? 'DESC' : 'ASC';
                            table1SortColumn = column;
                            const query = $('#searchBar').val();
                            fetchTableData('contractTable', 'search/search_contract.php', 1, query, table1SortColumn, table1SortOrder);
                        });

                        // Table 2: Physical Contracts Table
                        let table2SortColumn = 'id';
                        let table2SortOrder = 'ASC';
                        $('#physical_contract_searchBar').on('input', function () {
                            const query = $(this).val();
                            fetchTableData('physicalcontractsTable', 'search/search_physical_contract.php', 1, query, table2SortColumn, table2SortOrder);
                        });
                        $(document).on('click', '#physicalcontractsTable .pagination-btn', function () {
                            const page = $(this).data('page');
                            const query = $('#physical_contract_searchBar').val();
                            fetchTableData('physicalcontractsTable', 'search/search_physical_contract.php', page, query, table2SortColumn, table2SortOrder);
                        });
                        $('#physicalcontractsTable .sortable-column').on('click', function () {
                            const column = $(this).data('column');
                            table2SortOrder = (table2SortColumn === column && table2SortOrder === 'ASC') ? 'DESC' : 'ASC';
                            table2SortColumn = column;
                            const query = $('#physical_contract_searchBar').val();
                            fetchTableData('physicalcontractsTable', 'search/search_physical_contract.php', 1, query, table2SortColumn, table2SortOrder);
                        });
                    });
                </script>

                <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                <script>
                    // Initialize both signature pads
                    const canvas1 = document.getElementById("signature-pad");
                    const signaturePad1 = new SignaturePad(canvas1);
                    
                    const canvas2 = document.getElementById("signature-pad-2");
                    const signaturePad2 = new SignaturePad(canvas2);

                    function resizeCanvas(canvas, signaturePad) {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        canvas.getContext("2d").scale(ratio, ratio);
                        signaturePad.clear(); // Clear the canvas after resizing to prevent scaling artifacts
                    }

                    // Resize both canvases when the modal is shown and on window resize
                    const newContractModal = document.getElementById("newContractModal");
                    newContractModal.addEventListener("shown.bs.modal", () => {
                        resizeCanvas(canvas1, signaturePad1);
                        resizeCanvas(canvas2, signaturePad2);
                    });

                    window.addEventListener("resize", () => {
                        resizeCanvas(canvas1, signaturePad1);
                        resizeCanvas(canvas2, signaturePad2);
                    });

                    // Function to center the signature in the canvas
                    function centerSignature(canvas, signaturePad) {
                        const context = canvas.getContext("2d");
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        let minX = canvas.width, minY = canvas.height, maxX = 0, maxY = 0;

                        // Loop over every pixel to find the bounding box
                        for (let y = 0; y < canvas.height; y++) {
                            for (let x = 0; x < canvas.width; x++) {
                                const index = (y * canvas.width + x) * 4;
                                const alpha = imageData.data[index + 3];
                                if (alpha > 0) {
                                    if (x < minX) minX = x;
                                    if (y < minY) minY = y;
                                    if (x > maxX) maxX = x;
                                    if (y > maxY) maxY = y;
                                }
                            }
                        }

                        const width = maxX - minX;
                        const height = maxY - minY;

                        const centeredCanvas = document.createElement("canvas");
                        centeredCanvas.width = canvas.width;
                        centeredCanvas.height = canvas.height;
                        const centeredContext = centeredCanvas.getContext("2d");

                        centeredContext.drawImage(
                            canvas,
                            minX, minY, width, height,
                            (canvas.width - width) / 2, (canvas.height - height) / 2, width, height
                        );

                        return centeredCanvas.toDataURL("image/png");
                    }

                    // Clear both signature pads
                    document.getElementById("clear").addEventListener("click", () => {
                        signaturePad1.clear();
                        signaturePad2.clear();
                    });

                    // Handle form submission
                    document.getElementById("newContractForm").addEventListener("submit", (event) => {
                        if (!signaturePad1.isEmpty() && !signaturePad2.isEmpty()) {
                            document.getElementById("signature").value = centerSignature(canvas1, signaturePad1);
                            document.getElementById("signature2").value = centerSignature(canvas2, signaturePad2);
                        } else {
                            event.preventDefault();
                            alert("Please provide both signatures.");
                        }
                    });
                </script>

                <!-- Include jQuery library -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function fetchUnreadMessages() {
                        $.ajax({
                        url: '../fetch_unread_count.php',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data && data.unread_messages !== undefined) {
                            $('#unseenChatLabel').text(data.unread_messages);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error fetching unread messages:", textStatus, errorThrown);
                        }
                        });
                    }

                    // Run once on page load
                    fetchUnreadMessages();

                    // Poll every 3 seconds
                    setInterval(fetchUnreadMessages, 3000);
                </script>
                <script>
                    // Function to create and set the favicon
                    function setFavicon(iconURL) {
                    // Create a new link element
                    const favicon = document.createElement('link');
                    favicon.rel = 'icon';
                    favicon.type = 'image/x-icon';
                    favicon.href = iconURL;

                    // Remove any existing favicons
                    const existingIcons = document.querySelectorAll('link[rel="icon"]');
                    existingIcons.forEach(icon => icon.remove());

                    // Append the new favicon to the head
                    document.head.appendChild(favicon);
                    }

                    // Example usage: set the favicon on page load
                    document.addEventListener('DOMContentLoaded', () => {
                    setFavicon('../asset/Renttrack pro no word.png'); // Change to your favicon path
                    });
                </script>
                <script>
                    function showImageModal(imageUrl) {
                        document.getElementById('modalImage').src = imageUrl;
                    }
                </script>
            </div>
        </div>
    </div>
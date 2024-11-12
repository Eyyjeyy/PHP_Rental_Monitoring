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
        $username = trim(htmlspecialchars($_POST['username']));
        $signatureData = $_POST['signature'];

        // Validate username to allow only letters, numbers, underscores, and apostrophes
        if (!preg_match("/^[a-zA-Z0-9_' ]+$/", $username)) {
            // Invalid username
            $_SESSION['error_message'] = "Input can only contain letters, numbers, underscores, and spaces.";
            header("Location: admin_contract_template.php?error=invalid_username");
            exit();
        }

        $added = $admin->addContract($username, $signatureData);
        if($added) {
            // Contract added successfully, you can display a success message here if needed
            // echo "Contract added successfully.";
            header("Location: admin_contract_template.php?contract_added=1");
            exit();
        } else {
            // Error occurred while adding contract, display an error message or handle as needed
            // echo "Error occurred while adding contract.";

            $_SESSION['error_message'] = "Addition Failed due to an error";
            header("Location: admin_contract_template.php?error=add");
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

    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "";
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
                            <button class="btn btn-primary float-end table-buttons-update" id="new_contract"><i class="fa fa-plus"></i> New Contract</button>
                        </div>
                    </div>
                </div>

                <!-- New Contract Modal -->
                <div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #527853;">
                                <h5 class="modal-title text-white" id="newcategoryModalLabel">New Category</h5>
                                <button type="button" class="btn-svg p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg w-100" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                        </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="newContractForm" method="POST" action="admin_contract_template.php">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                        <input type="hidden" id="signature" name="signature">
                                    </div>
                                    <!-- <div class="mb-3 position-relative d-inline-block" style="max-width: 200px; min-height: 150px; flex: 1;"> -->
                                    <div class="mb-3 position-relative d-inline-block" style="min-height: 150px; flex: 1;">
                                        <!-- <canvas id="signature-pad" width="400" height="200" class="position-absolute" style="border: 1px solid #000; width: 100%; height: 100%;"></canvas> -->
                                        <div class="wrapper">
                                            <canvas id="signature-pad" class="signature-pad"></canvas>
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

                <script>
                    document.getElementById('new_contract').addEventListener('click', function () {
                        var newContractModal = new bootstrap.Modal(document.getElementById('newContractModal'), {
                            keyboard: false
                        });
                        newContractModal.show();
                    });
                </script>

                <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                <script>
                    const canvas = document.getElementById("signature-pad");
                    const signaturePad = new SignaturePad(canvas);

                    // Adjust the canvas size on window resize to maintain accurate drawing positions
                    function resizeCanvas() {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        canvas.getContext("2d").scale(ratio, ratio); // Scale context to ensure proper drawing
                        signaturePad.clear(); // Clear the canvas after resizing to prevent scaling artifacts
                    }

                    // Trigger resize when the modal is fully shown, without this, the signature input won't appear ----
                    const newContractModal = document.getElementById("newContractModal");
                    newContractModal.addEventListener("shown.bs.modal", () => {
                        resizeCanvas(); // Resize canvas when modal is shown
                    });
                    // ---------------------------------------------------------------

                    // Initialize the canvas size
                    resizeCanvas();
                    window.addEventListener("resize", resizeCanvas);

                    document.getElementById("clear").addEventListener("click", () => {
                        signaturePad.clear();
                    });

                    document.getElementById("newContractForm").addEventListener("submit", () => {
                        if (!signaturePad.isEmpty()) {
                            // Step 1: Get the bounding box of the drawn area
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

                            // Calculate width and height of the bounding box
                            const width = maxX - minX;
                            const height = maxY - minY;

                            // Step 2: Create a new canvas for the centered image
                            const centeredCanvas = document.createElement("canvas");
                            centeredCanvas.width = canvas.width;
                            centeredCanvas.height = canvas.height;
                            const centeredContext = centeredCanvas.getContext("2d");

                            // Step 3: Draw the cropped image onto the new canvas, centered
                            centeredContext.drawImage(
                                canvas,
                                minX, minY, width, height, // Source rectangle
                                (canvas.width - width) / 2, (canvas.height - height) / 2, width, height // Destination rectangle
                            );

                            // Step 4: Get the centered image as a data URL
                            const dataURL = centeredCanvas.toDataURL("image/png");
                            document.getElementById("signature").value = dataURL;
                        } else {
                            event.preventDefault();
                            alert("Please provide a signature.");
                        }
                    });
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
            </div>
        </div>
    </div>
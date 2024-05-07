<?php
require_once "../controllers/session_Config.php";
?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addAdmin_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$adminID = $_SESSION['adminID'];
$adminData = getAdminDataById($connect, $adminID);
$companyData = get_Settings($connect);

?>

<?php require_once "../views/style.config.php"; ?>
<?php require_once "../views/header.php"; ?>

<style>
    .editClient .edituserprofile img {
        width: 100%;
    }

    .card {
        border: none;
        background-color: var(--latto);
    }

    .upload {
        width: 200px;
        position: relative;
        margin: auto;
        text-align: center;
    }

    .upload img {
        /* border-radius: 50%; */
        border: 8px solid var(--latto);
        box-shadow: 2px 3px 5px var(--latto);
        width: 150px;
        height: 150px;
    }

    .upload .rightRound {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: var(--blue);
        width: 32px;
        height: 32px;
        line-height: 33px;
        text-align: center;
        border-top-left-radius: 50%;
        overflow: hidden;
        cursor: pointer;
    }

    .upload .rightRound #submit {
        width: 100%;
        height: 100%;
    }

    .upload .leftRound {
        position: absolute;
        bottom: 0;
        left: 0;
        background-color: var(--red);
        width: 32px;
        height: 32px;
        line-height: 33px;
        text-align: center;
        border-top-left-radius: 50%;
        overflow: hidden;
        cursor: pointer;
    }

    .upload #cancel {
        display: none;
    }

    .upload #confirm {
        display: none;
    }

    .upload .fa {
        color: var(--color);
    }

    .upload input {
        position: absolute;
        transform: scale(2);
        opacity: 0;
    }

    .upload input::-webkit-file-upload-button,
    .upload input[type=submit] {
        cursor: pointer;
    }
</style>

<!-- SIDEBAR -->
<?php require_once "side_nav.php"; ?>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <!-- TOP-NAVBAR -->
    <?php require_once "top_nav.php"; ?>
    <!-- TOP-NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Hi, <?= $_SESSION['Username']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="choosePayment.php">Company Settings</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div id="loader">Loading...</div>
        <div class="main-content">
            <div class="content">

                <div class="page editClient">
                    <h4>Edit Logo</h4>
                    <?php if (isset($companyData)) : ?>
                        <div class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <form action="" enctype="multipart/form-data" id="editForm" method="post" onsubmit="submitForm(event)">
                                        <input type="hidden" name="id" id="id" value="<?= $companyData[0]["SettingID"]; ?>">
                                        <div class="upload">
                                            <img id="editprofilePicture" src="../img/<?= $companyData[0]["LogoURL"]; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="200px">

                                            <div class="rightRound" id="upload">
                                                <input type="file" name="fileImage" id="fileImage" accept=".jpg, .jpeg, .png">
                                                <i class="fa fa-camera camera"></i>
                                            </div>

                                            <div class="leftRound" id="cancel">
                                                <!-- <i class="fa fa-camera"></i> -->
                                                <i class="fa fa-times"></i>
                                            </div>

                                            <div class="rightRound" id="confirm">
                                                <input type="submit" id="submit" name="submit" value="">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>



                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name">Business Name</label>
                                                <input type="text" class="form-control" name="name" id="name" value="<?= $companyData[0]["CompanyName"] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email">Business Email</label>
                                                <input type="email" class="form-control" name="email" id="email" value="<?= $companyData[0]["Email"] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="website">Website</label>
                                                <input type="text" class="form-control" name="website" id="website" value="<?= $companyData[0]["Website"] ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" name="address" id="address" value="<?= $companyData[0]["Address"] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" name="city" id="city" value="<?= $companyData[0]["City"] ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="zipcode">ZipCode</label>
                                                <input type="number" class="form-control" name="zipcode" id="zipcode" value="<?= $companyData[0]["Zipcode"] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="number">Phone Number</label>
                                                <input type="tel" class="form-control" name="number" id="number" value="<?= $companyData[0]["PhoneNumber"] ?>">
                                            </div>
                                        </div>
                                        <p id="editError"></p>


                                        <div class="row mt-3">
                                            <button id="updateProfileBtn" class="btn btn-success col-md-4">Save Changes</button>
                                        </div>

                                    <?php else : ?>
                                        <div class="alert alert-warning" role="alert">
                                            No client data found for the provided ClientID.
                                            <?php header("Location: viewClient.php"); ?>
                                        </div>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>



            </div>


            <?php require_once "../views/footer.php"; ?>


            <script>
                var updateProfileBtn = document.getElementById("updateProfileBtn");

                var initialCompanyName = <?= json_encode($companyData[0]["CompanyName"]); ?>;
                var initialEmail = <?= json_encode($companyData[0]["Email"]); ?>;
                var initialWebsite = <?= json_encode($companyData[0]["Website"]); ?>;
                var initialAddress = <?= json_encode($companyData[0]["Address"]); ?>;
                var initialCity = <?= json_encode($companyData[0]["City"]); ?>;
                var initialZipcode = <?= json_encode($companyData[0]["Zipcode"]); ?>;
                var initialNumber = <?= json_encode($companyData[0]["PhoneNumber"]); ?>;




                updateProfileBtn.addEventListener("click", function() {
                    var id = document.getElementById("id").value;
                    var nameInput = document.getElementById("name").value;
                    var emailInput = document.getElementById("email").value;
                    var websiteInput = document.getElementById("website").value;
                    var addressInput = document.getElementById("address").value;
                    var cityInput = document.getElementById("city").value;
                    var zipcodeInput = document.getElementById("zipcode").value;
                    var numberInput = document.getElementById("number").value;









                    // Compare input values with initial values
                    if (
                        nameInput != initialCompanyName ||
                        emailInput != initialEmail ||
                        websiteInput != initialWebsite ||
                        addressInput != initialAddress ||
                        cityInput != initialCity ||
                        zipcodeInput != initialZipcode ||
                        numberInput != initialNumber
                    ) {
                        // At least one input value has changed




                        if (nameInput.trim() === "") {
                            displayMessage("editError", "Company name cannot be empty", true);
                            return;
                        } else {
                            if (!/^[a-zA-Z0-9 ]+$/.test(nameInput)) {
                                displayMessage("editError", "Only letters and numbers allowed in name", true);
                                return;
                            }
                        }

                        if (emailInput.trim() === "") {
                            displayMessage("editError", "Company Email cannot be empty", true);
                            return;
                        } else {
                            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput)) {
                                displayMessage("editError", "Invalid Company email address", true);
                                return;
                            }
                        }

                        if (websiteInput.trim() === "") {
                            displayMessage("editError", "Company Website cannot be empty", true);
                            return;
                        } else {
                            // Regular expression for validating website input
                            const websiteRegex = /^[a-zA-Z0-9\-./]+$/;

                            // Check if the input matches the regular expression
                            if (!websiteRegex.test(websiteInput)) {
                                displayMessage("editError", "Only letters, numbers, dots, hyphens, and slashes allowed in website", true);
                                return;
                            }
                        }



                        if (numberInput.trim() === "") {
                            displayMessage("editError", "Company Number cannot be empty", true);
                            return;
                        } else {
                            // Check if the number input has less than 10 digits
                            if (numberInput.replace(/[^\d]/g, '').length < 10) {
                                displayMessage("editError", "Company Number must contain at least 10 digits", true);
                                return;
                            }

                            // Check if the number input contains any characters other than digits, brackets, or dashes
                            if (!/^[\d() -]+$/.test(numberInput)) {
                                displayMessage("editError", "Company Number must contain only digits, brackets, or dashes", true);
                                return;
                            }
                        }




                        if (zipcodeInput.trim() !== "" && !isValidZipCode(zipcodeInput.trim())) {
                            displayMessage("editError", "Please enter a valid zip code.", true);
                            return; // Stop further execution
                        }


                        document.querySelector("#loader").style.display = 'block';


                        var formData = new FormData();
                        formData.append("id", id);
                        formData.append("nameInput", nameInput);
                        formData.append("emailInput", emailInput);
                        formData.append("websiteInput", websiteInput);
                        formData.append("addressInput", addressInput);
                        formData.append("cityInput", cityInput);
                        formData.append("zipcodeInput", zipcodeInput);
                        formData.append("numberInput", numberInput);

                        // Fetch API to send data to updateUserProfilePic_mod.php
                        fetch("../controllers/updateCompanySettings_contr.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                displayMessage("editError", "Updated Details Successfully", false);
                                document.querySelector("#loader").style.display = 'none';
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            })
                            .catch(error => {
                                console.error("Error:", error);
                            });


                    } else {
                        // No input values have changed
                        displayMessage("editError", "No changes made", true);
                        return;
                    }
                });


                // Function to validate zip code format
                function isValidZipCode(zipcode) {
                    // Regular expression to validate zip code format (example: 12345 or 12345-6789)
                    var zipCodePattern = /^\d{5}(?:-\d{4})?$/;
                    return zipCodePattern.test(zipcode);
                }


                document.getElementById("fileImage").onchange = function(event) {
                    var editProfilePicture = document.getElementById("editprofilePicture");

                    // Set new image source with transition
                    editProfilePicture.style.opacity = "0";
                    setTimeout(function() {
                        editProfilePicture.src = URL.createObjectURL(event.target.files[0]);
                        editProfilePicture.style.opacity = "1";
                    }, 300);

                    // Show buttons and hide upload button
                    document.getElementById("cancel").style.display = "block";
                    document.getElementById("confirm").style.display = "block";
                    document.getElementById("upload").style.display = "none";
                };

                var userImage = document.getElementById("editprofilePicture").src;
                document.getElementById("cancel").onclick = function() {
                    var editProfilePicture = document.getElementById("editprofilePicture");

                    // Set back to the original image with transition
                    editProfilePicture.style.opacity = "0";
                    setTimeout(function() {
                        editProfilePicture.src = userImage;
                        editProfilePicture.style.opacity = "1";
                    }, 300);

                    // Hide buttons and show the upload button
                    document.getElementById("cancel").style.display = "none";
                    document.getElementById("confirm").style.display = "none";
                    document.getElementById("upload").style.display = "block";
                };

                // Add an event listener for form submission
                document.getElementById("editForm").addEventListener("submit", function(event) {
                    event.preventDefault();


                    // Your existing code for image transition
                    var editProfilePicture = document.getElementById("editprofilePicture");
                    var fileInput = document.getElementById("fileImage");

                    var formData = new FormData();
                    formData.append("id", document.getElementById("id").value);
                    formData.append("fileImage", fileInput.files[0]);

                    // Fetch API to send data to updateUserProfilePic_mod.php
                    fetch("../controllers/updateCompanyLogo_contr.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            displayMessage("editError", "Please enter amount to be paid before proceeding.", false);
                            document.getElementById("cancel").style.display = "none";
                            document.getElementById("confirm").style.display = "none";
                            document.getElementById("upload").style.display = "block";
                            localStorage.setItem('updateClientProfileToast', 'true');
                            location.reload();
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                });
            </script>
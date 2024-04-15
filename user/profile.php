<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/viewSingleUser_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
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
                <h1>Hi, <?= $_SESSION['FirstName']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="choosePayment.php">Profile</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <!-- <div id="loader">Loading...</div> -->
        <div class="main-content">
            <div class="content">

                <div class="page editClient">
                    <h4>Edit User Details</h4>
                    <?php if (isset($clientData)) : ?>
                        <div class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <form action="" enctype="multipart/form-data" id="editForm" method="post" onsubmit="submitForm(event)">
                                        <input type="hidden" name="id" id="id" value="<?= $clientData['ClientID']; ?>">
                                        <div class="upload">
                                            <img id="editprofilePicture" src="../img/<?= $clientData['ProfilePictureURL']; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="200px">

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
                                                <label for="firstname">firstname</label>
                                                <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $clientData['FirstName'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lastname">lastname</label>
                                                <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $clientData['LastName'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="PrimaryEmail">Email Address</label>
                                                <input type="email" class="form-control" name="PrimaryEmail" id="PrimaryEmail" value="<?= $clientData['PrimaryEmail'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="SecondaryEmail">Secondary Email</label>
                                                <input type="email" class="form-control" name="SecondaryEmail" id="SecondaryEmail" value="<?= $clientData['SecondaryEmail'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="primaryNumber">Phone Number</label>
                                                <input type="tel" class="form-control" name="primaryNumber" id="primaryNumber" value="<?= $clientData['PrimaryNumber'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="secondaryNumber">secondary Number</label>
                                                <input type="tel" class="col-md-6 form-control" name="secondaryNumber" id="secondaryNumber" value="<?= $clientData['SecondaryNumber'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="Address">Address</label>
                                                <input type="text" class="form-control" name="Address" id="Address" value="<?= $clientData['Address'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="City">City</label>
                                                <input type="text" class="form-control" name="City" id="City" value="<?= $clientData['City'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="Country">Country</label>
                                                <input type="text" class="form-control" name="Country" id="Country" value="<?= $clientData['Country'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="zipcode">zipcode</label>
                                                <input type="number" class="form-control" name="zipcode" id="zipcode" value="<?= $clientData['Zipcode'] ?>">
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

                var initialFirstName = <?= json_encode($clientData['FirstName']); ?>;
                var initialLastName = <?= json_encode($clientData['LastName']); ?>;
                var initialPrimaryEmail = <?= json_encode($clientData['PrimaryEmail']); ?>;
                var initialSecondaryEmail = <?= json_encode($clientData['SecondaryEmail']); ?>;
                var initialPrimaryNumber = <?= json_encode($clientData['PrimaryNumber']); ?>;
                var initialSecondaryNumber = <?= json_encode($clientData['SecondaryNumber']); ?>;
                var initialAddress = <?= json_encode($clientData['Address']); ?>;
                var initialCity = <?= json_encode($clientData['City']); ?>;
                var initialCountry = <?= json_encode($clientData['Country']); ?>;
                var initialZipcode = <?= json_encode($clientData['Zipcode']); ?>;



                updateProfileBtn.addEventListener("click", function() {
                    var firstnameInput = document.getElementById("firstname").value;
                    var lastnameInput = document.getElementById("lastname").value;
                    var PrimaryEmailInput = document.getElementById("PrimaryEmail").value;
                    var SecondaryEmailInput = document.getElementById("SecondaryEmail").value;
                    var primaryNumberInput = document.getElementById("primaryNumber").value;
                    var secondaryNumberInput = document.getElementById("secondaryNumber").value;
                    var AddressInput = document.getElementById("Address").value;
                    var CityInput = document.getElementById("City").value;
                    var CountryInput = document.getElementById("Country").value;
                    var zipcodeInput = document.getElementById("zipcode").value;








                    // Compare input values with initial values
                    if (
                        firstnameInput != initialFirstName ||
                        lastnameInput != initialLastName ||
                        PrimaryEmailInput != initialPrimaryEmail ||
                        SecondaryEmailInput != initialSecondaryEmail ||
                        primaryNumberInput != initialPrimaryNumber ||
                        secondaryNumberInput != initialSecondaryNumber ||
                        AddressInput != initialAddress ||
                        CityInput != initialCity ||
                        CountryInput != initialCountry ||
                        zipcodeInput != initialZipcode
                    ) {
                        // At least one input value has changed




                        if (firstnameInput.trim() === "") {
                            displayMessage("editError", "First name cannot be empty", true);
                            return;
                        } else {
                            if (!/^[a-zA-Z0-9 ]+$/.test(firstnameInput)) {
                                displayMessage("editError", "Only letters and numbers allowed", true);
                                return;
                            }
                        }

                        if (lastnameInput.trim() === "") {
                            displayMessage("editError", "Last name cannot be empty", true);
                            return;
                        } else {
                            if (!/^[a-zA-Z0-9 ]+$/.test(lastnameInput)) {
                                displayMessage("editError", "Only letters and numbers allowed", true);
                                return;
                            }
                        }


                        if (PrimaryEmailInput.trim() === "") {
                            displayMessage("editError", "Primary Email cannot be empty", true);
                            return;
                        } else {
                            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(PrimaryEmailInput)) {
                                displayMessage("editError", "Invalid Primary email address", true);
                                return;
                            }
                        }


                        if (SecondaryEmailInput.trim() === "") {
                            SecondaryEmailInput = '';
                        } else {
                            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(SecondaryEmailInput)) {
                                displayMessage("editError", "Invalid Secondary email address", true);
                                return;
                            }
                        }

                        if (primaryNumberInput.trim() === "") {
                            displayMessage("editError", "Primary Number cannot be empty", true);
                            return;
                        } else {
                            if (!/^\d{10}$/.test(primaryNumberInput)) {
                                displayMessage("editError", "Primary Number must contain exactly 10 digits (only numbers allowed)", true);
                                return;
                            }
                        }

                        if (secondaryNumberInput.trim() !== "") {
                            if (!/^\d{10}$/.test(secondaryNumberInput)) {
                                displayMessage("editError", "Invalid Secondary phone number (must contain exactly 10 digits, only numbers allowed)", true);
                                return;
                            }
                        }



                        if (zipcodeInput.trim() !== "" && !isValidZipCode(zipcodeInput.trim())) {
                            displayMessage("editError", "Please enter a valid zip code.", true);
                            return; // Stop further execution
                        }








                        document.querySelector("#loader").style.display = 'block';


                        var formData = new FormData();
                        formData.append("firstnameInput", firstnameInput);
                        formData.append("lastnameInput", lastnameInput);
                        formData.append("PrimaryEmailInput", PrimaryEmailInput);
                        formData.append("SecondaryEmailInput", SecondaryEmailInput);
                        formData.append("primaryNumberInput", primaryNumberInput);
                        formData.append("secondaryNumberInput", secondaryNumberInput);
                        formData.append("AddressInput", AddressInput);
                        formData.append("CityInput", CityInput);
                        formData.append("CountryInput", CountryInput);
                        formData.append("zipcodeInput", zipcodeInput);

                        // Fetch API to send data to updateUserProfilePic_mod.php
                        fetch("../controllers/updateUserProfile_contr.php", {
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
                    fetch("../modals/updateUserProfilePic_mod.php", {
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
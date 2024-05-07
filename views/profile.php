<?php
require_once "../controllers/session_Config.php";
?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addAdmin_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$adminID = $_SESSION['adminID'];
$adminData = getAdminDataById($connect, $adminID);
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
                <h1>Hi, <?= $_SESSION['Username']; ?></h1>
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

        </div>

        <!-- content-container -->
        <div id="loader">Loading...</div>
        <div class="main-content">
            <div class="content">

                <div class="page editClient">
                    <h4>Edit Admin Details</h4>
                    <?php if (isset($adminData)) : ?>
                        <div class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <form action="" enctype="multipart/form-data" id="editForm" method="post" onsubmit="submitForm(event)">
                                        <input type="hidden" name="id" id="id" value="<?= $adminData['AdminID']; ?>">
                                        <div class="upload">
                                            <img id="editprofilePicture" src="../img/<?= $adminData['ProfilePictureURL']; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="200px">

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
                                                <label for="firstname">Username</label>
                                                <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $adminData['Username'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lastname">first & lastname</label>
                                                <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $adminData['FullName'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="PrimaryEmail">Email Address</label>
                                                <input type="email" class="form-control" name="PrimaryEmail" id="PrimaryEmail" value="<?= $adminData['Email'] ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="primaryNumber">Phone Number</label>
                                                <input type="tel" class="form-control" name="primaryNumber" id="primaryNumber" value="<?= $adminData['Phone'] ?>">
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

                var initialFirstName = <?= json_encode($adminData['Username']); ?>;
                var initialLastName = <?= json_encode($adminData['FullName']); ?>;
                var initialPrimaryEmail = <?= json_encode($adminData['Email']); ?>;
                var initialPrimaryNumber = <?= json_encode($adminData['Phone']); ?>;




                updateProfileBtn.addEventListener("click", function() {
                    var firstnameInput = document.getElementById("firstname").value;
                    var lastnameInput = document.getElementById("lastname").value;
                    var PrimaryEmailInput = document.getElementById("PrimaryEmail").value;
                    var primaryNumberInput = document.getElementById("primaryNumber").value;









                    // Compare input values with initial values
                    if (
                        firstnameInput != initialFirstName ||
                        lastnameInput != initialLastName ||
                        PrimaryEmailInput != initialPrimaryEmail ||
                        primaryNumberInput != initialPrimaryNumber
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




                        if (primaryNumberInput.trim() === "") {
                            displayMessage("editError", "Primary Number cannot be empty", true);
                            return;
                        } else {
                            if (!/^\d{10}$/.test(primaryNumberInput)) {
                                displayMessage("editError", "Primary Number must contain exactly 10 digits (only numbers allowed)", true);
                                return;
                            }
                        }



                        document.querySelector("#loader").style.display = 'block';


                        var formData = new FormData();
                        formData.append("Username", firstnameInput);
                        formData.append("fullName", lastnameInput);
                        formData.append("email", PrimaryEmailInput);
                        formData.append("phone", primaryNumberInput);

                        // Fetch API to send data to updateUserProfilePic_mod.php
                        fetch("../controllers/updateAdminProfile_contr.php", {
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
                    fetch("../controllers/updateAdminProfilePic_contr.php", {
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
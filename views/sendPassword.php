<?php
session_start();
require_once  '../database/pdo.php';
require_once  '../modals/addAdmin_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/login_mod.php';
require_once "header.php";
require_once "style.config.php";

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<style>
    body {
        background-color: var(--grey);
    }

    .login-container {

        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-box {
        position: relative;
        background-color: var(--light);
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .companyName {
        color: var(--blue);
        font-weight: 700;
    }

    h5 {
        color: var(--dark-grey);
    }

    .detailsDiv {
        width: 100%;
    }


    .radiodiv {
        display: flex;
        flex-direction: row;
    }
</style>


<div id="loader">Loading...</div>
<div class="login-container">
    <div class="login-box col-md-4">
        <?php if (isset($_SESSION["clientEmailFound"]) || isset($_SESSION["adminEmailFound"])) : ?>
            <div class="text-center mb-5">
                <h4 class="companyName mt-3">Reset Password</h4>
            </div>




            <div class="div">

                <h5 class="mb-3">Choose where do you want to recieve the reset password, </h5>


                <?php
                if (isset($_SESSION["clientEmailFound"])) {
                    $clientID = $_SESSION["clientEmailFound"];
                    $clientData = getClientDataById($connect, $clientID);
                } elseif (isset($_SESSION["adminEmailFound"])) {
                    $adminID = $_SESSION["adminEmailFound"];
                    $adminData = getAdminDataById($connect, $adminID);
                }

                ?>

                <?php if (isset($clientData)) : ?>

                    <div class="detailsDiv m-3">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body">


                                    <?php if (isset($clientData["PrimaryEmail"])) : ?>
                                        <div class="mt-5 radiodiv">
                                            <input type="radio" id="primaryEmailRadio" name="fav_language" value="<?= $clientData["PrimaryEmail"]; ?>">
                                            <label class="form-check-label" for="primaryEmailRadio">
                                                <?= maskEmail($clientData["PrimaryEmail"]); ?>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="form-check mt-5"></div>
                                    <?php endif; ?>



                                    <?php if (isset($clientData["PrimaryNumber"])) : ?>
                                        <div class="radiodiv">
                                            <input type="radio" id="primaryNumberRadio" name="fav_language" value="<?= $clientData["PrimaryNumber"]; ?>">
                                            <label class="form-check-label" for="primaryNumberRadio">
                                                <?= maskPhoneNumber($clientData["PrimaryNumber"]); ?>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="form-check mt-5"></div>
                                    <?php endif; ?>


                                    <?php if (isset($clientData["SecondaryEmail"]) && !empty($clientData["SecondaryEmail"])) : ?>
                                        <div class="mt-5 radiodiv">
                                            <input type="radio" value="<?= $clientData["SecondaryEmail"]; ?>" id="SecondaryEmailRadio" name="fav_language">
                                            <label class="form-check-label" for="SecondaryEmailRadio">
                                                <?= maskEmail($clientData["SecondaryEmail"]); ?>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="mt-5">
                                            <input type="hidden" id="SecondaryEmailRadio">
                                        </div>
                                    <?php endif; ?>


                                    <?php if (isset($clientData["SecondaryNumber"]) && !empty($clientData["SecondaryNumber"])) : ?>
                                        <div class="radiodiv">
                                            <input type="checkbox" value="<?= $clientData["SecondaryNumber"]; ?>" id="SecondaryNumberRadio" name="fav_language">
                                            <label class="form-check-label" for="SecondaryNumberRadio">
                                                <?= maskPhoneNumber($clientData["SecondaryNumber"]); ?>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="form-check mt-5">
                                            <input type="hidden" id="SecondaryNumberRadio">
                                        </div>
                                    <?php endif; ?>


                                </div>
                            </div>

                            <?php if (isset($clientData["ProfilePictureURL"]) && isset($clientData["FirstName"]) && isset($clientData["LastName"])) : ?>
                                <div class="col-md-4 profile text-center">
                                    <img src="../img/<?= $clientData["ProfilePictureURL"]; ?>" class="img-fluid rounded-start" alt="..." width="50px">
                                    <p class="userName mt-3"><?= $clientData["FirstName"]; ?> <?= $clientData["LastName"]; ?></p>
                                </div>
                            <?php else : ?>
                                <div class="form-check mt-5"></div>
                            <?php endif; ?>


                        </div>
                    </div>


                    <div class="d-flex justify-content-between">
                        <a href="login.php" class="me-5">Login with password</a>
                        <button type="button" id="clientBtn" class="btn btn-primary">Send Email</button>
                    </div>



                <?php else : ?>

                    <?php if (isset($adminData)) : ?>


                        <div class="detailsDiv m-3">
                            <div class="row g-0">
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <?php if (isset($adminData["Email"]) && !empty($adminData["Email"])) : ?>
                                            <div class="mt-5 radiodiv">
                                                <input type="radio" value="<?= $adminData["Email"]; ?>" id="EmailRadio" name="fav_language">
                                                <label class="form-check-label" for="EmailRadio">
                                                    <?= maskEmail($adminData["Email"]); ?>
                                                </label>
                                            </div>
                                        <?php else : ?>
                                            <div class="form-check mt-5"></div>
                                        <?php endif; ?>

                                        <?php if (isset($adminData["Phone"]) && !empty($adminData["Phone"])) : ?>
                                            <div class="radiodiv">
                                                <input type="radio" value="<?= $adminData["Phone"]; ?>" id="NumberRadio" name="fav_language">
                                                <label class="form-check-label" for="NumberRadio">
                                                    <?= maskPhoneNumber($adminData["Phone"]); ?>
                                                </label>
                                            </div>
                                        <?php else : ?>
                                            <div class="form-check mt-5"></div>
                                        <?php endif; ?>


                                    </div>
                                </div>

                                <?php if (isset($adminData["ProfilePictureURL"]) && isset($adminData["FullName"])) : ?>
                                    <div class="col-md-4 text-center">
                                        <img src="../img/<?= $adminData["ProfilePictureURL"]; ?>" class="img-fluid rounded-start" alt="..." width="50px">
                                        <p class="userName mt-3"><?= $adminData["FullName"]; ?></p>
                                    </div>
                                <?php endif; ?>


                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="login.php" class="me-5">Login with password</a>
                            <button type="button" id="adminBtn" class="btn btn-primary">Send Email</button>
                        </div>


                    <?php endif; ?>
                <?php endif; ?>


            </div>

        <?php else : ?>

            <div class="div">
                <p>No such Email was found</p>
                <a href="login.php" class="me-5">Go Back</a>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once "footer.php"; ?>
<?php
unset($_SESSION['clientEmailFound']);
unset($_SESSION['adminEmailFound']);
?>


<script>
    var client = document.getElementById("clientBtn");
    var admin = document.getElementById("adminBtn");
    var loader = document.getElementById("loader");

    if (client) {
        client.addEventListener("click", function() {
            // client
            var clientID = <?= isset($clientData["ClientID"]) ? $clientData["ClientID"] : 'false'; ?>;


            var primaryEmailRadio = document.getElementById("primaryEmailRadio");
            var primaryNumberRadio = document.getElementById("primaryNumberRadio");
            var SecondaryEmailRadio = document.getElementById("SecondaryEmailRadio");
            var SecondaryNumberRadio = document.getElementById("SecondaryNumberRadio");

            var primaryEmailChecked = primaryEmailRadio.checked;
            var primaryNumberChecked = primaryNumberRadio.checked;
            var SecondaryEmailChecked = SecondaryEmailRadio.checked;
            var SecondaryNumberChecked = SecondaryNumberRadio.checked;

            loader.style.display = "flex";
            var formData = new FormData();

            if (primaryEmailChecked) {
                formData.append("primaryEmail", primaryEmailRadio.value);
                formData.append("clientID", clientID);
            } else if (primaryNumberChecked) {
                formData.append("primaryNumber", primaryNumberRadio.value);
                formData.append("clientID", clientID);
            } else if (SecondaryEmailChecked) {
                formData.append("secondaryEmail", SecondaryEmailRadio.value);
                formData.append("clientID", clientID);
            } else if (SecondaryNumberChecked) {
                formData.append("secondaryNumber", SecondaryNumberRadio.value);
                formData.append("clientID", clientID);
            }

            // Fetch API to send data to updateUserProfilePic_mod.php
            fetch("../controllers/sendPassword_contr.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // displayMessage("editError", "Updated Details Successfully", false);

                    if (data.success) {
                        document.querySelector("#loader").textContent = 'Password sent Successfuly';
                        setTimeout(() => {
                            loader.style.display = "none";
                            window.location.href = `login.php?reset=true`;
                        }, 2000);

                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });


        })
    }



    if (admin) {
        admin.addEventListener("click", function() {
            // admin
            var adminID = <?= isset($adminData["AdminID"]) ? $adminData["AdminID"] : 'false'; ?>;

            var EmailRadio = document.getElementById("EmailRadio");
            var NumberRadio = document.getElementById("NumberRadio");

            var EmailChecked = EmailRadio.checked;
            var NumberChecked = NumberRadio.checked;

            loader.style.display = "flex";
            var formData = new FormData();

            if (EmailChecked) {
                formData.append("Email", EmailRadio.value);
                formData.append("adminID", adminID);
            } else if (NumberChecked) {
                formData.append("Number", NumberRadio.value);
                formData.append("adminID", adminID);
            }

            // Fetch API to send data to updateUserProfilePic_mod.php
            fetch("../controllers/sendPassword_contr.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector("#loader").textContent = 'Password sent Successfuly';
                        setTimeout(() => {
                            loader.style.display = "none";
                            window.location.href = `login.php?reset=true`;
                        }, 2000);

                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });


        })
    }
</script>
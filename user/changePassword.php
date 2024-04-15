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
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getTime_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];
$greeting = getGreeting();
?>

<?php require_once "../views/header.php"; ?>

<?php require_once "../views/style.config.php"; ?>


<style>
    #togglePassword {
        cursor: pointer;
    }

    .newPassword,
    .confirmNewPassword {
        background-color: var(--light) !important;
        color: var(--dark) !important;
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
                <h1><?= $greeting ?>, <?= $_SESSION['FirstName']; ?></h1>
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
                        <a class="active" href="#">Change Password</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- content-container -->
        <div id="loader">Processing... Please check your phone for a popup</div>
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Change Password</h3>
                </div>

                <form>
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <label for="newPassword">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control newPassword" id="newPassword" autocomplete="off" name="newPassword" value="">
                                <span class="input-group-text" id="togglePassword1">
                                    <i class='bx bxs-low-vision'></i>
                                </span>
                            </div>
                            <div id="password-strength"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmNewPassword">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control confirmNewPassword" id="confirmNewPassword" name="confirmNewPassword">
                                <span class="input-group-text" id="togglePassword2">
                                    <i class='bx bxs-low-vision'></i>
                                </span>
                            </div>
                        </div>

                        <p id="errorMsg"></p>
                        <div class="col mt-4">
                            <button type="button" class="btn btn-success" onclick="changePassword()">Change</button>
                        </div>
                    </div>
                </form>
            </div>


            <script>
                function changePassword() {
                    const newPassword = document.getElementById("newPassword").value;
                    const confirmNewPassword = document.getElementById("confirmNewPassword").value;
                    const loader = document.getElementById("loader");

                    if (!newPassword || !confirmNewPassword) {
                        displayMessage("errorMsg", "Please fill in all fields", true);
                        return;
                    }
                    if (newPassword !== confirmNewPassword) {
                        displayMessage("errorMsg", "Your password did not match", true);
                        return;
                    }
                    if (newPassword.length < 6) {
                        displayMessage("errorMsg", "Password must be at least 6 characters long.", true);
                        return;
                    }

                    var clientID = <?php echo json_encode($clientID); ?>;
                    loader.style.display = "flex";
                    var formData = new FormData();
                    formData.append("clientID", clientID);
                    formData.append("confirmNewPassword", confirmNewPassword);

                    fetch("../controllers/changeClientPassword_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loader.style.display = "none";
                                displayMessage("errorMsg", "Changed Successfully", false);
                                setTimeout(() => {
                                    window.location.href = "settings.php";
                                }, 1000);
                            } else {
                                displayMessage("error", "Error fetching Data", true);
                            }
                        })
                        .catch(error => {
                            displayMessage("error", "Network Error", true);
                            document.getElementById("addbtn").disabled = false;
                        })
                }


                $('#password-strength').text('');
                // Function to update password strength score
                function updatePasswordStrength(password) {
                    var result = zxcvbn(password);
                    var score = result.score; // Password strength score (0 to 4)
                    var messages = ["Poor", "Fair", "Good", "Strong", "Excellent"];
                    var strengthMessage = messages[score];

                    // Update the password strength display
                    $('#password-strength').text('Password Strength: ' + strengthMessage).css('color', getColor(score));
                }

                // Function to assign color based on password strength score
                function getColor(score) {
                    var colors = ['#ff6347', '#ffa500', '#ffd700', '#7cfc00', '#32cd32'];
                    return colors[score];
                }


                // Event listener to check password strength on input
                $('.newPassword').on('input', function() {
                    var password = $(this).val();
                    updatePasswordStrength(password);
                });


                // showhidePswd(togglePassword1);
                // showhidePswd(togglePassword2);

                var togglePassword1 = document.getElementById("togglePassword1");
                var togglePassword2 = document.getElementById("togglePassword2");



                togglePassword1.addEventListener("click", function() {
                    showhidePswd(togglePassword1);
                });

                togglePassword2.addEventListener("click", function() {
                    showhidePswd(togglePassword2);
                });


                function showhidePswd(element) {
                    var passwordInput = element.previousElementSibling;

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                    } else {
                        passwordInput.type = 'password';
                    }
                }



                function displayMessage(messageElement, message, isError) {
                    // Get the HTML element where the message should be displayed
                    var targetElement = document.getElementById(messageElement);

                    // Set the message text
                    targetElement.innerText = message;

                    // Add styling based on whether it's an error or success
                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }

                    // Set a timeout to hide the message with the fade-out effect
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 2000);
                }
            </script>
            <?php require_once "../views/footer.php"; ?>
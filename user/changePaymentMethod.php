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
require_once  '../modals/setup_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../modals/viewSingleUser_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$clientID = $_SESSION["clientID"];
?>

<?php require_once "../views/header.php"; ?>

<?php require_once "../views/style.config.php"; ?>

<style>
    .changePaymentMethod .Choosenoption {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .changePaymentMethod .Choosenoption .div {
        position: relative;
        width: 60%;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 3%;
        border: 1px solid var(--light-blue);
        border-radius: 5px;
        padding: 1% 4%;
    }

    .changePaymentMethod .Choosenoption .div:hover {
        cursor: pointer;
    }

    .changePaymentMethod .Choosenoption .div img {
        width: 190px;
        height: 80px;
        object-fit: contain;
    }

    .changePaymentMethod .Choosenoption .div .form-check {
        position: absolute;
        right: 0%;
        padding: 10px;
    }

    .changePaymentMethod .Choosenoption .div .form-check .checkbox {
        padding: 10px;
    }

    .actions {
        margin-top: 10px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .actions button {
        border: none;
        outline: none;
    }

    .actions button a {
        background-color: var(--light);
        padding: 5px 7px;
        border: 1px solid var(--light-blue);
        border-radius: 5px;
        transition: .3s;
    }

    .actions button a:hover {
        background-color: var(--blue);
        color: var(--light);
    }

    .selectedMethod {
        border: 4px solid var(--blue);
    }

    .checkIcon {
        display: none;
        position: absolute;
        top: -2%;
        right: -3%;
        background-color: white;
        /* padding: 10px; */
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
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Change Subscription Plan</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="changePaymentMethod shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                    <h5>Payment Method</h5>
                    <p class="mb-3">Click on Your Prefered Payment Option And Save Changes made</p>
                    <?php $PaymentMethods = getPaymentMethods($connect); ?>

                    <?php $clientData = getClientDataById($connect, $clientID); ?>

                    <?php echo $clientData["PreferedPaymentMethod"];
                    ?>

                    <?php if (!empty($PaymentMethods)) : ?>
                        <?php foreach ($PaymentMethods as $PaymentMethod) : ?>
                            <?php
                            // echo $PaymentMethod['PaymentOptionID'];
                            // Set a class for the default selected div based on the preferred payment ID
                            $selectedClass = ($PaymentMethod['PaymentOptionID'] === $clientData["PreferedPaymentMethod"]) ? '3px solid #3C91E6' : '';
                            $changeIcon = ($PaymentMethod['PaymentOptionID'] === $clientData["PreferedPaymentMethod"]) ? 'flex' : '';

                            ?>
                            <div class="Choosenoption">
                                <div class="div" style="border: <?= $selectedClass; ?>;">
                                    <i class="fa-solid fa-circle-check checkIcon fa-lg" style="color: #3c91e6; display:<?= $changeIcon; ?> ;"></i>
                                    <input type="hidden" class="PaymentOptionID" value="<?= $PaymentMethod['PaymentOptionID']; ?>">
                                    <img src="../img/<?= $PaymentMethod['PaymentOptionImg']; ?>" class="rounded float-start me-3" alt="...">
                                    <p class="quote"><?= $PaymentMethod['PaymentOptionQuote']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No chosen options available</p>
                    <?php endif; ?>
                    <p id="error"></p>

                    <div class="actions">
                        <!-- <a href="#">Remove</a> -->
                        <button type="button"><a href="" class="changeBtn">Change</a></button>
                    </div>
                </div>
            </div>


        </div>

        </div>


        <script>
            var divs = document.querySelectorAll(".div");
            var selectedMethod = document.querySelector(".selectedMethod");
            var selectedPaymentId = null;

            divs.forEach(div => {
                var checkIcon = div.querySelector(".checkIcon");

                div.addEventListener("click", function() {
                    loader.style.display = "flex";

                    divs.forEach(otherDiv => {
                        otherDiv.style.border = '1px solid var(--light-blue)';
                        otherDiv.querySelector(".checkIcon").style.display = 'none';
                    });

                    selectedPaymentId = div.querySelector(".PaymentOptionID").value;

                    setTimeout(() => {
                        div.style.border = '3px solid var(--blue)';
                        checkIcon.style.display = 'flex';
                        loader.style.display = "none";
                    }, 1000);
                });
            });

            // Event listener for the "Change" button
            var changeBtn = document.querySelector(".changeBtn");
            changeBtn.addEventListener("click", function(e) {
                e.preventDefault();
                var selectedDiv = document.querySelector(".div");

                if (!selectedPaymentId) {
                    displayMessage("error", "No changes made", true);
                    return;
                }

                loader.style.display = "flex";
                var clientID = <?php echo json_encode($clientID); ?>;
                // Create a FormData object to send data via Fetch API
                var formData = new FormData();
                formData.append("clientID", clientID);
                formData.append("selectedPaymentId", selectedPaymentId);
                // Use Fetch API to send the data
                fetch('../controllers/changePaymentMethod_contr.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loader.style.display = "none";
                            displayMessage("error", "saved Successfuly", false);
                            window.location.href = "settings.php";
                        } else {
                            displayMessage("error", "Error fetching Data", true);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        displayMessage("error", "something went wrong", true);
                        addbtn.disabled = false;
                        loader.style.display = "none";
                    });

            });





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







            // console.log(paymentId);
            // if (!paymentId) {
            //     console.log("nothing");
            //     // return;
            // }
        </script>
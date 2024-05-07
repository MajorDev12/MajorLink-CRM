<?php require_once "../controllers/session_Config.php"; ?>

<?php
require_once  '../database/pdo.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/getSubarea_mod.php';
require_once  '../modals/getSms_mod.php';
require_once  '../modals/getClientsNames_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
?>

<style>
    #text-input {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
        min-height: 40vh;
        color: var(--dark);
    }

    #text-input1 {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
        min-height: 40vh;
        color: var(--dark);
    }

    #areacheckbox {
        display: none;
    }

    #subareacheckbox {
        display: none;
    }
</style>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>

<!-- SIDEBAR -->
<?php require_once "side_nav.php"; ?>
<!-- SIDEBAR -->


<div id="loader">Sending...</div>
<!-- CONTENT -->
<section id="content">
    <!-- TOP-NAVBAR -->
    <?php require_once "top_nav.php"; ?>
    <!-- TOP-NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">

            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Sms</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">

                <h3 class="mb-5">Send Mass Sms</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="dropdown">
                            <label for="">Choose recipient</label>
                            <select name="" id="recipientSelect" class="form-select">
                                <option value="" selected disabled>filter</option>
                                <option value="All">All Customers</option>
                                <option value="Area">Area</option>
                                <option value="SubArea">Sub Area</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>



                </div>


                <?php $areas = getData($connect); ?>
                <?php if ($areas) : ?>

                    <div id="areacheckbox" class="row mt-5">
                        <?php foreach ($areas as $area) : ?>
                            <div class="col-md-3 mt-2">
                                <input type="checkbox" value="<?= $area["AreaID"]; ?>" id="">
                                <span><?= $area["AreaName"]; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : "no data available"; ?>
                    <?php endif; ?>
                    </div>



                    <?php $subareas = getAllSubareas($connect); ?>
                    <?php if ($subareas) : ?>

                        <div id="subareacheckbox" class="row mt-5">
                            <?php foreach ($subareas as $area) : ?>
                                <div class="col-md-3 mt-2">
                                    <input type="checkbox" value="<?= $area["SubAreaID"]; ?>" id="">
                                    <span><?= $area["SubAreaName"]; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : "no data available"; ?>
                        <?php endif; ?>
                        </div>






                        <!-- Textarea -->
                        <label class="mt-5" for="backColor">Message</label>
                        <div id="text-input" class="massmessage" contenteditable="true"></div>
                        <select class="form-select mt-3" id="smsMode">
                            <option value="" disabled selected>Select Mode of Sending</option>
                            <option value="Nexmo">Nexmo</option>
                            <option value="Infobip">Infobip</option>
                        </select>
                        <div id="errorMsg"></div>
                        <button id="massSend" class="btn btn-primary mt-3">Send</button>
            </div>

            <div class="content">
                <h3 class="mb-5">Single User</h3>
                <div class="row">

                    <div class="col-md-4">
                        <label for="customer">Customer</label>
                        <select id="customer" class="form-select">
                            <option value="" selected hidden>--Search--</option>
                        </select>
                    </div>




                </div>
                <!-- Textarea -->
                <label class="mt-5" for="backColor">Message</label>
                <div id="text-input1" class="singlemessage" contenteditable="true"></div>
                <select class="form-select mt-3" id="provider">
                    <option value="" disabled selected>Select Mode of Sending</option>
                    <option value="Nexmo">Nexmo</option>
                    <option value="Infobip">Infobip</option>
                </select>
                <div id="errorMsg1"></div>
                <button id="singleSend" class="btn btn-primary mt-3">Send</button>
            </div>

            <?php require_once "footer.php"; ?>

            <script>
                var recipientSelect = document.querySelector("#recipientSelect");
                var customerSelect = document.querySelector("#customer");
                var areacheckbox = document.querySelector("#areacheckbox");
                var subareacheckbox = document.querySelector("#subareacheckbox");
                var massSend = document.querySelector("#massSend");
                var singleSend = document.querySelector("#singleSend");
                var singlemessage = document.querySelector("#text-input1");
                var massmessage = document.querySelector(".massmessage");
                var smsMode = document.querySelector("#smsMode");
                var provider = document.querySelector("#provider");
                var loader = document.querySelector("#loader");


                massSend.addEventListener("click", function() {
                    const selectedValue = recipientSelect.value;

                    if (!selectedValue) {
                        displayMessage("errorMsg", "Choose a recipient", true);
                        return;
                    }
                    if (!massmessage.innerText) {
                        displayMessage("errorMsg", "Fill in the message first", true);
                        return;
                    }



                    if (selectedValue === "Area") {
                        // Get all checkboxes inside the areacheckbox div
                        const checkboxes = areacheckbox.querySelectorAll('input[type="checkbox"]:checked');

                        // If no checkboxes are checked, display an alert
                        if (checkboxes.length === 0) {
                            displayMessage("errorMsg", "Choose atleast one Area", true);
                            return; // Exit the function
                        }

                        // Extract the values of the checked checkboxes and store them in an array
                        var checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
                        // console.log(checkedValues);
                    }


                    if (selectedValue === "SubArea") {
                        // Get all checkboxes inside the areacheckbox div
                        const checkboxes = subareacheckbox.querySelectorAll('input[type="checkbox"]:checked');
                        // If no checkboxes are checked, display an alert
                        if (checkboxes.length === 0) {
                            displayMessage("errorMsg", "Choose atleast one Sub Area", true);
                            return; // Exit the function
                        }

                        // Extract the values of the checked checkboxes and store them in an array
                        var checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
                        // console.log(checkedValues);
                    }

                    if (!smsMode.value) {
                        displayMessage("errorMsg", "Choose Mode of Sending", true);
                        return; // Exit the function
                    }

                    // console.log(selectedValue);
                    // console.log(checkedValues);
                    // console.log(massmessage.innerText);
                    // return
                    loader.style.display = 'flex';

                    var formData = new FormData();
                    formData.append("selectedValue", selectedValue);
                    formData.append("checkedValues", checkedValues);
                    formData.append("massmessage", massmessage.innerText);
                    formData.append("smsMode", smsMode.value);

                    fetch("../controllers/massSms_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loader.style.display = 'none';
                                // Handle the response from the server
                                displayMessage("errorMsg", data.message, false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                loader.style.display = 'none';
                                displayMessage("errorMsg", data.message, true);
                            }
                        })
                        .catch(error => {
                            loader.style.display = 'none';
                            console.error('Error:', error);
                        });

                });



                // Add a change event listener
                recipientSelect.addEventListener("change", function() {
                    // Get the selected value
                    var selectedValue = recipientSelect.value;
                    // Check the selected value and show/hide checkboxes accordingly
                    if (selectedValue === "Area") {
                        areacheckbox.style.display = 'flex';
                        subareacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "SubArea") {
                        subareacheckbox.style.display = 'flex';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "All") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "Active") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "Inactive") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                });







                singleSend.addEventListener("click", function() {
                    const selectedCustomer = customerSelect.value;
                    const message = singlemessage.innerText;

                    if (!selectedCustomer) {
                        displayMessage("errorMsg1", "Choose a recipient", true);
                        return;
                    }
                    if (!message) {
                        displayMessage("errorMsg1", "Fill in the message first", true);
                        return;
                    }
                    if (!provider.value) {
                        displayMessage("errorMsg1", "Choose the mode to send", true);
                        return;
                    }

                    loader.style.display = 'flex';

                    var formData = new FormData();
                    formData.append("selectedCustomer", selectedCustomer);
                    formData.append("provider", provider.value);
                    formData.append("message", message);

                    fetch("../controllers/singleSms_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loader.style.display = 'none';
                                // Handle the response from the server
                                displayMessage("errorMsg1", data.message, false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                loader.style.display = 'none';
                                displayMessage("errorMsg1", data.message, true);
                            }
                        })
                        .catch(error => {
                            loader.style.display = 'none';
                            console.error('Error:', error);
                        });

                });





                $(document).ready(function() {
                    var customerList = [];

                    <?php $clientData = getClientsNames($connect); ?>
                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>"
                        });
                    <?php endforeach; ?>

                    $("#customer").select2({
                        data: customerList
                    });



                    // Attach change event listener
                    $("#customer").on("change", function() {
                        selectedClientId = $(this).val();


                        // Make an AJAX request to get client data
                        $.ajax({
                            url: '../controllers/getClientInfo_contr.php',
                            type: 'GET',
                            data: {
                                clientId: selectedClientId
                            },
                            success: function(response) {
                                // Update the client information based on the response
                                $('.clientNames').text(response.FirstName + ' ' + response.LastName);
                            },
                            error: function(error) {
                                console.error('Error fetching client data:', error);
                            }
                        });
                    });


                });
            </script>
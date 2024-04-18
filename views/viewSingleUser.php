<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/viewSingleUser_contr.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../controllers/addClient_contr.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/reports_mod.php';
require_once  '../modals/addInvoice_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$invoicesData = getInvoicesByClientID($connect, $clientID);
?>
<?php require_once "header.php"; ?>

<style>
    .map {
        min-width: 200px;
        min-height: 200px;
        background-color: brown;
    }

    #map {
        width: 100%;
        height: 400px;
        background-color: var(--latto);
    }

    h1 {
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--dark);
        border-bottom: 1px solid var(--color);
    }


    .tabs {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        background-color: var(--latto);
    }


    h3 {
        background-color: var(--background-color);
        color: var(--grey-color);
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        transition: .3s;
        margin: 0;
    }

    .tab-content {
        background-color: var(--background-color);
        padding: 50px 40px;
    }

    .content .tabs button {
        margin: 2px;
        color: var(--dark);
    }

    .tab-content h4 {
        font-size: var(--font-size);
        margin-bottom: 20px;
        color: var(--dark);
        font-weight: 600;
    }

    .tab-content h4 span {
        color: var(--dark);
    }

    .tab-content p {
        text-align: justify;
        line-height: 1.9;
        letter-spacing: 0.4px;
        color: var(--light-dark);
    }



    .tab-content .page .profileImg {
        max-width: 250px;
        max-height: 50px;
    }

    .tab-content .page .profileImg img {
        width: 100%;
    }

    /* .editClient .edituserprofile {
        max-width: 200px;
        max-height: 50px;
    } */

    .editClient .edituserprofile img {
        width: 100%;
    }

    .summary .usercard {
        border: none;
        background-color: var(--latto);
    }

    .card {
        border: none;
        background-color: var(--latto);
    }

    table {
        background-color: none;
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

    #accountSummary h4 {
        color: var(--light-dark);
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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="viewClient.php">List Customers</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">View Client</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- toast -->
        <div id="toast">
            <div class="toast-header">
                <img src="../img/user.png" alt="" width="30px">
                <small id="toast-time">3 secs Ago</small>
            </div>
            <div class="toast-body">
                <h3>Created Successfully Custom Toast Example</h3>
            </div>
        </div>
        <!-- toast -->

        <div id="loader">Loading...</div>
        <!-- content-container -->
        <div class="main-content">

            <div class="content">
                <h1>Action Center</h1>
                <div class="tabs">
                    <button type="button" class="btn active">Summary</button>
                    <button type="button" class="btn active">Edit Customer</button>
                    <button type="button" class="btn active">Modify Subscription</button>
                    <button type="button" class="btn active">Accounting</button>
                    <button type="button" class="btn active">Invoices</button>
                    <!-- <button type="button" class="btn active">Send Message</button> -->
                </div>
            </div>

            <div class="content tab-content">




                <!-- summary Page -->
                <div class="page summary active">

                    <?php if (isset($clientData)) : ?>
                        <div class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                            <div class="row g-0">
                                <div class="col-md-4">

                                    <img id="profilePicture" src="../img/<?= $clientData['ProfilePictureURL'] ?? $defaultProfileImageURL ?>" class="img-fluid rounded-start" alt="...">

                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Name: </h4>
                                            <p class="col-md-6"><?php echo $clientData['FirstName'] . ' ' . $clientData['LastName']; ?></p>
                                        </div>
                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Email Address: </h4>
                                            <p class="col-md-6"><?php echo $clientData['PrimaryEmail']; ?></p>
                                        </div>
                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Phone Number: </h4>
                                            <p class="col-md-6"><?php echo $clientData['PrimaryNumber']; ?></p>
                                        </div>

                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Current Plan: </h4>
                                            <p class="col-md-6"><?php echo  empty($clientData['Plan']) ? '---' : $clientData['Plan']; ?></p>
                                        </div>

                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Active Status: </h4>
                                            <p class="col-md-6"><?php echo $clientData['ActiveStatus'] == 1 ? 'Active' : 'Inactive'; ?></p>
                                        </div>


                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Expire Date: </h4>
                                            <p class="col-md-6"><?php echo empty($clientData['ExpireDate']) ? '---' : $clientData['ExpireDate']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>







                        <div class="content otherUserDetails">

                            <div class="h4 pb-2 mb-4 border-bottom">
                                <h4>More Information</h4>
                            </div>
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                <div class="col">
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Joined Date: </h4>
                                        <p class="col-md-6"><?php echo $clientData['CreatedDate']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Secondary Email: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['SecondaryEmail']) ? '---' : $clientData['SecondaryEmail']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Secondary Number: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['SecondaryNumber']) ? '---' : $clientData['SecondaryNumber']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Installation Fees: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['InstallationFees']) ? '---' : $clientData['InstallationFees']; ?></p>
                                    </div>

                                </div>
                                <div class="col">

                                    <div class="row">
                                        <h4 class="col-md-4 text-start">Area: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Area']) ? '---' : $clientData['Area']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-4 text-start">Sub Area: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['SubArea']) ? '---' : $clientData['SubArea']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-4 text-start">Longitude: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Longitude']) ? '---' : $clientData['Longitude']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-4 text-start">Latitude: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Latitude']) ? '---' : $clientData['Latitude']; ?></p>
                                    </div>


                                </div>

                            </div>




                        </div>



                        <div class="content map shadow p-3 mb-5 rounded">
                            <h3 class="text-start">Location</h3>
                            <div id="map"></div>
                        </div>
                </div>

            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    No client data found for the provided ClientID.
                </div>
            <?php endif; ?>









            <!-- Edit Customer Page -->
            <?php require_once "customer_page.php"; ?>



            <!-- Accounting Page -->
            <?php require_once "accounting_page.php"; ?>



            <!-- Invoices Page -->
            <?php require_once "invoice_page.php"; ?>





            <!-- message Page -->






            <?php require_once "footer.php"; ?>



            <script>
                initializeTabs(".tabs button", ".tab-content .page");
                // Get currrent Location of User
                var clientData = <?php echo json_encode($clientData); ?>;

                var map = L.map("map");
                var defaultLatLng = [-75.679170, 45.381710];

                if (clientData && clientData['Longitude'] && clientData['Latitude']) {
                    var clientLatLng = [parseFloat(clientData['Latitude']), parseFloat(clientData['Longitude'])];
                    map.setView(clientLatLng, 16);
                    addMarker(clientLatLng);
                } else {
                    map.setView(defaultLatLng, 16);
                    addMarker(defaultLatLng);
                }

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                function addMarker(latlng) {
                    var marker = L.marker(latlng).addTo(map);
                    var circle = L.circle(latlng, {
                        radius: 10
                    }).addTo(map);
                }



                // send data for update
                document.addEventListener('DOMContentLoaded', function() {
                    const saveChangesButton = document.querySelector('#save');
                    const currentPlanDropdown = document.querySelector('#currentPlan');
                    let isFormChanged = false;

                    const clientData = <?= json_encode($clientData); ?>;

                    // Current clientData
                    const clientId = clientData["ClientID"];
                    const FirstName = clientData["FirstName"];
                    const LastName = clientData["LastName"];
                    const PrimaryEmail = clientData["PrimaryEmail"];
                    const SecondaryEmail = clientData["SecondaryEmail"];
                    const PrimaryNumber = clientData["PrimaryNumber"];
                    const SecondaryNumber = clientData["SecondaryNumber"];
                    const Area = clientData["Area"];
                    const SubArea = clientData["SubArea"];
                    const Latitude = clientData["Latitude"];
                    const Longitude = clientData["Longitude"];
                    const Address = clientData['Address'];
                    const City = clientData['City'];
                    const Country = clientData['Country'];
                    const Zipcode = clientData['Zipcode'];


                    const firstnameInput = document.querySelector('#firstname');
                    const lastnameInput = document.querySelector('#lastname');
                    const PrimaryEmailInput = document.querySelector('#PrimaryEmail');
                    const SecondaryEmailInput = document.querySelector('#SecondaryEmail');
                    const primaryNumberInput = document.querySelector('#primaryNumber');
                    const secondaryNumberInput = document.querySelector('#secondaryNumber');
                    const LatitudeInput = document.querySelector('#Latitude');
                    const LongitudeInput = document.querySelector('#Longitude');
                    const areaInput = document.querySelector('#area');
                    const subareaInput = document.querySelector('#subArea');
                    const AddressInput = document.querySelector('#Address');
                    const CityInput = document.querySelector('#City');
                    const CountryInput = document.querySelector('#Country');
                    const ZipcodeInput = document.querySelector('#zipcode');




                    //check if changed
                    firstnameInput.addEventListener('input', markFormAsChanged);
                    lastnameInput.addEventListener('input', markFormAsChanged);
                    PrimaryEmailInput.addEventListener('input', markFormAsChanged);
                    SecondaryEmailInput.addEventListener('input', markFormAsChanged);
                    primaryNumberInput.addEventListener('input', markFormAsChanged);
                    secondaryNumberInput.addEventListener('input', markFormAsChanged);
                    AddressInput.addEventListener('input', markFormAsChanged);
                    CityInput.addEventListener('input', markFormAsChanged);
                    CountryInput.addEventListener('input', markFormAsChanged);
                    ZipcodeInput.addEventListener('input', markFormAsChanged);

                    const getLocationButton = document.querySelector('#getLocationButton');

                    getLocationButton.addEventListener('click', () => {
                        // Assuming that getCoordinates is a function to fetch Latitude and Longitude dynamically
                        const {
                            latitude,
                            longitude
                        } = getCoordinates();

                        // Update Latitude and Longitude inputs
                        LatitudeInput.value = latitude;
                        LongitudeInput.value = longitude;

                        // Trigger the custom event
                        document.dispatchEvent(new Event('coordinatesChanged'));
                    });

                    document.addEventListener('coordinatesChanged', markFormAsChanged);
                    areaInput.addEventListener('input', markFormAsChanged);
                    subareaInput.addEventListener('input', markFormAsChanged);

                    // Add event listeners for other form fields

                    function markFormAsChanged() {
                        isFormChanged = true;
                    }

                    saveChangesButton.addEventListener('click', saveChanges);

                    function saveChanges() {
                        if (isFormChanged) {

                            // Form has changes, handle accordingly

                            // Get the submitted data
                            var submittedfirstName = document.querySelector('#firstname').value;
                            var submittedlastName = document.querySelector('#lastname').value;
                            var submittedPrimaryEmail = document.querySelector('#PrimaryEmail').value;
                            var submittedSecondaryEmail = document.querySelector('#SecondaryEmail').value;
                            var submittedprimaryNumber = document.querySelector('#primaryNumber').value;
                            var submittedsecondaryNumber = document.querySelector('#secondaryNumber').value;
                            var submittedLatitude = document.querySelector('#Latitude').value;
                            var submittedLongitude = document.querySelector('#Longitude').value;
                            var submittedarea = document.querySelector('#area').value;
                            var submittedsubarea = document.querySelector('#subArea').value;
                            var submittedAddress = document.getElementById("Address").value;
                            var submittedCity = document.getElementById("City").value;
                            var submittedCountry = document.getElementById("Country").value;
                            var submittedzipcode = document.getElementById("zipcode").value;



                            if (submittedfirstName.trim() === "") {
                                submittedfirstName = null;
                            } else {
                                if (!/^[a-zA-Z0-9 ]+$/.test(submittedfirstName)) {
                                    displayMessage("editError", "Only letters and numbers allowed", true);
                                    return;
                                }
                            }

                            if (submittedlastName.trim() === "") {
                                submittedlastName = null;
                            } else {
                                if (!/^[a-zA-Z0-9 ]+$/.test(submittedlastName)) {
                                    displayMessage("editError", "Only letters and numbers allowed", true);
                                    return;
                                }
                            }


                            if (submittedPrimaryEmail.trim() === "") {
                                submittedPrimaryEmail;
                            } else {
                                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(submittedPrimaryEmail)) {
                                    displayMessage("editError", "Invalid email address", true);
                                    return;
                                }
                            }


                            if (submittedSecondaryEmail.trim() === "") {
                                submittedSecondaryEmail;
                            } else {
                                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(submittedSecondaryEmail)) {
                                    displayMessage("editError", "Invalid email address", true);
                                    return;
                                }
                            }

                            if (submittedprimaryNumber.trim() === "") {
                                submittedprimaryNumber = '';
                            } else {
                                if (!/^\d+$/.test(submittedprimaryNumber)) {
                                    displayMessage("editError", "Invalid phone number (only numbers allowed)", true);
                                    return;
                                }

                            }

                            if (submittedsecondaryNumber.trim() === "") {
                                submittedsecondaryNumber = '';
                            } else {
                                if (!/^\d+$/.test(submittedsecondaryNumber)) {
                                    displayMessage("editError", "Invalid phone number (only numbers allowed)", true);
                                    return;
                                }

                            }


                            if (submittedzipcode.trim() !== "" && !isValidZipCode(submittedzipcode.trim())) {
                                displayMessage("editError", "Please enter a valid zip code.", true);
                                return; // Stop further execution
                            }






                            if (submittedLatitude.trim() === "") {
                                submittedLatitude = null;
                            } else {
                                // Regular expression for validating latitude
                                const latitudeRegex = /^-?\d+(\.\d+)?$/;

                                if (!latitudeRegex.test(submittedLatitude)) {
                                    displayMessage("editError", "Invalid latitude format", true);
                                    return;
                                }
                            }

                            if (submittedLongitude.trim() === "") {
                                submittedLongitude = null;
                            } else {
                                // Regular expression for validating latitude
                                const LongitudeRegex = /^-?\d+(\.\d+)?$/;

                                if (!LongitudeRegex.test(submittedLongitude)) {
                                    displayMessage("editError", "Invalid longitude format", true);
                                    return;
                                }
                            }



                            if (isNaN(submittedarea) || submittedarea === "") {
                                submittedarea = null;
                            }

                            if (isNaN(submittedsubarea) || submittedsubarea === "") {
                                submittedsubarea = null;
                            }


                            // Get other submitted data as needed

                            // Compare submitted data with the current clientData
                            if (
                                submittedfirstName !== FirstName ||
                                submittedlastName !== LastName ||
                                submittedPrimaryEmail !== PrimaryEmail ||
                                submittedSecondaryEmail !== SecondaryEmail ||
                                submittedprimaryNumber !== PrimaryNumber ||
                                submittedsecondaryNumber !== SecondaryNumber ||
                                submittedAddress !== Address ||
                                submittedCity !== City ||
                                submittedCountry !== Country ||
                                submittedzipcode !== Zipcode ||
                                submittedLatitude !== Latitude ||
                                submittedLongitude !== Longitude ||
                                submittedarea !== Area ||
                                submittedsubarea !== SubArea
                                // Compare other fields
                            ) {
                                document.getElementById("loader").style.display = 'flex';

                                var formData = new FormData();
                                formData.append("clientId", clientId);

                                const updatedData = {
                                    FirstName: submittedfirstName,
                                    LastName: submittedlastName,
                                    PrimaryEmail: submittedPrimaryEmail,
                                    SecondaryEmail: submittedSecondaryEmail,
                                    PrimaryNumber: submittedprimaryNumber,
                                    SecondaryNumber: submittedsecondaryNumber,
                                    Address: submittedAddress,
                                    City: submittedCity,
                                    Country: submittedCountry,
                                    zipcode: submittedzipcode,
                                    Latitude: submittedLatitude,
                                    Longitude: submittedLongitude,
                                    Area: submittedarea,
                                    SubArea: submittedsubarea,
                                    // Add other fields
                                };

                                // Append each key-value pair to the FormData
                                for (const key in updatedData) {
                                    formData.append(key, updatedData[key]);
                                }

                                // Perform a Fetch API POST request
                                fetch('../controllers/updateSingleUser_contr.php', {
                                        method: 'POST',
                                        body: formData,
                                    })
                                    .then(response => response.json()) // assuming the response is in JSON format
                                    .then(data => {
                                        console.log('data'.data);
                                        if (data.success) {
                                            // Handle the response if needed
                                            document.getElementById("loader").style.display = 'none';
                                            displayMessage("editError", data.message, false);
                                            location.reload();
                                        } else {
                                            displayMessage("editError", data.message, true);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        displayMessage("editError", "network error", true);
                                    });
                            } else {
                                // No changes, display error message or take appropriate action
                                displayMessage("editError", "No Changes Made", true);
                            }
                        } else {
                            // No changes, display error message or take appropriate action
                            console.log('No changes made.');
                            displayMessage("editError", "No Changes Made", true);
                        }
                    }
                });




                function isValidZipCode(zipcode) {
                    // Regular expression to validate zip code format (example: 12345 or 12345-6789)
                    var zipCodePattern = /^\d{5}(?:-\d{4})?$/;
                    return zipCodePattern.test(zipcode);
                }
                // error message function
                function displayMessage(messageElement, message, isError, ) {
                    var targetElement = document.getElementById(messageElement);
                    targetElement.innerText = message;

                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 1000);
                }



                //toast function
                function checkAndShowToastAfterReload() {
                    // Check if the flag is set to show the toast after reload
                    if (localStorage.getItem('updateClientProfileToast') === 'true') {
                        showToast('Congratulations!, Updated Successfuly!', 3000);
                        localStorage.removeItem('updateClientProfileToast');
                    }

                    if (localStorage.getItem('UpdateClientPlanToast') === 'true') {
                        showToast('Updated Successfuly!', 3000);
                        localStorage.removeItem('UpdateClientPlanToast');
                    }

                    if (localStorage.getItem('updateClientProfileToast') === 'true') {
                        showToast('Updated Successfuly!', 3000);

                        // Reset the flag after showing the toast
                        localStorage.removeItem('updateClientProfileToast');
                    }
                }



                // Call the function after the page loads
                window.onload = function() {
                    setTimeout(() => {
                        checkAndShowToastAfterReload();
                    }, 3000);
                };
            </script>
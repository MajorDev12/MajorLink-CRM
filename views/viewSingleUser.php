<?php
require_once  '../database/pdo.php';
require_once  '../controllers/viewSingleUser_contr.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../controllers/addClient_contr.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
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
        color: var(--fade-color);
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

    .tab-content h4 {
        font-size: var(--font-size);
        margin-bottom: 20px;
        color: var(--fade-color);
        font-weight: 600;
    }

    .tab-content h4 span {
        color: var(--grey-color);
    }

    .tab-content p {
        text-align: justify;
        line-height: 1.9;
        letter-spacing: 0.4px;
        color: var(--color);
    }

    .tab-content .page {
        display: none;
    }

    .tab-content .active {
        display: block;
    }

    .tabs .active {
        background-color: none;
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
        <div id="loader">Loading</div>
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
                    <button type="button" class="btn active">Send Message</button>
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
                                            <h4 class="col-md-4 text-start">Joined Date: </h4>
                                            <p class="col-md-6"><?php echo $clientData['CreatedDate']; ?></p>
                                        </div>
                                        <div class="row">
                                            <h4 class="col-md-4 text-start">Current Plan: </h4>
                                            <p class="col-md-6"><?php echo  empty($clientData['Plan']) ? '---' : $clientData['Plan']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>







                        <div class="content otherUserDetails">

                            <div class="h4 pb-2 mb-4 border-bottom">
                                <h4>More Information</h4>
                            </div>
                            <div class="row  row-cols-1 row-cols-md-2 g-4">
                                <div class="col">
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Secondary Email: </h4>
                                        <?php echo empty($clientData['SecondaryEmail']) ? '---' : $clientData['SecondaryEmail']; ?>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Secondary Number: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['SecondaryNumber']) ? '---' : $clientData['SecondaryNumber']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Payment status: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Plan']) ? '---' : $clientData['Plan']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Active Status: </h4>
                                        <p class="col-md-6"><?php echo $clientData['ActiveStatus'] == 1 ? 'Active' : 'Inactive'; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Expire Date: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['ExpireDate']) ? '---' : $clientData['ExpireDate']; ?></p>
                                    </div>

                                </div>
                                <div class="col">

                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Area: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Area']) ? '---' : $clientData['Area']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Sub Area: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['SubArea']) ? '---' : $clientData['SubArea']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Longitude: </h4>
                                        <p class="col-md-6"><?php echo empty($clientData['Longitude']) ? '---' : $clientData['Longitude']; ?></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-md-6 text-start">Latitude: </h4>
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

            <div class="page editClient">
                <h4>Edit User Details</h4>
                <?php if (isset($clientData)) : ?>
                    <div class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                        <div class="row g-0">
                            <div class="col-md-4 text-center">
                                <form action="" enctype="multipart/form-data" id="editForm" method="post" onsubmit="submitForm(event)">
                                    <input type="hidden" name="id" id="id" value="<?= $clientData['ClientID']; ?>">
                                    <div class="upload">
                                        <img id="editprofilePicture" src="../img/<?= $clientData['ProfilePictureURL']; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="100px">

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
                            <script>
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
                                            document.getElementById("cancel").style.display = "none";
                                            document.getElementById("confirm").style.display = "none";
                                            document.getElementById("upload").style.display = "block";
                                            location.reload();

                                        })
                                        .catch(error => {
                                            console.error("Error:", error);
                                        });
                                });
                            </script>




                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="firstname">firstname</label>
                                            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $clientData['FirstName'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastname">lastname</label>
                                            <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $clientData['LastName'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="PrimaryEmail">Email Address</label>
                                            <input type="email" class="form-control" name="PrimaryEmail" id="PrimaryEmail" value="<?= $clientData['PrimaryEmail'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="SecondaryEmail">Secondary Email</label>
                                            <input type="email" class="form-control" name="SecondaryEmail" id="SecondaryEmail" value="<?= $clientData['SecondaryEmail'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="primaryNumber">Phone Number</label>
                                            <input type="tel" class="form-control" name="primaryNumber" id="primaryNumber" value="<?= $clientData['PrimaryNumber'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="secondaryNumber">secondary Number</label>
                                            <input type="tel" class="col-md-6 form-control" name="secondaryNumber" id="secondaryNumber" value="<?= $clientData['SecondaryNumber'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <label for="Latitude">Latitude</label>
                                            <input type="number" class="col-md-6 form-control" name="Latitude" id="Latitude" value="<?= $clientData['Latitude'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Longitude">Longitude</label>
                                            <input type="number" class="col-md-6 form-control" name="Longitude" id="Longitude" value="<?= $clientData['Longitude'] ?>">
                                        </div>

                                        <div class="col-md-6 mt-1">
                                            <button id="getLocationButton" class="btn btn-primary btn-sm " onclick="getLocation()">Get Location</button>
                                        </div>
                                    </div>
                                    <script>
                                        function getLocation() {
                                            if (navigator.geolocation) {
                                                navigator.geolocation.getCurrentPosition(updateLocation);
                                            } else {
                                                alert("Geolocation is not supported by this browser.");
                                            }
                                        }

                                        function updateLocation(position) {
                                            const latitudeInput = document.getElementById('Latitude');
                                            const longitudeInput = document.getElementById('Longitude');

                                            // Update input values with the current location
                                            latitudeInput.value = position.coords.latitude;
                                            longitudeInput.value = position.coords.longitude;
                                        }
                                    </script>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="firstname">Choose Area</label>

                                            <select name="area" id="area" class="form-select">
                                                <option value="" disabled selected>Choose...</option>
                                                <?php
                                                $areas = getData($connect); // Replace with the actual function to get area data
                                                foreach ($areas as $area) {
                                                    echo '<option value="' . $area['AreaID'] . '">' . $area['AreaName'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <p class="currentArea"><?php echo empty($clientData['Area']) ? 'Not Set yet' : 'Current Area: ' . $clientData['Area']; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastname">Choose SubArea</label>
                                            <select id="subArea" name="subArea" class="form-select">
                                                <option value="" selected disabled>Choose...</option>
                                            </select>
                                            <p class="currentSubArea"><?php echo empty($clientData['SubArea']) ? 'Not Set yet' : 'Current Area: ' . $clientData['SubArea']; ?></p>

                                            <script>
                                                document.getElementById('area').addEventListener('change', function() {
                                                    var areaId = this.value;
                                                    var subAreaSelect = document.getElementById('subArea');

                                                    // Clear existing options
                                                    subAreaSelect.innerHTML = '<option selected>Choose...</option>';

                                                    if (areaId) {

                                                        var formData = new FormData();
                                                        formData.append("areaId", areaId);
                                                        // Fetch sub-areas based on the selected area
                                                        fetch('../controllers/listSubarea_contr.php', {
                                                                method: 'POST',
                                                                body: formData
                                                            })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                if (data.subareas) {
                                                                    // Populate the sub-area dropdown with fetched data
                                                                    data.subareas.forEach(subarea => {
                                                                        console.log("it returns")
                                                                        var option = document.createElement('option');
                                                                        option.value = subarea.SubAreaID;
                                                                        option.textContent = subarea.SubAreaName;
                                                                        subAreaSelect.appendChild(option);
                                                                    });
                                                                }

                                                            })
                                                            .catch(error => {
                                                                console.error('Error fetching sub-areas:', error);
                                                            });
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <p id="editError"></p>


                                    <div class="row mt-3">
                                        <button id="save" class="btn btn-success col-md-4">Save Changes</button>
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












            <!-- modifySubscription page -->
            <div class="page modifySubscription">
                <h4 class="border-bottom pb-3 mb-5">Change Subscription Plan</h4>
                <form id="modifySubscriptionForm">
                    <!-- Existing form elements -->

                    <!-- Choose Plan Section -->
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <label for="firstname">Choose Plan</label>
                            <select name="" id="Plans" class="form-select">
                                <option value="" selected disabled>Choose...</option>
                                <?php
                                $plans = getPlanData($connect);

                                foreach ($plans as $plan) {
                                    $selected = ($plan['PlanID'] == $clientData['PlanID']) ? 'selected' : '';
                                    echo "<option value=\"{$plan['PlanID']}\" {$selected} data-amount=\"{$plan['Price']}\">{$plan['Volume']}</option>";
                                }


                                ?>
                            </select>
                            <p class="currentPlan"><?php echo empty($clientData['Plan']) ? 'Not subscribed yet' : 'Current Plan: ' . $clientData['Plan']; ?></p>
                        </div>
                    </div>

                    <!-- Subscription Options Section -->
                    <div class="form-check form-switch col-md-6">
                        <input class="form-check-input" type="radio" name="subscriptionOption" id="applyNowSwitch">
                        <label class="form-check-label" for="applyNowSwitch">Apply Now</label>
                    </div>

                    <!-- Receipt Section -->
                    <div id="receiptSection" class="mt-5" style="display: none;">
                        <!-- Add your receipt details here -->
                        <div class="row mt-5">
                            <div class="form-group col-md-6">
                                <label for="paymentDate">Payment Date:</label>
                                <input type="date" id="paymentDate" name="paymentDate" value="<?php echo date('Y-m-d'); ?>" disabled required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="saleDate">Payment Amount:</label>
                                <input type="number" id="amountInput" name="amountInput" disabled required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="form-group col-md-6">
                                <label for="PaymentOption">Payment Method:</label>
                                <select class="form-select" name="PaymentOption" id="PaymentOption">
                                    <option value="" selected disabled>Choose</option>
                                    <?php
                                    $methods = getPaymentMethods($connect);

                                    foreach ($methods as $method) {
                                        $selected = ''; // Adjust this based on your logic for selecting a default method
                                        echo '<option value="' . $method['PaymentOptionID'] . '" ' . $selected . ' data-method-id="' . $method['PaymentOptionName'] . '">' . $method['PaymentOptionName'] . '</option>';
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="paymentStatus">Payment Status:</label>
                                <select class="form-select" name="" id="paymentStatus">
                                    <option selected disabled value="">Choose</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="form-group col-md-6">
                                <label for="paymentReference">payment Reference:</label>

                                <?php
                                // Generate a unique payment reference
                                $paymentReference = '#MJRLNK' . time();

                                ?>
                                <input type="text" class="form-control" id="paymentReference" aria-label="Payment Reference" value="<?php echo $paymentReference; ?>" readonly disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Total">Total:</label>
                                <input type="text" id="Total" name="Total" disabled value="">
                            </div>
                        </div>

                        <p id="suberror"></p>

                        <div class="row mt-4">
                            <button type="submit" id="updateSubscriptionBtn" class="btn btn-success col-md-4" onclick="updateSubscriptionPlan(event)">Save Changes</button>
                        </div>



                    </div>

                </form>
            </div>

            <!-- Existing HTML code -->

            <script>
                const applyNowSwitch = document.getElementById('applyNowSwitch');
                const receiptSection = document.getElementById('receiptSection');


                applyNowSwitch.addEventListener('change', function() {
                    // Show or hide the receipt section based on the radio button state
                    receiptSection.style.display = applyNowSwitch.checked ? 'block' : 'none';
                    applynextBtn.style.display = applyNowSwitch.checked ? 'none' : 'block';

                });



                $(document).ready(function() {
                    $('#Plans').change(function() {
                        var selectedPlan = $(this).find(':selected');
                        var planAmountInput = $('#amountInput');
                        var totalAmount = $('#Total');

                        if (!isNaN(selectedPlan.val())) {
                            var planPrice = selectedPlan.data('amount');
                            planAmountInput.val(planPrice);
                            totalAmount.val(planPrice);
                        } else {
                            planAmountInput.val(''); // Clear the input if the selected option is not a number
                        }


                    });
                });





                function updateSubscriptionPlan(event) {
                    event.preventDefault();

                    const clientData = <?= json_encode($clientData); ?>;

                    const clientId = clientData["ClientID"];
                    // Get selected plan details
                    var selectedPlan = document.getElementById('Plans');
                    var selectedPlanID = selectedPlan.value;
                    var selectedPlanAmount = selectedPlan.options[selectedPlan.selectedIndex].getAttribute('data-amount');
                    var paymentDate = document.getElementById('paymentDate').value;
                    var paymentMethodID = document.getElementById('PaymentOption').value;
                    var paymentStatus = document.getElementById('paymentStatus').value;
                    var paymentReference = document.getElementById('paymentReference').value;

                    var InstallationFees = null;
                    if (
                        !clientId ||
                        !selectedPlanID ||
                        !selectedPlanAmount ||
                        !paymentDate ||
                        !paymentMethodID ||
                        !paymentStatus ||
                        !paymentReference
                    ) {
                        // Display an error message or handle the empty fields as needed
                        displayMessage("suberror", "Error: All fields must be filled", true);
                        return;
                    } else {
                        loader.style.display = "flex";
                        // All fields are filled, proceed with sending data through Fetch API
                        var formData = new FormData();
                        formData.append("clientId", clientId);
                        formData.append("selectedPlanID", selectedPlanID);
                        formData.append("selectedPlanAmount", selectedPlanAmount);
                        formData.append("paymentDate", paymentDate);
                        formData.append("paymentMethodID", paymentMethodID);
                        formData.append("paymentStatus", paymentStatus);
                        formData.append("InstallationFees", InstallationFees);
                        formData.append("paymentReference", paymentReference);

                        // Perform fetch API request
                        fetch('../controllers/addPayment_contr.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.paymentSuccess) {
                                    // Handle the response from the server
                                    displayMessage("suberror", "Successfuly updated", false);
                                    location.reload();
                                    setTimeout(() => {
                                        loader.style.display = "none";
                                    }, 2000);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }








                }
            </script>








            <!-- makepayment -->























            <!-- Accounting Page -->
            <div class="page accounting">
                <a href="" class="btn btn-primary">View Transactions</a>
                <div class="card mx-auto" style="width: 18rem;">
                    <div class="card-body shadow-sm p-3 mb-5 rounded text-center">
                        <h5 class="card-title">0.Kshs</h5>
                        <p class="card-text text-center">Account Balance</p>
                        <a href="#" class="btn btn-primary">Refund</a>
                    </div>
                </div>

                <div class="pb-2 mb-4 border-bottom">
                    <h4>Account Summary</h4>
                </div>

                <div class="text-end">
                    <h4 class="text-dark border-bottom">Total Deposit: <span class="text-primary">$ 20,000</span></h4>
                    <h4 class=" text-dark border-bottom">Total Withdrawals: <span class="text-danger">$ 2,000</span></h4>
                    <h4 class=" text-dark border-bottom">Total Expenses: <span class="text-warning">$ 600</span></h4>
                    <h3 class="pt-2 text-dark border-end">Total: <span class="text-success">$22,000</span></h3>
                </div>
            </div>



            <!-- Invoices Page -->
            <div class="page invoice">

                <h4 class="pb-2 mb-5 border-bottom">Major Nganga</h4>

                <a href="" class="btn btn-primary pb-2 mb-5 border-bottom">New Invoice</a>

                <table class="table pt-2 mt-5 border-bottom">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Invoice Date</th>
                            <th scope="col">Payment Status</th>

                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>Paid</td>

                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            <td>Paid</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                            <td>Paid</td>
                        </tr>
                    </tbody>
                </table>
            </div>





            <!-- message Page -->
            <div class="page message">

            </div>
            </div>




            <?php require_once "footer.php"; ?>



            <script>
                // Get all tabs and tab content
                let tabs = document.querySelectorAll(".tabs button");
                let tabContents = document.querySelectorAll(".tab-content .page");

                // Add click event listeners to tabs
                tabs.forEach((tab, index) => {
                    tab.addEventListener("click", () => {
                        // Hide all tab contents
                        tabContents.forEach((content) => {
                            content.classList.remove("active");
                        });
                        // Deactivate all tabs
                        tabs.forEach((tab) => {
                            tab.classList.remove("active");
                        });
                        // Show the clicked tab content and activate the tab
                        tabContents[index].classList.add("active");
                        tabs[index].classList.add("active");
                    });
                });






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
                    //check if changed
                    firstnameInput.addEventListener('input', markFormAsChanged);
                    lastnameInput.addEventListener('input', markFormAsChanged);
                    PrimaryEmailInput.addEventListener('input', markFormAsChanged);
                    SecondaryEmailInput.addEventListener('input', markFormAsChanged);
                    primaryNumberInput.addEventListener('input', markFormAsChanged);
                    secondaryNumberInput.addEventListener('input', markFormAsChanged);

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
                                            displayMessage("editError", "Updated Successfully", false);
                                            location.reload();
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        console.log("ERROR")
                                    });
                            } else {
                                // No changes, display error message or take appropriate action
                                console.log('No changes made.');
                                displayMessage("editError", "No Changes Made", true);
                            }
                        } else {
                            // No changes, display error message or take appropriate action
                            console.log('No changes made.');
                            displayMessage("editError", "No Changes Made", true);
                        }
                    }
                });


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
            </script>
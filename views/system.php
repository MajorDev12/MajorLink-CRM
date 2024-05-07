<?php
require_once "../controllers/session_Config.php";
?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addAdmin_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$companyData = get_Settings($connect);
// JSON data
$countries = json_decode(file_get_contents("../assets/countryData.json"), true);
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


    #card select {
        background-color: var(--light);
        color: var(--dark);
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
                        <a class="active" href="choosePayment.php">System Settings</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">

                <div class="page editClient">
                    <h4>Edit System Settings</h4>
                    <?php if (isset($companyData)) : ?>
                        <div id="card" class="card usercard mb-3 shadow-sm p-3 mb-5 rounded" style="max-width: 900px;">
                            <div class="row g-0">

                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="row mb-5">
                                            <div class="col-md-6">
                                                <label for="Country">Country ( <?= $companyData[0]["Country"] ?> )</label>
                                                <select class="form-select" id="countrySelect">
                                                    <option value="">Choose</option>
                                                    <?php foreach ($countries as $country) : ?>
                                                        <option value="<?= $country['name'] ?>"><?= $country['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="Timezone">Timezone ( <?= $companyData[0]["TimeZone"] ?> )</label>
                                                <select class="form-select" id="timezoneSelect">
                                                    <option value="">Choose</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-md-6">
                                                <label for="PrimaryEmail">Phone Code ( <?= $companyData[0]["PhoneCode"] ?> )</label>
                                                <select class="form-select" id="phoneCodeSelect">
                                                    <option value="">Choose</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="primaryNumber">Currency ( <?= $companyData[0]["CurrencyName"] ?> )</label>
                                                <select class="form-select" id="currencySelect">
                                                    <option value="">Choose</option>
                                                </select>
                                            </div>
                                        </div>

                                        <p id="editError"></p>

                                        <div class="row mt-3">
                                            <button id="updateProfileBtn" class="btn btn-success col-md-4">Save Changes</button>
                                        </div>
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
            const countries = <?= json_encode($countries) ?>;
            var initialCountry = <?= json_encode($companyData[0]["Country"]); ?>;
            var SettingID = <?= json_encode($companyData[0]["SettingID"]); ?>;

            const countrySelect = document.getElementById('countrySelect');
            const timezoneSelect = document.getElementById('timezoneSelect');
            const phoneCodeSelect = document.getElementById('phoneCodeSelect');
            const currencySelect = document.getElementById('currencySelect');

            countrySelect.addEventListener('change', (event) => {
                const selectedCountry = countries.find(country => country.name === event.target.value);

                timezoneSelect.innerHTML = `<option value="">Choose</option>`;
                phoneCodeSelect.innerHTML = `<option value="">Choose</option>`;
                currencySelect.innerHTML = `<option value="" data-code="" data-symbol="">Choose</option>`;

                if (selectedCountry) {
                    selectedCountry.timezones.forEach(timezone => {
                        timezoneSelect.innerHTML += `<option value="${timezone}">${timezone}</option>`;
                    });

                    selectedCountry.phoneCode.forEach(phoneCode => {
                        phoneCodeSelect.innerHTML += `<option value="${phoneCode}">${phoneCode}</option>`;
                    });

                    for (const [currencyCode, currency] of Object.entries(selectedCountry.currencies)) {
                        currencySelect.innerHTML += `<option value="${currency.name}" data-code="${currency.code}" data-symbol="${currency.symbol}">${currency.name}  -  ${currency.symbol}</option>`;
                    }
                }
            });




            var updateProfileBtn = document.getElementById("updateProfileBtn");


            updateProfileBtn.addEventListener("click", function() {
                var countrySelect = document.getElementById("countrySelect").value;
                var timezoneSelect = document.getElementById("timezoneSelect").value;
                var phoneCodeSelect = document.getElementById("phoneCodeSelect").value;
                var currencySelect = document.getElementById("currencySelect").value;

                const currency = document.getElementById('currencySelect');
                const selectedOption = currency.options[currency.selectedIndex];

                const currencyCode = selectedOption.getAttribute('data-code');
                const currencySymbol = selectedOption.getAttribute('data-symbol');

                if (countrySelect === '' || timezoneSelect === '' || phoneCodeSelect === '' || currencySelect === '') {
                    displayMessage("editError", "All fields must be filled", true);
                    return;
                }


                // Compare input values with initial values
                if (countrySelect != initialCountry) {

                    document.querySelector("#loader").style.display = 'block';


                    var formData = new FormData();
                    formData.append("SettingID", SettingID);
                    formData.append("countrySelect", countrySelect);
                    formData.append("timezoneSelect", timezoneSelect);
                    formData.append("phoneCodeSelect", phoneCodeSelect);
                    formData.append("currencySelect", currencySelect);
                    formData.append("currencyCode", currencyCode);
                    formData.append("currencySymbol", currencySymbol);

                    // Fetch API to send data to updateUserProfilePic_mod.php
                    fetch("../controllers/updateSystemSettings_contr.php", {
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
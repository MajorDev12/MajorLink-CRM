<style>
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
        z-index: 10;
    }

    .upload .rightRound .camera {
        z-index: 0;
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
                                localStorage.setItem('updateClientProfileToast', 'true');
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
                                <label for="Address">Address</label>
                                <input type="text" class="form-control" name="Address" id="Address" value="<?= $clientData['Address'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="City">City</label>
                                <input type="text" class="form-control" name="City" id="City" value="<?= $clientData['City'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Country">Country</label>
                                <input type="text" class="form-control" name="Country" id="Country" value="<?= $clientData['Country'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="zipcode">zipcode</label>
                                <input type="number" class="form-control" name="zipcode" id="zipcode" value="<?= $clientData['Zipcode'] ?>">
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
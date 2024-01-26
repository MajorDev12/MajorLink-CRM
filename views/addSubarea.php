<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addSubarea_contr.php';
require_once  '../modals/addSubarea_mod.php';
require  '../modals/getSubarea_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

?>

<?php require_once "header.php"; ?>


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
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Add Sub Area</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>
        <div id="overlay"></div>
        <!-- content-container -->
        <div class="main-content">



            <div class="content">
                <!-- Modal -->

                <div class="modal-container" id="areaModal">
                    <div class="modal-background"></div>
                    <div class="modal-header">
                        <h5 class="fs-5" id="areaModalLabel">Modify Sub Area</h5>
                        <button type="button" class="close-btn" id="closeModal" aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <p id="modalAreaName" name="modalAreaName"></p>
                        <input type="hidden" id="modalAreaId" name="modalAreaId" value="">

                        <p id="modalmessage"></p>
                        <div id="loader">Loading...</div>
                        <input type="text" id="updatedName" name="updatedName" class="form-control updatedmodalAreaName">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update" class=" btn btn-primary">Save Changes</button>
                        <button type="button" id="delete" class=" btn btn-danger">Delete</button>
                    </div>
                </div>




                <form id="subareaForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="areaSelect">
                                <option selected disabled>Choose Area...</option>
                                <?php
                                $areas = getData($connect);
                                foreach ($areas as $area) {
                                    echo '<option id="areaName" class="area" aria-current="true" data-area-name="' . $area['AreaName'] . '" data-area-id="' . $area['AreaID'] . '">' . $area['AreaName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="subAreaInput" placeholder="Enter Sub Area">
                            <p id="message"></p>
                            <button type="button" id="addSubareabtn" class="btn btn-primary mt-4" onclick="addSubArea()">Add</button>
                        </div>
                    </div>
                </form>

                <div class="col-md-12 mt-5">
                    <div class="list-group" id="subAreaList">
                        <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                            Available Sub-Area
                        </button>
                        <?php
                        // $areas = getSubareasByAreaId($connect, $areaId);
                        // if ($areas) {
                        //     foreach ($areas as $area) {
                        //         echo '<button type="button" id="areaName" class="list-group-item list-group-item-action area" aria-current="true" data-subarea-name="' . $area['SubAreaName'] . '" data-subarea-id="' . $area['SubAreaID'] . '">' . $area['SubAreaName'] . '</button>';
                        //     }
                        // } else {
                        //     echo "choose area";
                        // }
                        ?>
                    </div>
                </div>
            </div>
            <?php require_once "footer.php"; ?>


            <script>
                var areaButtons = document.querySelectorAll('.subareas');
                var modalAreaId = document.getElementById('modalAreaId');
                var modalAreaName = document.getElementById('modalAreaName');
                var closeModal = document.getElementById('closeModal');
                var modal = document.getElementById('areaModal');

                // Variable to store the current areaId
                var currentAreaId = null;

                areaButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        areaName = button.getAttribute('data-subarea-name');
                        currentAreaId = button.getAttribute('data-subarea-id');
                        modalAreaName.innerText = currentAreaId;
                        console.log("Current Area ID: " + currentAreaId);
                        // Add a callback function to refresh the page when the modal is hidden
                        // showModal();
                    });
                });



                //populate areas in select
                function updateSubAreaList(subareas) {
                    var subAreaListContainer = document.getElementById('subAreaList');

                    subAreaListContainer.innerHTML = '';
                    subAreaListContainer.innerHTML += '<button type="button" class="list-group-item list-group-item-action active" aria-current="true">Available Sub-Areas</button>';


                    subareas.forEach(function(subarea) {
                        var subAreaButton = document.createElement('button');
                        subAreaButton.type = 'button';
                        subAreaButton.className = 'list-group-item list-group-item-action subareas';
                        subAreaButton.setAttribute('data-subarea-id', subarea.SubAreaID);
                        subAreaButton.setAttribute('data-subarea-name', subarea.SubAreaName);
                        subAreaButton.setAttribute('id', '' + subarea.SubAreaID);
                        subAreaButton.innerText = subarea.SubAreaName;

                        subAreaButton.addEventListener('click', function() {
                            var subAreaID = this.getAttribute('data-subarea-id');
                            var subAreaName = this.getAttribute('data-subarea-name');
                            var subAreaid = this.getAttribute('id');
                            // modalAreaName.innerText = subAreaName + '   areaid:' + subAreaid;
                            // console.log(subAreaButton);
                            showModal();
                        });

                        subAreaListContainer.appendChild(subAreaButton);
                    });
                }


                // Function to fetch and populate areas in select
                function fetchDataAndUpdate() {
                    var areaSelect = document.getElementById('areaSelect');
                    var subAreaInput = document.getElementById('subAreaInput');
                    var subAreaList = document.getElementById('subAreaList');
                    var message = document.getElementById('message');

                    var selectedOption = areaSelect.options[areaSelect.selectedIndex];
                    var areaId = selectedOption.getAttribute('data-area-id');
                    var subArea = subAreaInput.value.trim();


                    var sendData = new FormData();
                    sendData.append('areaId', areaId);

                    document.getElementById('loader').style.display = 'flex';

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../controllers/listSubarea_contr.php', true);

                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            try {
                                document.getElementById('loader').style.display = 'none';
                                var responseData = JSON.parse(xhr.responseText);

                                if (responseData.success) {
                                    updateSubAreaList(responseData.subareas);
                                } else {
                                    console.log("Error: " + responseData.error);
                                }
                            } catch (error) {
                                console.error('Error parsing JSON response:', error);
                            }
                        } else {
                            console.error('Request failed with status: ' + xhr.status);
                        }
                    };

                    xhr.send(sendData);
                }

                // Add an event listener to the areaSelect dropdown to update the list when an option is selected
                document.getElementById('areaSelect').addEventListener('change', fetchDataAndUpdate);




                function addSubArea() {
                    var areaSelect = document.getElementById('areaSelect');
                    var subAreaInput = document.getElementById('subAreaInput');
                    var subAreaList = document.getElementById('subAreaList');
                    var message = document.getElementById('message');
                    var addSubareabtn = document.getElementById('addSubareabtn');

                    addSubareabtn.disabled = true;
                    var selectedOption = areaSelect.options[areaSelect.selectedIndex];
                    var areaId = selectedOption.getAttribute('data-area-id');
                    var subArea = subAreaInput.value.trim();

                    if (!subArea) {
                        displayMessage("message", "Cannot be empty", true);
                        addSubareabtn.disabled = false;
                        return;
                    }

                    // Check if updatedAreaName contains only letters and numbers
                    if (!/^[a-zA-Z0-9 ]+$/.test(subArea)) {
                        displayMessage("message", "Only letters and numbers allowed", true);
                        addSubareabtn.disabled = false;
                        return;
                    }


                    if (areaId !== null) {
                        var sendData = new FormData();
                        sendData.append('areaId', areaId);
                        sendData.append('subArea', subArea);

                        document.getElementById('loader').style.display = 'flex';

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '../controllers/addSubarea_contr.php', true);

                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 400) {
                                try {
                                    document.getElementById('loader').style.display = 'none';
                                    var responseData = JSON.parse(xhr.responseText);

                                    if (responseData.success) {
                                        updateSubAreaList(responseData.subareas);
                                        displayMessage("message", "Saved Successfuly", false);
                                        document.getElementById('subareaForm').reset();
                                        addSubareabtn.disabled = false;
                                    } else {
                                        console.log("Error: " + responseData.error);
                                        displayMessage("message", "Something went wrong", true);
                                        addSubareabtn.disabled = false;
                                    }
                                } catch (error) {
                                    console.error('Error parsing JSON response:', error);
                                    displayMessage("message", "Network Error", true);
                                    addSubareabtn.disabled = false;
                                }
                            } else {
                                console.error('Request failed with status: ' + xhr.status);
                                displayMessage("message", `Request failed with status: ${xhr.status}`, true);
                                addSubareabtn.disabled = false;
                            }
                        };

                        xhr.send(sendData);
                    } else {
                        displayMessage("message", "Choose an area First", true);
                        document.getElementById('subareaForm').reset();
                        addSubareabtn.disabled = false;
                    }
                }





                closeModal.addEventListener('click', function() {
                    hideModal();
                })


                var updateButton = document.getElementById('update');

                updateButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("id " + currentAreaId);
                    console.log("name " + areaName.value);

                });




















                var deleteButton = document.getElementById('delete');

                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Check if a valid areaId is available
                    if (currentAreaId !== null) {

                        loader.style.display = "flex";
                        var data = new FormData();
                        data.append('currentAreaId', currentAreaId);

                        // Make an AJAX request using Fetch API
                        fetch('../controllers/deleteArea_contr.php', {
                                method: 'POST',
                                body: data
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // If the update is successful, handle accordingly
                                    displayMessage("modalmessage", "Update successful", false);
                                    hideModal();
                                    location.reload();
                                    // Optionally, you can perform additional actions here
                                } else {
                                    // If the update fails, display an error message
                                    displayMessage("modalmessage", "deletion failed", true);
                                }
                            })
                            .catch(error => {
                                // Handle any network or request errors
                                displayMessage("modalmessage", "An error occurred", true);
                            })
                            .finally(() => {
                                // Hide the loader regardless of the outcome
                                loader.style.display = "none";
                            });
                    } else {
                        displayMessage("modalmessage", "Invalid AreaId", true);
                    }
                });











                function displayMessage(messageElement, message, isError, ) {
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
                    }, 1000);
                }


                // Show modal and overlay
                function showModal() {
                    document.getElementById('areaModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('overlay').style.transition = '.3s';
                }

                // Hide modal and overlay
                function hideModal() {
                    document.getElementById('areaModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }
            </script>
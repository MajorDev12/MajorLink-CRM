<?php require_once "../controllers/session_Config.php"; ?>

<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addarea_contr.php';
require_once  '../modals/addArea_mod.php';

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
        <h1>hi, <?php echo $_SESSION['Username']; ?></h1>
        <ul class="breadcrumb">
          <li>
            <a href="index.php">Dashboard</a>
          </li>
          <li><i class='bx bx-chevron-right'></i></li>
          <li>
            <a class="active" href="">addArea</a>
          </li>
        </ul>
      </div>

      <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
      </a>
    </div>

    <!-- content-container -->
    <div class="main-content">
      <div id="loader">
        <div class="spinner-grow text-Primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>


      <div class="content">


        <!-- action="../controllers/addArea_contr.php" method="post" -->
        <form class="g-3" id="areaForm">

          <!-- Modal -->
          <div class="overlay" id="overlay"></div>
          <div class="modal-container" id="areaModal">
            <div class="modal-background"></div>
            <div class="modal-header">
              <h5 class="fs-5" id="areaModalLabel">Modify Area</h5>
              <button type="button" class="close-btn" id="closeModal" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
              <p id="modalAreaName" name="modalAreaName"></p>
              <!-- <div id="error" class="text-danger"></div> -->
              <div class="loader1">Loading...</div>
              <p id="modalerror"></p>
              <input type="text" id="updatedName" name="updatedName" class="form-control updatedmodalAreaName">
            </div>
            <div class="modal-footer">
              <button type="button" id="update" class="btn btn-primary">Save Changes</button>
              <button type="button" id="delete" class="btn btn-danger">Delete</button>
            </div>
          </div>





          <div class="row">

            <div class="col-6">
              <label for="area" class="form-label">Add Area</label>

              <?php
              if (isset($_GET['error'])) {
                $errorMessage = '';

                if ($_GET['error'] === 'emptyInput') {
                  $errorMessage = 'Please enter a valid Area Name.';
                } elseif ($_GET['error'] === 'success') {
                  $successMessage = 'Area saved successfully.';
                }
              }

              ?>
              <?php if (isset($errorMessage)) : ?>
                <p class="alert-danger"><?= $errorMessage ?></p>
              <?php elseif (isset($successMessage)) : ?>
                <div class="alert-success"><?= $successMessage ?></div>
              <?php endif; ?>
              <div id="error"></div>
              <div id="success" class="text-success"></div>
              <input type="text" class="form-control form_data" id="areaInput" name="areaInput">
              <button type="submit" id="addbtn" name="addbtn" class="btn btn-primary mt-4">Add</button>
            </div>
            <div class="col-md-6 mt-5">
              <div class="list-group" id="areaList">

                <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                  The Locations Available
                </button>
                <?php
                $areas = getData($connect);
                foreach ($areas as $area) {
                  echo '<button type="button" id="areaName" class="list-group-item list-group-item-action area" aria-current="true" data-area-name="' . $area['AreaName'] . '" data-area-id="' . $area['AreaID'] . '">' . $area['AreaName'] . '</button>';
                }
                ?>
              </div>

            </div>
          </div>



        </form>
      </div>

      <?php require_once "footer.php"; ?>


      <script>
        var successmsg = document.getElementById("success");
        var areaInput = document.getElementById("areaInput");
        var addbtn = document.getElementById("addbtn");
        var loader = document.getElementById("loader");






        addbtn.addEventListener("click", function(e) {
          e.preventDefault();

          if (!areaInput.value.trim()) {
            displayMessage("error", "Cannot be empty", true);
            return;
          }

          if (!/^[a-zA-Z0-9 ]+$/.test(areaInput.value)) {
            displayMessage("error", "Only letters and numbers allowed", true);
            return;
          }

          // Show loader while waiting for the server response
          loader.style.display = "flex";


          // Create a FormData object and append the data
          var formData = new FormData();
          formData.append("areaInput", areaInput.value);

          var xhr = new XMLHttpRequest();

          xhr.open("POST", "../controllers/addArea_contr.php", true);

          xhr.send(formData);

          xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
              // document.getElementById('submit').disabled = false;
              loader.style.display = "none";

              var response = JSON.parse(xhr.responseText);

              if (response.success) {
                document.getElementById('areaForm').reset();
                displayMessage("error", "Saved Successfuly", false);
                setTimeout(() => {
                  location.reload();
                }, 1000);
              } else {
                //display validation error
                displayMessage("error", "network error", true);
              }
            }
          }
        })









        var areaButtons = document.querySelectorAll('.area');
        var modalAreaId = document.getElementById('modalAreaId');
        var modalAreaName = document.getElementById('modalAreaName');
        var closeModal = document.getElementById('closeModal');
        var modal = document.getElementById('areaModal');

        // Variable to store the current areaId
        var currentAreaId = null;

        areaButtons.forEach(function(button) {
          button.addEventListener('click', function() {
            areaName = button.getAttribute('data-area-name');
            currentAreaId = button.getAttribute('data-area-id');
            modalAreaName.innerText = areaName;
            // Add a callback function to refresh the page when the modal is hidden
            showModal();
          });
        });


        closeModal.addEventListener('click', function() {
          hideModal();
        })








        var updateButton = document.getElementById('update');

        updateButton.addEventListener('click', function(e) {
          e.preventDefault();

          var updatedAreaName = document.getElementById('updatedName');

          if (!updatedAreaName.value.trim()) {
            displayMessage("modalerror", "Cannot be empty", true);
            return;
          }

          // Check if updatedAreaName contains only letters and numbers
          if (!/^[a-zA-Z0-9 ]+$/.test(updatedAreaName.value)) {
            displayMessage("modalerror", "Only letters and numbers allowed", true);
            return;
          }

          // Check if a valid areaId is available
          if (currentAreaId !== null) {

            loader.style.display = "flex";
            var data = new FormData();
            data.append('currentAreaId', currentAreaId);
            data.append('updatedAreaName', updatedAreaName.value);

            // Make an AJAX request using Fetch API
            fetch('../controllers/updateArea_contr.php', {
                method: 'POST',
                body: data
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  // If the update is successful, handle accordingly
                  displayMessage("modalerror", "Update successful", false);
                  hideModal();
                  location.reload();
                  // Optionally, you can perform additional actions here
                } else {
                  // If the update fails, display an error message
                  displayMessage("modalerror", data.error, true);
                }
              })
              .catch(error => {
                // Handle any network or request errors
                console.error('Error:', error);
                displayMessage("modalerror", "An error occurred", true);
              })
              .finally(() => {
                // Hide the loader regardless of the outcome
                loader.style.display = "none";
              });
          } else {
            displayMessage("modalerror", "Invalid AreaId", true);
          }
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
                  displayMessage("modalerror", "Update successful", false);
                  hideModal();
                  location.reload();
                  // Optionally, you can perform additional actions here
                } else {
                  // If the update fails, display an error message
                  displayMessage("modalerror", "deletion failed", true);
                }
              })
              .catch(error => {
                // Handle any network or request errors
                displayMessage("modalerror", "An error occurred", true);
              })
              .finally(() => {
                // Hide the loader regardless of the outcome
                loader.style.display = "none";
              });
          } else {
            displayMessage("modalerror", "Invalid AreaId", true);
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






















      <!-- <script>
        function save_data() {
          var form_element = document.getElementById('areaForm');
          var formData = new FormData(form_element);
          var areaValue = formData.get('area');

          // Create a new FormData object and append only the 'area' field
          var sendData = new FormData();
          sendData.append('area', areaValue);

          document.getElementById('addbtn').disabled = true;
          document.querySelector('.loader').style.display = 'block';

          var ajax_request = new XMLHttpRequest();

          ajax_request.open('POST', '../controllers/addArea_contr.php');

          // Send the new FormData object containing only the 'area' field
          ajax_request.send(sendData);

          ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
              document.getElementById('addbtn').disabled = false;
              document.querySelector('.loader').style.display = 'none';

              var response = JSON.parse(ajax_request.responseText);

              if (response.success != '') {
                document.getElementById('areaForm').reset();

                document.getElementById('message').innerHTML = response.success;

                // Update the area list dynamically
                updateAreaList(response.areas);

                setTimeout(function() {
                  document.getElementById('message').innerHTML = '';
                  location.reload();
                }, 2000);

              } else {
                // display validation error
              }
            }
          }
        }




        // Modify your updateAreaList function to accept the updated areas
        function updateAreaList(areas) {
          var areaListContainer = document.getElementById('areaList');

          areaListContainer.innerHTML = ''; // Clear the existing list
          areaListContainer.innerHTML += '<button type="button" class="list-group-item list-group-item-action active" aria-current="true">The Locations Available</button>';

          // Append each area to the area list
          areas.forEach(area => {
            areaListContainer.innerHTML += '<button type="button" class="list-group-item list-group-item-action area" aria-current="true">' + area.AreaName + '</button>';
          });
          // Close the modal after updating
          hideModal();
        }




        var areaButtons = document.querySelectorAll('.area');
        var updateButton = document.getElementById('update');
        var deleteButton = document.getElementById('delete');
        var errorMsg = document.getElementById('error');
        var modalAreaId = document.getElementById('modalAreaId');
        var modalAreaName = document.getElementById('modalAreaName');
        var closeModal = document.getElementById('closeModal');
        var modal = document.getElementById('areaModal');

        // Variable to store the current areaId
        var currentAreaId = null;

        areaButtons.forEach(function(button) {
          button.addEventListener('click', function() {
            areaName = button.getAttribute('data-area-name');
            currentAreaId = button.getAttribute('data-area-id');
            modalAreaName.innerText = areaName;
            // Add a callback function to refresh the page when the modal is hidden
            showModal();
          });
        });


        closeModal.addEventListener('click', function() {
          hideModal();
        })









        updateButton.addEventListener('click', function() {
          var updatedAreaName = document.getElementById('updatedName');

          if (!updatedAreaName.value.trim()) {
            errorMsg.innerText = "cannot be empty";
            setTimeout(() => {
              errorMsg.innerText = "";
            }, 2000);
            return;
          }

          // Check if a valid areaId is available
          if (currentAreaId !== null) {
            // Create a new FormData object and append the data
            var sendData = new FormData();
            sendData.append('currentAreaId', currentAreaId);
            sendData.append('updatedName', updatedAreaName.value);

            document.querySelector('.loader1').style.display = 'block';

            // Create a new XMLHttpRequest object
            var ajax_request = new XMLHttpRequest();

            // Set up the request with method, URL, and asynchronous flag
            ajax_request.open('POST', '../controllers/updateArea_contr.php', true);

            // Set up the callback function for when the request completes
            ajax_request.onload = function() {
              if (ajax_request.status >= 200 && ajax_request.status < 400) {
                // Parse the response as JSON
                try {
                  document.querySelector('.loader1').style.display = 'none';
                  var responseData = JSON.parse(ajax_request.responseText);
                  if (responseData.success) {
                    // If the update is successful, refresh the area list
                    updateAreaList(responseData.areas);
                    location.reload();
                  } else {
                    // If the delete fails, display the error message
                    displayError(responseData.error);
                    // location.reload();
                  }
                } catch (error) {
                  console.error('Error parsing JSON response:', error);
                }

              } else {
                // Handle errors
                console.error('Request failed with status: ' + ajax_request.status);
              }
            };

            // Send the request with the FormData
            ajax_request.send(sendData);

            // Reset the currentAreaId after the update is complete
            currentAreaId = null;
          } else {
            displayError('Invalid AreaId');
          }
        });








        deleteButton.addEventListener('click', function() {
          // Check if a valid areaId is available
          if (currentAreaId !== null) {
            // Create a new FormData object and append the data
            var sendData = new FormData();
            sendData.append('currentAreaId', currentAreaId);

            document.querySelector('.loader1').style.display = 'block';
            // Create a new XMLHttpRequest object
            var ajax_request = new XMLHttpRequest();

            // Set up the request with method, URL, and asynchronous flag
            ajax_request.open('POST', '../controllers/deleteArea_contr.php', true);

            // Set up the callback function for when the request completes
            ajax_request.onload = function() {
              if (ajax_request.status >= 200 && ajax_request.status < 400) {
                // Parse the response as JSON
                try {
                  document.querySelector('.loader1').style.display = 'none';
                  var responseData = JSON.parse(ajax_request.responseText);
                  if (responseData.success) {
                    // If the delete is successful, refresh the area list
                    updateAreaList(responseData.areas);
                    // location.reload();
                  } else {
                    // If the delete fails, display the error message
                    displayError(responseData.error);
                  }
                } catch (error) {
                  console.error('Error parsing JSON response:', error);
                }


              } else {
                // Handle errors
                console.error('Request failed with status: ' + ajax_request.status);
              }
            };

            // Send the request with the FormData
            ajax_request.send(sendData);

            // Reset the currentAreaId after the delete is complete
            currentAreaId = null;
          } else {
            displayError('Invalid AreaId');
          }
        });





        // Function to display error messages
        function displayError(errorMessage) {
          var errorElement = document.getElementById('error');
          errorElement.innerText = errorMessage;
        }


        // Show modal and overlay
        function showModal() {
          document.getElementById('areaModal').style.display = 'block';
          document.getElementById('overlay').style.display = 'block';
        }

        // Hide modal and overlay
        function hideModal() {
          document.getElementById('areaModal').style.display = 'none';
          document.getElementById('overlay').style.display = 'none';
        }
      </script> -->
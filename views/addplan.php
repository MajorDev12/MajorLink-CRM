<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addPlan_contr.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/validate_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$settings = get_Settings($connect);
$code = $settings[0]["CurrencyCode"];
$symbol = $settings[0]["CurrencySymbol"];
?>
<?php require_once "header.php"; ?>

<style>
  p {
    text-transform: capitalize;
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
            <a href="#">Dashboard</a>
          </li>
          <li><i class='bx bx-chevron-right'></i></li>
          <li>
            <a class="active" href="#">Add Plan</a>
          </li>
        </ul>
      </div>


    </div>

    <!-- content-container -->
    <div class="main-content">
      <div id="loader">Loading...</div>


      <!-- Update modal -->
      <div id="overlay"></div>
      <div class="modal-container" id="planModal">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Plan</h5>
            <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="edit-PlanId" value="">
            <label for="editPlanName">Name:</label>
            <input type="text" id="edit-PlanName" class="form-control modalInput">
            <label for="editPlanVolume">Volume:</label>
            <input type="text" id="edit-PlanVolume" class="form-control modalInput">
            <label for="editPlanPrice">Price (<?= $symbol; ?>):</label>
            <input type="number" id="edit-PlanPrice" class="form-control modalInput">
          </div>
          <div class="modal-footer">
            <p id="modalerror"></p>
            <button type="button" class="btn btn-info" data-plan-id="<?= $plan['PlanID'] ?>" onclick="updatePlanData(this)">Save Changes</button>
          </div>
        </div>
      </div>



      <!-- Delete modal -->
      <div class="modal-container" id="deleteModal">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Delete</h5>
            <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <p class="mt-3">Are you sure you want to delete this plan?</p>
            <input type="hidden" id="deletePlanId" value="">
          </div>
          <div class="modal-footer">
            <p id="errordelmodal"></p>
            <button type="button" id="deleteButton" class="btn btn-danger ml-3" onclick="deletePlanConfirmed()">Delete</button>
          </div>
        </div>

      </div>







      <div class="content">
        <form id="addPlanform" class="row g-3">
          <div class="col-md-5 me-5">
            <label for="inputText" class="form-label">Add Name</label>
            <input type="text" class="form-control forminput" id="planName">
            <label for="inputText" class="form-label mt-4">Add Volume</label>
            <input type="text" class="form-control forminput" id="planVolume">
            <label for="inputEmail4" class="form-label  mt-4">Add Price (<?= $symbol; ?>)</label>
            <input type="number" class="form-control forminput" id="planPrice">
            <p id="error"></p>
            <button type="button" id="addbtn" onclick="addplan(event); return false;" class="btn btn-primary mt-4">Add</button>
          </div>


          <div class="col-md-6">
            <table>
              <thead class="">
                <tr class="">
                  <th scope="col">No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Volume</th>
                  <th scope="col">Price (<?= $symbol; ?>)</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="">
                <?php $plans = getPlanData($connect);
                ?>
                <?php foreach ($plans as $key => $plan) : ?>
                  <tr>
                    <th scope="row"><?= $key + 1 ?></th>
                    <td><?= $plan['Name'] ?></td>
                    <td><?= $plan['Volume'] ?></td>
                    <td><?= $plan['Price'] ?></td>
                    <td>
                      <button type="button" class="btn btn-info" data-plan-id="<?= $plan['PlanID'] ?>" onclick="editPlan('<?= $plan['PlanID'] ?>', '<?= $plan['Name'] ?>', '<?= $plan['Volume'] ?>', '<?= $plan['Price'] ?>')">Update</button>
                      <!-- <button type="button" class="btn btn-danger" data-plan-id="<?= $plan['PlanID'] ?>" onclick="confirmDelete('<?= $plan['PlanID'] ?>')">Del</button> -->
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <?php require_once "footer.php"; ?>

      <script type="text/javascript">
        var loader = document.getElementById("loader");

        var closeModal = document.getElementById("closeModal");

        function addplan(event) {
          event.preventDefault();

          document.getElementById("addbtn").disabled = true;
          var planName = document.getElementById("planName").value.trim();
          var planVolume = document.getElementById("planVolume").value.trim();
          var planPrice = document.getElementById("planPrice").value.trim();
          var isValid = true;
          // displayMessage("error", "Cannot be empty", true);
          if (!planName || !planVolume || !planPrice) {
            displayMessage("error", "Cannot be empty", true);
            isValid = false;
            document.getElementById("addbtn").disabled = false;
            return;
          }
          if (!/^[a-zA-Z0-9\s]+$/.test(planName)) {
            displayMessage("error", "Invalid characters in Name", true);
            isValid = false;
            document.getElementById("addbtn").disabled = false;
            return;
          }

          if (!/^[a-zA-Z0-9\s]+$/.test(planVolume)) {
            displayMessage("error", "Invalid characters in Volume", true);
            isValid = false;
            document.getElementById("addbtn").disabled = false;
            return;
          }

          if (!/^\d+$/.test(planPrice)) {
            displayMessage("error", "Price must be a number", true);
            isValid = false;
            document.getElementById("addbtn").disabled = false;
            return;
          }

          if (isValid) {
            // e.stopImmediatePropagation();
            loader.style.display = "flex";
            // Create a FormData object to send data via Fetch API
            var formData = new FormData();
            formData.append("planName", planName);
            formData.append("planVolume", planVolume);
            formData.append("planPrice", planPrice);

            // Use Fetch API to send the data
            fetch('../controllers/addPlan_contr.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  document.getElementById("addbtn").disabled = false;
                  loader.style.display = "none";
                  displayMessage("error", "saved Successfuly", false);
                  document.getElementById("addPlanform").reset();
                  location.reload();
                } else {
                  displayMessage("error", "Error fetching Data", true);
                }
              })
              .catch(error => {
                displayMessage("error", "Network Error", true);
                document.getElementById("addbtn").disabled = false;
              })
          }
        }






        function editPlan(planId, planName, planVolume, planPrice) {
          document.getElementById('edit-PlanId').value = planId;
          document.getElementById('edit-PlanName').value = initialPlanName = planName;
          document.getElementById('edit-PlanVolume').value = initialPlanVolume = planVolume;
          document.getElementById('edit-PlanPrice').value = initialPlanPrice = planPrice;
          // Show the modal (you need to implement your own showModal function)
          showModal();
        }




        var initialPlanName = "";
        var initialPlanVolume = "";
        var initialPlanPrice = "";

        // Function to check for changes and send data for update
        function updatePlanData(button) {
          var planId = document.getElementById('edit-PlanId').value;
          var updatedPlanName = document.getElementById('edit-PlanName').value;
          var updatedPlanVolume = document.getElementById('edit-PlanVolume').value;
          var updatedPlanPrice = document.getElementById('edit-PlanPrice').value;
          var modalInputs = document.querySelectorAll('.modalInput');
          var isValid = true;

          modalInputs.forEach(dataInput => {
            if (!dataInput.value.trim()) {
              displayMessage("modalerror", `Cannot be empty ${dataInput.id}`, true);
              isValid = false;
              return;
            } else if (!/^[a-zA-Z0-9\s]+$/.test(dataInput.value.trim())) {
              displayMessage("modalerror", `Only letters and numbers allowed in: ${dataInput.id}`, true);
              isValid = false;
              return;
            }
          });
          if (!isValid) {
            return; // Exit the function if validation fails
          }

          // Check if changes are made
          if (
            updatedPlanName !== initialPlanName ||
            updatedPlanVolume !== initialPlanVolume ||
            updatedPlanPrice !== initialPlanPrice
          ) {
            loader.style.display = "flex";
            // Changes detected, send data for update
            // Perform your AJAX request here
            // You can use the Fetch API for this purpose
            var sendData = new FormData();
            sendData.append('planId', planId);
            sendData.append('updatedPlanName', updatedPlanName);
            sendData.append('updatedPlanVolume', updatedPlanVolume);
            sendData.append('updatedPlanPrice', updatedPlanPrice);

            fetch('../controllers/updatePlan_contr.php', {
                method: 'POST',
                body: sendData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  location.reload();
                  hideModal();
                  loader.style.display = "none";
                  // displayMessage("modalerror", "Updated Successfuly", false);
                } else {
                  // Handle failure (e.g., display an error message)
                  console.error("Update failed: " + data.error);
                  displayMessage("modalerror", "Update failed: " + data.error, true);
                }
              })
              .catch(error => {
                // Handle network or request errors
                console.error("An error occurred: " + error);
                displayMessage("modalerror", "An error occurred: " + error, true);
                loader.style.display = "none";
                setTimeout(() => {
                  hideModal();
                }, 2000);
              });
          } else {
            // No changes made
            displayMessage("modalerror", "No changes made", true);
          }
        }








        // function confirmDelete(planId) {
        //   // Set the planId to a hidden input in the delete confirmation modal
        //   document.getElementById('deletePlanId').value = planId;
        //   // Show the delete confirmation modal
        //   showDeleteModal();
        // }






        // function deletePlanConfirmed() {
        //   var planId = document.getElementById('deletePlanId').value;
        //   button = document.querySelector("#deleteButton");

        //   console.log(planId);


        //   if (planId !== null) {
        //     loader.style.display = "flex";

        //     var sendData = new FormData();
        //     sendData.append('planId', planId);

        //     fetch('../controllers/deletePlan_contr.php', {
        //         method: 'POST',
        //         body: sendData
        //       })
        //       .then(response => response.json())
        //       .then(data => {
        //         if (data.success) {
        //           hideDeleteModal();
        //           planId = null;
        //           loader.style.display = "none";
        //           displayMessage("errordelmodal", "Deleted Successfuly", false);
        //           location.reload();
        //         } else {
        //           // Handle failure (e.g., display an error message)
        //           console.error("Update failed: " + data.error);
        //           displayMessage("errordelmodal", "Delete failed: " + data.error, true);
        //         }
        //       })
        //       .catch(error => {
        //         // Handle network or request errors
        //         loader.style.display = "none";
        //         document.getElementById("errordelmodal").innerText = "error";
        //         console.error("An error occurred: " + error);
        //         displayMessage("errordelmodal", "An error occurred: " + error, true);
        //         setTimeout(() => {
        //           hideDeleteModal();
        //         }, 2000);
        //       });
        //   } else {
        //     displayMessage("errordelmodal", "NULL", false);
        //   }
        // }










        closeModal.addEventListener('click', function() {
          hideModal();
        })

        // closeDelModal.addEventListener('click', function() {
        //   hideDeleteModal();
        // })

        // function showDeleteModal() {
        //   document.getElementById('deleteModal').style.display = 'block';
        //   document.getElementById('overlay').style.display = 'block';
        // }


        // function hideDeleteModal() {
        //   document.getElementById('deleteModal').style.display = 'none';
        //   document.getElementById('overlay').style.display = 'none';
        // }

        // Show modal and overlay
        function showModal() {
          document.getElementById('planModal').style.display = 'block';
          document.getElementById('overlay').style.display = 'block';
          document.getElementById('overlay').style.transition = '.3s';
        }

        // Hide modal and overlay
        function hideModal() {
          document.getElementById('planModal').style.display = 'none';
          document.getElementById('overlay').style.display = 'none';
        }
      </script>
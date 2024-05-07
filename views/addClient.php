<?php require_once "../controllers/session_Config.php";
?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addarea_contr.php';
require_once  '../controllers/addPlan_contr.php';
require_once  '../controllers/addProduct_contr.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../modals/validate_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>


<style>
  #paymentDiv {
    display: none;
  }

  #paydiv {
    color: var(--blue);
  }

  #header {
    background-color: var(--blue);
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
  }

  button {
    background-color: var(--blue) !important;
    color: var(--light-green) !important;
  }
</style>
<!-- SIDEBAR -->
<?php require_once "side_nav.php"; ?>
<!-- SIDEBAR -->


<div id="loader">Processing...</div>
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
            <a class="active" href="#">Add Client</a>
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

    <!-- content-container -->
    <div class="main-content">
      <!-- Your HTML form goes here -->
      <div class="content">
        <form id="sample_form" class="row g-3 form">


          <div id="header" class="col-md-6 p-3 text-light w-100">General information</div>

          <div class="col-md-6 form-group">
            <label for="Fname" class="form-label">First Name <span class="asterik">*</span> <span id="Fname_error" class="text-danger"></span></label>
            <input type="text" id="Fname" name="Fname" class="form-control form_data" aria-label="First name" !important>
          </div>
          <div class="col-md-6 form-group">
            <label for="Lname" class="form-label">Last Name <span class="asterik">*</span> <span id="Lname_error" class="text-danger"></span></label>
            <input type="text" class="form-control form_data" id="Lname" name="Lname" aria-label="Last name">
          </div>


          <div class="col-md-6">
            <label for="Email" class="form-label">Primary Email <span class="asterik">*</span> <span id="pemail_error" class="text-danger"></span></label>
            <input type="email" class="form-control form_data" id="primaryEmail" name="primaryEmail">
          </div>

          <div class="col-md-6">
            <label for="secondaryEmail" class="form-label">secondary Email <span id="semail_error" class="text-danger"></span></label>
            <input type="tel" class="form-control form_data" id="secondaryEmail" name="secondaryEmail">
          </div>


          <div class="col-md-6">
            <label for="phoneNumber" class="form-label">Phone Number <span class="asterik">*</span> <span id="primaryNumber_error" class="text-danger"></span></label>
            <input type="tel" class="form-control form_data" id="primaryNumber" name="primaryNumber">
          </div>

          <div class="col-md-6">
            <label for="sphoneNumber" class="form-label">Secondary Phone Number <span id="secondaryNumber_error" class="text-danger"></span></label>
            <input type="text" class="form-control form_data" id="secondaryNumber" name="secondaryNumber">
          </div>








          <!-- location -->
          <div id="header" class="col-md-6 mt-4 mb-2 p-3 text-light w-100">Location</div>

          <div class="col-md-6">
            <label for="Address" class="form-label">Address <span id="Address_error" class="text-danger"></span></label>
            <input type="address" class="form-control form_data" id="Address" name="Address">
          </div>

          <div class="col-md-6">
            <label for="City" class="form-label">City <span id="City_error" class="text-danger"></span></label>
            <input type="text" class="form-control form_data" id="City" name="City">
          </div>


          <div class="col-md-6">
            <label for="Country" class="form-label">Country <span id="Country_error" class="text-danger"></span></label>
            <input type="text" class="form-control form_data" id="Country" name="Country">
          </div>

          <div class="col-md-6">
            <label for="ZipCode" class="form-label">ZipCode <span id="ZipCode_error" class="text-danger"></label>
            <input type="text" class="form-control form_data" id="ZipCode" name="ZipCode">
          </div>



          <div class="col-md-6">
            <label for="area" class="form-label">Area Town</label>
            <select name="area" id="area" class="form-select">
              <option value="" selected disabled>Choose...</option>
              <?php
              $areas = getData($connect); // Replace with the actual function to get area data
              foreach ($areas as $area) {
                echo '<option value="' . $area['AreaID'] . '">' . $area['AreaName'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="subArea" class="form-label">Sub-Area</label>
            <select name="subArea" id="subArea" class="form-select">
              <option value="" selected disabled>Choose...</option>
            </select>
          </div>



          <div class="col-md-6">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="form-control" value="*" disabled>
          </div>
          <div class="col-md-6">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="form-control" value="*" disabled>
          </div>
          <div class="col-md-12"><button type="button" class="btn" onclick="getLocation()">Get Location</button></div>






          <!-- Payment -->
          <div id="header" class="col-md-6 mt-4 mb-2 p-3 text-light w-100">Choose Plan</div>
          <a type="button" id="paydiv">Make Payment Now</a>
          <p id="paymentError" class="text-danger"></p>
          <div id="paymentDiv" class="row">
            <div class="row">
              <div class="col-md-6">
                <label for="Plan" class="form-label">Plan <span class="asterik">*</span></label>
                <select id="Plan" name="Plan" class="form-select">
                  <option value="" selected>Choose...</option>
                  <?php
                  $plans = getPlanData($connect);
                  foreach ($plans as $plan) {
                    echo '<option value="' . $plan['PlanID'] . '" data-price="' . $plan['Price'] . '">' . $plan['Volume'] . '</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="PlanAmount" class="form-label">Amount <span class="asterik">*</span></label>
                <input type="text" name="PlanAmount" id="PlanAmount" class="form-control" value="" disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="othersCharges" class="form-label">Installation fees <span class="asterik">*</span></label>
                <input type="number" name="InstallationFees" id="InstallationFees" class="form-control" value="">
              </div>

              <div class="col-md-6">
                <label for="Paymentdate" class="form-label">Payment Date <span class="asterik">*</span></label>
                <input type="date" name="Paymentdate" id="Paymentdate" class="form-control" value="">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="PaymentStatus" class="form-label">Payment Status <span class="asterik">*</span></label>
                <select id="PaymentStatus" name="PaymentStatus" class="form-select">
                  <option selected>Pending </option>
                  <option>Paid</option>
                  <option>Canceled</option>
                </select>
              </div>


              <div class="col-md-6">
                <label for="PaymentOptionID" class="form-label">Payment Method <span class="asterik">*</span></label>
                <select id="PaymentOptionID" name="PaymentOptionID" class="form-select">
                  <option value="" selected>Choose...</option>
                  <?php
                  $methods = getPaymentMethods($connect);

                  foreach ($methods as $method) {
                    echo '<option value="' . $method['PaymentOptionID'] . '">' . $method['PaymentOptionName'] . '</option>';
                  }

                  ?>
                </select>
              </div>
            </div>

          </div>

          <div class="col-md-6">
            <label for="advance" class="form-label">Joined Date</label>
            <input type="date" name="JoinedDate" id="JoinedDate" class="form-control" value="<?= date('Y-m-d'); ?>" readonly>
          </div>



          <!-- Add a div to display messages -->
          <span id="message"></span>
          <div class="col-12">
            <button type="button" id="submit" name="submit" onclick="save_data(); return false;" class="btn">Register</button>
          </div>

        </form>
      </div>




      <?php require_once "footer.php"; ?>


      <script>
        var paydiv = document.getElementById("paydiv");
        var paymentDiv = document.getElementById("paymentDiv");

        paydiv.addEventListener("click", function() {
          // Toggle the display of paymentDiv
          if (paymentDiv.style.display === 'block') {
            paymentDiv.style.display = 'none';
          } else {
            paymentDiv.style.display = 'block';
          }
        });


        function fadeOutErrorMessage(errorMessageElement) {
          errorMessageElement.style.opacity = '0'; // Fade out the error message
          setTimeout(() => {
            errorMessageElement.textContent = ''; // Clear the error message after fade out
          }, 3000); // Set timeout to match the duration of the transition
        }






        // handle form submission
        function save_data() {

          // General Information
          var Fname = document.getElementById('Fname').value;
          var Fname_error = document.getElementById('Fname_error');
          var Lname = document.getElementById('Lname').value;
          var Lname_error = document.getElementById('Lname_error');
          var primaryEmail = document.getElementById('primaryEmail').value;
          var primaryEmail_error = document.getElementById('pemail_error');
          var secondaryEmail = document.getElementById('secondaryEmail').value;
          var secondaryEmail_error = document.getElementById('semail_error');
          var primaryNumber = document.getElementById('primaryNumber').value;
          var primaryNumber_error = document.getElementById('primaryNumber_error');
          var secondaryNumber = document.getElementById('secondaryNumber').value;
          var secondaryNumber_error = document.getElementById('secondaryNumber_error');
          // location
          var Address = document.getElementById('Address').value;
          var City = document.getElementById('City').value;
          var Country = document.getElementById('Country').value;
          var zipCode = document.getElementById('ZipCode').value;
          var zipCode_error = document.getElementById('ZipCode_error');

          var area = document.getElementById('area').value;
          var subArea = document.getElementById('subArea').value;
          var longitude = document.getElementById('longitude').value;
          var latitude = document.getElementById('latitude').value;
          // payment data
          var Plan = document.getElementById('Plan').value;
          var PlanAmount = document.getElementById('PlanAmount').value;
          var InstallationFees = document.getElementById('InstallationFees').value;
          var Paymentdate = document.getElementById('Paymentdate').value;
          var PaymentStatus = document.getElementById('PaymentStatus').value;
          var PaymentOptionID = document.getElementById('PaymentOptionID').value;
          var JoinedDate = document.getElementById('JoinedDate').value;
          var paymentError = document.getElementById('paymentError');
          var loader = document.getElementById('loader');
          let error = false;


          if (!Fname) {
            Fname_error.textContent = 'First Name is Required';
            Fname_error.style.opacity = '1'; // Set opacity to fully visible
            error = true;

            // Show the error message for 5 seconds
            setTimeout(() => {
              fadeOutErrorMessage(Fname_error);
            }, 5000);
          } else {
            if (!/^[a-zA-Z-' ]*$/.test(Fname)) {
              Fname_error.textContent = 'Only Letters and White Space Allowed in First name';
              Fname_error.style.opacity = '1'; // Set opacity to fully visible
              error = true;

              // Show the error message for 5 seconds
              setTimeout(() => {
                fadeOutErrorMessage(Fname_error);
              }, 5000);
            }
          }



          if (!Lname) {
            Lname_error.textContent = 'Last Name is Required';
            error = true;
            Lname_error.style.opacity = '1';
            setTimeout(() => {
              fadeOutErrorMessage(Lname_error);
            }, 5000);
          } else {
            if (!/^[a-zA-Z-' ]*$/.test(Lname)) {
              Lname_error.textContent = 'Only Letters and White Space Allowed in Last name';
              error = true;
              Lname_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(Lname_error);
              }, 5000);
            }
          }



          // Regular expression to validate email format
          var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


          if (!primaryEmail) {
            primaryEmail_error.textContent = 'Email is required';
            error = true;
            primaryEmail_error.style.opacity = '1';
            setTimeout(() => {
              fadeOutErrorMessage(primaryEmail_error);
            }, 5000);
          } else {
            if (!email_pattern.test(primaryEmail)) {
              primaryEmail_error.textContent = 'Invalid email format';
              error = true;

              primaryEmail_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(primaryEmail_error);
              }, 5000);
            }
          }




          if (secondaryEmail) {
            if (!email_pattern.test(secondaryEmail)) {
              secondaryEmail_error.textContent = 'Invalid email format';
              error = true;

              secondaryEmail_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(secondaryEmail_error);
              }, 5000);
            }
          }





          // Regular expression to validate phone number format (allowing only digits)
          var phone_pattern = /^\d+$/;


          if (!primaryNumber) {
            primaryNumber_error.textContent = 'Phone number is required';
            error = true;

            primaryNumber_error.style.opacity = '1';
            setTimeout(() => {
              fadeOutErrorMessage(primaryNumber_error);
            }, 5000);
          } else {

            if (!phone_pattern.test(primaryNumber)) {
              primaryNumber_error.textContent = 'Phone number must contain only digits';
              error = true;

              primaryNumber_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(primaryNumber_error);
              }, 5000);
            } else if (primaryNumber.length < 10) {
              primaryNumber_error.textContent = 'Phone number must be at least 10 digits';
              error = true;

              primaryNumber_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(primaryNumber_error);
              }, 5000);
            }
          }





          if (secondaryNumber) {
            if (!phone_pattern.test(secondaryNumber)) {
              secondaryNumber_error.textContent = 'Phone number must contain only digits';
              error = true;

              secondaryNumber_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(secondaryNumber_error);
              }, 5000);
            } else if (secondaryNumber.length < 10) {
              secondaryNumber_error.textContent = 'Phone number must be at least 10 digits';
              error = true;

              secondaryNumber_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(secondaryNumber_error);
              }, 5000);
            }
          }



          // Regular expression to validate zip code format (allowing only digits)
          var zipCode_pattern = /^\d+$/;

          if (zipCode) {
            if (!zipCode_pattern.test(zipCode)) {
              zipCode_error.textContent = 'Zip Code must contain only digits';
              error = true;

              zipCode_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(zipCode_error);
              }, 5000);
            } else if (zipCode.length < 5) {
              zipCode_error.textContent = 'Zip Code must be at least 5 digits';
              error = true;

              zipCode_error.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(zipCode_error);
              }, 5000);
            }
          }



          if (Plan && PlanAmount) {
            // Check if other fields are empty when Plan is set
            if (!InstallationFees || !Paymentdate || !PaymentStatus || !PaymentOptionID) {
              paymentError.textContent = 'Related Fields are required when Plan is set.';
              error = true;
              paymentError.style.opacity = '1';
              setTimeout(() => {
                fadeOutErrorMessage(paymentError);
              }, 5000);
            }
          }





          if (!error) {

            loader.style.display = 'flex';

            var formData = new FormData();
            formData.append("Fname", Fname);
            formData.append("Lname", Lname);
            formData.append("primaryEmail", primaryEmail);
            formData.append("secondaryEmail", secondaryEmail);
            formData.append("primaryNumber", primaryNumber);
            formData.append("secondaryNumber", secondaryNumber);
            //location
            formData.append("Address", Address);
            formData.append("City", City);
            formData.append("Country", Country);
            formData.append("zipCode", zipCode);
            formData.append("area", area);
            formData.append("subArea", subArea);
            formData.append("longitude", longitude);
            formData.append("latitude", latitude);
            // payment
            formData.append("Plan", Plan);
            formData.append("PlanAmount", PlanAmount);
            formData.append("InstallationFees", InstallationFees);
            formData.append("Paymentdate", Paymentdate);
            formData.append("PaymentStatus", PaymentStatus);
            formData.append("PaymentOptionID", PaymentOptionID);
            formData.append("JoinedDate", JoinedDate);

            // Perform fetch API request
            fetch('../controllers/addClient_contr.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  loader.style.display = 'none';
                  displayMessage("message", data.message, false);
                  localStorage.setItem('AddNewClientToast', 'true');
                  setTimeout(() => {
                    location.reload();
                  }, 3000);
                } else {
                  loader.style.display = 'none';
                  displayMessage("message", data.message, true);
                }
              })
              .catch(error => {
                console.error('Error:', error);
                loader.style.display = 'none';
                displayMessage("message", "Network Error", true);
              });

          }

        }


















        document.getElementById('area').addEventListener('change', function() {
          var areaId = this.value;
          console.log(areaId)
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
                  console.log("it returns")
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



        $(document).ready(function() {
          $('#Plan').change(function() {
            var selectedPlan = $(this).find(':selected');
            var planAmountInput = $('#PlanAmount');

            if (!isNaN(selectedPlan.val())) {
              var planPrice = selectedPlan.data('price');
              planAmountInput.val(planPrice);
            } else {
              planAmountInput.val(''); // Clear the input if "Choose..." is selected
            }
          });
        });



        function getLocation() {
          // e.preventDefault();
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error, {
              enableHighAccuracy: true,
              timeout: 5000,
              maximumAge: 0
            });
          } else {
            alert("Geolocation is not supported by this browser.");
          }
        }

        function success(pos) {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;

          document.getElementById("latitude").value = lat;
          document.getElementById("longitude").value = lng;
        }

        function error(err) {
          if (err.code === 1) {
            alert("Please allow location access.");
          } else {
            alert("Error getting location.");
          }
        }






        //toast function
        function checkAndShowToastAfterReload() {

          if (localStorage.getItem('AddNewClientToast') === 'true') {
            showToast('Congratulations! you\'ve Just Added a New Customer.', 9000);

            // Reset the flag after showing the toast
            localStorage.removeItem('AddNewClientToast');
          }
        }



        // Call the function after the page loads
        window.onload = function() {
          setTimeout(() => {
            checkAndShowToastAfterReload();
          }, 3000);
        };
      </script>
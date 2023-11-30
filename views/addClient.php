<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addarea_contr.php';
require_once  '../controllers/addPlan_contr.php';
require_once  '../controllers/addProduct_contr.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/validate_mod.php';

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
    <div id="loader">
      <div class="spinner-grow text-Primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="main-content">
      <!-- Your HTML form goes here -->
      <div class="content">
        <form id="sample_form" class="row g-3 form">


          <div class="col-md-6 mt-0 mb-0 text-light bg-primary w-100">General information</div>

          <div class="col-md-6 form-group">
            <label for="Fname" class="form-label">First Name <span id="Fname_error" class="text-danger"></span></label>
            <input type="text" id="Fname" name="Fname" class="form-control form_data" aria-label="First name" !important>
          </div>
          <div class="col-md-6 form-group">
            <label for="Lname" class="form-label">Last Name <span id="Lname_error" class="text-danger"></span></label>
            <input type="text" class="form-control form_data" id="Lname" name="Lname" aria-label="Last name">
          </div>


          <div class="col-md-6">
            <label for="Email" class="form-label">Primary Email <span id="email_error" class="text-danger"></span></label>
            <input type="email" class="form-control form_data" id="primaryEmail" name="primaryEmail">
          </div>

          <div class="col-md-6">
            <label for="secondaryEmail" class="form-label">secondary Email <span id="phoneNumber_error" class="text-danger"></span></label>
            <input type="tel" class="form-control form_data" id="secondaryEmail" name="secondaryEmail">
          </div>


          <div class="col-md-6">
            <label for="phoneNumber" class="form-label">Phone Number <span id="phoneNumber_error" class="text-danger"></span></label>
            <input type="tel" class="form-control form_data" id="primaryNumber" name="primaryNumber">
          </div>

          <div class="col-md-6">
            <label for="sphoneNumber" class="form-label">Secondary Phone Number</label>
            <input type="text" class="form-control form_data" id="secondaryNumber" name="secondaryNumber">
          </div>





          <!-- location -->
          <div class="col-md-6 mt-4 mb-2 text-light bg-primary w-100">Location</div>
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
          <div class="col-md-12"><button type="button" class="btn btn-primary" onclick="getLocation()">Get Location</button></div>






          <!-- Payment -->
          <div class="col-md-6 mt-4 mb-2 text-light bg-primary w-100">Choose Plan</div>
          <div class="col-md-6">
            <label for="Plan" class="form-label">Plan</label>
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
            <label for="PlanAmount" class="form-label">Amount</label>
            <input type="text" name="PlanAmount" id="PlanAmount" class="form-control" value="" disabled>
          </div>

          <div class="col-md-6">
            <label for="othersCharges" class="form-label">Installation fees</label>
            <input type="text" name="InstallationFees" id="InstallationFees" class="form-control" value="">
          </div>
          <div class="col-md-6">
            <label for="Paymentdate" class="form-label">Payment Date</label>
            <input type="date" name="Paymentdate" id="Paymentdate" class="form-control" value="">
          </div>
          <div class="col-md-6">
            <label for="PaymentStatus" class="form-label">Payment Status</label>
            <select id="PaymentStatus" name="PaymentStatus" class="form-select">
              <option selected>Pending </option>
              <option>Paid</option>
              <option>Canceled</option>
            </select>
          </div>


          <div class="col-md-6">
            <label for="advance" class="form-label">Joined Date</label>
            <input type="date" name="JoinedDate" id="JoinedDate" class="form-control" value="">
          </div>



          <!-- Add a div to display messages -->
          <span id="message"></span>
          <div class="col-12">
            <button type="button" id="submit" name="submit" onclick="save_data(); return false;" class="btn btn-primary">Register</button>
          </div>

        </form>
      </div>




      <?php require_once "footer.php"; ?>


      <script>
        // handle form submission

        function save_data() {
          var form_element = document.getElementsByClassName('form_data');

          var form_data = new FormData();

          for (var count = 0; count < form_element.length; count++) {
            form_data.append(form_element[count].name, form_element[count].value);
          }


          // Append additional data (location, plan, and payment status)
          form_data.append('area', document.getElementById('area').value);
          form_data.append('subArea', document.getElementById('subArea').value);
          form_data.append('longitude', document.getElementById('longitude').value);
          form_data.append('latitude', document.getElementById('latitude').value);
          form_data.append('Plan', document.getElementById('Plan').value);
          form_data.append('PlanAmount', document.getElementById('PlanAmount').value);
          form_data.append('InstallationFees', document.getElementById('InstallationFees').value);
          form_data.append('Paymentdate', document.getElementById('Paymentdate').value);
          form_data.append('PaymentStatus', document.getElementById('PaymentStatus').value);
          form_data.append('JoinedDate', document.getElementById('JoinedDate').value);



          document.getElementById('submit').disabled = true;
          document.getElementById('loader').style.display = 'block';

          var ajax_request = new XMLHttpRequest();

          ajax_request.open('POST', '../controllers/addClient_contr.php');

          ajax_request.send(form_data);

          ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
              document.getElementById('submit').disabled = false;
              document.getElementById('loader').style.display = 'none';
              var response = JSON.parse(ajax_request.responseText);

              if (response.success != '') {
                document.getElementById('sample_form').reset();
                document.getElementById('message').innerHTML = response.success;
                setTimeout(function() {

                  document.getElementById('message').innerHTML = '';

                }, 7000);
                localStorage.setItem('AddNewClientToast', 'true');
                location.reload();


              } else {
                //display validation error
              }
            }
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
            console.log("it starts")
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
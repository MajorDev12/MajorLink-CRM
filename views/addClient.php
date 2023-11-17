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
            <a class="active" href="#">Home</a>
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
            <label for="Email" class="form-label">Email <span id="email_error" class="text-danger"></span></label>
            <input type="email" class="form-control form_data" id="Email" name="Email">
          </div>

          <div class="col-md-6">
            <label for="phoneNumber" class="form-label">Phone Number <span id="phoneNumber_error" class="text-danger"></span></label>
            <input type="tel" class="form-control form_data" id="phoneNumber" name="phoneNumber">
          </div>

          <div class="col-6">
            <label for="sphoneNumber" class="form-label">Secondary Phone Number</label>
            <input type="text" class="form-control" id="sphoneNumber" name="sphoneNumber">
          </div>






          <!-- location -->
          <div class="col-md-6 mt-4 mb-2 text-light bg-primary w-100">Location</div>
          <div class="col-md-6">
            <label for="area" class="form-label">Area Town</label>
            <select name="area" id="area" class="form-select">
              <option selected>Choose...</option>
              <option>...</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="subArea" class="form-label">Sub-Area</label>
            <select name="subArea" id="subArea" class="form-select">
              <option selected>Choose...</option>
              <option>...</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="form-control" value="*0785382244" disabled>
          </div>
          <div class="col-md-6">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="form-control" value="*0785382244" disabled>
          </div>








          <!-- Payment -->
          <div class="col-md-6 mt-4 mb-2 text-light bg-primary w-100">Choose Plan</div>
          <div class="col-md-6">
            <label for="Plan" class="form-label">Plan</label>
            <select id="Plan" name="Plan" class="form-select">
              <option selected>Choose...</option>
              <option>...</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="PlanAmount" class="form-label">Amount</label>
            <input type="text" name="PlanAmount" id="PlanAmount" class="form-control" value="KSHS. 2000" disabled>
          </div>

          <div class="col-md-6">
            <label for="advance" class="form-label">Advance Payment</label>
            <input type="text" name="advance" id="advance" class="form-control" value="KSHS. 6000">
          </div>
          <div class="col-md-6">
            <label for="othersCharges" class="form-label">Other Charges</label>
            <input type="text" name="othersCharges" id="othersCharges" class="form-control" value="KSHS. 100">
          </div>
          <div class="col-md-6">
            <label for="Paymentdate" class="form-label">Payment Date</label>
            <input type="date" name="Paymentdate" id="Paymentdate" class="form-control" value="KSHS. 100">
          </div>
          <div class="col-md-6">
            <label for="PaymentStatus" class="form-label">Payment Status</label>
            <select id="PaymentStatus" name="PaymentStatus" class="form-select">
              <option selected>Pending </option>
              <option>Passed</option>
              <option>Canceled</option>
            </select>
          </div>


          <!-- Payment -->
          <div class="col-md-6 mt-4 mb-2 text-light bg-primary w-100">Products</div>
          <div class="col-md-6">
            <label for="Product" class="form-label">Name of Product</label>
            <select name="Product" id="Product" class="form-select">
              <option selected>Choose...</option>
              <option>Router</option>
              <option>Cable</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="ProductQuantity" class="form-label">Number of Products</label>
            <select name="ProductQuantity" id="ProductQuantity" class="form-select">
              <option selected>Choose...</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
            </select>
          </div>
          <div class="loader">Loading...</div>
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

          document.getElementById('submit').disabled = true;
          document.querySelector('.loader').style.display = 'block';

          var ajax_request = new XMLHttpRequest();

          ajax_request.open('POST', '../controllers/addClient_contr.php');

          ajax_request.send(form_data);

          ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
              document.getElementById('submit').disabled = false;
              document.querySelector('.loader').style.display = 'none';

              var response = JSON.parse(ajax_request.responseText);

              if (response.success != '') {
                document.getElementById('sample_form').reset();

                document.getElementById('message').innerHTML = response.success;

                setTimeout(function() {

                  document.getElementById('message').innerHTML = '';

                }, 7000);


              } else {
                //display validation error
              }
            }
          }
        }
      </script>
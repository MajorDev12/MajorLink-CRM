<?php require_once "../controllers/session_Config.php"; ?>
<section id="sidebar">
  <a href="#" class="brand ml-2">
    <!-- <i class='bx bxs-smile'></i> -->
    <img src="../img/<?= $companyData[0]["LogoURL"] ?>" class="logoImg" alt="" style="width: 50px; height: 50px;">
    <span class="text"><?= $companyData[0]["CompanyName"] ?></span>
  </a>



  <ul class="nav-links">
    <li>
      <div class="iocn-link">
        <a href="index.php">
          <i class='bx bx-grid-alt'></i>
          <span class="link_name">Dashboard</span>
        </a>
      </div>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="index.php">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bx-user-plus'></i>
          <span class="link_name">Customer</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name">Customer</a></li>
        <li><a href="addClient.php">Add Client</a></li>
        <li><a href="viewClient.php">View Client</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bx-edit'></i>
          <span class="link_name">Setup</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name">Setup</a></li>
        <li><a href="addClient.php">Add Client</a></li>
        <li><a href="addArea.php">Add Area</a></li>
        <li><a href="addSubarea.php">Add Sub-Area</a></li>
        <li><a href="addplan.php">Add Plan</a></li>
        <li><a href="addProduct.php">Add Product</a></li>
        <li><a href="ExpenseType.php">Add Expense Type</a></li>
        <li><a href="paymentMethod.php">Add Payment Method</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="#Billing">
          <i class='bx bx-credit-card-front'></i>
          <span class="link_name">Billing</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Billing</a></li>
        <li><a href="makePayment.php">First Payment</a></li>
        <li><a href="advancePayment.php">Advance Payment</a></li>
        <li><a href="viewTransaction.php">View Transactions</a></li>
        <li><a href="addExpense.php">Add Expense</a></li>
        <li><a href="addSale.php">Add Sale</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bxs-report'></i>
          <span class="link_name">Reports</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Reports</a></li>
        <li><a href="revenue.php">Revenue</a></li>
        <li><a href="expense.php">Expense</a></li>
        <li><a href="incomeVsExpense.php">Income Vs Expense</a></li>
        <!-- <li><a href="summary.php">Business Summary</a></li> -->
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bx-bell'></i>
          <span class="link_name">Notifications</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Notifications</a></li>
        <li><a href="invoice.php" data-target="invoice">Invoice</a></li>
        <li><a href="email.php" data-target="email">Email</a></li>
        <li><a href="sms.php" data-target="sms">Sms</a></li>
      </ul>
    </li>


    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bx-cog'></i>
          <span class="link_name">Management</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Management</a></li>
        <li><a href="settings.php" data-target="settings">Settings</a></li>
        <li><a href="profile.php" data-target="systemlogs">Profile</a></li>
      </ul>
    </li>

    <script>
      let arrow = document.querySelectorAll(".arrow");
      for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
          let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
          arrowParent.classList.toggle("showMenu");
        });
      }
    </script>
</section>
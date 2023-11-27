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
            <div class="content">
                <form class="row g-3 form">
                    <div class="form-group">
                        <label for="saleDate">Payment Date:</label>
                        <input type="date" id="saleDate" name="saleDate" required>
                    </div>
                    <div class="col-md-6">
                        <label for="saleDate">From Date:</label>
                        <input type="date" id="saleDate" name="saleDate" required disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="saleDate">To Date:</label>
                        <input type="date" id="saleDate" name="saleDate" required disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="salesperson">Customer:</label>
                        <select class="form-select form-select-md" id="salesperson" aria-label="Default select example">
                            <option selected>Customer 1</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="customer">Amount Paid:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>



                    <div class="col-md-6">
                        <label for="Product">Plan:</label>
                        <select class="form-select form-select-md" id="Product" aria-label="Default select example">
                            <option selected>5mbps</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="Product">Payment Method:</label>
                        <select class="form-select form-select-md" id="Product" aria-label="Default select example">
                            <option selected>Cash</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="Product">Payment Reference:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" disabled>
                        </div>
                    </div>


                    <!-- Rest of the form fields remain the same -->
                    <!-- ... -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Payment</button>
                    </div>
                </form>
            </div>
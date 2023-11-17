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
                        <label for="expenseDate">Expense Date:</label>
                        <input type="date" id="expenseDate" name="saleDate" required>
                    </div>

                    <div class="col-md-6">
                        <label for="CategoryType">Category Type:</label>
                        <select class="form-select form-select-md" id="CategoryType" aria-label="Default select example">
                            <option selected>Utilities</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="amount">Amount Spent:</label>
                        <div class="input-group">
                            <input type="number" id="amount" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="PaymentAmount">Payment Amount:</label>
                        <select class="form-select form-select-md" id="PaymentAmount" aria-label="Default select example">
                            <option selected>Cash</option>
                            <option value="1">M-Pesa</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="PaymentReciept">Payment Reciept:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="PaymentReciept" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <label for="">Expense Description</label>
                        <textarea name="" id="" cols="100" rows="10"></textarea>
                    </div>
                    <!-- Rest of the form fields remain the same -->
                    <!-- ... -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>


            <?php require_once "footer.php"; ?>
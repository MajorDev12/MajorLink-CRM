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

                    <div class="col-md-6">
                        <label for="ProductName">Product Name:</label>
                        <div class="input-group mb-3">
                            <input type="text" id="ProductName" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ProductName">Product Price:</label>
                        <div class="input-group mb-3">
                            <input type="number" id="ProductName" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="">Notes</label>
                        <textarea name="" id="" cols="10" rows="10"></textarea>
                    </div>
                    <!-- Rest of the form fields remain the same -->
                    <!-- ... -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

                <div class="col-md-12 p-4">
                    <table class="table table-hover caption-top">
                        <caption>List of Products</caption>
                        <thead class="table-Primary">
                            <tr class="table-primary bg-primary">
                                <th scope="col">No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr>
                                <th scope="row">1</th>
                                <td>Router</td>
                                <td>1500</td>
                                <td>The router is huawei</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Ethernet Cable</td>
                                <td>2000</td>
                                <td>cable can be upto 1000metres long</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php require_once "footer.php"; ?>
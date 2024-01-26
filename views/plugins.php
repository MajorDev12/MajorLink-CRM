<?php require_once "../controllers/session_Config.php"; ?>

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
                        <a class="active" href="#">Plugins</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">
            <div id="loader">Loading...</div>
            <div class="content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="list-home">Currency</a>
                            <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Twillio</a>
                            <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Infobip</a>
                            <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="list-messages">Paypal</a>
                            <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="list-settings">Mpesa</a>
                            <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="list-settings">Stripe</a>
                            <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="list-settings">Razorpay</a>
                            <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="list-settings">PhpMailler</a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="nav-tabContent">
                            <!-- currency page -->
                            <div class="tab-pane fade show p-3 mt-5 border border border-start-0 rounded active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                <form>
                                    <label for="newPassword">Api Key</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">Secret key Key</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            </div>
                            <!-- Twillio page -->
                            <div class="tab-pane fade p-3 mt-5" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                                <form>
                                    <label for="newPassword">Api Key</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">Secret key Key</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            </div>
                            <div class="tab-pane fade p-3 mt-5" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
                            <div class="tab-pane fade p-3 mt-5" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">...</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once "footer.php"; ?>
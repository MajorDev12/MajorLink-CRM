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
                            <a class="list-group-item list-group-item-action" id="list-Twillio-list" data-bs-toggle="list" href="#list-Twillio" role="tab" aria-controls="list-Twillio">Twillio</a>
                            <a class="list-group-item list-group-item-action" id="list-Infobip-list" data-bs-toggle="list" href="#list-Infobip" role="tab" aria-controls="list-Infobip">Infobip</a>
                            <a class="list-group-item list-group-item-action" id="list-Stripe-list" data-bs-toggle="list" href="#list-Stripe" role="tab" aria-controls="list-Stripe">Stripe</a>
                            <a class="list-group-item list-group-item-action" id="list-Phpmailer-list" data-bs-toggle="list" href="#list-Phpmailer" role="tab" aria-controls="list-Phpmailer">PhpMailler</a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="nav-tabContent">
                            <!-- Twillio page -->
                            <div class="tab-pane fade p-3 mt-5" id="list-Twillio" role="tabpanel" aria-labelledby="list-Twillio-list">
                                <form>
                                    <label for="newPassword">ACCOUNT_ID</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">AUTH_TOKEN</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            </div>
                            <!-- INFOBIP PAGE -->
                            <div class="tab-pane fade p-3 mt-5" id="list-Infobip" role="tabpanel" aria-labelledby="list-Infobip-list">
                                <form>
                                    <label for="newPassword">BASE_URL</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">API_KEY</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            </div>
                            <!-- STRIPE PAGE -->
                            <div class="tab-pane fade p-3 mt-5" id="list-Stripe" role="tabpanel" aria-labelledby="list-Stripe-list">
                                <form>
                                    <label for="newPassword">STRIPE_API_KEY</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">STRIPE_PUBLISHABLE_KEY</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            </div>
                            <!-- PHPMAILER PAGE -->
                            <div class="tab-pane fade p-3 mt-5" id="list-Phpmailer" role="tabpanel" aria-labelledby="list-Phpmailer-list">
                                <form>
                                    <label for="newPassword">USERNAME</label>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox">
                                    <label for="newPassword" class=" mt-3">PASSWORD</label>
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
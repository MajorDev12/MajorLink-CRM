<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>

<?php require_once "../views/header.php"; ?>

<?php require_once "../views/style.config.php"; ?>
<style>
    .planPrice .month {
        color: var(--blue);
        font-size: 18px;
    }

    .overlay {
        display: none;
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--light);
        z-index: 1000;
    }

    .modal {
        position: absolute;
        top: 15%;
        left: 30%;
        width: 40%;
        height: 70%;
        background-color: var(--light);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1001;
        display: none;
    }

    .note {
        position: absolute;
        top: 4%;
        width: 70%;
        background-color: var(--light-green);
    }
</style>
<div class="overlay"></div>
<!-- stripe modal -->
<div class="modal shadow-sm p-3 mb-5 bg-body rounded">

    <p class="note p-2">
        note that changing subscription plan will remove any past payment and start afresh
    </p>
    <form action="">
        <div class="row">
            <div class="form-group">
                <label for="Plan" class="form-label">Payment Date</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
                <label for="Plan" class="form-label">Start Date</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
                <label for="Plan" class="form-label">Amount</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
                <label for="Plan" class="form-label">Months</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
            </div>

            <button type="submit" class="btn btn-success mt-4">Change Now</button>
        </div>

    </form>
</div>



<!-- paypal modal -->
<div class="modal shadow-sm p-3 mb-5 bg-body rounded">

    <p class="note p-2">
        note that changing subscription plan will remove any past payment and start afresh
    </p>
    <form action="">
        <div class="row">
            <div class="form-group">
                <label for="Plan" class="form-label">Payment Date</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan" value="<?php echo date('Y-m-d'); ?>">
                <label for="Plan" class="form-label">Start Date</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan" value="<?php echo date('Y-m-d'); ?>">
                <label for="Plan" class="form-label">Amount</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
                <label for="Plan" class="form-label">Months</label>
                <input type="text" class="form-control" readonly id="Plan" name="Plan">
            </div>

            <button type="submit" class="btn btn-success mt-4">Change Now</button>
        </div>

    </form>
</div>






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
                <h1>Hi, <?= $_SESSION['FirstName']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Change Plan</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">


                <!-- modifySubscription page -->
                <div class="page modifySubscription">
                    <h4 class="border-bottom pb-3 mb-5">Change Subscription Plan</h4>


                    <div class="grid text-center plans row">
                        <?php $plans = getPlanData($connect); ?>
                        <?php foreach ($plans as $plan) : ?>

                            <div class="col-md-6 shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                                <form action="">
                                    <div class="planVolume mt-5 bg-primary">
                                        <h3 class="p-4 text-light"><?php echo $plan["Volume"]; ?></h3>
                                    </div>
                                    <div class="planName mt-5">
                                        <h4 class=""><?php echo $plan["Name"]; ?></h4>
                                        <p><?php //echo $plan["Notes"]; 
                                            ?></p>
                                    </div>
                                    <div class="planPrice mb-3">
                                        <h3 class=""> <sup class="currency"> <?php
                                                                                $settings = get_Settings($connect);
                                                                                echo $settings[0]["CurrencySymbol"];
                                                                                ?></sup><span class="number"><?php echo $plan["Price"]; ?></span><span class="month" name="duration">/month</span></h3>
                                    </div>
                                    <button type="button" class="btn btn-primary apply">Apply Now</button>
                                </form>
                            </div>

                        <?php endforeach; ?>


                    </div>


                </div>




            </div>

            <?php require_once "../views/footer.php"; ?>

            <script>
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('apply')) {
                        var form = event.target.closest('form');
                        if (form) {
                            var priceElement = form.querySelector('.planPrice .number');
                            if (priceElement) {
                                var price = priceElement.textContent;
                                console.log(price);

                                // You can now use the 'price' variable for further processing
                                // For example, you might want to submit it in the form or perform some other action.
                            }
                        }
                    }
                });
            </script>
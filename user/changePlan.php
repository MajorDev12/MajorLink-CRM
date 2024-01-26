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
</style>

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
                        <a class="active" href="#">Change Subscription Plan</a>
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
                                                                            ?></sup><span class="number"><?php echo $plan["Price"]; ?>/</span><span class="month" name="duration">month</span></h3>
                                </div>
                                <button type="button" class="btn btn-primary">Apply Now</button>
                            </div>

                        <?php endforeach; ?>

                        <div class="features"></div>
                    </div>


                </div>




            </div>

            <?php require_once "../views/footer.php"; ?>

            <?php
            // $plans = getPlanData($connect);

            // foreach ($plans as $plan) {
            //     $selected = ($plan['PlanID'] == $clientData['PlanID']) ? 'selected' : '';
            //     echo "<option value=\"{$plan['PlanID']}\" {$selected} data-amount=\"{$plan['Price']}\">{$plan['Volume']}</option>";
            // }


            // 
            ?>
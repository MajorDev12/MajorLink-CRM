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

<style>
    #map {
        width: 450px;
        height: 450px;
        background-color: var(--lato);
    }

    .userInfo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .img-cont {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .img-cont img {
        width: 100px;
        height: 100px;
    }

    .userDetails {
        width: 100%;
        margin-top: 15%;
        display: flex;
        flex-direction: column;
        justify-content: start;
        align-items: start;
        /* background-color: grey; */
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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">View Client</a>
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
            <div class="row">
                <div class="content me-4 col-md-5 infor">
                    <div class="userInfo">
                        <h3>Basic Information</h3>
                        <div class="img-cont">
                            <img src="../img/user.png" alt="">
                        </div>
                        <div class="userDetails">
                            <div class="row mb-3">
                                <h5 class="">Name:</h5>
                                <span class="">Major Nganga</span>
                            </div>
                            <div class="row mb-3">
                                <h5 class="">Email:</h5>
                                <span class="">major@gmail.com</span>
                            </div>
                            <div class="row mb-3">
                                <h5 class="">Address:</h5>
                                <span class="">2nd Street Avenue</span>
                            </div>
                            <div class="row mb-3">
                                <h5 class="">Telephone:</h5>
                                <span class="">+(254)-183-177-26</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content col-md-6 map">
                    <h3>Location</h3>
                    <div id="map"></div>

                </div>
            </div>


            <div class="content">
                <div class="col-md-6 contacts mt-5">
                    <h3 class="mb-5">Contacts</h3>
                    <div class="row mb-3">
                        <h5 class="col-md-6">Email Address:</h5>
                        <span class="col-md-3">Majordev12@gmail.com</span>
                    </div>
                    <div class="row mb-3">
                        <h5 class="col-md-6">Phone Number:</h5>
                        <span class="col-md-3">0718317726</span>
                    </div>
                    <div class="row mb-3">
                        <h5 class="col-md-6">Secondary Number:</h5>
                        <span class="col-md-3">Null</span>
                    </div>
                </div>
            </div>






            <script>
                var map = L.map("map");
                map.setView([-0.3741591, 36.1510264], 11);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                navigator.geolocation.getCurrentPosition(success, error, {
                    enableHighAccuracy: true, // Request high accuracy
                    timeout: 5000, // Set a timeout (milliseconds)
                    maximumAge: 0 // Force a fresh location reading
                });

                function success(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const accuracy = pos.coords.accuracy;

                    let marker = L.marker([lat, lng]).addTo(map);
                    let circle = L.circle([lat, lng], {
                        radius: accuracy
                    }).addTo(map);

                    // Set the view to the circle's center
                    map.setView([lat, lng], 11);
                }

                function error(err) {
                    if (err.code === 1) {
                        alert("allow");
                    } else {
                        alert("cannot");
                    }
                }
            </script>
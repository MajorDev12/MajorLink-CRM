<?php
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION["clientID"];
$clientData = getClientDataById($connect, $clientID);
?>

<nav class="top-nav">
    <div class="navDiv">
        <i class='bx bx-menu'></i>


        <div class="centerdiv">
            <!-- TIME -->
            <div class="date">
                <?php
                require_once  '../database/pdo.php';
                require_once "../modals/setup_mod.php";
                require_once "../modals/getTime_mod.php";
                require_once  '../modals/addAdmin_mod.php';

                $connect = connectToDatabase($host, $dbname, $username, $password);

                $settings = get_Settings($connect);
                $timezone = $settings[0]["TimeZone"];
                $time = getTime($timezone);
                $timeAsDate = date('Y-m-d H:i:s', strtotime($time));
                $timeFormatted = date('F j, Y -- h:i:s A', strtotime($timeAsDate));

                // Display the formatted date
                echo "<p id='date' class='text-center'></p>";
                ?>
            </div>

            <!-- NOTIFICATION MODAL -->
            <?php
            require_once '../modals/notification_mod.php';
            $clientID = $_SESSION["clientID"];
            $unreadMsgs = getUnreadMessages($connect, $clientID);
            $numUnreadMsgs = count($unreadMsgs);
            ?>


            <div id="notification">
                <a href="#" class="notification dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bxs-bell notificationIcon'></i>
                    <span class="num"><?= $numUnreadMsgs ?></span>
                </a>

                <ul id="notificationDropdown" class="dropdown-menu border mt-4 custom-dropdown">
                    <?php if ($numUnreadMsgs > 0) : ?>
                        <?php foreach ($unreadMsgs as $unRead) : ?>
                            <li class="not">
                                <p class="dropdown-item"><?= $unRead["MessageContent"]; ?></p>
                                <span class="messageTime"><span class="msgType"><?= $unRead["SenderName"]; ?></span><?= $unRead["Timestamp"]; ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li class="not">
                            <p class="dropdown-item text-center">No new messages</p>
                        </li>
                    <?php endif; ?>

                    <a href="notification.php" class="allnotificationBtn mt-4">See All Notifications</a>
                </ul>
            </div>


        </div>




        <!-- TOGGLE AND PROFILE LOGO -->
        <div class="nav-items">
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>

            <a href="#" class="profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../img/<?= $clientData['ProfilePictureURL']; ?>">
            </a>
            <ul class="dropdown-menu border mt-3">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../controllers/logout_contr.php">Logout</a></li>
            </ul>

        </div>
    </div>
</nav>





<script>
    var currentTime = <?= json_encode($timeAsDate); ?>;

    function updateTime() {
        // Create a new Date object to get the current time

        var Time = new Date(currentTime);
        // Format the time as desired
        var formattedTime = Time.toLocaleString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        });

        // Update the content of the element with id 'date'
        document.getElementById('date').textContent = formattedTime;

        // Update the current time for the next interval
        Time.setSeconds(Time.getSeconds() + 1);
        currentTime = Time;
    }

    // Call updateTime function immediately to set the initial time
    updateTime();

    // Call updateTime function every second
    setInterval(updateTime, 1000);
</script>







<!-- 
<nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Categories</a>
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>
    <a href="#" class="notification">
        <i class='bx bxs-bell'></i>
        <span class="num">8</span>
    </a>
    <a href="#" class="profile">
        <img src="../img/people.png">
    </a>
</nav> -->
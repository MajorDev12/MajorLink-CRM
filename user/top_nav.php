<?php
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION["clientID"];
$clientData = getClientDataById($connect, $clientID);
?>

<nav class="top-nav">
    <i class='bx bx-menu'></i>


    <!-- TIME -->
    <div class="date">
        <?php
        require_once  '../modals/setup_mod.php';
        require_once  '../modals/getTime_mod.php';
        $settings = get_Settings($connect);
        $timezone = $settings[0]["TimeZone"];
        $datetime = getTime($timezone);
        // Create a DateTime object from the formatted date
        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        // Format the date to day, month, year
        $DateTime = $dateObj->format('d F Y H:i:s'); // Example: 01 January 2023
        // Display the formatted date
        echo "<p id='date' class='text-center'>" . $DateTime . "</p>";
        ?>
    </div>

    <!-- NOTIFICATION MODAL -->
    <?php
    require_once '../modals/notification_mod.php';
    $clientID = $_SESSION["clientID"];
    $unreadMsgs = getUnreadMessages($connect, $clientID);
    $numUnreadMsgs = count($unreadMsgs);
    ?>

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
</nav>





<script>
    function updateTime() {
        // Get the current date and time
        var now = new Date();

        // Format the date and time
        var formattedDate = now.toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        var formattedTime = now.toLocaleTimeString();

        // Display the formatted date and time
        document.getElementById('date').textContent = formattedDate + ' - ' + formattedTime;
    }

    // Update the time initially
    updateTime();

    // Update the time every second
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
<nav class="top-nav">
    <i class='bx bx-menu'></i>

    <div class="date">
        <?php
        require_once  '../database/pdo.php';
        require_once "../modals/setup_mod.php";
        require_once "../modals/getTime_mod.php";
        require_once  '../modals/addAdmin_mod.php';

        $connect = connectToDatabase($host, $dbname, $username, $password);

        $adminID = $_SESSION['adminID'];
        $adminData = getAdminDataById($connect, $adminID);


        $settings = get_Settings($connect);
        $timezone = $settings[0]["TimeZone"];
        $time = getTime($timezone);
        $timeAsDate = date('Y-m-d H:i:s', strtotime($time));
        $timeFormatted = date('F j, Y -- h:i:s A', strtotime($timeAsDate));

        // Display the formatted date
        echo "<p id='date' class='text-center'></p>";
        ?>
    </div>


    <div class="nav-items">


        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="profile  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../img/<?= $adminData['ProfilePictureURL']; ?>">
        </a>
        <ul class="dropdown-menu border mt-3">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="../controllers/logout_contr.php">Logout</a></li>
        </ul>
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









    // function updateTime() {
    //     // Get the current date and time
    //     var now = new Date();

    //     // Format the date and time
    //     var formattedDate = now.toLocaleDateString('en-US', {
    //         day: 'numeric',
    //         month: 'long',
    //         year: 'numeric'
    //     });
    //     var formattedTime = now.toLocaleTimeString();

    //     // Display the formatted date and time
    //     document.getElementById('date').textContent = formattedDate + ' - ' + formattedTime;
    // }

    // // Update the time initially
    // updateTime();

    // // Update the time every second
    // setInterval(updateTime, 1000);
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
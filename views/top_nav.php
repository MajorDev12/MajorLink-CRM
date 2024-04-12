<nav class="top-nav">
    <i class='bx bx-menu'></i>

    <div class="date">
        <?php
        require_once "../modals/setup_mod.php";

        // Get the current date and format it as per your requirement
        $currentDate = date('y:m:d'); // Change the format as needed

        // Display the formatted date
        echo "<p id='date' class='text-center'>" . $currentDate . "</p>";
        ?>
    </div>

    <a href="#" class="notification">
        <i class='bx bxs-bell'></i>
        <span class="num">8</span>
    </a>

    <div class="nav-items">


        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="profile  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../img/people.png">
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
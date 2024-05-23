<div id="loading">
    <h5></h5>
</div>

<div id="loader"></div>
<div id="spinner"></div>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/getTime_mod.php';
require_once  '../modals/setup_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$companyData = get_Settings($connect);
$greeting = getGreeting();
?>
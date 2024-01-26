<?php
require_once "../controllers/session_Config.php";
// require_once "../assets/currencies.json";
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    p {
        font-size: 16px;
        font-weight: 100;
        width: 90%;
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
                        <a class="active" href="#">Set Currency</a>
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
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Choose Currency</h3>
                    <p class="display-8">You can choose from more than 250 different currencies across the world and still have a smoothless transaction.</p>
                </div>

                <?php
                // Read the JSON file
                $currenciesJson = file_get_contents("../assets/countryData.json");

                // Decode the JSON data
                $currencies = json_decode($currenciesJson, true);
                ?>

                <select id="selectedCurrency" class="form-select mt-4" size="10" aria-label="Size 3 select example">
                    <option selected disabled value="">Select your preferred currency</option>

                    <?php foreach ($currencies as $currency) : ?>
                        <?php foreach ($currency['currencies'] as $code => $details) : ?>
                            <?php
                            // Check if the currency name is already added
                            if (!isset($uniqueCurrencyNames[$details['name']])) {
                            ?>
                                <option class="currency" value="<?php echo $details['name']; ?>" data-currency-symbol="<?php echo $details['symbol']; ?>">
                                    <?php echo $details['name'] . ' - ' . $details['symbol']; ?>
                                </option>
                            <?php
                                // Mark the currency name as added
                                $uniqueCurrencyNames[$details['name']] = true;
                            }
                            ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                </select>
                <p id="errorMsg" class="mt-2"></p>
                <button type="button" class="btn btn-success mt-4 change">Change</button>
            </div>
            <?php require_once "../views/footer.php"; ?>

            <script>
                var changeButtons = document.querySelectorAll(".change");

                // Attach a click event listener to each 'Change' button
                changeButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        // Find the selected currency element
                        var selectedCurrencyElement = document.getElementById("selectedCurrency");
                        var selectedOption = selectedCurrencyElement.options[selectedCurrencyElement.selectedIndex];

                        // Get the values
                        var selectedCurrency = selectedOption.value;
                        var currencySymbol = selectedOption.getAttribute("data-currency-symbol");

                        // Log or do something with the selected currency and currency symbol
                        // console.log("Selected Currency:", selectedCurrency);
                        // console.log("Currency Symbol:", currencySymbol);

                        if (!selectedCurrency || !currencySymbol) {
                            displayMessage("errorMsg", "Choose a currency first", true);
                            return;
                        }

                        loader.style.display = "flex";

                        var formData = new FormData();
                        formData.append("selectedCurrency", selectedCurrency);
                        formData.append("currencySymbol", currencySymbol);

                        fetch("../controllers/changeCurrency_contr.php", {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Handle the response from the server
                                    displayMessage("errorMsg", "Successfuly updated", false);
                                    // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                    setTimeout(() => {
                                        loader.style.display = "none";
                                        window.location.href = "index.php";
                                    }, 2000);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });






                function displayMessage(messageElement, message, isError, ) {
                    // Get the HTML element where the message should be displayed
                    var targetElement = document.getElementById(messageElement);

                    // Set the message text
                    targetElement.innerText = message;

                    // Add styling based on whether it's an error or success
                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }

                    // Set a timeout to hide the message with the fade-out effect
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 1000);
                }
            </script>
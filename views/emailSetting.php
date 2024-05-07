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
                        <a class="active" href="#">Email Settings</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Email Setup</h3>
                    <p class="display-8">You can choose from more than 250 different currencies across the world and still have a smoothless transaction.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="customer">From</label>
                        <div class="input-group">
                            <input type="number" id="Amount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="customer"></label>
                        <div class="input-group">
                            <input type="number" id="Amount" class="form-control">
                        </div>
                    </div>
                </div>

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
                        var currencyCode = selectedOption.getAttribute("data-currency-code");


                        if (!selectedCurrency || !currencySymbol || !currencyCode) {
                            displayMessage("errorMsg", "Choose a currency first", true);
                            return;
                        }

                        loader.style.display = "flex";

                        var formData = new FormData();
                        formData.append("selectedCurrency", selectedCurrency);
                        formData.append("currencySymbol", currencySymbol);
                        formData.append("currencyCode", currencyCode);

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
            </script>
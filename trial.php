<?php

function getTtime($CurrentTimezone)
{
    $targetTimezone = $CurrentTimezone;

    // Set the default timezone
    date_default_timezone_set($targetTimezone);

    // Get the current date and time in the specified timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Display the formatted date and time
    return $currentDateTime;
}


echo getTtime('America/New_York');








$expireDate = new DateTime('2024-03-29 00:00:00');
$lastPaymentDate = new DateTime('2024-03-26 01:37:00');
// Calculate the difference between the two dates
$dateInterval = $expireDate->diff($lastPaymentDate);

// Access the difference in days, months, and years
$daysDifference = $dateInterval->days;
$monthsDifference = $dateInterval->m;
$yearsDifference = $dateInterval->y;

// Print the differences
echo "Days Difference: $daysDifference days\n";
echo "<br />";
echo "Months Difference: $monthsDifference months\n";
echo "<br />";
echo "Years Difference: $yearsDifference years\n";


$today = new DateTime();
$daysRemaining = max(0, $today->diff($expireDate)->days);
echo "<br />";
echo "<br />";
echo "Remaining: $daysRemaining days\n";



$today = new DateTime();

// Get the Unix timestamp representing the current date and time
$initialExpireDate = $today->getTimestamp();

// Format the timestamp into a human-readable date
$initialExpireDateString = date('Y-m-d', $initialExpireDate);

// Print the formatted date
echo "<br />";
echo "<br />";
echo $initialExpireDateString;

// JSON data
// $currencies = json_decode(file_get_contents("assets/currencies.json"), true);
// $timezones = json_decode(file_get_contents("assets/timezones.json"), true);

// Create an associative array using the country name as the key for timezones
// $timezonesMap = [];
// foreach ($timezones as $timezone) {
//     $countryName = $timezone['name'];
//     $timezonesMap[$countryName] = $timezone['timezones'];
// }

// // Merge data
// foreach ($currencies as &$currency) {
//     // Check if the country name exists in the timezones data
//     $countryName = $currency['name'];
//     if (isset($timezonesMap[$countryName])) {
//         // Replace the timezones property with the one from the timezones data
//         $currency['timezones'] = $timezonesMap[$countryName];
//     }
// }

// // Convert the merged data to JSON format
// $mergedJson = json_encode($currencies, JSON_PRETTY_PRINT);

// // Output the result
// echo $mergedJson;
// JSON data
// $countries = json_decode(file_get_contents("assets/countryData.json"), true);

// $currency = 'kes';

// $stripeCurrencies = json_decode(file_get_contents("assets/stripeCurrencies.json"), true);

// // Get the length of the array
// $length = count($stripeCurrencies);

// echo "Length of \$stripeCurrencies: {$length}";


// // Convert to lowercase
// $currency = strtolower($currency);


// // Check if it's in the list of Stripe currencies
// if (!in_array($currency, $stripeCurrencies)) {
//     $currency = 'usd';
// }

// echo $currency;

?>

<script>
    // const countryData = <?php //echo json_encode($countries); 
                            ?>;

    // // Assuming you have the data stored in a variable named 'countryData'

    // const updatedCountryData = countryData.map(country => {
    //     if (country.currencies && typeof country.currencies === 'object') {
    //         Object.keys(country.currencies).forEach(currencyCode => {
    //             const currencyDetails = country.currencies[currencyCode];
    //             currencyDetails.code = currencyCode;
    //         });
    //     }
    //     return country;
    // });

    // console.log(JSON.stringify(updatedCountryData, null, 2));
</script>


<?php





// $Paymentdate = date('2024-02-28');

// $paymentDate = new DateTime($Paymentdate);
// $expireDate = clone $paymentDate;
// $expireDate->add(new DateInterval('P1M')); // Adding 1 month to the payment date
// $expireDate = $expireDate->format('Y-m-d');

// echo $expireDate;
// $baseUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// $baseUrl .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

// define('STRIPE_SUCCESS_URL', $baseUrl . '/payment-success.php'); //Payment success URL 
// define('STRIPE_CANCEL_URL', $baseUrl . '/payment-cancel.php');

// echo STRIPE_SUCCESS_URL;
// echo '<br />';
// echo STRIPE_CANCEL_URL;

function getTime($CurrentTimezone)
{
    $targetTimezone = $CurrentTimezone;

    // Set the default timezone
    date_default_timezone_set($targetTimezone);

    // Get the current date and time in the specified timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Display the formatted date and time
    return $currentDateTime;
}

// getTime();
// geoip_country_name_by_name();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post" action="test.php">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required>

        <label for="email">email</label>
        <input type="email" name="email" id="email" required>

        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" required></textarea>

        <br>

        <button>Send</button>
    </form>
</body>

</html>




















































<div class="content tab-content">


    <!-- services page -->
    <div class="page active" id="services">
        <div class="h4 pb-2 mt-2 mb-5 border-bottom">
            <h3>Services Records</h3>
        </div>
        <div class="tableActions">
            <input type="submit" value="Search" class="searchBtn" id="searchBtn1">
            <input type="search" class="searchInput" id="searchInput1">
            <i class='bx bx-search' id="searchIcon1" onclick=""></i>
            <i class='bx bxs-printer' id="printIcon1"></i>
            <i class='bx bxs-spreadsheet' id="spreadsheetIcon1"></i>
            <!-- <i class='bx bx-filter'></i> -->
            <div class="filterdiv shadow-sm p-3 mb-5 bg-white rounded row">
                <p>Filter</p>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>None</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Start Date</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Due Date</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Status</span>
                </div>
            </div>
        </div>
        <table class="mt-5">
            <thead id="thead1">
                <tr>
                    <th>#</th>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody1" class="tableBody searchData">
                <?php $counter = 1; ?>
                <?php if ($invoicesData) : ?>
                    <?php foreach ($invoicesData as $index => $invoice) : ?>
                        <tr>
                            <td class="index pe-3"><?= $index + 1;  ?></td>
                            <td class=""><?php echo $invoice['InvoiceNumber']; ?></td>
                            <td class=""><?php echo $invoice['FirstName'] . ' ' . $invoice['LastName']; ?></td>
                            <td><span class=""><?php echo $invoice['TotalAmount']; ?></span></td>
                            <td><span class=""><?php echo $invoice['StartDate']; ?></span></td>
                            <td><span class=""><?php echo $invoice['DueDate']; ?></span></td>
                            <td><span class=""><?php echo $invoice['Status']; ?></span></td>
                            <td style="text-align:center">
                                <a href="viewInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                                <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                <abbr title="print"><a href="../views/printInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>






    <!-- products page -->
    <div class="page" id="products">
        <h3>Products Records</h3>
        <div class="tableActions">
            <input type="submit" value="Search" class="searchBtn" id="searchBtn2">
            <input type="search" class="searchInput" id="searchInput2">
            <i class='bx bx-search' id="searchIcon2"></i>
            <i class='bx bxs-printer' id="printIcon2"></i>
            <i class='bx bxs-spreadsheet' id="spreadsheetIcon2"></i>
            <!-- <i class='bx bx-filter'></i> -->
            <div class="filterdiv shadow-sm p-3 mb-5 bg-white rounded row">
                <p>Filter</p>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>None</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Start Date</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Due Date</span>
                </div>
                <div class="col">
                    <input type="radio" name="" id="">
                    <span>Status</span>
                </div>
            </div>
        </div>
        <table class="mt-5">
            <thead id="thead2">
                <tr>
                    <th>#</th>
                    <th>Invoice Number</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Issue Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody2" class="tableBody searchSales">
                <?php $counter = 1; ?>
                <?php $sales = getSalesData($connect); ?>
                <?php if ($sales) : ?>
                    <?php foreach ($sales as $index => $sale) : ?>
                        <tr>
                            <td class="index pe-3"><?= $index + 1;  ?></td>
                            <td class=""><?php echo $sale['InvoiceNumber']; ?></td>
                            <td class=""><?php echo $sale['FirstName'] . ' ' . $sale['LastName']; ?></td>
                            <td><span class=""><?php echo $sale['Quantity'] * $sale['UnitPrice']; ?></span></td>
                            <td><span class=""><?php echo $sale['SaleDate']; ?></span></td>
                            <td><span class=""><?php echo $sale['PaymentStatus']; ?></span></td>
                            <td style="text-align:center">
                                <a href="viewProduct.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                                <abbr title="download pdf"><a href="../controllers/generateSalesInvoice_contr.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                <abbr title="print"><a href="../views/printSaleInvoice.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>





    <!-- Create new invoice -->
    <div class="page" id="newInvoice">

        <!-- the service modal -->
        <div id="overlay"></div>
        <div class="modal-container" id="changeModal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose Service</h5>
                    <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <table class="mt-3" id="serviceTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Volume</th>
                                <th>Price</th>
                                <th>Select</th> <!-- Add a new column for radio buttons -->
                            </tr>
                        </thead>
                        <tbody>

                            <?php $plans = getPlanData($connect); ?>
                            <?php if ($plans) : ?>
                                <?php foreach ($plans as $index => $plan) : ?>
                                    <tr>
                                        <td class=""><?php echo $plan['Name']; ?></td>
                                        <td><span class=""><?php echo $plan['Volume']; ?></span></td>
                                        <td><span class=""><?php echo $plan['Price']; ?></span></td>
                                        <td style="text-align: center;"><input type="radio" name="selectedRow"></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" id="saveBtn" class="btn btn-primary ml-3">Yes</button>
                    <button type="button" id="cancel" class="btn btn-danger ml-3">Cancel</button>
                </div>
            </div>
        </div>





















        <div class="newInvoice shadow-sm bg-body rounded">
            <!-- header -->
            <div class="header">

                <div class="companyInfo">
                    <div class="">
                        <h2>INVOICE</h2>
                    </div>
                    <div class="first">
                        <p class="website"><?= $settings[0]["Website"]; ?></p>
                        <p class="email"><?= $settings[0]["Email"]; ?></p>
                        <p class="phonenumber"><?= $settings[0]["PhoneNumber"]; ?></p>
                    </div>
                    <div class="second">
                        <p class="Address"><?= $settings[0]["Address"]; ?></p>
                        <p class="City"><?= $settings[0]["City"]; ?> <?= $settings[0]["Country"]; ?></p>
                        <p class="zipCode"><?= $settings[0]["Zipcode"]; ?></p>
                    </div>
                </div>
            </div>

            <!-- Client info -->
            <div class="secondContainer">
                <div class="clientInfo">
                    <select id="user_select" name="user_id" class="clientsSelect">
                        <option value="" selected hidden>--Search--</option>
                    </select>
                    <p>Billed To</p>
                    <h5 class="clientNames"></h5>
                    <h5 class="address">Nakuru, Pipeline</h5>
                    <h5 class="City">Nakuru City</h5>
                    <h5 class="zipcode">20100</h5>
                    <h5 class="country">Kenya</h5>

                </div>

                <div class="invoiceInfo">
                    <p>Invoice Number</p>
                    <abbr title="leave blank for automatic generation">
                        <h5><input type="text" style="border: none;" placeholder="INV00001" class="invoiceNumber"></h5>
                    </abbr>

                    <p class="issueDate">Date of Issue</p>
                    <h5><input type="date" class="paymentDate"></h5>
                    <p class="issueDate">Start Date</p>
                    <h5><input type="date" class="startDate"></h5>
                </div>
                <div class="invoiceTotal">
                    <p class="issueDate">Expire Date</p>
                    <h5><input type="date" class="expireDate"></h5>
                    <p>Invoice Total</p>
                    <h4 class="toptotal"><span class="currency"><?= $symbol; ?> </span> <span class="topTotal">00.00</span></h4>
                    <select name="" id="status">
                        <option value="" disabled selected>Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partial Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="addbuttons">
                <button type="button" class="btn btn-primary" onclick="addRow()">Add Blank Line</button>
                <button type="button" class="btn btn-primary" id="addService">Add Service</button>
            </div>

            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Volume</th>
                        <th>Qty/Months</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td colspan="3" class="border-0"></td>
                        <td colspan="" class="Subtotal">Subtotal</td>
                        <td class="subtotalAmount">00.00</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-0"></td>
                        <td colspan="" id="Tax">
                            Tax<br />
                            <span><?= $symbol; ?></span>
                            <input type="radio" name="taxType" value="<?= $symbol; ?>" checked id="dollarRadio">
                            <br />
                            <span>%</span>
                            <input type="radio" name="taxType" value="%" id="percentRadio">
                        </td>
                        <td class="taxAmount"><input type="text" placeholder="" class="tax"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-0"></td>
                        <td colspan="" class="Total">Total</td>
                        <td class="totalPrice"><span class="currency"><?= $symbol; ?> </span>0</td>
                    </tr>
                </tbody>
            </table>

        </div>


        <div class="addbuttons">
            <!-- <button type="button" class="btn btn-primary">Save</button> -->
            <button type="button" class="btn btn-primary" onclick="saveInvoice()">Save and close</button>

            <div id="errorMsg"></div>
        </div>
    </div>



    <?php

    $totalIncomeByStatus = getTotalIncomeByInvoiceStatus($connect);
    if ($totalIncomeByStatus) {
        $totalPaid = $totalIncomeByStatus['total_paid'];
        $totalPending = $totalIncomeByStatus['total_pending'];
        $totalCancelled = $totalIncomeByStatus['total_cancelled'];
        $totalPartiallyPaid = $totalIncomeByStatus['total_partially_paid'];

        // Use the total income values as needed
    } else {
        $totalPaid = '';
        $totalPending = '';
        $totalCancelled = '';
        $totalPartiallyPaid = '';
    }
    ?>





    <!-- Transaction Report page -->
    <div class="page" id="analytics">
        <h3 class="pb-2 mt-2 mb-2 border-bottom">Reports</h3>

        <div class="sales mt-5">
            <h5> Services Analytics</h5>
            <div class="content.main">
                <ul class="box-info">
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($totalPaid, 2); ?></p>
                            <h3 style="color: #2cce89;">Paid</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($totalPartiallyPaid, 2); ?></p>
                            <h3 style="color: #3C91E6;">Partially Paid</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($totalPending, 2); ?></p>
                            <h3 style="color: #FFCE26;">Pending</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($totalCancelled, 2); ?></p>
                            <h3 style="color: #FD7238;">Cancelled</h3>
                        </span>
                    </li>
                </ul>

            </div>


        </div>


        <?php

        $totalIncomeByStatus = getTotalIncomeBySalesStatus($connect);
        if ($totalIncomeByStatus) {
            $total_paid = $totalIncomeByStatus['total_paid'];
            $total_pending = $totalIncomeByStatus['total_pending'];
            $total_cancelled = $totalIncomeByStatus['total_cancelled'];
            $total_partially_paid = $totalIncomeByStatus['total_partially_paid'];

            // Use the total income values as needed
        } else {
            $total_paid = '';
            $total_pending = '';
            $total_cancelled = '';
            $total_partially_paid = '';
        }
        ?>
        <div class="Plan mt-5">
            <h5>Sales Analytics</h5>

            <div class="content.main">
                <ul class="box-info">
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($total_paid, 2); ?></p>
                            <h3 style="color: #2cce89;">Paid</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($total_partially_paid, 2); ?></p>
                            <h3 style="color: #3C91E6;">Partially Paid</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($total_pending, 2); ?></p>
                            <h3 style="color: #FFCE26;">Pending</h3>
                        </span>
                    </li>
                    <li>
                        <span class="text">
                            <p><?= $symbol . ' ' . number_format($total_cancelled, 2); ?></p>
                            <h3 style="color: #FD7238;">Cancelled</h3>
                        </span>
                    </li>
                </ul>

            </div>
        </div>
    </div>


</div>
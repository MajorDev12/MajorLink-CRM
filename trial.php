<?php

$expireDate = new DateTime('2024-02-14');
$lastPaymentDate = new DateTime('2024-07-14');
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

<?php
if (!isset($_GET["i"]) && !isset($_GET["c"])) {
    echo "Something went wrong";
    exit();
}

require_once  '../database/pdo.php';
require_once  '../modals/addSale_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

//get invoice data
$SaleID = $_GET["i"];
$clientID = $_GET["c"];

$sales = getSalesByID($connect, $SaleID);
//get clientInfo
$clientData = getClientDataById($connect, $clientID);

if (!$clientData) {
    echo "something went wrong";
    exit();
}

if (empty($sales)) {
    echo "something went wrong";
    exit();
}

//clientInfo
$firstName = $clientData["FirstName"];
$LastName = $clientData["LastName"];


//invoiceInfo
$invoiceNumber = $sales[0]["InvoiceNumber"];
$taxSymbol = $sales[0]["TaxSymbol"];
$saleDate = $sales[0]["SaleDate"];

$subtotal = $sales[0]["UnitPrice"] * $sales[0]["Quantity"];
$total = $sales[0]["Total"];
$tax = $sales[0]["Tax"];
$taxSymbol = $sales[0]["TaxSymbol"];


require "../includes/dompdf/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

//Set the Dompdf options

$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);


//Set the paper size and orientation
$dompdf->setPaper("A4", "portrait");




// Include the product data in the HTML template
// Generate HTML for products
$htmlProducts = '';

$htmlProducts .= '<tr>';
$htmlProducts .= '<td>' . $sales[0]["ProductName"] . '</td>';
$htmlProducts .= '<td>' . $sales[0]["Quantity"] . '</td>';
$htmlProducts .= '<td>' . $sales[0]["UnitPrice"] . '</td>';
$htmlProducts .= '<td>' . number_format($subtotal, 2) . '</td>';
$htmlProducts .= '</tr>';




// Load the HTML and replace placeholders with values from the form
$html = file_get_contents("../views/salesPdfTemplate.php");

$html = str_replace(
    ["{{ firstName }}", "{{ LastName }}", "{{ invoiceNumber }}", "{{ saleDate }}", "{{ total }}", "{{ tax }}", "{{ taxSymbol }}", "{{ htmlProducts }}", "{{ subtotal }}"],
    [$firstName, $LastName, $invoiceNumber, $saleDate, $total, $tax, $taxSymbol, $htmlProducts, number_format($subtotal, 2)],
    $html
);



$dompdf->loadHtml($html);

//Create the PDF and set attributes
$dompdf->render();

$dompdf->addInfo("Title", "An Example PDF"); // "add_info" in earlier versions of Dompdf


//Send the PDF to the browser

$dompdf->stream("invoice.pdf", ["Attachment" => 0]);


//Save the PDF file locally

$output = $dompdf->output();
file_put_contents("file.pdf", $output);

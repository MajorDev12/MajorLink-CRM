<?php
session_start();

if (!isset($_SESSION["invoiceID"]) && !isset($_SESSION["clientID"])) {
    echo "Something went wrong";
    exit();
}


require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

//get invoice data
$invoiceID = $_SESSION["invoiceID"];
$clientID = $_SESSION["clientID"];


$invoice = getInvoiceData($connect, $invoiceID);
//get clientInfo
$clientData = getClientDataById($connect, $clientID);

if (!$clientData) {
    echo "something went wrong";
    exit();
}

if (empty($invoice)) {
    echo "something went wrong";
    exit();
}

//clientInfo
$firstName = $clientData["FirstName"];
$LastName = $clientData["LastName"];


//invoiceInfo
$invoiceNumber = $invoice["InvoiceNumber"];
$taxSymbol = $invoice["TaxSymbol"];
$paymentDate = date("Y-m-d", strtotime($invoice["paymentDate"]));
$StartDate = date("Y-m-d", strtotime($invoice["StartDate"]));
$expireDate = date("Y-m-d", strtotime($invoice["DueDate"]));

$totalAmount = number_format($invoice["TotalAmount"], 2);
$tax = number_format($invoice["Taxamount"], 2);


//invoiceProducts
$products = getInvoiceProducts($connect, $invoiceID);


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
$subtotal = 0; // Initialize subtotal
foreach ($products as $product) {
    $htmlProducts .= '<tr>';
    $htmlProducts .= '<td>' . $product["Name"] . '</td>';
    $htmlProducts .= '<td>' . $product["Volume"] . '</td>';
    $htmlProducts .= '<td>' . $product["Qty"] . '</td>';
    $htmlProducts .= '<td>' . $product["Price"] . '</td>';
    $htmlProducts .= '<td>' . $product["Amount"] . '</td>';
    $htmlProducts .= '</tr>';

    // Update subtotal for each product
    $subtotal += $product["Amount"];
}


// Load the HTML and replace placeholders with values from the form
$html = file_get_contents("../views/pdfTemplate.php");

$html = str_replace(["{{ firstName }}", "{{ LastName }}", "{{ invoiceNumber }}", "{{ paymentDate }}", "{{ StartDate }}", "{{ expireDate }}", "{{ totalAmount }}", "{{ tax }}", "{{ taxSymbol }}", "{{ htmlProducts }}", "{{ subtotal }}"], [$firstName, $LastName, $invoiceNumber, $paymentDate, $StartDate, $expireDate, $totalAmount, $tax, $taxSymbol, $htmlProducts, number_format($subtotal, 2)], $html);



$dompdf->loadHtml($html);

//Create the PDF and set attributes
$dompdf->render();

$dompdf->addInfo("Title", "An Example PDF"); // "add_info" in earlier versions of Dompdf


//Send the PDF to the browser

$dompdf->stream("invoice.pdf", ["Attachment" => 0]);


//Save the PDF file locally

$output = $dompdf->output();
file_put_contents("file.pdf", $output);

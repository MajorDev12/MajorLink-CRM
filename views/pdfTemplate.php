<?php require "header.php"; ?>
<?php
session_start();

require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

$invoiceID = $_SESSION["invoiceID"];
?>
<style>
    .invoiceContainer {
        width: 100%;
        height: 100vh;
        position: relative;
        background-color: #F9F9F9;
        font-family: 'Poppins', sans-serif;
    }


    .header {
        background-color: #3C91E6;
    }

    .invoiceContainer h1 {
        color: #F9F9F9;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 4em;
    }

    .invoiceContainer .header p {
        color: #eee;
        font-size: 14px;
        text-align: end;
        line-height: 10px;
    }

    .invoiceContainer .header .companyInfo {
        padding: 2%;
    }

    /* second container */

    .invoiceContainer .secondContainer {
        position: relative;
        top: 5%;
        line-height: 5px;
    }

    .invoiceContainer .secondContainer label {
        color: #AAAAAA;
        font-size: 14px;
    }

    .invoiceContainer .secondContainer p {
        color: #342E37;
        font-size: 16px;
        padding: 10px 0;
    }



    .invoiceContainer .secondContainer .clientInfo {
        position: absolute;
        left: 5%;

    }

    .invoiceContainer .secondContainer .clientInfo p {
        padding: 5px 0;
    }

    .invoiceContainer .secondContainer .invoiceInfo {
        position: absolute;
        left: 40%;
    }


    .invoiceContainer .secondContainer .invoiceTotal {
        position: absolute;
        left: 75%;
    }

    .invoiceContainer .secondContainer .invoiceTotal .topTotal {
        padding-top: 0;
        font-size: 2em;
        color: #3C91E6;
    }

    .invoiceContainer .table {
        width: 100%;
        position: absolute;
        top: 65%;
    }

    .invoiceContainer .table thead {
        border-bottom: 3px solid #3C91E6;

    }

    .invoiceContainer .table thead tr th {
        padding-top: 10px;
        padding-bottom: 10px;
        margin-bottom: 30px;
        text-align: center;
        color: #3C91E6;
    }

    .invoiceContainer .table tbody tr td {
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .invoiceContainer .table tbody tr .space {
        border: none;
    }

    .invoiceContainer .table tbody tr .Subtotal,
    .invoiceContainer .table tbody tr .Tax {
        color: #AAAAAA;
    }

    .invoiceContainer .table tbody tr .totalPrice {
        color: #3C91E6;
    }
</style>


<div class="invoiceContainer">
    <!-- header -->

    <div class="header">

        <div class="companyInfo">
            <div class="">
                <h1>INVOICE</h1>
            </div>
            <div class="">
                <p class="website">www.majorlink.com</p>
                <p class="email">majorlink@gmail.com</p>
                <p class="phonenumber">(254) 718 317 726</p>
            </div>
            <div class="">
                <p class="Address">Pipeline, Nakuru</p>
                <p class="City">Nakuru City, Kenya</p>
                <p class="zipCode">20100</p>
            </div>
        </div>
    </div>


    <!-- Client info -->
    <div class="secondContainer">
        <div class="clientInfo">
            <label>Billed To</label>
            <p>{{ firstName }} {{ LastName }}</p>
            <p>Nakuru, Pipeline</p>
            <p>Nakuru City</p>
            <p>20100</p>
            <p>Kenya</p>
        </div>
        <div class="invoiceInfo">
            <label>Invoice Number</label>
            <p>{{ invoiceNumber }}</p>
            <label class="issueDate">Date of Issue</label>
            <p>{{ paymentDate }}</p>
            <label class="startDate">Start Date</label>
            <p>{{ StartDate }}</p>
        </div>
        <div class="invoiceTotal">
            <label class="expireDate">Expire Date</label>
            <p>{{ expireDate }}</p>
            <label>Invoice Total</label>
            <h4 class="topTotal"><span class="currency">$</span>{{ totalAmount }}</h4>
        </div>
    </div>



    <table class="table">
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
            {{ htmlProducts }}
            <!-- Add Subtotal, Tax, and Total rows here -->
            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Subtotal">Subtotal</td>
                <td class="">{{ subtotal }}</td>
            </tr>
            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Tax">Tax({{ taxSymbol }})</td>
                <td class="">{{ tax }}</td>
            </tr>

            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Total">Total</td>
                <td class="totalPrice">$ {{ totalAmount }}</td>
            </tr>
        </tbody>
    </table>

</div>

<?php require "footer.php"; ?>
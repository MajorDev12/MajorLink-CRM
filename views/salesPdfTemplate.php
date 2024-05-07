<?php require "header.php"; ?>
<?php require_once "style.config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addSale_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

?>
<style>
    .invoiceContainer {
        width: 100%;
        height: 9vh;
        position: relative;
        background-color: #F9F9F9;
        /* font-family: 'Poppins', sans-serif; */
        font-family: 'Lato', sans-serif;
        z-index: 1;
    }

    .header {
        background-color: #3C91E6;
        padding: 25px 15px;
        position: relative;
        top: -2%;
        height: 100px;
    }


    .invoiceContainer h2 {
        color: #F9F9F9;
        font-size: 2.5em;
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        margin-right: 50px;
        text-align: center;
    }

    .invoiceContainer .header p {
        color: #eee;
        font-size: 14px;
        text-align: end;
        line-height: 10px;
    }


    .invoiceContainer .companyInfo {
        line-height: 5px;
    }


    .invoiceContainer .companyInfo .first {
        position: absolute;
        top: 20%;
        left: 5%;
    }

    .invoiceContainer .companyInfo .second {
        position: absolute;
        left: 50%;
        text-align: end;
    }


    .invoiceContainer .companyInfo .third {
        position: absolute;
        left: 75%;
        text-align: end;
    }

    .invoiceContainer .status.paid {
        color: #2cce89;
        background-color: #ecfaf6;
    }

    .invoiceContainer .status.partially-paid {
        color: #FFCE26;
        background-color: #FFF2C6;
    }

    .invoiceContainer .status.pending {
        color: #FD7238;
        background-color: #FFE0D3;
    }

    .invoiceContainer .status.cancelled {
        color: #DB504A;
        background-color: #FFE0D3;
    }

    .invoiceContainer .header .companyInfo .second,
    .invoiceContainer .header .companyInfo .third {
        text-align: end;
    }



    /* second container */

    .invoiceContainer .secondContainer {
        position: relative;
        top: 7%;
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
        left: 10%;
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
        left: 70%;
    }

    .invoiceContainer .secondContainer .invoiceTotal .topTotal {
        font-size: 2em;
        color: #3C91E6;
    }

    .invoiceContainer .status {
        font-size: 18px;
        color: #3C91E6;
        text-align: center;
        border-radius: 10px;
        padding: 10px 5px;
    }

    .invoiceContainer .secondContainer .invoiceTotal h1 {
        margin-top: 10px;
        padding-top: 10%;
    }


    /* table */
    .invoiceContainer .table {
        width: 100%;
        position: absolute;
        top: 40%;
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
            <div class="first">
                <h2>INVOICE</h2>
            </div>
            <div class="second">
                <p class="website">{{ CompanyWebsite }}</p>
                <p class="email">{{ CompanyEmail }}</p>
                <p class="phonenumber">{{ CompanyPhoneNumber }}</p>
            </div>
            <div class="third">
                <p class="Address">{{ CompanyAddress }}</p>
                <p class="City">{{ CompanyCity }}, {{ CompanyCountry }}</p>
                <p class="zipCode">{{ CompanyZipcode }}</p>
            </div>
        </div>
    </div>


    <!-- Client info -->
    <div class="secondContainer">
        <div class="clientInfo">
            <label>Billed To</label>
            <p>{{ firstName }} {{ LastName }}</p>
            <p>{{ Address }}</p>
            <p>{{ City }}</p>
            <p>{{ Zipcode }}</p>
            <p>{{ Country }}</p>
        </div>
        <div class="invoiceInfo">
            <label>Invoice Number</label>
            <p>{{ invoiceNumber }}</p>
            <label class="issueDate">Date of Issue</label>
            <p>{{ saleDate }}</p>
        </div>
        <div class="invoiceTotal">
            <label>Invoice Total</label>
            <h4 class="topTotal"><span class="currency">{{ Symbol }} </span>{{ total }}</h4>

            <h1 class="status {{ statusClass }}">{{ Status }}</h1>
        </div>
    </div>



    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            {{ htmlProducts }}
            <!-- Add Subtotal, Tax, and Total rows here -->
            <tr>
                <td colspan="2" class="space"></td>
                <td colspan="" class="Subtotal">Subtotal</td>
                <td class="">{{ subtotal }}</td>
            </tr>
            <tr>
                <td colspan="2" class="space"></td>
                <td colspan="" class="Tax">Tax({{ taxSymbol }})</td>
                <td class="">{{ tax }}</td>
            </tr>

            <tr>
                <td colspan="2" class="space"></td>
                <td colspan="" class="Total">Total</td>
                <td class="totalPrice">{{ Symbol }} {{ total }}</td>
            </tr>
        </tbody>
    </table>

</div>

<?php require "footer.php"; ?>
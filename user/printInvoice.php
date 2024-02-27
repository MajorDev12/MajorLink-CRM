<?php require "../views/header.php"; ?>
<style>
    .invoiceContainer {
        width: 60%;
        height: 100vh;
        position: relative;
        left: 20%;
        background-color: #F9F9F9;
        font-family: 'Poppins', sans-serif;
    }

    .invoiceContainer h5 {
        color: #342E37;
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
    }

    .invoiceContainer .secondContainer p {
        color: #AAAAAA;
        font-size: 14px;
        text-align: end;
        line-height: 10px;
    }

    .invoiceContainer .secondContainer .clientInfo {
        position: absolute;
        left: 5%;
    }

    .invoiceContainer .secondContainer .invoiceInfo {
        position: absolute;
        left: 40%;
    }

    .invoiceContainer .secondContainer .invoiceInfo .issueDate {
        padding-top: 10px;
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

    .invoiceContainer table {
        width: 100%;
        position: absolute;
        top: 65%;
    }

    .invoiceContainer table thead {
        border-bottom: 3px solid #3C91E6;

    }

    .invoiceContainer table thead tr th {
        padding-top: 10px;
        padding-bottom: 10px;
        margin-bottom: 30px;
        text-align: center;
        color: #3C91E6;
    }

    .invoiceContainer table tbody tr td {
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .invoiceContainer table tbody tr .space {
        border: none;
    }

    .invoiceContainer table tbody tr .Subtotal,
    .invoiceContainer table tbody tr .Tax {
        color: #AAAAAA;
    }

    .invoiceContainer table tbody tr .totalPrice {
        color: #3C91E6;
    }

    @media print {
        .invoiceContainer {
            width: 100%;
        }

        .actions {
            display: none;
        }

        h5 {
            font-size: 18px;
        }
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
            <p>Billed To</p>
            <h5>Major Nganga</h5>
            <h5>Nakuru, Pipeline</h5>
            <!-- <h5>Nakuru City, Kenya</h5> -->
            <h5>20100</h5>
        </div>
        <div class="invoiceInfo">
            <p>Invoice Number</p>
            <h5>INV00000</h5>
            <p class="issueDate">Date of Issue</p>
            <h5>2024/02/23</h5>
        </div>
        <div class="invoiceTotal">
            <p>Invoice Total</p>
            <h4 class="topTotal"><span class="currency">$</span>4500.00</h4>
        </div>
    </div>



    <table>
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
                <td>Basic</td>
                <td>5Mbps</td>
                <td>1</td>
                <td>1500</td>
                <td>1500</td>
            </tr>
            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Subtotal">Subtotal</td>
                <td class="">4500</td>
            </tr>
            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Tax">Tax(0%)</td>
                <td class="">0</td>
            </tr>

            <tr>
                <td colspan="3" class="space"></td>
                <td colspan="" class="Total">Total</td>
                <td class="totalPrice">$ 4500</td>
            </tr>
        </tbody>
    </table>

</div>

<?php require "../views/footer.php"; ?>
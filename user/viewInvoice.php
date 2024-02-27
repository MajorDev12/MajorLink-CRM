<?php require "../views/header.php"; ?>
<style>
    .invoiceContainer {
        width: 60%;
        height: 100vh;
        position: relative;
        left: 20%;
        background-color: var(--light);
        font-family: 'Poppins', sans-serif;
    }

    .invoiceContainer h5 {
        color: var(--dark);
    }

    .invoiceContainer .header {
        background-color: var(--blue);
        padding: 10px;
    }

    .invoiceContainer .header h1 {
        color: var(--light);
        font-size: 3.5em;
    }

    .invoiceContainer .header p {
        color: var(--grey);
        font-size: 14px;
        line-height: 10px;
    }


    .invoiceContainer .secondContainer {
        margin: 7% 0;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        /* background-color: var(--yellow); */
    }

    .invoiceContainer .secondContainer p {
        color: var(--dark-grey);
        font-size: 14px;
    }

    .invoiceContainer .secondContainer h5 {
        color: var(--dark);
        font-size: 16px;
    }

    .invoiceContainer table {
        margin-top: 10%;
    }

    .invoiceContainer .table tr .Subtotal,
    .invoiceContainer .table tr .Tax {
        color: var(--dark-grey);
    }

    .invoiceContainer .secondContainer .status {
        background-color: var(--green);
        color: var(--light-green);
        padding: 0px 5px;
        border-radius: 10px;
        text-align: center;
    }

    .actions {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: 2% 0;
    }

    .actions a {
        margin-left: 20px;
    }

    .invoiceContainer footer {
        position: absolute;
        top: 100%;
    }

    @media print {
        body {
            scrollbar-width: thin;
            /* Firefox */
            scrollbar-color: transparent transparent;
            /* Firefox */
            overflow: -moz-scrollbars-none;
            /* Firefox */
        }

        /* For Webkit based browsers (Chrome, Safari, Edge) */
        ::after {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 9999;
            /* background-color: white; */
            /* Set the background color of the overlay */
        }
    }

    @media print {

        .invoiceContainer {
            width: 80%;
            position: relative;
            left: 10%;
            background-color: #3C91E6;
            font-family: 'Poppins', sans-serif;
        }

        .invoiceContainer .header p {
            font-size: 12px;
        }

        .actions {
            display: none;
        }

        h5 {
            font-size: 18px;
        }

        footer {
            display: none;
        }
    }
</style>

<div class="actions">
    <a href="invoices.php" class="btn btn-primary" target="_blank">Go Back</a>
    <a href="viewInvoice.php" class="btn btn-primary">View Pdf</a>
    <a href="viewInvoice.php" class="btn btn-primary">Download Pdf</a>
    <a href="viewInvoice.php" class="btn btn-primary" onclick="printPage()">Print</a>
</div>
<div class="invoiceContainer shadow-sm bg-body rounded">
    <!-- header -->
    <script>
        function printPage() {
            window.print();
        }
    </script>
    <div class="header">

        <div class="companyInfo">
            <div class="">
                <h1>INVOICE</h1>
            </div>
            <div class="first">
                <p class="website">www.majorlink.com</p>
                <p class="email">majorlink@gmail.com</p>
                <p class="phonenumber">(254) 718 317 726</p>
            </div>
            <div class="second">
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
            <tr>
                <td>Basic</td>
                <td>5Mbps</td>
                <td>1</td>
                <td>1500</td>
                <td>1500</td>
            </tr>
            <tr>
                <td colspan="3" class="border-0"></td>
                <td colspan="" class="Subtotal">Subtotal</td>
                <td class="">4500</td>
            </tr>
            <tr>
                <td colspan="3" class="border-0"></td>
                <td colspan="" class="Tax">Tax(0%)</td>
                <td class="">0</td>
            </tr>

            <tr>
                <td colspan="3" class="border-0"></td>
                <td colspan="" class="Total">Total</td>
                <td class="totalPrice">$ 4500</td>
            </tr>
        </tbody>
    </table>
    <footer>
        note: if this message doest belong to you, ignore
    </footer>
</div>

<?php require "../views/footer.php"; ?>
<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/addInvoice_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$clientData = getClientsNames($connect);
$invoicesData = getAllInvoices($connect);
?>
<?php require_once "header.php"; ?>

<style>
    .page {
        width: 100%;
        height: 100%;
    }

    .newInvoice {
        width: 80%;
        min-height: 130vh;
        position: relative;
        left: 10%;
        background-color: var(--light);
        font-family: 'Poppins', sans-serif;
        padding: 5%;
    }

    .newInvoice h5 {
        color: var(--dark);
    }

    .newInvoice .header {
        background-color: var(--blue);
        padding: 20px;
        position: relative;
    }

    .newInvoice .header h1 {
        color: var(--light);
        font-size: 3.5em;
        padding-bottom: 10px;
    }

    .newInvoice .header p {
        color: var(--grey);
        font-size: 14px;
        line-height: 10px;
    }

    .newInvoice .header .companyInfo .first {
        padding-bottom: 10px;
    }

    .newInvoice .secondContainer {
        margin: 7% 0;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        /* background-color: var(--yellow); */
    }

    .newInvoice .secondContainer p {
        color: var(--dark-grey);
        font-size: 14px;
    }

    .newInvoice .secondContainer h5 {
        color: var(--dark);
        font-size: 16px;
    }

    .newInvoice .secondContainer .clientsSelect {
        width: 140%;
    }

    .newInvoice input {
        border: none;
        color: var(--dark);
    }

    .newInvoice input::placeholder {
        color: var(--dark);
        font-size: 16px;
        font-weight: 500;
    }

    .newInvoice .secondContainer .topTotal {
        color: var(--blue);
    }

    .newInvoice table {
        margin-top: 10%;
    }

    .newInvoice .table thead {
        margin-bottom: 10px;
    }

    .newInvoice .table thead tr th {
        color: var(--blue);
        border-bottom: 2px solid var(--blue);

    }

    .newInvoice .table tr .Subtotal,
    .newInvoice .table tr #Tax {
        color: var(--dark-grey);
    }

    .newInvoice .table input {
        width: 80%;
    }

    .newInvoice .secondContainer .status {
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

    .newInvoice footer {
        text-align: center;
        position: relative;
        bottom: 0%;
        width: 90%;
        color: var(--dark-grey);
    }



    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content table td {
        padding: 16px 0;
        text-align: left;
    }

    .main-content .content table td .icon {
        background-color: var(--blue);
        border-radius: 5px;
        padding: 4px;
        cursor: pointer;
    }

    .main-content .content table td .view {
        background-color: var(--blue);
    }

    .main-content .content table td .pdf {
        background-color: var(--yellow);
    }

    .main-content .content table td .print {
        background-color: var(--orange);
    }

    .main-content .content table td .icon img {
        width: 20px;
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .main-content .content .tablenav {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content .content .tablenav p {
        display: flex;
        justify-content: start;
    }

    .main-content .content .tablenav .pagination {
        display: flex;
        justify-content: end;
    }


    @media screen and (max-width: 920px) {
        .main-content .content {
            min-width: 700px;
        }

        .main-content .content .head {
            min-width: 900px;
        }

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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Invoices</a>
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
                    <div class="tabs">
                        <button type="button" class="btn active">All</button>

                        <button type="button" class="btn active">Paid</button>
                        <button type="button" class="btn active">Unpaid</button>
                        <!-- <button type="button" class="btn active">Reports</button> -->
                        <button type="button" class="btn active">New Invoice</button>
                        <button type="button" class="btn active">Recurring</button>
                        <button type="button" class="btn active">New Recurring</button>
                    </div>
                </div>
            </div>


            <div class="content tab-content">

                <div class="page active" id="all">
                    <div class="order">

                        <div class="head">
                            <h3>ALL Records</h3>
                            <i class='bx bx-search'></i>
                            <i class='bx bxs-printer'></i>
                            <i class='bx bxs-spreadsheet'></i>
                            <i class='bx bx-filter'></i>
                        </div>



                        <table class="mt-5">
                            <thead>
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
                            <tbody id="tableBody">
                                <tr>
                                    <td>INV0001</td>
                                    <td>Major Nganga</td>
                                    <td>2000</td>
                                    <td>13/03/24</td>
                                    <td>13/02/24</td>
                                    <td>Paid</td>
                                    <td class="actions">
                                        <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                        <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                        <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV0002</td>
                                    <td>Collings wahome</td>
                                    <td>1500</td>
                                    <td>12/02/23</td>
                                    <td>12/01/23</td>
                                    <td>Paid</td>
                                    <td class="actions">
                                        <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                        <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                        <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav class="tablenav mt-5" aria-label="Page navigation">
                            <p id="paginationInfo" class=""></p>
                            <ul class="pagination" id="pagination"></ul>
                        </nav>




                    </div>
                </div>







                <div class="page" id="paid">
                    <h3>Paid Records</h3>
                    <table class="mt-5">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td>INV0001</td>
                                <td>Major Nganga</td>
                                <td>2000</td>
                                <td>13/03/24</td>
                                <td>13/02/24</td>
                                <td>Paid</td>
                                <td class="actions">
                                    <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                    <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                    <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                </td>
                            </tr>
                            <tr>
                                <td>INV0002</td>
                                <td>Collings wahome</td>
                                <td>1500</td>
                                <td>12/02/23</td>
                                <td>12/01/23</td>
                                <td>Paid</td>
                                <td class="actions">
                                    <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                    <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                    <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>







                <div class="page" id="unpaid">
                    <h3>Unpaid Records</h3>
                    <table class="mt-5">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td>INV0001</td>
                                <td>Major Nganga</td>
                                <td>2000</td>
                                <td>13/03/24</td>
                                <td>13/02/24</td>
                                <td>Paid</td>
                                <td class="actions">
                                    <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                    <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                    <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                </td>
                            </tr>
                            <tr>
                                <td>INV0002</td>
                                <td>Collings wahome</td>
                                <td>1500</td>
                                <td>12/02/23</td>
                                <td>12/01/23</td>
                                <td>Paid</td>
                                <td class="actions">
                                    <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                    <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                    <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>






                <div class="page" id="newInvoice">
                    <div class="tabs mb-2">
                        <!-- <button type="button" class="btn active">Service Invoice</button>
                    <button type="button" class="btn active">Product Invoice</button> -->
                    </div>

                    <div class="newInvoice shadow-sm bg-body rounded">
                        <!-- header -->
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
                                    <p class="City">Nakuru City</p>
                                    <p class="country">Kenya</p>
                                    <p class="zipCode">20100</p>
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
                                <h4 class="topTotal"><span class="currency">$</span>00.00</h4>

                            </div>
                        </div>

                        <div class="addbuttons">
                            <button type="button" class="btn btn-primary" onclick="addRow()">Add Blank Line</button>
                            <!-- <button type="button" class="btn btn-primary">Add Service</button> -->
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
                                    <td class="subtotalAmount">0</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td colspan="" id="Tax">
                                        Tax<br />
                                        <span>$</span>
                                        <input type="radio" name="taxType" value="$" checked id="dollarRadio">
                                        <br />
                                        <span>%</span>
                                        <input type="radio" name="taxType" value="%" id="percentRadio">
                                    </td>
                                    <td class="taxAmount"><input type="text" placeholder="" class="tax"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td colspan="" class="Total">Total</td>
                                    <td class="totalPrice">0</td>
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





                <div class="page" id="recurring"></div>






                <div class="page" id="newrecurring"></div>












            </div>

            <?php require_once "footer.php"; ?>

            <script>
                // tab navigation
                initializeTabs(".tabs button", ".tab-content .page");

                const invoicesData = <?php echo json_encode($invoicesData); ?>;





                // Function to render pagination
                function renderPagination(totalPages, currentPage) {
                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';

                    for (let i = 1; i <= totalPages; i++) {
                        const li = document.createElement('li');
                        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                        li.innerHTML = `<a class="page-link" href="#" onclick="loadData(${i})">${i}</a>`;
                        pagination.appendChild(li);
                    }
                }

                // Function to load data based on page number
                function loadData(page) {
                    const itemsPerPage = 5;
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;
                    const dataToShow = invoicesData.slice(startIndex, endIndex);

                    renderTableRows(dataToShow, page, itemsPerPage);
                    renderPagination(Math.ceil(invoicesData.length / itemsPerPage), page);
                }





                // Function to render table rows
                function renderTableRows(data, currentPage, itemsPerPage) {
                    const tableBody = document.getElementById('tableBody');
                    tableBody.innerHTML = '';
                    const actions = `
                <abbr title="View"><a href="viewInvoice.php" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>`;

                    const startIndex = (currentPage - 1) * itemsPerPage;

                    data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        // console.log(item.StartDate);
                        // console.log(formatDate(item.StartDate));
                        // return;

                        row.innerHTML = `
            <td>${startIndex + index + 1}</td>
            <td>${item.InvoiceNumber}</td>
            <td>${item.FirstName} ${item.LastName}</td>
            <td>${item.TotalAmount}</td>
            <td>${item.startDate}</td>
            <td>${item.DueDate}</td>
            <td>${item.Status}</td>
            <td>${actions}</td>
        `;
                        tableBody.appendChild(row);
                    });

                    // Update the paginationInfo
                    const endIndex = Math.min(startIndex + itemsPerPage - 1, data.length);
                    const paginationInfo = document.getElementById('paginationInfo');
                    paginationInfo.textContent = `${startIndex + 1} to ${endIndex} of ${data.length}`;
                }

                // Initial load (page 1)
                loadData(1);
                // Function to format date
                function formatDate(dateTime) {
                    const date = new Date(dateTime);

                    const options = {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    };
                    const formattedDate = date.toLocaleDateString('en-US', options);

                    return formattedDate;
                }




                // Get payments data from PHP and convert it to a JavaScript array


















                var selectedClientId;
                var taxSymbol = "$";
                var dollarRadio = document.getElementById('dollarRadio');
                var percentRadio = document.getElementById('percentRadio');

                $(document).ready(function() {
                    var customerList = [];

                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>"
                        });
                    <?php endforeach; ?>

                    $("#user_select").select2({
                        data: customerList
                    });



                    // Attach change event listener
                    $("#user_select").on("change", function() {
                        selectedClientId = $(this).val();


                        // Make an AJAX request to get client data
                        $.ajax({
                            url: '../controllers/getClientInfo_contr.php',
                            type: 'GET',
                            data: {
                                clientId: selectedClientId
                            },
                            success: function(response) {
                                // Update the client information based on the response
                                $('.clientNames').text(response.FirstName + ' ' + response.LastName);
                                // $('.address').text(response.address);
                                // $('.City').text(response.PrimaryEmail);
                                // $('.zipcode').text(response.zipcode);
                                // $('.country').text(response.country);
                            },
                            error: function(error) {
                                console.error('Error fetching client data:', error);
                            }
                        });
                    });


                });








                document.addEventListener('DOMContentLoaded', function() {
                    var clientsSelect = document.querySelector(".clientsSelect").value;

                    // Add event listeners to radio buttons
                    dollarRadio.addEventListener('change', function() {
                        if (dollarRadio.checked) {
                            percentRadio.checked = false;
                            taxSymbol = "$";
                        }
                        updateTotal();
                    });

                    percentRadio.addEventListener('change', function() {
                        if (percentRadio.checked) {
                            dollarRadio.checked = false;
                            taxSymbol = "%";
                        }
                        updateTotal();
                    });

                    document.getElementById("dataTable").addEventListener('input', function(event) {
                        updateAmount();
                        updateSubtotal();
                        updateTotal();
                    });


                    // Add event listener to the tax input field
                    var taxInput = document.querySelector('.tax');
                    taxInput.addEventListener('input', updateTotal);

                    // Function to update the total
                    function updateTotal() {
                        var subtotalAmount = parseFloat(document.querySelector('.subtotalAmount').textContent) || 0;
                        var taxAmount = parseFloat(taxInput.value) || 0;
                        var taxType = document.querySelector('input[name="taxType"]:checked').value;

                        var total = 0;

                        if (taxType === '$') {
                            total = subtotalAmount + taxAmount;
                        } else if (taxType === '%') {
                            var taxPercentage = (taxAmount / 100) * subtotalAmount;
                            total = subtotalAmount + taxPercentage;
                        }

                        // Update the total price element
                        var totalPriceElement = document.querySelector('.totalPrice');
                        var topTotalElement = document.querySelector('.topTotal');
                        if (totalPriceElement && topTotalElement) {
                            totalPriceElement.textContent = total.toFixed(2);
                            topTotalElement.textContent = total.toFixed(2);
                        }
                    }


                    // Initial calculation
                    updateTotal();





                });



                // Function to create a new row
                function addRow() {
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var newRow = table.insertRow(table.rows.length - 3); // Insert before the subtotal row

                    // Add cells to the new row
                    for (var i = 0; i < 5; i++) {
                        var cell = newRow.insertCell(i);

                        if (i < 4) {
                            // For cells other than the last one
                            var input = document.createElement("input");
                            input.type = "text";

                            // Add classes and event listeners based on conditions
                            if (i === 2 || i === 3) {
                                input.classList.add(i === 2 ? 'months' : 'price');
                                input.addEventListener('input', updateAmount);
                            }

                            cell.appendChild(input);
                        } else {
                            // For the last cell
                            cell.classList.add('amount');
                            cell.textContent = '0';
                        }
                    }
                }

                // Function to update amount based on input values
                function updateAmount() {
                    // Get the input element that triggered the event
                    var input = event.target;

                    // Find the closest ancestor tr element
                    var row = input;
                    while (row && row.tagName !== 'TR') {
                        row = row.parentNode;
                    }

                    if (!row) {
                        console.error('Could not find the closest tr element.');
                        return;
                    }

                    // Rest of the code remains the same...
                    var monthsValue = parseFloat(row.querySelector('.months').value) || 0;
                    var priceValue = parseFloat(row.querySelector('.price').value) || 0;
                    var amount = monthsValue * priceValue;

                    // Update the "amount" cell
                    row.querySelector('.amount').textContent = isNaN(amount) ? 'NaN' : amount.toFixed(2);

                    // Recalculate subtotal
                    updateSubtotal();
                }


                // Function to calculate and update subtotal
                function updateSubtotal() {
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var rows = table.getElementsByTagName('tr');
                    var subtotal = 0;

                    // Iterate through rows and calculate subtotal
                    for (var i = 0; i < rows.length; i++) {
                        var amountCell = rows[i].querySelector('.amount');
                        if (amountCell) {
                            var amountValue = parseFloat(amountCell.textContent) || 0;
                            subtotal += amountValue;
                        }
                    }

                    // Update the subtotalAmount element
                    var subtotalAmountElement = document.querySelector('.subtotalAmount');
                    if (subtotalAmountElement) {
                        subtotalAmountElement.textContent = subtotal.toFixed(2);
                    }

                }

                // Attach event listener to recalculate on any input change
                document.getElementById("dataTable").addEventListener('input', function(event) {
                    updateAmount();
                });

                // Initial calculation
                updateSubtotal();


                function saveInvoice() {

                    //invoice info
                    var invoiceNumber = document.querySelector(".invoiceNumber");
                    var paymentDate = document.querySelector(".paymentDate");
                    var startDate = document.querySelector(".startDate");
                    var expireDate = document.querySelector(".expireDate");




                    if (!selectedClientId) {
                        displayMessage("errorMsg", "Choose the recipient first", true);
                        return;
                    }

                    // Collect invoice products data from the table
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var rows = table.getElementsByTagName('tr');
                    var tax = document.querySelector(".tax").value;
                    var invoiceProducts = [];
                    var subtotalAmount = parseFloat(document.querySelector('.subtotalAmount').textContent) || 0; // Get the initial subtotal
                    var totalPrice = parseFloat(document.querySelector('.totalPrice').textContent) || 0; // Get the initial subtotal


                    for (var i = 0; i < rows.length - 3; i++) { // Exclude the subtotal and tax rows
                        var cells = rows[i].getElementsByTagName('td');
                        var product = cells[0].querySelector('input').value;
                        var volume = cells[1].querySelector('input').value;
                        var qty = cells[2].querySelector('input').value;
                        var price = cells[3].querySelector('input').value;
                        var price = cells[3].querySelector('input').value;

                        // Get the amount from the last td
                        var amount = cells[4].textContent;

                        // Add the product data to the array
                        invoiceProducts.push({
                            product: product,
                            volume: volume,
                            qty: qty,
                            price: price
                        });
                    }


                    // console.log(tax)
                    // return


                    var formData = new FormData();
                    formData.append("selectedClientId", selectedClientId);
                    formData.append("invoiceNumber", invoiceNumber.value);
                    formData.append("paymentDate", paymentDate.value);
                    formData.append("startDate", startDate.value);
                    formData.append("expireDate", expireDate.value);
                    formData.append("tax", tax);
                    formData.append("taxSymbol", taxSymbol);
                    formData.append("subtotalAmount", subtotalAmount);
                    formData.append("totalPrice", totalPrice);
                    formData.append("invoiceProducts", JSON.stringify(invoiceProducts));

                    // Perform fetch API request
                    fetch('../controllers/addInvoice_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.addInvoice && data.saveProducts) {
                                window.location.href = "viewInvoice.php";
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });









                }





                // error message function
                function displayMessage(messageElement, message, isError, ) {
                    var targetElement = document.getElementById(messageElement);
                    targetElement.innerText = message;

                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 1000);
                }
            </script>
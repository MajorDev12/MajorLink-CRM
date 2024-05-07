<style>
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
        /* background-color: var(--blue); */
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .tablenav {
        display: flex;
        justify-content: start;
    }
</style>




<div class="page invoice">

    <h4 class="pb-2 pl-2 mb-5 border-bottom"><?= $clientData["FirstName"] . ' ' . $clientData["LastName"]; ?></h4>

    <a href="invoice.php" class="btn btn-primary pb-2 mb-5 border-bottom">New Invoice</a>

    <table class="mt-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice Number</th>
                <th>Amount</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php $counter = 1; ?>
            <?php if ($invoicesData) : ?>
                <?php foreach ($invoicesData as $index => $invoice) : ?>
                    <tr>
                        <td class="index pe-3"><?= $index + 1;  ?></td>
                        <td class=""><?php echo $invoice['InvoiceNumber']; ?></td>
                        <td><span class=""><?php echo $invoice['TotalAmount']; ?></span></td>
                        <td><span class=""><?php echo $invoice['StartDate']; ?></span></td>
                        <td><span class=""><?php echo $invoice['DueDate']; ?></span></td>
                        <td><span class=""><?php echo $invoice['Status']; ?></span></td>
                        <td>
                            <a href="viewInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                            <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                            <abbr title="print"><a href="printInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
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
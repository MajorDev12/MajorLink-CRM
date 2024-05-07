<div class="page accounting">
    <a href="viewTransaction.php" class="btn btn-primary mb-3">View Transactions</a>


    <div class="pb-2 mb-4 border-bottom">
        <h4>Account Summary</h4>
    </div>

    <div id="accountSummary" class="text-end mt-3">
        <h4 class="border-bottom">Total Paid: <span class="text-primary"><?= $symbol; ?> <?= number_format($clientTotalAmount['totalPaid'], 2); ?></span></h4>
        <h4 class="border-bottom">Total Partially Paid: <span class="text-info"><?= $symbol; ?> <?= number_format($clientTotalAmount['totalPartiallyPaid'], 2); ?></span></h4>
        <h4 class="border-bottom">Total Pending: <span class="text-warning"><?= $symbol; ?> <?= number_format($clientTotalAmount['totalPending'], 2); ?></span></h4>
        <h4 class="border-bottom">Total Cancelled: <span class="text-danger"><?= $symbol; ?> <?= number_format($clientTotalAmount['totalCancelled'], 2); ?></span></h4>
        <h3 class="pt-2">Total: <span class="text-success"><?= $symbol; ?> <?= number_format($clientTotalAmount['totalPaid'] + $clientTotalAmount['totalPartiallyPaid'], 2); ?></span></h3>
    </div>


</div>
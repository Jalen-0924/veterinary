<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Sales List</h1>
    <?php if(isset($start_date) && isset($end_date)): ?>
        <h2 class="card-title">Period: <?= date('F d, Y', strtotime($start_date)); ?> - <?= date('F d, Y', strtotime($end_date)); ?></h2>
    <?php endif; ?>
</div>

<div class="col-12sm-12">
    <div class="card">
        <div class="card-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Services</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($sales)): ?>
                        <?php foreach($sales as $row): ?>
                            <tr>
                                <td><?= date('F d, Y', strtotime($row['date']))?></td>
                                <td><?= $row['ser_name'] ?? ''; ?></td>
                                <td><?= $row['ser_price'] ?? ''; ?></td>
                                <td><?= $row['total']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No sales found for this month.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Profit:</th>
                        <th>₱<?= number_format($totalProfit, 2); ?></th>
                    </tr>
                </tfoot>
            </table>

            <?php if(!empty($sales)): ?>
                <div class="form-group mt-3" style="text-align:right">
                    <a href="<?= base_url('sales/downloadExcelServices?start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                       target='_blank' class="btn btn-primary mt-3">
                        <i class="align-middle" data-feather="download"></i> Excel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

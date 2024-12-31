<style>
.badge {
    border: none;
}

.alert.alert-danger {
    background: #ff1a6c57;
    color: #fff;
    padding: 5px 10px;
    border: 1px solid #ff1a6c33;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #48d79b94;
    color: #fff;
    padding: 5px 10px;
    border: 1px solid #48d79b94;
    border-radius: 5px;
    margin-bottom: 20px;
}

#action {
    color: white;
}

</style>


<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Appointment List </h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">

            <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('success')):?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif;?>

            <table class="table table-bordered table-hover display nowrap" id="mytable">
    <thead>
        <tr>
            <th>Sr</th>
            <th>Pet Names</th>
            <th>Date</th>
            <th>Services</th>
            <th>Timeslot</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $grouped_appointments = [];
        
        // Group appointments by date and timeslot
        foreach ($appointments as $row) {
            $key = $row['date'] . '_' . $row['slot'];
            if (!isset($grouped_appointments[$key])) {
                $grouped_appointments[$key] = [
                    'date' => $row['date'],
                    'services' => $row['services'],
                    'slot' => $row['slot'],
                    'status' => $row['status'],
                    'invoice_id' => $row['invoice_id'],
                    'pets' => []
                ];
            }
            $grouped_appointments[$key]['pets'][] = $row['name'];
        }
        
        foreach ($grouped_appointments as $appointment) { ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= implode(', ', $appointment['pets']) ?></td>
            <td><?= date('F d, Y', strtotime($appointment['date'])) ?></td>
            <td><?= $appointment['services'] ?></td>
            <td><button class="badge bg-primary light" style="padding:3px 10px"><?= $appointment['slot'] ?></button></td>
            <td>
                <button class="badge <?= $appointment['status'] == 'Pending' ? 'bg-info' : 'bg-success' ?> light" style="padding:3px 10px">
                    <?= $appointment['status'] ?>
                </button>
                <?php if ($appointment['status'] == 'Confirm' && isset($appointment['invoice_id'])): ?>
                    <a href="<?= base_url('billing/report/print/'.$appointment['invoice_id']); ?>" class="badge bg-primary">
                        <i class="align-middle" data-feather="printer"></i> View Report
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$('.delete_record').click(function() {
    var url = $(this).attr('data-ids');
    Swal.fire({
        icon: 'error',
        html: 'Are you sure you want to delete this record?',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = url;
        }
    });
})
</script>

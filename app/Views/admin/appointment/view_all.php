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
</style>


<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Appointment List</h1>
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

          <div class="mb-3 d-flex justify-content-end">
    <select class="form-select form-select-sm" id="appointmentFilter" onchange="filterAppointments()" style="width: auto; max-width: 150px;">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="today">Today</option>
        <option value="tomorrow">Tomorrow</option>
    </select>
</div>

<table class="table table-bordered table-hover" id="mytable">
    <thead>
        <tr>
            <th>Sr</th>
            <th>Owner Name</th>
            <th>Pet Name</th>
            <th>Services</th>
            <th>Date</th>
            <th>Timeslot</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $i = 1;
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day')); 
    $todayFlag = false; 
    
    if (is_array($appointment) && !empty($appointment)) {
        foreach ($appointment as $row) {
            $isToday = ($row['date'] == $today);
            $isTomorrow = ($row['date'] == $tomorrow); 
            $isOvertime = ($row['date'] < $today); 
            if ($isToday) {
                $todayFlag = true;  
            }
    ?>
   <tr class="appointment-row" 
        data-status="<?= $row['status'] ?>" 
        data-date="<?= date('F d, Y', strtotime($row['date']))?>">
        <td><?= $i++ ?></td>
        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['services'] ?></td>
        <td>
            <?= date('F d, Y', strtotime($row['date'])) ?>
            <?php if ($isToday): ?>
                <span class="badge" style="background-color:#28a475; color:#fff;">Today</span>
            <?php elseif ($isTomorrow): ?>
                <span class="badge" style="background-color:#007bff; color:#fff;">Tomorrow</span>
            <?php elseif ($isOvertime): ?>
                <span class="badge" style="background-color:#ff0000; color:#fff;">Overtime</span>
            <?php endif; ?>
        </td>
        <td><button class="badge bg-primary light" style="padding:3px 10px"><?= $row['slot'] ?></button></td>
        <td>
            <?php 
            if ($row['status'] == 'Confirm') {
                echo '<button class="badge bg-success" style="padding:3px 10px">Confirmed</button>';
            } elseif ($row['status'] == 'Decline') {
                echo '<button class="badge bg-danger" style="padding:3px 10px">Declined</button>';
            } else {
                echo '<button class="badge bg-warning" style="padding:3px 10px">' . $row['status'] . '</button>';
            }
            ?>
        </td>
        <td>
            <?php if($row['status'] == 'Confirm'){ ?>
                <a href="<?= base_url('billing/report/print/'); ?>/<?= $row['invoice_id'] ?>" class="badge bg-primary">
                    <i class="align-middle" data-feather="file"></i> View Report
                </a>
            <?php } else if($row['status'] == 'Pending') { ?>
                <a href="<?= base_url('appointment/start_appointment/'.$row['id']) ?>" class="badge bg-success">
                    <i class="align-middle" data-feather="edit-2"></i> Start
                </a>
                <a href="<?= base_url('appointment/edit/'.$row['id']) ?>" class="badge bg-info">
                    <i class="align-middle" data-feather="edit"></i> Edit
                </a>
            <?php } ?>
            <a href="javascript:void(0)" data-ids="<?= base_url('appointment/delete/'.$row['id']); ?>" class="badge bg-danger delete_record">
                <i class="align-middle" data-feather="trash"></i> Delete
            </a>
        </td>
    </tr>

    <?php 
        } 
    }  
    ?>
</tbody>

</table>

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
                window.location = url
            }
        });
    });

    function filterAppointments() {
        var filter = document.getElementById("appointmentFilter").value;
        var rows = document.querySelectorAll(".appointment-row");

        rows.forEach(function(row) {
            var status = row.getAttribute("data-status");
            var appointmentDate = row.getAttribute("data-date");

         
            var today = new Date().toISOString().split('T')[0];
            var tomorrow = new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0];

            if (filter === "pending" && status !== "Pending") {
                row.style.display = "none";
            } else if (filter === "today" && appointmentDate !== today) {
                row.style.display = "none";
            } else if (filter === "tomorrow" && appointmentDate !== tomorrow) {
                row.style.display = "none";
            } else {
                row.style.display = ""; 
            }
        });
    }
</script>

<style>
    .badge {
    border: none;
}


#appointment:hover,
#owners:hover,
#pets:hover,
#vet:hover{
box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>


<h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

<div class="row">
           <div class="col-sm-3">           
    <div class="card">
        <div class="card-body" id="appointment" style="cursor: pointer;" onclick="window.location.href='<?= site_url('admin/appointment/view_all/') ?>'">
            <div class="row">
                <div class="col mt-0">
                    <h5 class="card-title">Appointment</h5>
                </div>
                <div class="col-auto">
                    <div class="stat text-primary">
                        <i class="align-middle" data-feather="calendar"></i>
                    </div>
                </div>
            </div>
            <h1 class="mt-1 mb-3"><?= $appointment ?></h1>
            <div class="mb-0">
                <span class="badge bg-info"><i class="mdi mdi-arrow-bottom-right"></i><?= $appointment . ".00%" ?></span>
                <span class="text-muted">All Appointments</span>
            </div>
        </div>
    </div>
</div>

        <div class="col-sm-3">
    <div class="card">
        <div class="card-body" id="owners" style="cursor: pointer;" onclick="window.location.href='<?= site_url('admin/patient/all') ?>'">
            <div class="row">
                <div class="col mt-0">
                    <h5 class="card-title">Pet Owners</h5>
                </div>
                <div class="col-auto">
                    <div class="stat text-primary">
                        <i class="align-middle" data-feather="users"></i>
                    </div>
                </div>
            </div>
            <h1 class="mt-1 mb-3"><?= $patient ?></h1>
            <div class="mb-0">
                <span class="badge bg-success">
                    <i class="mdi mdi-arrow-bottom-right"></i><?= $patient . ".00%" ?>
                </span>
                <span class="text-muted">Registered Owners</span>
            </div>
        </div>
    </div>
</div>

           

            <div class="col-sm-3">           
                <div class="card">
                        <div class="card-body" id="pets">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Pets</h5>
                                </div>



                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="gitlab"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3"><?= $pets ?></h1>
                                                <div class="mb-0">
                                                 <span class="badge bg-danger"> <i class="mdi mdi-arrow-bottom-right"></i><?= $pets.".00" . "%" ?></span>
                                                    <span class="text-muted">Number of Pets</span>
                                                </div>
                        </div>
                    </div>
        </div>
        <div class="col-sm-3">           
                <div class="card">
                        <div class="card-body" id="vet">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Veterinarian</h5>
                                </div>



                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="user"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3"><?= $doctors ?></h1>
                                                <div class="mb-0">
                                                 <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                                    <span  style="color: red;">Authorized Vet.</span>
                                                </div>
                        </div>
                    </div>
        </div>
    </div>
    </div>
<?php 
$todayFlag = false;
$today = date('Y-m-d');


    if (!empty($PendingAppointments)) {
    foreach ($PendingAppointments as $appointment) {
        if ($appointment['date'] == $today && $appointment['status'] == 'Pending') {
            $todayFlag = true;
            break;
        }
    }
}
    
?>

    

<div class="col-12 col-md-12 col-xxl-12 d-flex" <?= $todayFlag ? 'data-today="true"' : '' ?>>
    <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Upcoming Appointments</h5>
            <button class="btn btn-primary mt-3">
                <a href="<?= site_url('admin/appointment/view_all/') ?>" style="text-decoration: none; color: white;">View</a>
            </button>
        </div>
        <table class="table table-hover my-0">
            <thead>
                <tr>
                    <th class="d-none d-xl-table-cell">No.</th>
                    <th>Owner Name</th>
                    <th class="d-none d-xl-table-cell">Pet Name</th>
                    <th class="d-none d-xl-table-cell">Services</th>
                    <th class="d-none d-xl-table-cell">Date</th>
                    <th class="d-none d-xl-table-cell">Timeslot</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($PendingAppointments)): 
                    $i = 1; 
                    foreach ($PendingAppointments as $appointment): 
                        $isToday = ($appointment['date'] == $today);
                        
                        if ($isToday && $appointment['status'] == 'Pending') {
                            $todayFlag = true;
                        }
                ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                            <td class="d-none d-xl-table-cell"><?= esc($appointment['name']) ?></td>
                            <td class="d-none d-xl-table-cell"><?= esc($appointment['services']) ?></td>
                            <td class="d-none d-xl-table-cell">
                                <?= esc(date('F d, Y', strtotime($appointment['date']))) ?>
                                <?php if($isToday): ?>
                                    <span class="badge" style="background-color:#28a475; color:#fff;">Today</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <button class="badge bg-primary light" style="padding:3px 10px"><?= $appointment['slot'] ?></button>
                            </td>
                            <td>
                                <?php 
                                if ($appointment['status'] == 'Confirm') {
                                    echo '<button class="badge bg-success" style="padding:3px 10px">Confirmed</button>';
                                } elseif ($appointment['status'] == 'Decline') {
                                    echo '<button class="badge bg-danger" style="padding:3px 10px">Declined</button>';
                                } else {
                                    echo '<button class="badge bg-warning" style="padding:3px 10px">' . esc($appointment['status']) . '</button>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No pending appointments</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const appointmentCard = document.querySelector('[data-today="true"]');
        if (appointmentCard) {
            Swal.fire({
                title: "Appointment Today",
                text: "You have a pending appointment scheduled for today.",
                icon: "info",
                confirmButtonText: "OK"
            });
        }
    });
</script>


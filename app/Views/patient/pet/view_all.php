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
    <h1 class="h3 d-inline align-middle">Pet List</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">

            <!-- Error Alert -->
            <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <!-- Success Alert -->
            <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Table to display the pets -->
            <table class="table table-bordered table-hover" id="mytable">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Species</th>
                        <th>Breed</th>
                        <th>Weight</th>
                        <th>Sex</th>
                        <th>Birth Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($pets as $pet){ ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $pet['name']; ?></td>
                        <td><?= $pet['species']?></td>
                        <td><?= $pet['breed']; ?></td>
                        <td><?= $pet['weight']; ?></td>
                        <td><?= $pet['sex']; ?></td>
                        <td><?= !empty($pet['birthdate']) && $pet['birthdate'] != '0000-00-00' ? date('F d, Y', strtotime($pet['birthdate'])) : ''; ?> </td>
                        <td><button class="badge 
                            <?php 
                                if ($pet['status'] == 'Pending') { 
                                    echo 'bg-info'; 
                                } elseif ($pet['status'] == 'Confirm') { 
                                    echo 'bg-success'; 
                                } else { 
                                    echo 'bg-danger'; 
                                } 
                            ?> light" style="padding:3px 10px">
                                <?= $pet['status'] == 'Confirm' ? 'Confirmed' : ($pet['status'] == 'Decline' ? 'Declined' : $pet['status']); ?>
                            </button>
                        </td>
                        <td>
                            <!-- Edit and Delete Actions -->
                            <a href="<?= base_url('pet/edit/' . $pet['id']); ?>" class="badge bg-info">
                                <i class="align-middle" data-feather="edit"></i> Edit
                            </a>
                            <a href="javascript:void(0)" data-ids="<?= base_url('pet/delete/' . $pet['id']); ?>" class="badge bg-danger delete_record">
                                <i class="align-middle" data-feather="trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>






<script>
$(document).ready(function() {

});
</script>
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
})
</script>
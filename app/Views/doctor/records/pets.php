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
.alert-success{
                background:#48d79b94;
                 color: #fff;
                padding: 5px 10px;
                border: 1px solid #48d79b94;
                border-radius: 5px;
                margin-bottom: 20px;
            }
</style>


<div class="mb-3">
    <div class="row">
        <div class="col-md-6">
            <h1 class="h3 d-inline align-middle">Pet List - 
                <?php 
                if (!empty($petowner) && isset($petowner[0])) {
                    echo $petowner[0]['first_name'] . ' ' . $petowner[0]['last_name'];
                } else {
                    echo 'No pet owner found';
                }
                ?>
            </h1>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <!-- Display flash messages -->
            <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Pet table -->
            <table class="table table-bordered table-hover" id="mytable">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Name</th>
                      
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($pets as $pet){ ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $pet['name']; ?></td>
                       

                        <td>
                          <!--   <a href="<?= base_url('records/card/' . $pet['id']); ?>" class="badge bg-success">
                        <i class="align-middle" data-feather="plus"></i> Add
                    </a> -->
                             <a href="<?= base_url('records/table/' . $pet['id']); ?>" class="badge bg-info">
                        <i class="align-middle" data-feather="eye"></i> View Record
                    </a>

                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">

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
$(document).ready(function() {
    $('.update-status').change(function() {
        var petId = $(this).data('id');
        var newStatus = $(this).val();

        $.ajax({
    url: '<?= base_url('doctor/pet/update_status'); ?>/' + petId, // Ensure the route is correct
    method: 'POST',
    data: { status: newStatus },
    success: function(response) {
        if (newStatus === 'Confirm') {
            Swal.fire('Success', 'Pet has been confirmed and stored in the database!', 'success');
        } else {
            Swal.fire('Info', 'Pet status updated!', 'info');
        }
        location.reload(); 
    },
    error: function() {
        Swal.fire('Error', 'An error occurred while updating the status.', 'error');
    }
});

    });
});

$(document).ready(function() {
    $('select[name="status"]').change(function() {
        var newStatus = $(this).val();
        if (newStatus === 'Confirm') {
            Swal.fire('Success', 'Pet has been confirmed and stored in the database!', 'success');
        } else if (newStatus === 'Decline') {
            Swal.fire('Error', 'Pet has been declined!', 'error');
        } else {
            Swal.fire('Info', 'Pet status updated!', 'info');
        }
    });
});

</script>
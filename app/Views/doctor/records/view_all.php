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
    <h1 class="h3 d-inline align-middle">All Records</h1>
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

            <table class="table table-bordered table-hover" id="mytable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Pet Owner</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php if (is_array($patients) && !empty($patients)) : ?>
        <?php $i = 1; ?>
        <?php foreach ($patients as $row) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
              
                <td>
                    <!-- View Pets link -->
                    <a href="<?= base_url('records/pets/'.$row['id']); ?>" class="badge bg-info edit"> 
                        <i class="align-middle" data-feather="gitlab"></i> View Pets
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="2">No records found.</td>
        </tr>
    <?php endif; ?>
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
            $.ajax({
                url: url,
                method: 'POST',
                success: function(response) {
                    
                    location.reload();
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to delete the record.', 'error');
                }
            });
        }
    });
});

</script>
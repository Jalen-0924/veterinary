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

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                   
                        <th>Pet Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($records)) : ?>
                        <?php foreach ($records as $record) : ?>
                            <tr>
                          
                                <td><?= $record['pet_name']; ?></td>
                                 <td>
                                    <a href="<?= base_url('records/table/' . $record['id']); ?>"
                                        class="badge bg-info">
                                        <i class="align-middle" data-feather="eye"></i> View Record
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No records found.</td>
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

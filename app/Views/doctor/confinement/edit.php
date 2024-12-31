<style>
label {
    margin-bottom: 5px;
}

label>span {
    color: red;
}

textarea.form-control {
    height: 150px;
}

.form-control {
    height: 45px;
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
    <h1 class="h3 d-inline align-middle">Edit Confinement</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('confinement/update'); ?>" method="post">
                <input type="hidden" name="id" value="<?= $confinement['id']; ?>">

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

                <div class="row">
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Owner<span>*</span></label>
                        <select class="form-control" name="patient_id" id="patient" required>
                            <option value="">Select Owner</option>
                            <?php foreach($patients as $row): ?>
                            <option value="<?= $row['id'] ?>" <?= ($row['id'] == $confinement['patient_id']) ? 'selected' : '' ?>>
                                <?= $row['first_name'].' '.$row['last_name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Pet<span>*</span></label>
                        <select class="form-control" name="pet" id="pets" required>
                            <option value="">Select Pet</option>
                            <!-- Populate pets based on selected owner -->
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Reason<span>*</span></label>
                        <input type="text" class="form-control" id="reason" name="reason" value="<?= $confinement['reason']; ?>" required>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Treatment<span>*</span></label>
                        <input type="text" class="form-control" id="treatment" name="treatment" value="<?= $confinement['treatment']; ?>" required>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Status<span>*</span></label>
                        <select class="form-control" name="status" required>
                            <option value='Ongoing' <?= ($confinement['status'] == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                            <option value='Discharged' <?= ($confinement['status'] == 'Discharged') ? 'selected' : ''; ?>>Discharged</option>
                            
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Notes<span>*</span></label>
                        <input type="text" class="form-control" id="notes" name="notes" value="<?= $confinement['notes']; ?>">
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Start Date<span>*</span></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $confinement['start_date']; ?>" required>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>End Date<span>*</span></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $confinement['end_date']; ?>">
                    </div>

                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="UPDATE CONFINEMENT">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#patient').on('change', function() {
        var id = $(this).val();
        if (id != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/get/pets'); ?>",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    id: id
                },
                success: function(data) {
                    $('#pets').html(data);
                    $('#pets').val('<?= $confinement['pet_id']; ?>'); // Pre-select the pet
                }
            });
        }
    });

    // Trigger change to pre-fill pets
    $('#patient').trigger('change');
});
</script>

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
    <h1 class="h3 d-inline align-middle">Add Follow Up </h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('doctor/send_followup_email') ?>" method="post">

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

                <div class="row">

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Pet<span>*</span></label>
                        <select class="form-control" name="pet" required>
                            <option value="">Select Pet</option>
                            <?php foreach($pets as $row){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--- ====== form-group ======= ---->

                        <div class="form-group col-sm-12 col-xs-12 mb-3">
                            <label>Reasons for Follow-Up<span>*</span></label>
                            <select class="form-control" id="followupSelect" name="followup" required>
                                <option value="">Select Reason</option>
                                <option value="Additional Medication">Additional Medication</option>
                                <option value="Laboratory Test">Laboratory Test</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Parasite Control">Parasite Control</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="text" class="form-control" id="followupInput" name="followup_other" placeholder="Specify" style="display: none; margin-top: 10px;">
                        </div>


                  
                    <!--- ====== form-group ======= ---->



                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="FOLLOW UP">
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>







<script>
$(document).ready(function() {
    $('#date').on('change', function() {
        var date = $(this).val();
        var doctor = $('#doctor').val();
        if (date != '' && doctor != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/getSlots'); ?>",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    date: date,
                    doctor: doctor
                },
                success: function(data) {
                    console.log(data);
                    $('#slotWrap').html(data);
                }
            });
        } else {
            alert('Please Choose Doctor and Date');
        }
    });

    $('#followupSelect').on('change', function() {
        if ($(this).val() === 'Others') {
            $('#followupInput').show().attr('required', true);
            $('#followupSelect').hide().attr('disabled', true);
        } else {
            $('#followupInput').hide().removeAttr('required');
            $('#followupSelect').show().removeAttr('disabled');
        }
    });
});


</script>
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
    <h1 class="h3 d-inline align-middle">Add New Confinement</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
       <form action="<?= base_url('confinement/save'); ?>" method="post">


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
<!-- 
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Doctor<span>*</span></label>
                        <select class="form-control" id="doctor" name="doctor" required>
                            <option value="">Select Doctor</option>
                            <?php foreach($doctors as $row){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['first_name'].' '.$row['last_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div> -->
                    <!--- ====== form-group ======= ---->

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Owner<span>*</span></label>
                        <select class="form-control" name="patient_id" id="patient" required>
                            <option value="">Select Owner</option>
                            <?php foreach($patients as $row){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['first_name'].' '.$row['last_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--- ====== form-group ======= ---->
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Pet<span>*</span></label>
                        <select class="form-control" name="pet" id="pets" required>
                            <option value="">Select Pet</option>

                        </select>
                    </div>
                    <!--- ====== form-group ======= ---->
                       <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Reason<span>*</span></label>
                        <input type="text" class="form-control" id="reason" name="reason" placeholder="Reason" required>
                    </div>

                    <!--- ====== form-group ======= ---->
                         <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Treatment<span>*</span></label>
                        <input type="text" class="form-control" id="treatment" name="treatment" placeholder="Treatment" required>
                    </div>
                  
                  
                  <!--- ====== form-group ======= ---->

                      <div class="form-group col-sm-12 col-xs-12 mb-3">
                     <label>Status<span>*</span></label>
             <select class="form-control" name="status" required>
            <option value='Ongoing'>Ongoing</option>
            <option value='Discharged'>Discharged</option>
       
                                                </select>
                                            </div>
                      <!--- ====== form-group ======= ---->

                       <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Notes<span>*</span></label>
                        <input type="text" class="form-control" id="notes" name="notes" placeholder="Notes">
                    </div>

                     <!--- ====== form-group ======= ---->
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Start Date<span>*</span></label>
                        <input type="Date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required>
                    </div>
                    <!--- ====== form-group ======= ---->
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>End Date<span>*</span></label>
                        <input type="Date" class="form-control" id="end_date" name="end_date" placeholder="End Date" >
                    </div>
                    <!--- ====== form-group ======= ---->

        



                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="ADD CONFINEMENT">
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
                    console.log(data);
                    $('#pets').html(data);
                }
            });
        } else {
            alert('Please Choose Doctor and Date');
        }
    });

});

$('#doctor').change(function() {
    var date = $('#date').val();
    var doctor = $('#doctor').val();
    if (date != '') {
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
    }
})
</script>
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

span.removeRP {
    background: #fb4040;
    color: #fff;
    padding: 2px 5px;
    border-radius: 2px;
    box-shadow: 0 3px 5px #ddd;
    position: absolute;
    right: 10px;
    top: 0px;
    cursor: pointer;
}

.removeRP {
    display: none;
}


.newClass .removeRP {
    display: block !important;
}

.newClass #serWrapper::first-child .removeRP {
    display: none !important;
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

<?php 
$appointment = $appointment[0];

?>

<form action="<?= base_url('report/pateint/store'); ?>" method="post">


    <input type="hidden" value="<?= $appointment['id']; ?>" name="appointment_id">
    <input type="hidden" value="<?= $appointment['patient_id']; ?>" name="patient_id">
    <input type="hidden" value="<?= $appointment['pet_id']; ?>" name="pet_id">


    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Appointment Start</h1>
    </div>
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


    <div class="col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="mytable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Owner Name</th>
                            <th>Pet Name</th>
                            <th>Timeslot</th>
                            <th>Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $appointment['date']; ?></td>
                            <td><?= $appointment['first_name'].' '.$appointment['last_name']; ?></td>
                            <td><?= $appointment['name']; ?></td>
                            <td><?= $appointment['slot']; ?></td>
                            <td>
                                <select class="form-control" name="status" style="max-width:150px">
                                    <option value="Pending"
                                        <?php if($appointment['status'] == 'Pending'){ echo 'selected';} ?>>Pending
                                    </option>
                                    <option value="Processing"
                                        <?php if($appointment['status'] == 'Processing'){ echo 'selected';} ?>>
                                        Processing</option>
                                    <option value="Complete"
                                        <?php if($appointment['status'] == 'Complete'){ echo 'selected';} ?>>Complete
                                    </option>
                                </select>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="col-sm-12 col-xs-12">
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div id="serMain">
                        <div class="row mb-3" id="serWrapper">
                            <div class="col-sm-12" style="position:relative">
                                <label>Select Service</label>
                                <select class="form-control" name="service[]" required>
                                    <option value="">Select Service</option>
                                    <?php foreach($services as $row){ ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>

                                <span class="removeRP" style="display:none">Remove</span>
                            </div>
                            <!--- form-group --->

                        </div>
                        <!--- ===== row ====== ----->
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-info" id="addSER" style="width:200px">ADD MORE
                            SERVICE</button>
                    </div>
                </div>

            </div>


        </div>
    </div>


    <div class="row">


        <div class="col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4>Symptoms</h4>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div id="rpMain">
                            <div class="row mb-3" id="rpWrapper">
                                <div class="col-sm-12" style="position:relative">
                                    <label>Select Symptom<span>*</span></label>
                                    <select class="form-control symptom" multiple="" name="symp[]" required>
                                        <?php foreach($symptom as $row){ ?>
                                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>

                                    <!--<span class="removeRP">Remove</span>-->
                                </div>
                                <!--- form-group --->

                            </div>
                            <!--- ===== row ====== ----->
                        </div>
                        <!-- <div class="col-sm-12">
								                <button type="button" class="btn btn-info" id="addRP" style="width:200px">ADD MORE SYMPTOM</button>
								                </div>-->
                    </div>

                </div>
            </div>
        </div>


        <div class="col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4>Item</h4>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div id="mdMain">
                            <div class="row mb-3" id="mdWrapper">
                                <div class="col-sm-12" style="position:relative">
                                    <label>Select Item<span>*</span></label>
                                    <select class="form-control medicine" multiple="" name="medicine[]" required>
                                        <?php foreach($medicines as $row){ ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <!--<span class="removeRP">Remove</span>-->
                                </div>
                                <!--- form-group --->

                            </div>
                            <!--- ===== row ====== ----->
                        </div>
                        <!--<div class="col-sm-12">
								                <button type="button" class="btn btn-info" id="addmd" style="width:200px">ADD MORE MEDICINE</button>
								                </div>-->
                    </div>

                </div>
            </div>
        </div>


        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-ms btn-block" style="height:50px;width:100%"
                    value="SAVE NOW">
            </div>
        </div>
        <!--- ===col-sm-8 === ---->

    </div>

</form>


<script>
$(document).ready(function() {
    $('#addRP').click(function() {
        var rp = $("#rpWrapper").clone();
        $('#rpMain').append(rp);
        console.log(rp);
    });

    $('#addmd').click(function() {
        var md = $("#mdWrapper").clone();
        $('#mdMain').append(md);
        console.log(md);
    });
    var i = 0;
    $('#addSER').click(function() {
        var list = i++;
        var ser = $("#serWrapper").clone().addClass('newClass');
        $('#serMain').append(ser);
        console.log(ser);
    });



    $(document).on('click', '.removeRP', function() {
        $(this).parent().parent().remove();
    });

});
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.medicine').select2({
        placeholder: "Select Item",
    });
});
$(document).ready(function() {
    $('.symptom').select2({
        placeholder: "Select Symptom",
    });
});
</script>
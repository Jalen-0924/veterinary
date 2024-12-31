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

<form method="post" action="<?= base_url('appointment/save_appointment'); ?>">
    <input type="hidden" value="<?= $appointment['id']; ?>" name="appointment_id">
    <input type="hidden" value="<?= $appointment['patient_id']; ?>" name="patient_id">
    <input type="hidden" value="<?= $appointment['pet_id']; ?>" name="pet_id">
    <input type="hidden" value="<?= $appointment['services']; ?>" name="services">
    <input type="hidden" value="<?= $appointment['service_price']; ?>" name="service_price">
    <input type="hidden" value="<?= $appointment['service_id']; ?>" name="service_id">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Appointment Start</h1>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif;?>

    <?php if(session()->getFlashdata('success')): ?>
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
                            <th>Pets Name</th>
                            <th>Services</th>
                            <th>Timeslot</th>
                            <th>Current Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= date('F d, Y', strtotime($appointment['date']))?></td>
                            <td><?= $appointment['first_name'] . ' ' . $appointment['last_name']; ?></td>
                            <td><?= $appointment['name']; ?></td>
                            <td><?= $appointment['services']; ?></td>
                            <td><?= $appointment['slot']; ?></td>
                            <td>
                                <select id="statusDropdown" class="form-control" name="status" style="max-width:150px">
                                    <option value="Pending" <?= $appointment['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Confirm" <?= $appointment['status'] == 'Confirm' ? 'selected' : '' ?>>Confirm</option>
                                    <option value="Decline" <?= $appointment['status'] == 'Decline' ? 'selected' : '' ?>>Decline</option>
                                </select>
                            </td>
                           <td>
                            <textarea id="declineReason" class="form-control" name="decline_reason" style="max-width:250px; display: none;" placeholder="Reason for Decline"></textarea>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            <div class="row">
       <div class="col-sm-6 col-xs-12">
            <div class="card">
               
            </div>
        </div>


        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-ms btn-block"  style="height:50px;width:100%; margin-left: 220px;"
                    value="SAVE">
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
    $(document).ready(function() {
    $('#statusDropdown').change(function() {
        var selectedStatus = $(this).val();
        
        if (selectedStatus === 'Decline') {
            $('#declineReason').show(); 
        } else {
            $('#declineReason').hide();  
            $('#declineReason').val(''); // Clear the textbox when not needed
        }
    });
});

</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

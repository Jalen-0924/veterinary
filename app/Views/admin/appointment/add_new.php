<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

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
.ui-datepicker td.available-date a {
    background-color: #48d79b !important;
    color: white !important;
    border-radius: 50% !important;
    border: none !important;
}

.ui-datepicker td.unavailable-date a {
    background-color: red !important;
    color: white !important;
    border-radius: 50% !important;
    border: none !important;
}
 .select2-container--default .select2-results__option {
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-results__option input[type="checkbox"] {
        margin-right: 8px;
    }
</style>



<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Add New Appointment</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('appointment/store') ?>" method="post">

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
    <select class="form-control select2" name="pets[]" id="pets" multiple="multiple" required>
        <!-- Options will be dynamically populated -->
    </select>
</div>


                    <!--- ====== form-group ======= ---->
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Services<span>*</span></label>
                        <select class="form-control" name="services_id" id="services" required>
                            <option value="">Select Services</option>
                            <?php foreach($services as $row){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>




                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Date<span>*</span></label>
                        <input type="Date" class="form-control" id="date" name="date" placeholder="Date" required>
                    </div>
                    <!--- ====== form-group ======= ---->

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Available Timeslots<span>*</span></label>
                        <select class="form-control" name="timeslot" id="slotWrap" required>
                            <option value="">Select Timeslot</option>

                        </select>
                    </div>
                    <!--- ====== form-group ======= ---->



                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="ADD APPOINTMENT">
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

  $('#pets').select2({
            placeholder: "Select Pets",
            closeOnSelect: false, // Keep dropdown open for multiple selections
            templateResult: formatOption, // Add checkboxes to options
            templateSelection: formatSelection // Customize selected items display
        });

        // Customize how options are displayed with checkboxes
        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            const checkbox = $('<span><input type="checkbox" style="margin-right: 8px;" /> ' + option.text + '</span>');
            return checkbox;
        }

        // Customize how selected items are displayed
        function formatSelection(option) {
            return option.text || option.id;
        }


     $('#patient').on('change', function () {
            var id = $(this).val();
            if (id !== '') {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('appointment/get/pets'); ?>",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        id: id
                    },
                    success: function (data) {
                        // Populate the dropdown with fetched pet options
                        $('#pets').html(data).trigger('change');
                    }
                });
            } else {
                alert('Please choose an owner to load pets.');
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



});
$(document).ready(function() {

 var availableDates = [];
    var unavailableDates = [];

    // Fetch available dates based on service selection
    function fetchAvailableDates() {
        var services = $('#services').val();
        if (services != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/getAvailableDates'); ?>",
                data: { services: services },
                success: function(response) {
                    availableDates = response || [];
                    $("#date").datepicker("refresh");
                }
            });
        }
    }

    // Highlight available/unavailable dates
    function highlightDates(date) {
        var dateStr = $.datepicker.formatDate('yy-mm-dd', date);
        if (availableDates.includes(dateStr)) {
            return [true, "available-date"];
        } else {
            return [false, "unavailable-date"];
        }
    }

    // Initialize datepicker
    $("#date").datepicker({
        dateFormat: 'yy-mm-dd',
        beforeShowDay: highlightDates,
        onSelect: function(dateText) {
            fetchTimeslots(dateText);
        }
    });

    // Fetch timeslots for a selected date
    function fetchTimeslots(date) {
        var services = $('#services').val();
        if (services != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/getDateSlots'); ?>",
                data: { date: date, services: services },
                success: function(response) {
                    let optionsHtml = response.map(slot => `<option value="${slot.id}">${slot.slot}</option>`).join('');
                    $('#slotWrap').html(optionsHtml);
                }
            });
        }
    }

    // Fetch dates when service changes
    $('#services').change(fetchAvailableDates);
});
</script>
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

/* Available date styling (green) */
.ui-datepicker td.available-date a {
    background-color: #48d79b !important; /* Green background */
    color: white !important;
    border-radius: 50% !important;
    border: none !important;
}

/* Unavailable date styling (red) */
.ui-datepicker td.unavailable-date a {
    background-color: red !important; /* Red background */
    color: white !important;
    border-radius: 50% !important;
    border: none !important;
}
</style>

<?php 
$appointment = $appointment[0]; // Accessing the first element of the appointment array
$slot = $slot[0]; // Access the first element of the slot array if there are multiple slots
?>

<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Update Appointment</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('appointment/update') ?>" method="post">
                <input type="hidden" value="<?= $appointment['id'] ?>" name="id">
                <input type="hidden" value="<?= $appointment['patient_id'] ?>" name="patient_id">

                <div class="row">
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Pet<span>*</span></label>
                        <select class="form-control" name="pet" required>
                            <option value="">Select Pet</option>
                            <?php foreach($pets as $row) { ?>
                                <option value="<?= $row['id'] ?>" <?php if($appointment['pet_id'] == $row['id']) { echo 'selected'; } ?>>
                                    <?= $row['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Services<span>*</span></label>
                        <select class="form-control" name="services" required>
                            <option value="">Select Service</option>
                            <?php foreach($services as $service) { ?>
                                <option value="<?= $service['id'] ?>" <?php if($appointment['service_id'] == $service['id']) { echo 'selected'; } ?>>
                                    <?= $service['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Date<span>*</span></label>
                        <input type="text" class="form-control" value="<?= $appointment['date']; ?>" id="date" name="date" placeholder="Select Date" required readonly>
                        <small id="slotMessage" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Available Timeslots<span>*</span></label>
                        <select class="form-control" name="timeslot" id="slotWrap" required>
                            <option value="<?= $slot['id'] ?>"><?= $slot['slot'] ?></option>
                        </select>
                    </div>

                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="UPDATE APPOINTMENT">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    var availableDates = []; // Store dates with available slots

    // Initialize the date picker with the custom highlighting
    $("#date").datepicker({
        dateFormat: 'yy-mm-dd',
        beforeShowDay: highlightDates,
        onSelect: function(dateText) {
            fetchTimeslots(dateText); // Fetch timeslots for the selected date
        }
    });

    // Function to check if a date is in available array
    function highlightDates(date) {
        var dateStr = $.datepicker.formatDate('yy-mm-dd', date);
        if (availableDates.includes(dateStr)) {
            return [true, "available-date"];
        } else {
            return [false, "unavailable-date"];
        }
        return [true, ""]; // Default styling for other dates
    }

    // Fetch available dates from the server based on selected service
    fetchAvailableDates(); // Fetch available dates on page load

    function fetchAvailableDates() {
        var services = '<?= $appointment['service_id'] ?>'; // Assuming service_id is part of the appointment data
        if (services != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/getAvailableDates'); ?>", // Route for available dates
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    services: services 
                },
                success: function(response) {
                    availableDates = response || []; // Populate available dates
                    $("#date").datepicker("refresh");
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching available dates:', status, error);
                }
            });
        }
    }

    // Fetch available timeslots based on the selected date
    function fetchTimeslots(date) {
        var services = '<?= $appointment['service_id'] ?>'; // Assuming service_id is part of the appointment data
        if (date != '' && services != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('appointment/getDateSlots'); ?>", // Route for timeslots on a specific date
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    date: date,
                    services: services
                },
                success: function(response) {
                    if (response.length === 0) {
                        $('#slotMessage').text('No available slots on this date. Please choose another date.');
                        $('#slotWrap').empty();
                    } else {
                        $('#slotMessage').text('Available slots found. Please select one.');
                        let optionsHtml = response.map(slot => `<option value="${slot.id}">${slot.slot}</option>`).join('');
                        $('#slotWrap').html(optionsHtml); // Populate the select with timeslot options
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching slots:', status, error);
                }
            });
        }
    }

    // Initialize with the existing appointment date
    fetchTimeslots($("#date").val());
});
</script>

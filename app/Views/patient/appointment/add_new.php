<!-- Include jQuery and jQuery UI for enhanced date picker -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

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
        background-color: #48d79b !important;
        /* Green background */
        color: white !important;
        border-radius: 50% !important;
        border: none !important;
    }

    /* Unavailable date styling (red) */
    .ui-datepicker td.unavailable-date a {
        background-color: red !important;
        /* Red background */
        color: white !important;
        border-radius: 50% !important;
        border: none !important;
    }

    /* Add padding for checkbox dropdown items */
    .select2-results__option {
        padding: 5px;
    }

    .select2-results__option input[type="checkbox"] {
        margin-right: 10px;
    }
</style>

<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Add New Appointment </h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('appointment/store') ?>" method="post">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>


                <div class="form-group col-sm-12 col-xs-12 mb-3">
                    <label>Select Pets<span>*</span></label>
                    <select class="form-control select2-pets" name="pets[]" multiple required>
                        <?php foreach ($pets as $row): ?>
                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-sm-12 col-xs-12 mb-3">
                    <label>Select Services<span>*</span></label>
                    <select class="form-control" name="services_id" id="services" required>
                        <option value="">Select Services</option>
                        <?php foreach ($services as $row): ?>
                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-sm-12 col-xs-12 mb-3">
                    <label>Date<span>*</span></label>
                    <input type="text" class="form-control" id="date" name="date" placeholder="Select Date" required
                        readonly>
                    <small id="slotMessage" class="form-text text-muted"></small>
                </div>


                <div class="form-group col-sm-12 col-xs-12 mb-3">
                    <label>Available Timeslots<span>*</span></label>
                    <select class="form-control" name="timeslot" id="slotWrap" required>
                        <option value="">Select Timeslot</option>
                    </select>
                </div>

                <div class="form-group mt-3" style="text-align:right">
                    <input type="submit" class="btn btn-primary" value="ADD APPOINTMENT">
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        var availableDates = []; // Store dates with available slots
        var unavailableDates = []; // Store dates with no available slots

        // Fetch available dates from the server based on selected service
        function fetchAvailableDates() {
            var services = $('#services').val();
            if (services != '') {
                console.log('Fetching available dates for services ID:', services);

                // Make AJAX request to fetch available dates from the server
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('appointment/getAvailableDates'); ?>", // Route for available dates
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        services: services
                    },
                    success: function (response) {
                        console.log('Available dates response:', response);
                        availableDates = response || []; // Populate available dates

                        // Refresh datepicker to apply date highlights
                        $("#date").datepicker("refresh");
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching available dates:', status, error);
                    }
                });
            }
        }

        // Function to check if a date is in available or unavailable arrays
        function highlightDates(date) {
            var dateStr = $.datepicker.formatDate('yy-mm-dd', date);

            if (availableDates.includes(dateStr)) {
                return [true, "available-date"];
            } else {
                return [false, "unavailable-date"];
            }
            return [true, ""]; // Default styling for other dates
        }

        // Initialize the date picker with the custom highlighting
        $("#date").datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: highlightDates,
            onSelect: function (dateText) {
                fetchTimeslots(dateText); // Fetch timeslots for the selected date
            }
        });

        // Trigger available date fetch when services are selected
        $('#services').change(function () {
            fetchAvailableDates();
        });

        // Fetch available timeslots based on the selected date
        function fetchTimeslots(date) {
            var services = $('#services').val();
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
                    success: function (response) {
                        console.log('Timeslots response:', response);

                        if (response.length === 0) {
                            $('#slotMessage').text(
                                'No available slots on this date. Please choose another date.');
                            $('#slotWrap').empty();
                        } else {
                            $('#slotMessage').text('Available slots found. Please select one.');
                            let optionsHtml = response.map(slot =>
                                `<option value="${slot.id}">${slot.slot}</option>`).join('');
                            $('#slotWrap').html(
                                optionsHtml); // Populate the select with timeslot options
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching slots:', status, error);
                    }
                });
            }
        }

        // Trigger available date fetch when doctor is selected
        $('#doctor').change(function () {
            fetchAvailableDates();
        });
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select Pets",
                allowClear: true
            });
        });
        $(document).ready(function () {
            $('.select2-pets').select2({
                placeholder: "Select Pets",
                allowClear: true,
                closeOnSelect: false,
                templateResult: function (option) {
                    if (!option.id) {
                        return option.text;
                    }
                    var checkbox = $(
                        '<span><input type="checkbox" style="margin-right: 10px;">' + option
                            .text + '</span>');
                    return checkbox;
                },
                templateSelection: function (option) {
                    return option.text;
                }
            });
        });

    });
</script>
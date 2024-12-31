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
    top: 12px;
    cursor: pointer;
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

#ck-button {
    margin: 4px;
    background-color: #EFEFEF;
    border-radius: 4px;
    border: 1px solid #D0D0D0;
    overflow: auto;
    float: left;
    width: 102px;
}

#ck-button label {
    float: left;
    width: 4.0em;
    margin-bottom: 0px;
}

#ck-button label span {
    text-align: center;
    padding: 3px 0px;
    display: block;
    width: 100px;
}

#ck-button label input {
    position: absolute;
    top: -20px;
}

#ck-button input:checked+span {
    background-color: #00aa00;
    color: #fff;
}

#ck-button input {
    display: none;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1060;
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
}

.modal-header .btn-close {
    padding: 0.5rem 0.5rem;
    margin: -0.5rem -0.5rem -0.5rem auto;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    flex-shrink: 0;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.show {
    opacity: .5;
}

.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.modal.fade .modal-dialog {
    transition: transform .3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}
#time{
    margin-top: 30px;
    margin-left: 30px;
}

.fc-disabled-date {
    background-color: #e0e0e0 !important; /* Grey background */
    color: #b0b0b0 !important; /* Grey text */
    pointer-events: none !important; /* Disable interaction */
    opacity: 0.5; /* Adjust opacity for better visibility */
}



</style>


 <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>




<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Custom Date</h1>
</div>
    
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div id="calendar"></div>
                </div>
                    <div class="col-sm-6">
                        <form action="<?php  echo base_url('slot/store');  ?>" method="post">
                                    <input type="hidden" name="id" value="<?php if(isset($_GET['slot_id'])){echo $_GET['slot_id']; } ?>">
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
                            <h1 class="h3 d-inline align-middle" style="margin-left: 10px;">Time Slots</h1>
                            <div id="time">




                                <div class="row">
                                    <div class="col-sm-6">
                                    <label>Start Date <span>*</span></label>
                                    <input type="text" id="startDate" name="startDate" class="form-control" placeholder="Select Start Date" required readonly>
                                    
                                    </div>
                                    <div class="col-sm-6">
                                    <label>End Date <span>*</span></label>
                                    <input type="text" id="endDate" name="endDate" class="form-control" placeholder="Select End Date" required readonly>
                                    </div><br>


                                    <!-- <div class="col-sm-6">
                                <input type="button" class="btn btn-primary" value="ADD" id="aDD">
                                    
                            </div> -->
                                </div>



                                <br>
                                <div class="row">

                                <div class="col-sm-6">
                                    <label>Start Time<span>*</span></label>
                                    <?php 
                                        $start = '0830';
                                        $end = '1830';
                                        $date_end = date_create($end);
                                    ?>
                                    <select name="start_time" class="form-control start_time" required> <!-- Added required attribute -->
                                        <option value=""></option>
                                        <?php for($date = date_create($start); $date <= $date_end; $date->modify('+30 Minutes')) { ?>
                                            <option value="<?php echo $date->format('H:i'); ?>">
                                                <?php echo $date->format('H:i'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>


                                <div class="col-sm-6">
                                    <label>End Time<span>*</span></label>
                                    <input type="text" readonly="" class="form-control end_time" name="end_time"
                                        value="<?php echo isset($_GET['end_time']) ? $_GET['end_time'] : ''; ?>"
                                        placeholder="End time will be calculated" required>
                                </div>


                            </div><br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Slot Duration<span>*</span></label>
                                        <select name="duration" class="form-control duration">
                                            <option value="30">30 Minutes</option>
                                            <option value="60">60 Minutes</option>
                                        </select>
                                </div>
                            
                                <div class="col-sm-6">
                                    <label>Number Of Slots<span>*</span></label>
                                        <input type="number" class="form-control number_of_slots" name="number_of_slots" min="1"
                                            placeholder="" value="<?php if(isset($_GET['slot'])){echo $_GET['slot']; } ?>"
                                            required>

                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-sm-6">
                                        <div class="timeing_slots"></div>
                                </div>
                            </div>
                                <div class="col-sm-6"><p></p></div>
                                <div class="col-sm-6">
                                    <input type="submit" class="btn btn-primary" value="SAVE SLOT">
                                        <button type="button" id="clearSlots" class="btn btn-secondary">CLEAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="col-sm-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>ALL SLOTS</h4>
            <!-- Search Input -->
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search slots...">
        </div>
        <div class="card-body">
            <?php if(session()->getFlashdata('del_error')):?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('del_error') ?>
                </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('del_success')):?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('del_success') ?>
                </div>
            <?php endif;?>

            <!-- Scrollable Table Wrapper -->
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-hover" id="mytable">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Slots</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            foreach($slots as $row) {
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('F d, Y', strtotime($row['start_date'])) ?></td>
                            <td><?= date('F d, Y', strtotime($row['end_date'])) ?></td>
                            <td><?= $row['slot'] ?></td>
                            <td>
                                <a href="javascript:void(0)" class="badge bg-info edit_slot"
                                   data-time="<?= $row['slot']; ?>" data-ids="<?= $row['id']; ?>">
                                    <i class="align-middle" data-feather="edit"></i> Edit
                                </a>
                                <a href="javascript:void(0)" data-ids="<?= base_url('slot/delete/'.$row['id']); ?>" 
                                   class="badge bg-danger delete_record">
                                    <i class="align-middle" data-feather="trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- End Scrollable Table Wrapper -->
        </div>
    </div>
</div>

<div class="modal fade" id="timeslotEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSlotForm">
                    <div class="row">
                        <div class="form-group col-sm-12 mb-3">
                            <label>Slot<span>*</span></label>
                            <input type="text" class="form-control edit_slot_time_val" name="edit_slot" />
                            <input type="hidden" class="edit_slot_id" name="edit_slot_id" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button style="margin: 10px;" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update_changes">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Include required libraries -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const startTimeSelect = document.querySelector('.start_time');
    const endTimeInput = document.querySelector('.end_time');
    const durationSelect = document.querySelector('.duration');
    const slotsInput = document.querySelector('.number_of_slots');

    let disabledDates = []; // Dates that cannot be selected

    // Fetch disabled dates from the server
    function fetchDisabledDates() {
        $.post({
            url: "<?= base_url('appointment/getAvailableDates'); ?>",
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                disabledDates = response.map(dateObj => dateObj); // Load disabled dates
                loadUnavailableEvents();
                calendar.render();
            },
            error: function() {
                console.error("Failed to load disabled dates.");
            }
        });
    }
    fetchDisabledDates();

    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        selectable: true,
        
        selectAllow: function(info) {
            const start = moment(info.start);
            const end = moment(info.end);
            return !isDateRangeDisabled(start, end);
        },
        
        select: function(info) {
            startDateInput.value = info.startStr;
            endDateInput.value = moment(info.endStr).subtract(1, 'day').format('YYYY-MM-DD');
            updateCalendarSelection(info.startStr, info.endStr);
        },
        
        datesSet: function() {
            clearDisabledDates();
            highlightDisabledDates();
        },

        eventContent: function(arg) {
            return { html: `<div class="fc-content"><div class="fc-title">${arg.event.title}</div><div class="fc-description">${arg.event.extendedProps.description || ''}</div></div>` };
        },
        
        eventDidMount: function(info) {
            if (info.event.extendedProps.status === 'available') {
                info.el.style.backgroundColor = '#28a745';
                info.el.style.borderColor = '#28a745';
            } else if (info.event.extendedProps.status === 'unavailable') {
                info.el.style.backgroundColor = '#dc3545';
                info.el.style.borderColor = '#dc3545';
            }
        }
    });
    calendar.render();

    // Check if any date in the range is disabled
    function isDateRangeDisabled(start, end) {
        let isDisabled = false;
        while (start.isBefore(end)) {
            if (disabledDates.includes(start.format('YYYY-MM-DD'))) {
                Swal.fire('Selection not allowed', 'Some dates in the selected range are already booked.', 'error');
                isDisabled = true;
                break;
            }
            start.add(1, 'day');
        }
        return isDisabled;
    }

    // Update calendar selection with the selected range
    function updateCalendarSelection(startDate, endDate) {
        calendar.getEvents().forEach(event => {
            if (event.startStr >= startDate && event.startStr <= endDate) {
                event.remove();
            }
        });
        calendar.addEvent({ title: 'Selected', start: startDate, end: endDate, status: 'available' });
    }

    // Highlight disabled dates on the calendar
    function highlightDisabledDates() {
        disabledDates.forEach(date => {
            const dayCell = calendar.getDayCell(date);
            if (dayCell) {
                dayCell.classList.add('fc-disabled-date');
                dayCell.style.pointerEvents = 'none';
            }
        });
    }

    // Clear previous disabled date styles
    function clearDisabledDates() {
        document.querySelectorAll('.fc-day.fc-disabled-date').forEach(cell => {
            cell.classList.remove('fc-disabled-date');
        });
    }

    // Load unavailable dates as events
    function loadUnavailableEvents() {
        disabledDates.forEach(date => {
            calendar.addEvent({ title: 'Already Selected', start: date, end: date, status: 'unavailable' });
        });
    }

    // Update end time based on start time, duration, and number of slots
    function updateEndTime() {
        const [hours, minutes] = startTimeSelect.value.split(':').map(Number);
        const totalMinutes = hours * 60 + minutes + (parseInt(durationSelect.value) * parseInt(slotsInput.value || 1));
        endTimeInput.value = `${String(Math.floor(totalMinutes / 60)).padStart(2, '0')}:${String(totalMinutes % 60).padStart(2, '0')}`;
    }

    // Event listeners for start time, duration, and slots input changes
    [startTimeSelect, durationSelect, slotsInput].forEach(el => el.addEventListener('change', updateEndTime));

    // Confirm and handle slot deletion
    $(document).on('click', '.delete_record', function(e) {
        e.preventDefault();
        const slotId = $(this).data('ids');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the slot!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post(slotId, function(response) {
                    Swal.fire(response.status === 'success' ? 'Deleted!' : 'Error!', response.message, response.status);
                    if (response.status === 'success') location.reload();
                }).fail(() => Swal.fire('Error!', 'Failed to delete slot. Please try again.', 'error'));
            }
        });
    });

    // Populate and handle slot edit modal
    $(document).on('click', '.edit_slot', function(e) {
        e.preventDefault();
        $('#timeslotEditModal').modal('show');
        $('#updateSlotForm .edit_slot_id').val($(this).data('ids'));
        $('#updateSlotForm .edit_slot_time_val').val($(this).data('time'));
    });

    // Handle slot update with AJAX
    $(document).on('click', '.update_changes', function() {
        $.post("<?= base_url('slot/update'); ?>", {
            edit_slot_id: $('#updateSlotForm .edit_slot_id').val(),
            edit_slot: $('#updateSlotForm .edit_slot_time_val').val()
        }, function(response) {
            Swal.fire(response.success ? 'Updated!' : 'Error!', response.message, response.success ? 'success' : 'error');
            if (response.success) {
                $('#timeslotEditModal').modal('hide');
                location.reload();
            }
        }).fail(() => Swal.fire('Error!', 'Failed to update slot. Please try again.', 'error'));
    });

    
});
</script>
<!-- JavaScript for Search Functionality -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#mytable tbody tr');

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });
</script>
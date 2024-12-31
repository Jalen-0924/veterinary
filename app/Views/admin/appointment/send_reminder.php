<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Appointment List - Send Reminder (EMAIL)</h1>
    <button id="sendReminder" class="btn btn-primary float-end"> Reminder</button>
</div>
<!-- In your view file -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add CSRF token meta tag -->
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div id="reminderAlert" style="display: none;" class="alert alert-success">

            </div>

            <table class="table table-bordered table-hover" id="mytable">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Owner Name</th>
                        <th>Pet Name</th>
                        <th>Services</th>
                        <th>Date</th>
                        <th>Timeslot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $key => $row): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                            <td><?= $row['services'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= date('F d, Y', strtotime($row['date'])) ?></td>
                            <td><button class="badge bg-primary light" style="padding:3px 10px"><?= $row['slot'] ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Add this to your existing script section
    $(document).ready(function () {
        // Existing button click handler
        $('#sendReminder').on('click', handleReminderTrigger);

        // Set up automatic trigger every 3 hours
        const THREE_HOURS = 3 * 60 * 60 * 1000; // 3 hours in milliseconds

        // Initial trigger after page load (optional)
        setTimeout(handleReminderTrigger, 5000); // 5 second initial delay

        // Set up recurring trigger
        setInterval(handleReminderTrigger, THREE_HOURS);

        function handleReminderTrigger() {
            const button = $('#sendReminder');
            const originalText = button.text();

            button.prop('disabled', true);
            button.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...'
            );

            $.ajax({
                url: '<?= base_url('admin/trigger-reminders') ?>',
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function (response) {
                    $('#reminderAlert')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .html(`Reminders sent successfully! Next trigger in 3 hours`)
                        .show();

                    updateNextTriggerTime();
                },
                error: function (xhr) {
                    $('#reminderAlert')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .html('Error sending reminders. Will retry in 3 hours.')
                        .show();
                },
                complete: function () {
                    button.prop('disabled', false);
                    button.html(originalText);

                    setTimeout(() => {
                        $('#reminderAlert').fadeOut();
                    }, 5000);
                }
            });
        }

        function updateNextTriggerTime() {
            const nextTrigger = new Date(Date.now() + THREE_HOURS);
            const timeString = nextTrigger.toLocaleTimeString();

            // Add this div to your HTML to show next trigger time
            if (!$('#nextTrigger').length) {
                $('<div id="nextTrigger" class="text-muted small mt-2"></div>')
                    .insertAfter('#reminderAlert');
            }

            $('#nextTrigger').text(`Next automatic trigger at: ${timeString}`);
        }

        // Initial display of next trigger time
        updateNextTriggerTime();
    });
</script>
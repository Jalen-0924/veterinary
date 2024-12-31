<style>
    th[colspan="4"].text-center {
        background-color: lightblue;
    }
    th[colspan="4"].text-center {
        background-color: lightgreen;
    }
    th[colspan="5"].text-center {
        background-color: lightgreen;
    }

</style>
<div class="card">
    <div class="card-header">
        <h2>Record Details</h2>
    </div>
    <div class="card-body">

        <form method="post" action="<?= base_url('records/save') ?>">
    <input type="hidden" name="record_id" value="<?= $record['id'] ?>">

    <!-- Vaccine Table -->
    <table class="table table-bordered" id="vaccineHistoryTable">
        <thead>
            <tr>
                <th colspan="5" class="text-center">VACCINE</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Weight</th>
                <th>Vaccine</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="date" name="vaccine_date[]" class="form-control"></td>
                <td><input type="text" name="weight[]" class="form-control"></td>
                <td><input type="text" name="vaccine[]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-primary" id="addRow">Add</button>
                    <button type="button" class="btn btn-danger removeRow">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered" id="dewormingHistoryTable">
        <thead>
            <tr>
                <th colspan="5" class="text-center">DEWORMING</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Repeat Date</th>
                <th>Weight</th>
                <th>Product</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="date" name="deworm_date[]" class="form-control"></td>
                <td><input type="date" name="r_date[]" class="form-control"></td>
                <td><input type="text" name="weight[]" class="form-control"></td>
                <td><input type="text" name="product[]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-primary" id="addRow">Add</button>
                    <button type="button" class="btn btn-danger removeRow">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Medical History Table -->
    <table class="table table-bordered" id="medicalHistoryTable">
        <thead>
            <tr>
                <th colspan="5" class="text-center">MEDICAL HISTORY</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Diagnosis</th>
                <th>Treatment</th>
                <th>Results</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="date" name="date[]" class="form-control"></td>
                <td><input type="text" name="diagnosis[]" class="form-control"></td>
                <td><input type="text" name="treatment[]" class="form-control"></td>
                <td><input type="text" name="results[]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-primary" id="addRow">Add</button>
                    <button type="button" class="btn btn-danger removeRow">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>


    <div class="form-group mt-3" style="text-align:right">
        <input type="submit" class="btn btn-primary mt-3" value="SAVE RECORD">
    </div>
</form>

        <a href="<?= base_url('records/all'); ?>" class="btn btn-secondary">Back to Records</a>
    </div>
</div>

<script>
   
    $('#addRow').on('click', function() {
        var newRow = $('#medicalHistoryTable tbody tr:last').clone(); 
        newRow.find('input').val(''); 
        $('#medicalHistoryTable tbody').append(newRow); 
    });

    
    $(document).on('click', '.removeRow', function() {
        var rowCount = $('#medicalHistoryTable tbody tr').length;
        if (rowCount > 1) {
            var confirmation = confirm('Are you sure you want to delete this row?');
            if (confirmation) {
                $(this).closest('tr').remove();
            }
        } else {
            alert('You must have at least one row.');
        }
    });
</script>

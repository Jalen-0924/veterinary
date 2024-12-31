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
    <h1 class="h3 d-inline align-middle">Add Records</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form action="<?= base_url('records/saveinfo') ?>" method="post">
                        <div class="form-group col-sm-12 col-xs-12 mb-3"> </div>
          <label>Pet Owner<span>*</span></label>
                        <select class="form-control" name="patient_id" id="patient" required>
    <option value="">Select Owner</option>
    <?php foreach($patients as $row){ ?>
        <option value="<?= $row['id'] ?>"><?= $row['first_name'].' '.$row['last_name']; ?></option>
    <?php } ?>
</select>

    </div><br>

                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Select Pet<span>*</span></label>
                        <select class="form-control" name="pet" id="pets" required>
                            <option value="">Select Pet</option>

                        </select>

                    </div>
                    <div class="form-group col-sm-12 col-xs-12 mb-3">
                        <label>Date<span>*</span></label>
                        <input type="Date" class="form-control" id="date" name="date" placeholder="Date" required>
                    </div>
        </div>

             <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary mt-3" value="ADD RECORD">
                    </div>
                </form>

    </div>
   </div>
  </div>

     


<script>
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
        $('#pets').html('<option value="">Select Pet</option');
    }
});

</script>

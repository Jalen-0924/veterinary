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
    <h1 class="h3 d-inline align-middle">Generate Item & Services Sales</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form action="<?= base_url('sales/generateMonthlyReport'); ?>" method='post'>
                    <div class="row">
                        <h4><span class="text-muted">Items</span></h4>
                        <p></p>
                        <div class="col-sm-6">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary mt-3" value="GENERATE">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
              <form action="<?= base_url('sales/generateMonthlyReportServices'); ?>" method='post'>
                    <div class="row">
                        <h4><span class="text-muted">Services</span></h4>
                        <p></p>
                        <div class="col-sm-6">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary mt-3" value="GENERATE">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

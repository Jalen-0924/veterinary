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
    <h1 class="h3 d-inline align-middle">Add New Invoice</h1>
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




<div class="row">


    <div class="col-sm-6 col-xs-12">
        <div class="card">

            <div class="card-header" style="padding-bottom:0">
                <h4>ADD Services</h4>
            </div>
                   <div class="card-body">
            <div class="row">
                <div class="col-sm-6" style="position:relative">
                    <label>Select Service</label>
                    <select class="form-control" id="service" required>
                        <option value="">Select Service</option>
                        <?php foreach($services as $row){ ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                        <?php } ?>
                        <option value="Others">Others</option>
                    </select>
                     <input type="text" class="form-control" id="serviceOthers" placeholder="Specify" style="display:none;">
                </div>
                <!--- form-group --->


                <div class="col-sm-6" style="position:relative">
                    <label>Price</label>
                    <input type="number" class="form-control" id="serPrice" disabled>
                </div>
                <!--- form-group --->

                <div class="col-sm-2 mt-4">
                    <button type="button" class="btn btn-info btn-block" id="addSER"
                        style="width:100%;height:45px">ADD</button>
                </div>
            </div>
        </div>



        </div>
    </div>


    <div class="col-sm-6 col-xs-12">
        <div class="card">

            <div class="card-header" style="padding-bottom:0">
                <h4>ADD Item</h4>
            </div>
            <div class="card-body">

                <div class="row">
                    <div id="medAvailable"></div>

                    <div class="col-sm-3" style="position:relative">
                        <label>Category<span>*</span></label>
                        <select class="form-control" id="category">
                            <option value="">Category</option>
                            <option value="Medications">Medication</option>
                            <option value="Vaccine">Vaccine</option>
                            <option value="Food and Beverages">Food and Beverages</option>
                            <option value="Pet Supplies">Pet Supplies</option>
                           
                        </select>
                    </div>
                    <!--- form-group --->
                    <div class="col-sm-3" style="position:relative">
                        <label>Item<span>*</span></label>
                        <select class="form-control" id="medicine">
                            <option value="">Select Item</option>
                            <?php foreach($medicines as $row){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="col-sm-2" style="position:relative">
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="medQty" value="1" min="1">
                    </div>
                    <!--- form-group --->


                    <div class="col-sm-4" style="position:relative">
                        <label>Price</label>
                        <input type="number" class="form-control" id="medPrice" disabled>
                    </div>
                    <!--- form-group --->


                  
                    <!--- form-group --->


                    <div class="col-sm-2 mt-4">
                        <button type="button" class="btn btn-info btn-block" id="addMed"
                            style="width:100%;height:45px">ADD</button>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <form action="<?= base_url('invoice/store'); ?>" method='post'>

        <div class="row">

            <div class="col-sm-9 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bodered">
                            <thead>
                                <tr>
                                    <th style="width:80px"> <span class="removeRP"
                                            style="visibility: hidden">Remove</span></th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="tbWrapper">
                                
                            </tbody>
                        </table>
                        <table class="table table-bodered">
                            <thead>
                                <tr>
                                     <th style="width:80px"> <span class="removeRP"
                                            style="visibility: hidden">Remove</span></th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                             <tbody id="tbWrapper1">
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


            <div class="col-sm-3 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-sm-12 mb-3" style="position:relative">
                                <label>Date<span>*</span></label>
                                <input type="date" class="form-control" name="date" value="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>
                            <!--- form-group --->

                            <div class="col-sm-12 mb-3" style="position:relative">
                                <label>Pet Owner<span>*</span></label>
                                <select class="form-control" id="petOwner" name="patient" required>
                                    <option value="">Select Pet Owner</option>
                                    <?php foreach($patient as $row){ ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['first_name'].' '.$row['last_name'] ?></option>
                                    <?php } ?>
                                    <option value="others">Others</option>
                                </select>
                               <input type="text" class="form-control" id="petOwnerOthers" name="petOwnerName" placeholder="Specify" style="display:none;">

                            </div>
                            <!--- form-group --->

                            <div class="col-sm-12 mb-3" style="position:relative">
                                <label>Total</label>
                                <input type="text" class="form-control" name="total" id="total" value="0" readonly>
                            </div>
                            <!--- form-group --->

                            <div class="col-sm-12 mb-3" style="position:relative">
                                <label>Note</label>
                                <textarea class="form-control" name="note"></textarea>
                            </div>
                            <!--- form-group --->


                            <div class="col-sm-12 mt-3">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-ms btn-block"
                                        style="height:50px;width:100%" value="SAVE NOW">
                                </div>
                            </div>
                            <!--- ===col-sm-8 === ---->



                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>



</div>



<script>
$(document).ready(function() {

    //add service
$('#addSER').click(function() {
    var id = $('#service').val() || 'Others';
    var name = (id === 'Others') ? $('#serviceOthers').val() : $('#service option:selected').text();
    var price = $('#serPrice').val();
    var amount = parseInt(price);

    if (name && price) {
        $('#tbWrapper1').append('<tr>' +
            '<td style="width:80px"> <span class="removeRP" data-price="' + amount +
            '">Remove<input type="hidden" value="' + amount + '" class="amount"></span></td>' +
            '<td>' + name + 
            '<input type="hidden" name="serviceName[]" value="' + id + '">' +
            (id === 'Others' ? '<input type="hidden" name="serviceOthers[]" value="' + name + '">' : '') +
            '</td>' +
            '<td class="amount">' + amount +
            '<input type="hidden" name="servicePrice[]" value="' + amount + '"></td>' +
            '</tr>');

            // Update total
            var total = $('#total').val();
            $('#total').val(parseInt(total) + parseInt(amount));

            // Clear inputs
            $('#serPrice').val('');
            $('#serviceOthers').val('');
            $('#service').val('').show();
            $('#serviceOthers').hide();
            $('#serPrice').prop('disabled', true);
        } else {
            alert('Please specify a service and price.');
        }
    });


    document.getElementById('petOwner').addEventListener('change', function () {
    const petOwnerOthers = document.getElementById('petOwnerOthers');
    petOwnerOthers.style.display = this.value === 'others' ? 'block' : 'none';
});
    

    //add Medicine
    $('#addMed').click(function() {
    var id = $('#medicine').val();
    var name = $('#medicine option:selected').text();
    var qty = $('#medQty').val();
    var price = $('#medPrice').val();
    var amount = parseInt(qty) * parseInt(price);
    var available = $('#available').val();

    if (id != '' && name != '' && qty != '' && price != '' && parseInt(qty) <= parseInt(available)) {
        $('#tbWrapper').append('<tr>' +
            '<td style="width:80px"> <span class="removeRP" date-price="' + amount +
            '" >Remove<input type="hidden" value="' + amount + '" class="amount"></span></td>' +
            '<td>' + name + '<input type="hidden" name="medicineName[]" value="' + id +
            '"></td>' +
            '<td>' + qty + '<input type="hidden" name="medicineQty[]" value="' + qty +
            '"></td>' +
            '<td>' + amount + '<input type="hidden" name="medicinePrice[]" value="' + amount +
            '"></td>' +
            '</tr>');

        // Set value for total
        var total = $('#total').val();
        $('#total').val(parseInt(total) + parseInt(amount));

        // Set fields to null
        $('#medPrice').val(null);
        $('#medQty').val(1);
        $('#medicine').val('');

    } else {
        alert('Insufficient Item.');
    }
});




    $(document).on('click', '.removeRP', function() {

        var total = $('#total').val();

        var price = $(this).find('input').val();
        var amount = parseInt(total) - parseInt(price);
        console.log('price :' + price + " / Total : " + total + " / amount : " + amount);
        if (amount > 0) {
            $('#total').val(amount);
        } else {
            $('#total').val(0);
        }


        $(this).parent().parent().remove();
    });





    //get service prices

    $('#service').on('change', function() {
        var id = $(this).val();
        if (id != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('invoice/service/price'); ?>",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    id: id
                },
                success: function(data) {
                    var obj = $.parseJSON(data)
                    $.each(obj, function(key, value) {
                        $('#serPrice').val(value['price']);
                    });

                }
            });
        } else {
            alert('Please Choose Doctor and Date');
        }
    });


    //get Medicine prices

    $('#medicine').on('change', function() {
        var id = $(this).val();
        if (id != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('invoice/medicine/price'); ?>",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    id: id
                },
                success: function(data) {
                    var obj = $.parseJSON(data)
                    $.each(obj, function(key, value) {
                        $('#medPrice').val(value['price']);
                        $('#medAvailable').html('<b>Available Quantity : ' + value[
                                'quantity'] +
                            '</b><input type="hidden" value="' + value[
                                'quantity'] + '" id="available">');
                    });

                }
            });
        } else {
            alert('Please Choose Doctor and Date');
        }
    });


       $('#category').on('change', function() {
        var category = $(this).val();
        if (category) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('invoice/getMedicinesByCategory'); ?>",
                data: { category: category },
                success: function(data) {
                    $('#medicine').empty().append('<option value="">Select Item</option>');
                    var items = JSON.parse(data);
                    items.forEach(function(item) {
                        $('#medicine').append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                }
            });
        } else {
            $('#medicine').empty().append('<option value="">Select Item</option>');
        }
    });


         $('#service').change(function() {
        var selectedValue = $(this).val();
        const priceInput = $('#serPrice');

        if (selectedValue === "Others") {
            $('#service').hide();
            $('#serviceOthers').show().val('').attr("required", true);
            priceInput.prop('disabled', false).val(''); 
        } else {
            $('#serviceOthers').hide();
            $('#service').show();
            priceInput.prop('disabled', true).val(''); 
        }
    });

          document.getElementById('service').addEventListener('change', function() {
        const priceInput = document.getElementById('serPrice');
        if (this.value === 'Others') {
            priceInput.disabled = false; 
        } else {
            priceInput.disabled = true;
            priceInput.value = ''; 
        }
    });

$(document).ready(function() {
    $('#petOwner').change(function() {
        if ($(this).val() === 'others') {
            $('#petOwnerOthers').show();
            $('#petOwnerOthers').prop('required', true);
        } else {
            $('#petOwnerOthers').hide();
            $('#petOwnerOthers').prop('required', false);
        }
    });
});


});
</script>
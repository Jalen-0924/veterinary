<style>
label {
    margin-bottom: 5px;
}

label>span {
    color: red;
}

.form-control {
    height: 45px;
    margin-bottom: 15px;
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
    <h1 class="h3 d-inline align-middle">Add New Pet</h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('pet/store') ?>" method="post">


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
                    <input type="hidden" name="p_id" value="<?= $p_id; ?>">
                    <div class="col-sm-4 col-xs-12">
                        <label>Name<span>*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Pet Name" required>
                    </div>

                <div class="col-sm-4 col-xs-12">
                        <label>Species<span>*</span></label>
                        <select class="form-control" name="species[]">
                            <option value="Canine">Canine - Dog</option>
                            <option value="Feline">Feline - Cat</option>
                            <option value="Others">Others</option>
                        </select>
                <span class="removeRP button_remove" style="display: none">Remove</span>
                </div>

                    <!--- form-group --->

                     <div class="col-sm-4 col-xs-12">
                                    <label>Breed<span>*</span></label>
                                    <select class="form-control" name="breed[]">
                                        <option value="Aspin">Aspin</option>
                                        <option value="Shih Tzu">Shih Tzu</option>
                                        <option value="Siberian Husky">Siberian Husky</option>
                                        <option value="Chihuahua">Chihuahua</option>
                                        <option value="Labrador Retriever">Labrador Retriver</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <span class="removeRP button_remove" style="display: none">Remove</span>
                                </div>

                  
                    <!--- form-group --->

                    <!--- form-group --->

                    <div class="col-sm-4 col-xs-12">
                        <label>Weight<span></span></label>
                        <input type="text" class="form-control" name="weight" placeholder="Weight">
                    </div>
                    <!--- form-group --->

                    <div class="col-sm-2 col-xs-12">
                        <label>Sex<span>*</span></label>
                        <select class="form-control" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>



                    <div class="col-sm-4 col-xs-12">
                        <label>Reproductive Status<span>*</span></label>
                        <select class="form-control" name="rstatus" required>
                            <option value="Intact">Intact</option>
                            <option value="Neutered/Spayed">Neutered/Spayed</option>
                        </select>
                    </div>

                    <!--- form-group --->

                    <div class="col-sm-10 col-xs-12">
                        <label>Color Marking<span>*</span></label>
                        <input type="text" class="form-control" name="colorm" placeholder="Color Marking" required>
                    </div>


                    <div class="col-sm-10 col-xs-12">
                        <label>Micro Chip<span></span></label>
                        <input type="number" class="form-control" name="mchip" placeholder="Microchip #" >
                    </div>

                    <div class="col-sm-12 col-xs-12">
                        <label>Birth Date<span></span></label>
                        <input type="date" class="form-control" name="birthdate" placeholder="Birthdate">
                    </div>


                    <div class="form-group mt-3" style="text-align:right">
                        <input type="submit" class="btn btn-primary" value="ADD PET">
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addRP').click(function() {
        var rp = $("#rpWrapper").clone();
        $('#rpMain').append(rp);
        console.log(rp);
    });

    $(document).on('click', '.removeRP', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('change', 'select[name="species[]"]', function() {
        var species = $(this).val();
        var breedDropdown = $(this).closest('.row').find('select[name="breed[]"]');
        
        if (species == 'Feline') {
    breedDropdown.html(`
        <option value="Puspin">Puspin</option>
        <option value="Persian">Persian</option>
        <option value="Maine Coon">Maine Coon</option>
        <option value="Sphynx">Sphynx</option>
        <option value="Siamese">Siamese</option>
        <option value="British Shorthair">British Shorthair</option>
        <option value="Others">Others</option>
    `);
} else if (species == 'Canine') {
    breedDropdown.html(`
        <option value="Aspin">Aspin</option>
        <option value="Shih Tzu">Shih Tzu</option>
        <option value="Siberian Husky">Siberian Husky</option>
        <option value="Chihuahua">Chihuahua</option>
        <option value="Labrador Retriever">Labrador Retriever</option>
        <option value="Others">Others</option>
    `);
}
 else if (species == 'Others') {
            var textInput = $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'species[]',
                placeholder: 'Specify'
            });
            $(this).replaceWith(textInput);
        } else {
            breedDropdown.html('<option value="">Select a Breed</option>');
        }
    });

    $(document).on('change', 'select[name="breed[]"]', function() {
        var breed = $(this).val();
        if (breed === "Others") {
            var textInput = $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'breed[]',
                placeholder: 'Specify'
            });
            $(this).replaceWith(textInput);
        }
    });
});
</script>
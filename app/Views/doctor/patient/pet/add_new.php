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

.newClass .removeRP {
    display: block !important;
}
.alert.alert-danger {
                background: #ff1a6c57;
                color: #fff;
                padding: 5px 10px;
                border: 1px solid #ff1a6c33;
                border-radius: 5px;
                margin-bottom: 20px;
            }
.alert-success{
                background:#48d79b94;
                 color: #fff;
                padding: 5px 10px;
                border: 1px solid #48d79b94;
                border-radius: 5px;
                margin-bottom: 20px;
            }
</style>



<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Add New Pets -
        <?php echo $petowner[0]['first_name'].' '.$petowner[0]['last_name'] ; ?></h1>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="<?php echo base_url(); ?>/doctor/pet/add_new" method="post">
                <input type="hidden" name="owner_id" value="<?php echo $petowner[0]['id'] ; ?>" />
                <div class="row">
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



                    <div class="col-sm-12">
                        <h4>Pets</h4>
                        <div id="rpMain">
                            <div class="row mb-3" id="rpWrapper">
                                <div class="col-sm-3 col-xs-12">
                                    <label>Name<span>*</span></label>
                                    <input type="text" class="form-control" name="name[]" placeholder="Pet Name">
                                </div>


                                
                                <div class="col-sm-2 col-xs-12" style="position:relative">
                                    <label>Species<span>*</span></label>
                                    <select class="form-control" name="species[]">
                                        <option value="Dog">Dog</option>
                                        <option value="Cat">Cat</option>
                                        <option value="Bird">Bird</option>
                                        <option value="Rabbit">Rabbit</option>
                                    </select>
                                    <span class="removeRP button_remove" style="display: none">Remove</span>
                                </div>
                                <!--- form-group --->

                              
                                <div class="col-sm-2 col-xs-12" style="position:relative">
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

                                <div class="col-sm-2 col-xs-12">
                                    <label>Age<span>*</span></label>
                                    <input type="text" class="form-control" name="age[]" placeholder="Age">
                                </div>
                                <!--- form-group --->

                                <div class="col-sm-2 col-xs-12">
                                    <label>Weight<span>*</span></label>
                                    <input type="text" class="form-control" name="weight[]" placeholder="Weight">
                                </div>
                                <!--- form-group --->

                                <div class="col-sm-2 col-xs-12" style="position:relative">
                                    <label>Gender<span>*</span></label>
                                    <select class="form-control" name="gender[]">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <span class="removeRP button_remove" style="display: none">Remove</span>
                                </div>
                                <!--- form-group --->
                                <div class="col-sm-2 col-xs-12">
                                    <label>Birth Date<span>*</span></label>
                                    <input type="date" class="form-control" name="birthdate[]" placeholder="birthdate">
                                </div>
                            </div>
                            <!--- ===== row ====== ----->
                        </div>

                        <button type="button" class="btn btn-info " id="addRP">ADD MORE</button>

                        <div class="form-group mt-3" style="text-align:right">
                            <input type="submit" class="btn btn-primary" value="SAVE PET">
                        </div>

                    </div>
                    <!--- ====== col-sm-12 ==== ---->

                </div>


            </form>
        </div>
    </div>
</div>






<script>
$(document).ready(function() {
    $('#addRP').click(function() {
        var rp = $("#rpWrapper").clone().addClass('newClass');
        $('#rpMain').append(rp);
        console.log(rp);
    });

    $(document).on('click', '.removeRP', function() {
        $(this).parent().parent().remove();
    });
});
 $(document).on('change', 'select[name="species[]"]', function() {
        var species = $(this).val();
        var breedDropdown = $(this).closest('.row').find('select[name="breed[]"]');
        
        if (species == 'Cat') {
            breedDropdown.html(`
                <option value="Persian">Persian</option>
                <option value="Maine Coon">Maine Coon</option>
                <option value="Sphynx">Sphynx</option>
                <option value="Siamese">Siamese</option>
                <option value="British Shorthair">British Shorthair</option>
                <option value="Others">Others</option>
            `);
        } else if (species == 'Dog') {
            breedDropdown.html(`
                <option value="Aspin">Aspin</option>
                <option value="Shih Tzu">Shih Tzu</option>
                <option value="Siberian Husky">Siberian Husky</option>
                <option value="Chihuahua">Chihuahua</option>
                <option value="Labrador Retriever">Labrador Retriever</option>
            `);
        } else if (species == 'Bird') {
            breedDropdown.html(`
                <option value="Parrot">Parrot</option>
                <option value="Canary">Canary</option>
                <option value="Sparrow">Sparrow</option>
                <option value="Pigeon">Pigeon</option>
                <option value="Others">Others</option>
            `);
        } else if (species == 'Rabbit') {
            breedDropdown.html(`
                <option value="Netherland Dwarf">Netherland Dwarf</option>
                <option value="Holland Lop">Holland Lop</option>
                <option value="Flemish Giant">Flemish Giant</option>
                <option value="Others">Others</option>
            `);
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
</script>
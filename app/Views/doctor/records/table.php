<style>
    #head {
        height: 230px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-left: 20px;
    }

    .pet_info {
        font-size: 20px;
        display: flex;
        align-items: center;
    }

    .pet_img {
        margin-right: 20px;
    }

    .pet_info img {
        max-width: 200px;
        height: auto;
        object-fit: cover;
    }

    .clinic_info {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        font-size: 16px;
        margin-right: 30px;
        margin-top: 20px;
    }

    .clinic_info img {
        max-width: 150px;
        height: auto;
        margin-right: 20px;
    }

    .clinic_info div {
        display: flex;
        flex-direction: column;
    }

    .clinic_info h5 {
        margin: 5px 0;
    }

    #front-btn {
        width: auto;
        min-width: 120px;
        height: auto;
        font-size: 15px;
        border: none;
        background: none;
        color: black;
        cursor: pointer;
        position: relative;
        padding: 15px 15px;
        white-space: normal;
        overflow: visible;
        text-align: center;
        line-height: 1.2;
    }

    #front-btn.active {
        color: #36bf2b;
        font-weight: bold;
    }

    #front-btn.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 4px;
        width: 100%;
        background-color: #36bf2b;
    }

    #front-btn:hover {
        color: #36bf2b;
    }

    .tabs {
    display: flex;
    gap: 8px;
    padding: 10px;
    border-bottom: 1px solid #ccc;
    flex-wrap: wrap;
}

.tabs-button {
    padding: 10px 20px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    position: relative;
    transition: color 0.3s;
}

.tabs-button:hover {
    color: #36bf2b;
}

.tabs-button.active {
    color: #36bf2b;
}

.tabs-button.active::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    height: 3px;
    width: 100%;
    background-color: #36bf2b;
}

.content-section{
    width: 100%;
}
#vaccineHistoryTable th, #dewormingHistoryTable th, #parasiteHistoryTable th{
    background-color: lightgreen;
}
/*#card{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}*/

#downloadRecord{
    background-color: blue;
}
</style>



<div class="col-sm-12">
    <div class="card">
        <div class="card-body" id="head">
            <div class="pet_info">
                <div class="pet_img">
                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none; object-fit: cover;">
                    
                </div>
                <?php if (!empty($record)) : ?>
                    <table>
                        <tr>
                            <td style="font-size: 40px; font-weight: bold;"><?= $record['pet_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Breed: <?= htmlspecialchars($record['pet_breed'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>Color Marking: <?= htmlspecialchars($record['pet_colorm'], ENT_QUOTES, 'UTF-8') ?></td>

                        </tr>
                        <tr>
                             <td>Sex: <?= htmlspecialchars($record['pet_sex'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                          <td>Species: <?= htmlspecialchars($record['pet_species'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    </table>
                <?php else : ?>
                    <p>No pet information available.</p>
                <?php endif; ?>
            </div>

            <div class="clinic_info">
                <!-- Logo added here -->
                <img src="<?= BASEURL ?>/public/img/Official.png" alt="Clinic Logo">
                <div>
                    <h5><span class="text-muted"><strong>Pawsome Furiends Veterinary Clinic</strong></span></h5>
                    <h5><span class="text-muted">Solana, Jasaaan, Misamis Oriental</span></h5>
                    <h5><span class="text-muted">Cell # 09-123-345-6789</span></h5>
                    <br><input type="file" name="pet_image" id="petImageInput" onchange="previewImage(event)">
                </div>
            </div>

        </div>



      <div class="tabs">
    <button id="vaccines-tab" class="tabs-button active" onclick="switchTab('vaccines')">Vaccines</button>
    <button id="deworming-tab" class="tabs-button" onclick="switchTab('deworming')">Deworming</button>
    <button id="parasite-tab" class="tabs-button" onclick="switchTab('parasite')">Tick and Flea Treatment</button>
    <!-- <button id="history-tab" class="tabs-button" onclick="switchTab('history')">History</button> -->

   <div class="form-group mt-3" style="text-align:right; margin-left: auto;">
    <a href="<?= site_url('records/full_history/' . $pet_id) ?>" class="btn btn-primary btn-lg">
        <i class="align-middle" data-feather="download"></i> Download Record
    </a>
</div>

</div>

    </div>
</div>




<div class="row">
    <div class="col-sm-12">
        <form method="post" action="<?= base_url('records/save') ?>">
    <input type="hidden" name="pet_id" value="<?= htmlspecialchars($pet_id, ENT_QUOTES, 'UTF-8') ?>">

        <div id="vaccines-section" class="content-section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><span class="text-muted">Vaccines</span></h3>



                <div class="d-flex gap-2"> 
                    <button class="btn btn-primary btn-lg" id="addRow">
                        <i class="align-middle" data-feather="plus"></i> Add
                    </button>
                    <button class="btn btn-danger" onclick="window.open('<?= base_url('records/vaccine/' . $pet_id) ?>', '_blank')">
                            <i class="align-middle" data-feather="download"></i> PDF
</button>


                    </div>
            </div>

            <div class="card-body">
               
            <table class="table table-bordered table-hover" id="vaccineHistoryTable">
    <thead>
    <tr>
        <th>Vaccination Date</th>
        <th>Valid Until</th>
        <th>Weight (kg)</th>
        <th>Vaccine</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($vaccineHistory)): ?>
        <?php foreach ($vaccineHistory as $row): ?>
            <tr>
                <td><?= date('F d, Y', strtotime(htmlspecialchars($row['vaccine_date'], ENT_QUOTES, 'UTF-8'))) ?></td>
                <td><?= !empty($row['next_date']) ? date('F d, Y', strtotime(htmlspecialchars($row['next_date'], ENT_QUOTES, 'UTF-8'))) : '' ?></td>
                <td><?= htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['vaccine'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="<?= base_url('records/delete/' . $row['id']) ?>" 
                       class="btn btn-sm btn-danger deleteRow">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>

</table>

      <button type="submit" class="btn btn-primary">Save </button>
                </div>

            </div>
        </div>

         
            


    <div id="deworming-section" class="content-section">
    <div class="card" id="deworming">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><span class="text-muted">Deworming</span></h3>
           <div class="d-flex gap-2"> 
    <button class="btn btn-primary btn-lg" id="addDewormingRow">
        <i class="align-middle" data-feather="plus"></i> Add
    </button>
    <button class="btn btn-danger" onclick="window.open('<?= base_url('records/deworming/' . $pet_id) ?>', '_blank')">
        <i class="align-middle" data-feather="download"></i> PDF
    </button>
</div>

        </div>
        <div class="card-body">
            
            <?= csrf_field() ?>
            <table class="table table-bordered" id="dewormingHistoryTable">
                <thead>
                    <tr>
                        <th>Deworm Date</th>
                        <th>Repeat Date</th>
                        <th>Weight (kg)</th>
                        <th>Product</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dewormingHistory)): ?>
                        <?php foreach ($dewormingHistory as $row): ?>
                            <tr>
                                <td>
                                    <?= date('F d, Y', strtotime($row['deworm_date'])) ?>
                                </td>
                                <td>
                                   <?= !empty($row['r_date']) ? date('F d, Y', strtotime($row['r_date'])) : '' ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['weight']) ?>
                                </td>
                                <td>
                                   <?= htmlspecialchars($row['product']) ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeDewormingRow">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>

    
<div id="parasite-section" class="content-section">
    <div class="card" id="parasite">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><span class="text-muted">Tick and Flea Treatment</span></h3>
           <div class="d-flex gap-2"> 
    <button type="button" class="btn btn-primary btn-lg" id="addParasiteRow">
        <i class="align-middle" data-feather="plus"></i> Add
    </button>
    <button class="btn btn-danger" onclick="window.open('<?= base_url('records/parasite/' . $pet_id) ?>', '_blank')">
        <i class="align-middle" data-feather="download"></i> PDF
    </button>
</div>

        </div>
        <div class="card-body">
            
            <?= csrf_field() ?>
            <table class="table table-bordered" id="parasiteHistoryTable">
                <thead>
                    <tr>
                        <th>Treatment Date</th>
                        <th>Next Treatment Date</th>
                        <th>Weight (kg)</th>
                        <th>Product</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($parasiteHistory)): ?>
                        <?php foreach ($parasiteHistory as $row): ?>
                            <tr>
                                <td>
                                    <?= date('F d, Y', strtotime($row['treatment_date'])) ?>
                                </td>
                                <td>
                                   <?= !empty($row['next_date']) ? date('F d, Y', strtotime($row['next_date'])) : '' ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['weight']) ?>
                                </td>
                                <td>
                                   <?= htmlspecialchars($row['product']) ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeParasiteRow">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>


    


</form>
</div>
</div>


</div>






<script>
    function setActive(button) {
        const buttons = document.querySelectorAll('#front-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
    }

    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function() {
            var imagePreview = document.getElementById('imagePreview');
            var imgData = reader.result;

            localStorage.setItem('petImage', imgData);

            imagePreview.src = imgData;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }

    window.onload = function() {
        var savedImage = localStorage.getItem('petImage');
        if (savedImage) {
            var imagePreview = document.getElementById('imagePreview');
            imagePreview.src = savedImage;
            imagePreview.style.display = 'block';
        }
    };


  document.addEventListener('DOMContentLoaded', function() {
    const dewormingTable = document.getElementById('dewormingHistoryTable').getElementsByTagName('tbody')[0];
    const addDewormingButton = document.getElementById('addDewormingRow');

    addDewormingButton.addEventListener('click', function(e) {
        e.preventDefault();
        const newRow = dewormingTable.insertRow();
        
        // Create date input cell
        const dateCell = newRow.insertCell();
        const dateInput = document.createElement('input');
        dateInput.type = 'date';
        dateInput.name = 'deworm_date[]';
        dateInput.className = 'form-control';
        dateInput.required = true;
        dateCell.appendChild(dateInput);

        // Create repeat date input cell
        const repeatDateCell = newRow.insertCell();
        const repeatDateInput = document.createElement('input');
        repeatDateInput.type = 'date';
        repeatDateInput.name = 'r_date[]';
        repeatDateInput.className = 'form-control';
        repeatDateCell.appendChild(repeatDateInput);
        
        // Create weight input cell
        const weightCell = newRow.insertCell();
        const weightInput = document.createElement('input');
        weightInput.type = 'number';
        weightInput.step = '0.01';
        weightInput.name = 'deworm_weight[]'; // Changed from weight[] to match controller
        weightInput.className = 'form-control';
        weightInput.required = true;
        weightCell.appendChild(weightInput);
        
        // Create product input cell
        const productCell = newRow.insertCell();
        const productInput = document.createElement('input');
        productInput.type = 'text';
        productInput.name = 'product[]';
        productInput.className = 'form-control';
        productInput.required = true;
        productCell.appendChild(productInput);
        
        // Create action cell with remove button
        const actionCell = newRow.insertCell();
        const removeButton = document.createElement('button');
        removeButton.className = 'btn btn-sm btn-danger removeDewormingRow';
        removeButton.textContent = 'Remove';
        removeButton.onclick = function(e) {
            e.preventDefault();
            newRow.remove();
        };
        actionCell.appendChild(removeButton);
    });

    // Add event listeners for existing remove buttons
    document.querySelectorAll('.removeDewormingRow').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('tr').remove();
        });
    });
});

function switchTab(tabName) {
    // Hide all content sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tabs-button');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Show the selected section and activate the tab
    const selectedSection = document.getElementById(`${tabName}-section`);
    const selectedTab = document.getElementById(`${tabName}-tab`);

    if (selectedSection) {
        selectedSection.style.display = 'block';
    }
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
}

// Initialize the first tab as active
document.addEventListener('DOMContentLoaded', function () {
    switchTab('vaccines'); // Show vaccines tab by default
});


document.addEventListener('DOMContentLoaded', function() {
    const parasiteTable = document.getElementById('parasiteHistoryTable').getElementsByTagName('tbody')[0];
    const addParasiteButton = document.getElementById('addParasiteRow');

    addParasiteButton.addEventListener('click', function(e) {
        e.preventDefault();
        const newRow = parasiteTable.insertRow();
        
        // Create treatment date input cell
        const treatmentDateCell = newRow.insertCell();
        const treatmentDateInput = document.createElement('input');
        treatmentDateInput.type = 'date';
        treatmentDateInput.name = 'treatment_date[]';
        treatmentDateInput.className = 'form-control';
        treatmentDateInput.required = true;
        treatmentDateCell.appendChild(treatmentDateInput);

        // Create next date input cell
        const nextDateCell = newRow.insertCell();
        const nextDateInput = document.createElement('input');
        nextDateInput.type = 'date';
        nextDateInput.name = 'next_date[]';
        nextDateInput.className = 'form-control';
        nextDateCell.appendChild(nextDateInput);
        
        // Create weight input cell
        const weightCell = newRow.insertCell();
        const weightInput = document.createElement('input');
        weightInput.type = 'number';
        weightInput.step = '0.01';
        weightInput.name = 'weight[]'; // Changed to match controller for parasite
        weightInput.className = 'form-control';
        weightInput.required = true;
        weightCell.appendChild(weightInput);
        
        // Create product input cell
        const productCell = newRow.insertCell();
        const productInput = document.createElement('input');
        productInput.type = 'text';
        productInput.name = 'product[]';
        productInput.className = 'form-control';
        productInput.required = true;
        productCell.appendChild(productInput);
        
        // Create action cell with remove button
        const actionCell = newRow.insertCell();
        const removeButton = document.createElement('button');
        removeButton.className = 'btn btn-sm btn-danger removeParasiteRow';
        removeButton.textContent = 'Remove';
        removeButton.onclick = function(e) {
            e.preventDefault();
            newRow.remove();
        };
        actionCell.appendChild(removeButton);
    });

    // Add event listeners for existing remove buttons
    document.querySelectorAll('.removeParasiteRow').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('tr').remove();
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('vaccineHistoryTable').getElementsByTagName('tbody')[0];
    const addButton = document.getElementById('addRow');

    // Function to add new row
    addButton.addEventListener('click', function(e) {
        e.preventDefault();
        const newRow = table.insertRow();
        
        // Create date input cell
        const dateCell = newRow.insertCell();
        const dateInput = document.createElement('input');
        dateInput.type = 'date';
        dateInput.name = 'vaccine_date[]';
        dateInput.className = 'form-control';
        dateInput.required = true;
        dateCell.appendChild(dateInput);

        const nextDateCell = newRow.insertCell();
        const nextDateInput = document.createElement('input');
        nextDateInput.type = 'date';
        nextDateInput.name = 'next_date[]';
        nextDateInput.className = 'form-control';
        nextDateCell.appendChild(nextDateInput);

        
        // Create weight input cell
        const weightCell = newRow.insertCell();
        const weightInput = document.createElement('input');
        weightInput.type = 'number';
        weightInput.step = '0.01';
        weightInput.name = 'weight[]';
        weightInput.className = 'form-control';
        weightInput.required = true;
        weightCell.appendChild(weightInput);
        
        // Create vaccine input cell
        const vaccineCell = newRow.insertCell();
        const vaccineInput = document.createElement('input');
        vaccineInput.type = 'text';
        vaccineInput.name = 'vaccine[]';
        vaccineInput.className = 'form-control';
        vaccineInput.required = true;
        vaccineCell.appendChild(vaccineInput);
        
        // Create action cell with remove button
        const actionCell = newRow.insertCell();
        const removeButton = document.createElement('button');
        removeButton.className = 'btn btn-sm btn-danger';
        removeButton.textContent = 'Remove';
        removeButton.onclick = function(e) {
            e.preventDefault();
            newRow.remove();
        };
        actionCell.appendChild(removeButton);
    });

    // Handle delete buttons for existing rows
    document.querySelectorAll('.deleteRow').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this record?')) {
                window.location.href = this.href;
            }
        });
    });
});

$('#downloadRecord').click(function() {
    window.location.href = `/records/download/${pet_id}`;
});
</script>

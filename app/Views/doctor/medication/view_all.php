<style>
.badge {
    border: none;
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
.table-container {
    max-height: 150px; 
    overflow-y: auto;
    overflow-x: hidden;
}

.table-container::-webkit-scrollbar {
    display: none;
}
#exp:hover,
#low:hover,
#out:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
 .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .modal-content {
    position: absolute;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    max-width: 800px; 
    width: 100%; 
    margin-bottom: 400px;
}

    .close-button {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
    }

    .close-button:hover {
        color: red;
    }



</style>

<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Item List</h1>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="mytable">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Expiration Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        foreach ($medicines as $row) { 
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td><?= $row['price']; ?></td>
                        <td><?= $row['category']; ?></td>
                        <td>
                            <?= !empty($row['expiration']) ? date('F d, Y', strtotime($row['expiration'])) : ''; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('medicne/edit/'.$row['id']); ?>" class="badge bg-info">
                                <i class="align-middle" data-feather="edit"></i> Edit
                            </a>
                            <a href="<?= base_url('medicne/delete/'.$row['id']); ?>" class="badge bg-danger delete_record">
                                <i class="align-middle" data-feather="trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

                            
<div class="row">
 <?php

$today = date('Y-m-d'); 
$todayItems = [];
$otherItems = [];


foreach ($expiringSoonItems as $item) {
    $expirationDate = date('Y-m-d', strtotime($item['expiration']));
    if ($expirationDate == $today) {
        $todayItems[] = $item; 
    } else {
        $otherItems[] = $item; 
    }
}

$sortedItems = array_merge($todayItems, $otherItems);
?>

<div class="col-sm-4">
    <div class="card" id="exp" style="cursor: pointer;">
        <div class="card-body">
            <h5 class="card-title mb-0">
                <span class="badge bg-warning">Upcoming Expiration</span>
            </h5>
            <br>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sortedItems)): ?>
                            <?php foreach ($sortedItems as $item): ?>
                                <?php 
                                    $expirationDate = date('F d, Y', strtotime($item['expiration']));
                                    $todayFormatted = date('F d, Y');
                                ?>
                                <tr>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td>
                                        <?= $expirationDate ?>
                                        <?php if ($expirationDate == $todayFormatted): ?>
                                            <span class="badge bg-danger">Today</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No items expiring soon found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    <div class="col-sm-4">
        <div class="card" id="low" style="cursor: pointer;">
            <div class="card-body">
                <h5 class="card-title mb-0">
                    <span class="badge bg-info">Low Stock Items</span>
                </h5><br>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lowStockItems)): ?>
                            <?php foreach ($lowStockItems as $item): ?>
                                <tr>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td><?= esc($item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No low stock items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card" id="out" style="cursor: pointer;">
            <div class="card-body">
                <h5 class="card-title mb-0"><span class="badge bg-danger">Out of Stock Items</span></h5><br>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($outOfStockItems)): ?>
                            <?php foreach ($outOfStockItems as $item): ?>
                                <tr>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td><?= esc($item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No out of stock items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--- MODAL AREA --->

<?php
$today = date('Y-m-d'); 
$todayItems = [];
$otherItems = [];

foreach ($expiringSoonItems as $item) {
    $expirationDate = date('Y-m-d', strtotime($item['expiration']));
    if ($expirationDate == $today) {
        $todayItems[] = $item;
    } else {
        $otherItems[] = $item; 
    }
}

$sortedItems = array_merge($todayItems, $otherItems);
?>
<div id="modalOverlay" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h5 class="card-title mb-0">
            <center><span class="text-muted">Upcoming Expirations</span></center>
        </h5><br>
        <div class="card">
            <div class="table-container1">
                <form id="deleteForm" method="post" action="<?= base_url('medicne/delete_exp'); ?>">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"> All
                                </th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sortedItems)): ?>
                                <?php foreach ($sortedItems as $item): ?>
                                    <?php 
                                        $expirationDate = date('F d, Y', strtotime($item['expiration']));
                                        $todayFormatted = date('F d, Y');
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="itemCheckbox" name="selectedItems[]" value="<?= esc($item['id']) ?>">
                                        </td>
                                        <td><?= esc($item['name']) ?></td>
                                        <td><?= esc($item['category']) ?></td>
                                        <td>
                                            <?= $expirationDate ?>
                                            <?php if ($expirationDate == $todayFormatted): ?>
                                                <span class="badge bg-danger">Today</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No items expiring soon found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="badge bg-danger delete_exp">
                        <i class="align-middle" data-feather="trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

 <div id="modalLow" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h5 class="card-title mb-0">
            <center><span class="text-muted">Low Stock Items</span></center>
        </h5><br>
        <div class="card">
            <div class="table-container1">
                <form id="lowStockForm" method="post" action="<?= base_url('medicne/delete_low'); ?>">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAllLowStock" onclick="toggleSelectAll(this)"> All
                                </th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($lowStockItems)): ?>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="itemCheckbox" name="selectedItems[]" value="<?= esc($item['id']) ?>">
                                        </td>
                                        <td><?= esc($item['name']) ?></td>
                                        <td><?= esc($item['category']) ?></td>
                                        <td><?= esc($item['quantity']) ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No low stock items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <button type="submit" class="badge bg-danger delete_low">
                            <i class="align-middle" data-feather="trash"></i>
                        </button>
                        <button type="button" class="badge bg-info" id="openEditModal">
                            <i class="align-middle" data-feather="edit"></i> Edit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<div id="modalOut" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h5 class="card-title mb-0">
            <center><span class="text-muted">Out of Stock Items</span></center>
        </h5><br>
        <div class="card">
            <div class="table-container1">
                <form id="outOfStockForm" method="post" action="<?= base_url(''); ?>">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAllOutOfStock" onclick="toggleSelectAllOutOfStock(this)"> All
                                </th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($outOfStockItems)): ?>
                                <?php foreach ($outOfStockItems as $item): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="itemCheckboxOutOfStock" name="selectedItems[]" value="<?= esc($item['id']) ?>">
                                        </td>
                                        <td><?= esc($item['name']) ?></td>
                                        <td><?= esc($item['category']) ?></td>
                                        <td><?= esc($item['quantity']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No out of stock items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="badge bg-danger delete_out_of_stock">
                        <i class="align-middle" data-feather="trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--- FORM AREA  --->
<div id="modalEdit" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="col-sm-12" id="editLow">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <center><span class="text-muted">Restock Low Items</span></center>
                    </div>
                    <form action="<?= base_url('medicine/restock') ?>" method="post"> <!-- Added form action -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Restock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($lowStockItems)): ?>
                                    <?php foreach ($lowStockItems as $item): ?>
                                        <tr>
                                            <td><?= esc($item['name']) ?></td>
                                            <td><?= esc($item['category']) ?></td>
                                            <td><?= esc($item['quantity']) ?></td>
                                            <td>
                                                <input type="hidden" name="item_ids[]" value="<?= esc($item['id']) ?>"> 
                                                <input type="number" name="quantities[]" min="1" required>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">No low stock items found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="form-group mt-3" style="text-align:right">
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5><span class="text-muted">Inventory Reports</span></h5>
                </div>
                <div class="form-group mt-3" style="text-align:right">

                <form action="<?= base_url('medicne/download_report') ?>" method="post">
                    <button type="submit" class="btn btn-primary"><i class="align-middle" data-feather="download"></i> Excel</button>
               </div>

                <div style="max-height: 400px; overflow-y: auto;"> 
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Stock Value</th> 
                                <th>Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($medicines)): ?>
                                <?php foreach ($medicines as $medicine): ?>
                                    <tr>
                                        <td><?= esc($medicine['name']); ?></td>
                                        <td><?= esc($medicine['category']); ?></td>
                                        <td><?= esc($medicine['quantity']); ?></td>
                                        <td><?= esc($medicine['price']); ?></td>
                                        <td><?= esc($medicine['price'] * $medicine['quantity']); ?></td> 
                                        <td><?= empty($medicine['expiration']) ? '' : esc(date('F d, Y', strtotime($medicine['expiration']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No item data available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                 </form>
            </div>
        </div>
    </div>
</div>





<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    <?php if (!empty($lowStockItems)): ?>
        toastr.info('You have low stock items. Please check the Low Stock Items section.', 'Low Stock Alert', {
            positionClass: 'toast-bottom-right',
            closeButton: true,
            progressBar: true,
            timeOut: 30000
        });
    <?php endif; ?>

    <?php if (!empty($outOfStockItems)): ?>
        toastr.error('Some items are out of stock. Please check the Out of Stock Items section.', 'Out of Stock Alert', {
            positionClass: 'toast-bottom-right',
            closeButton: true,
            progressBar: true,
            timeOut: 30000
        });
    <?php endif; ?>

    $(document).ready(function() {
    <?php if (!empty($expiringSoonItems)): ?>
        toastr.warning('Some items are nearing expiration. Please check the Upcoming Expiration section.', 'Upcoming Expiration', {
            positionClass: 'toast-bottom-right',
            closeButton: true,
            progressBar: true,
            timeOut: 30000
        });
    <?php endif; ?>

    $('.delete_record').click(function() {
        var url = $(this).attr('data-ids');
        Swal.fire({
            icon: 'error',
            html: 'Are you sure you want to delete this record?',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = url;
            }
        });
    });
});
     $('.delete_exp').click(function() {
        var url = $(this).attr('href');
        Swal.fire({
            icon: 'error',
            html: 'Are you sure you want to delete this items?',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
               $('#deleteForm').submit();
            }
        });
        return false; 
    });

     $('.delete_low').click(function() {
        var url = $(this).attr('href');
        Swal.fire({
            icon: 'error',
            html: 'Are you sure you want to delete this items?',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
               $('#deleteForm').submit();
            }
        });
        return false; 
    });

      $(document).ready(function() {
        $('#exp').click(function() {
            $('#modalOverlay').fadeIn();
        });

        $('.close-button').click(function() {
            $('#modalOverlay').fadeOut();
        });

        $('#modalOverlay').click(function(e) {
            if ($(e.target).closest('.modal-content').length === 0) {
                $(this).fadeOut();
            }
        });
    });
      $(document).ready(function() {
        $('#low').click(function() {
            $('#modalLow').fadeIn();
        });

        $('.close-button').click(function() {
            $('#modalLow').fadeOut();
        });

        $('#modalLow').click(function(e) {
            if ($(e.target).closest('.modal-content').length === 0) {
                $(this).fadeOut();
            }
        });
    });
       $(document).ready(function() {
        $('#out').click(function() {
            $('#modalOut').fadeIn();
        });

        $('.close-button').click(function() {
            $('#modalOut').fadeOut();
        });

        $('#modalOut').click(function(e) {
            if ($(e.target).closest('.modal-content').length === 0) {
                $(this).fadeOut();
            }
        });
    });
        $(document).ready(function() {
        $('#openEditModal').click(function() {
            $('#modalEdit').fadeIn();
        });

        $('.close-button').click(function() {
            $('#modaEdit').fadeOut();
        });

        $('#modalEdit').click(function(e) {
            if ($(e.target).closest('.modal-content').length === 0) {
                $(this).fadeOut();
            }
        });
    });


        function toggleSelectAll(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.itemCheckbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllCheckbox.checked;
    });
}


</script>

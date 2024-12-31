<style>
    .badge {
    border: none;
}
#hover1:hover, #hover2:hover, #hover3:hover{
    cursor: pointer;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>


<h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

<div class="row">
                        <div class="col-xl-6 col-xxl-5 d-flex">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card" id="hover1">
                                            <div class="card-body" style="cursor: pointer;" onclick="window.location.href='<?= site_url('sales/add/') ?>'">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Sales</h5>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="truck"></i>
                                                        </div>
                                                    </div>

                                                        <h1 class="mt-1 mb-3"><?= count($invoiceData)?></h1>
                                                <div class="mb-0">
                                                 <span class="badge bg-primary"> <i class="mdi mdi-arrow-bottom-right"></i><?= number_format(count($invoiceData), 2, ). "%" ?></span>
                                                    <span class="text-muted">Sales this month</span>

                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="hover2">
                        <div class="card-body" style="cursor: pointer;" onclick="window.location.href='<?= site_url('medicne/all/') ?>'">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Inventory</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            
                                <h1 class="mt-1 mb-3"><?= $medicine ?></h1>
                                                <div class="mb-0">
                                       
                                            <span class="badge bg-danger"> <i class="mdi mdi-arrow-bottom-right"></i><?= $medicine.".00" . "%" ?></span>
                                                    <span class="text-muted">Items this week</span>
                                                </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card" id="hover3">
                        <div class="card-body" style="cursor: pointer;" onclick="window.location.href='<?= site_url('service/all/') ?>'">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Services</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="package"></i>
                                    </div>
                                </div>
                            </div>
                                    <h1 class="mt-1 mb-3"><?= $service ?></h1>
                                                <div class="mb-0">
                                                <span class="badge bg-warning"> <i class="mdi mdi-arrow-bottom-right"></i><?= $service.".00" . "%" ?></span>
                                                    <span class="text-muted">Services this week</span>
                                                </div>
                           
                        </div>
                    </div>
                    <div class="card" id="hover4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Revenue</h5>
                                </div>



                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="trending-up"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">₱<?= $totalProfit ?></h1>
                                                <div class="mb-0">
                                        <span class="badge bg-success"> <i class="mdi mdi-arrow-bottom-right"></i><?= number_format(count($invoiceData), 2, ). "%" ?></span>
                                                    <span class="text-muted">Since last week</span>
                                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-xxl-7">
                            <div class="card flex-fill w-100">
                                <div class="card-header">

                                    <h5 class="card-title mb-0">Sales Movement</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="chart chart-sm">
                                        <canvas id="chartjs-dashboard-line"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>


                            <div class="row">
   <!--  
  <div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Calendar</h5>
            </div>
            <div class="card-body d-flex">
                <div class="align-self-center w-100">
                    <div class="chart">
                        <div id="datetimepicker-dashboard"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
   

<div class="col-12 col-md-6 col-xxl-9 d-flex">
    <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Stocks Summary</h5>
        </div>
        <table class="table table-hover my-0">
            <thead>
                <tr>
                    <th class="d-none d-xl-table-cell">No.</th>
                    <th class="d-none d-xl-table-cell">Item Name</th>
                    <th class="d-none d-xl-table-cell">Category</th>
                    <?php if (!empty($medicines) && isset($medicines[0]['expiration'])): ?>
                        <th class="d-none d-xl-table-cell">Expiration Date</th>
                    <?php endif; ?>
                    <th class="d-none d-xl-table-cell">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($medicines) && count($medicines) > 0): ?>
                    <?php foreach ($medicines as $index => $medicine): ?>
                        <?php if ($index >= 5) break; ?>
                        <tr>
                            <td class="d-none d-xl-table-cell"><?= $index + 1; ?></td>
                            <td class="d-none d-xl-table-cell"><?= esc($medicine['name']); ?></td>
                            <td class="d-none d-xl-table-cell"><?= esc($medicine['category']); ?></td>
                            <td class="d-none d-xl-table-cell">
                                <?php if (!empty($medicine['expiration'])): ?>
                                    <?= esc(date('F d, Y', strtotime($medicine['expiration']))); ?>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <?php 
                                    if ($medicine['quantity'] > 0) {
                                        echo '<span class="badge bg-success">In Stock</span>';
                                    } else {
                                      
                                        if (!empty($medicine['expiration']) && strtotime($medicine['expiration']) < time()) {
                                            echo '<span class="badge bg-danger">Expired</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Out of Stock</span>';
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No medicines available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>





<?php
$categories = [];
$quantities = [];

foreach ($medicineQuantities as $medicine) {
    $categories[] = $medicine['category'];
    $quantities[] = $medicine['total_quantity'];
}
?>

<div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
    <div class="card flex-fill w-100">
        <div class="card-header">
            <h5 class="card-title mb-0">Stocks</h5>
        </div>
        <div class="card-body d-flex">
            <div class="align-self-center w-100">
                <div class="py-3">
                    <div class="chart chart-xs">
                        <canvas id="chartjs-dashboard-pie"></canvas>
                    </div>
                </div>

                <table class="table mb-0">
                    <tbody>
                        <?php for ($i = 0; $i < count($categories); $i++): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($categories[$i]); ?></td>
                                <td class="text-end"><?php echo htmlspecialchars($quantities[$i]); ?></td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<style>
.today {
    background: #3b7ddd !important;
    color: #fff !important;
}

.selected {
    background: transparent !important;
    color: #393939 !important;
    border: none !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/app.js"></script>
<script>
   
   document.addEventListener("DOMContentLoaded", function() {
    // Pie chart
    new Chart(document.getElementById("chartjs-dashboard-pie"), {
        type: "pie",
        data: {
            labels: <?php echo json_encode($categories); ?>, 
            datasets: [{
                data: <?php echo json_encode($quantities); ?>, 
                backgroundColor: [
                    window.theme.primary,
                    window.theme.warning,
                    window.theme.danger,
                    window.theme.info,
                    window.theme.success
                  
                ],
                borderWidth: 5
            }]
        },
        options: {
            responsive: !window.MSInputMethodContext,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            cutoutPercentage: 75
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
    var gradient = ctx.createLinearGradient(0, 0, 0, 225);
    gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
    gradient.addColorStop(1, "rgba(215, 227, 244, 0)");

    new Chart(document.getElementById("chartjs-dashboard-line"), {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Sales",
                fill: true,
                backgroundColor: gradient,
                borderColor: window.theme.primary,
                data: <?= json_encode($invoiceCounts) ?>
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                intersect: false
            },
            hover: {
                intersect: true
            },
            plugins: {
                filler: {
                    propagate: false
                }
            },
            scales: {
                xAxes: [{
                    reverse: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 1 
                    },
                    display: true,
                    borderDash: [3, 3],
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }]
            }
        }
    });

    var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
    var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
    document.getElementById("datetimepicker-dashboard").flatpickr({
        inline: true,
        prevArrow: "<span title=\"Previous month\">&laquo;</span>",
        nextArrow: "<span title=\"Next month\">&raquo;</span>",
        defaultDate: defaultDate
    });

 
    if (document.getElementById('appointmentToday')) {
        Swal.fire({
            icon: 'info',
            title: 'Appointment Due Today',
            text: 'There are appointments scheduled for today.',
            confirmButtonText: 'Okay'
        });
    }
});
</script>

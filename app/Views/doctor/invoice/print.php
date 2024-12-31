<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,400;1,100;1,200&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Invoice</title>
    <style type="text/css">
        .countainer {
            width: 100%;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        .countainer > img {
            height: 400px;
            position: absolute;
            z-index: -1;
            opacity: 0.2;
            left: calc(50% - 200px);
            top: calc(50% - 200px);
        }

        .head-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #00b1aa;
        }

        .img > img {
            height: 125px;
        }

        .input-flex {
            display: flex;
            justify-content: space-between;
            border-bottom: 3px solid #00b1aa;
        }

        .box {
            width: calc(100% / 3);
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
            border-left: 1px solid #00b1aa;
        }

        .box > p {
            margin-bottom: 0
        }

        .box > h2 {
            margin-top: 0
        }

        .box-active {
            background: #00b1aa;
            border-right: 3px solid #00b1aa;
            color: black;
        }

        .sec-part {
            margin-top: 50px;
        }

        table {
            width: 100%;
            text-align: left;
            border-bottom: 2px solid #00b1aa;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 5px;
        }

        .border {
            width: 100%;
            height: 30px;
            background-color: #00b1aa;
            margin-top: 25px;
        }

        @media print {
            .print_report {
                display: none
            }
        }

        label {
            margin-right: 120px;
            margin-bottom: 80px;
        }

        .dr-name {
            text-align: right;
            font-size: 15px;
        }

        .issued {
            margin-top: 250px;
            float: right;
        }

        .issued hr {
            width: 200px;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div class="countainer">
        <div class="head-flex">
            <div class="img">
                <img src="<?= BASEURL ?>/public/img/Official.png">
            </div>
            <div class="address">
                <label><b>Pawsome Furiends Veterinary Clinic</b><br>
                    Solana, Jasaan, Misamis Oriental<br>
                    Cell# : 0912-345-6789
                </label>
            </div>

        <div class="dr-name" style="text-align: right;">
    <p><b>Name :</b>
        <?php if (isset($print['pet_owner_name']) && !empty($print['pet_owner_name'])): ?>
            <?= htmlspecialchars($print['pet_owner_name']) ?>
        <?php elseif (isset($print['patient'][0])): ?>
            <?= $print['patient'][0]['first_name'].' '.$print['patient'][0]['last_name'] ?>
        <?php else: ?>
            <em>No patient data available</em>
        <?php endif; ?>
    </p>
    <?php if (isset($print['patient'][0]['phone']) && $print['patient'][0]['phone']) { ?>
        <p><b>Phone :</b> <?= $print['patient'][0]['phone']; ?></p>
    <?php } ?>
    <?php if (isset($print['patient'][0]['city']) && $print['patient'][0]['city']) { ?>
        <p><b>City :</b> <?= $print['patient'][0]['city']; ?></p>
    <?php } ?>
    <?php if (isset($print['patient'][0]['address']) && $print['patient'][0]['address']) { ?>
        <p><b>Address :</b> <?= $print['patient'][0]['address']; ?></p>
    <?php } ?>
    <?php if (isset($print['patient'][0]['zipcode']) && $print['patient'][0]['zipcode']) { ?>
        <p><b>Zipcode :</b> <?= $print['patient'][0]['zipcode']; ?></p>
    <?php } ?>
</div>


        </div>

        <div class="input-flex">
            <div class="box">
                <p>Invoice #</p>
                <h2>#<?= $print['id']; ?></h2>
            </div>

            <div class="box">
                <p>Date</p>
                <h2><?= $print['date']; ?></h2>
            </div>

            <div class="box box-active">
                <p>Total</p>
                <h2><?= $print['total']; ?></h2>
            </div>
        </div>

        <div class="sec-part">
         <table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Added On</th>
            <th>Price</th>
            <th style="text-align: right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $service_details = json_decode($print['service_details'], true) ?? [];
        $ser_price = json_decode($print['ser_price']) ?? [];
        $ser_dates = json_decode($print['ser_dates'] ?? '[]');  // Add null coalescing

        for($i = 0; $i < count($service_details); $i++) {
            $amount = $ser_price[$i] ?? 0;
            $display_name = $service_details[$i]['name'] ?? 'Unnamed Service';
            $date = isset($ser_dates[$i]) ? $ser_dates[$i] : $print['date'];  // Use invoice date as fallback
        ?>
            <tr>
                <td><?= htmlspecialchars($display_name); ?></td>
             <td><?= date('m/d/Y', strtotime($date)); ?></td>

                <td><?= number_format($amount, 0); ?></td>
                <td style="text-align: right;"><?= number_format($amount, 0); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>


            <br>

           
<table>
    <thead>
        <tr>
            <th>Items</th>
        </tr>
        <tr>
            <th>ID #</th>
            <th>Item</th>
            <th>Added On</th>
            <th>Qty</th>
            <th>Price</th>
            <th style="text-align: right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $medicine_details = json_decode($print['medicine_details'], true);
        $med_amount = json_decode($print['med_amount']);
        $med_qty = json_decode($print['med_qty']);
        $med_dates = json_decode($print['med_dates'] ?? '[]');  // Add null coalescing
        
        for($i = 0; $i < count($medicine_details); $i++) { 
            $date = isset($med_dates[$i]) ? $med_dates[$i] : $print['date'];  // Use invoice date as fallback
        ?>
        <tr>
            <td><?= $medicine_details[$i]['id']; ?></td>
            <td><?= $medicine_details[$i]['name']; ?></td>
          <td><?= date('m/d/Y', strtotime($date)); ?></td>

            <td><?= $med_qty[$i]; ?></td>
            <td><?= $med_amount[$i] / $med_qty[$i]; ?></td>
            <td style="text-align: right;"><?= $med_amount[$i]; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
            <p><b>Note : </b> <?= $print['note']; ?></p>

            <div class="issued">Issued by:<br><hr></div>

            <button type="button" class="btn btn-primary print_report" style="padding:8px 10px; background: #00b4d8; border:none;">
                <a href="javascript:void()" style="color:white; text-decoration:none;"><b>Print</b></a>
            </button>
        </div>

        <div class="border"></div>
    </div>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$('.print_report').click(function() {
    window.print();
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report - <?= $month ?>/<?= $year ?></title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>
    <h1>Monthly Sales Report for <?= $month ?>/<?= $year ?></h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Service Name</th>
                <th>Service Quantity</th>
                <th>Service Price</th>
                <th>Medicine Name</th>
                <th>Medicine Quantity</th>
                <th>Medicine Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salesData as $sale): ?>
                <tr>
                    <td><?= $sale['date'] ?></td>
                    <td><?= $sale['ser_name'] ?></td>
                    <td><?= $sale['ser_qty'] ?></td>
                    <td><?= $sale['ser_price'] ?></td>
                    <td><?= $sale['med_name'] ?></td>
                    <td><?= $sale['med_qty'] ?></td>
                    <td><?= $sale['med_price'] ?></td>
                    <td><?= $sale['total'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

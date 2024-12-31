<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<style type="text/css">
.countainer {
    width: 100%;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: sans-serif;
}

.countainer>img {
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
    border-bottom: 3px solid #88adff;
}

.img>img {
    height: 120px;
}


.input-flex {
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
}

.outline {
    width: 150px;
    outline: none;
    border-top: none;
    border-left: none;
    border-right: none;
    font-size: 18px;
    text-align: center;
    text-decoration: none;
}

.sec-part {
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
}

.left {
    width: 220px;
    border-right: 1px solid black;
    align-items: center;
    height: 800px;
    padding: 0 20px 20px 20px;
    box-sizing: border-box;

}

.left ul {
    padding-left: 0px;
}

.left li {
    text-decoration: none
}

.right {
    width: calc(100% - 220px);
    padding: 0 20px 20px 50px;

}

.dumy>li {
    margin-top: 10px;
    margin-left: 20px;
    padding-left: 0;
}

.sec-dumy {
    margin-top: 10px;
    margin-left: 15px;
    padding-left: 0;

}

ol.sec-dumy>li {
    margin-top: 10px;
}

.border {
    width: 100%;
    height: 30px;
    background-color: #88adff;
    margin-top: 25px;
}

@media print {
    .print_report {
        display: none
    }
}
</style>



<div class="countainer">
    <img src="<?= BASEURL ?>/public/img/Official.png">

    <div class="head-flex">
        <div class="dr-name">
            <label>Veterinarian:  <b><u><?= isset($report['doctor_name']) ? $report['doctor_name'] : 'N/A' ?></u></b>
                <button type="button" class="btn btn-primary print_report"
                    style="padding:8px 10px; background: #00b4d8; border:none;">
                    <a href="javascript:void()" class="" style="color:white; text-decoration:none;"><b>Print
                            Report</b></a>
                </button>
            </label>
        </div>
        <div class="img">
            <img src="<?= BASEURL ?>/public/img/Official.png"><br><br>
        </div>
    </div>

    <div class="input-flex">
        <div>
            <label>Name:</label>
            <input type="text" class="outline" value="<?= isset($report['patient_name']) ? $report['patient_name'] : 'N/A' ?>">
        </div>

        <div>
            <label>Pet name:</label>
            <input type="text" class="outline" value="<?= isset($report['pet_name']) ? $report['pet_name'] : 'N/A' ?>">
        </div>

        <div>
            <label>Date:</label>
            <input type="text" class="outline" value="<?= isset($report['date']) ? $report['date'] : 'N/A' ?>">
        </div>
    </div>

    <div class="sec-part">
        <div class="left">
            <h3>Services</h3>
            <ul class="dumy">
                <?php if (isset($report['services']) && is_array($report['services'])): ?>
                    <?php foreach($report['services'] as $row): ?>
                        <li><?= $row ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>N/A</li>
                <?php endif; ?>
            </ul>

            <hr>

            <h3>Symptoms</h3>
            <ul class="dumy">
                <?php if (isset($report['symptoms']) && is_array(json_decode($report['symptoms']))): ?>
                    <?php foreach(json_decode($report['symptoms']) as $row): ?>
                        <li><?= $row ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                 
                <?php endif; ?>
            </ul>
        </div>

        <div class="right">
            <h3>Items</h3>
            <ol class="sec-dumy">
                <?php if (isset($report['medicines']) && is_array($report['medicines'])): ?>
                    <?php foreach($report['medicines'] as $row): ?>
                        <li><?= $row ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>N/A</li>
                <?php endif; ?>
            </ol>
        </div>
    </div>
    <div class="border"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $('.print_report').click(function() {
        console.log('work');
        window.print();
    });
    </script>
</div>

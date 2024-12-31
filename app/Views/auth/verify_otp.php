<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="Veterinary">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="<?= BASEURL ?>public/img/icons/pet.ico" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    
    <title>Veterinary Clinic Management System</title>

    <link href="<?= BASEURL ?>public/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        #logo {
            margin: 30px auto;
        }

        .auth-wrapper {
            background: #fff;
            padding: 20px;
            max-width: 990px;
            margin: 0 auto;
            width: 100%;
            position: relative;
        }

        .loginWrap {
            padding-right: 40px;
        }

        .loginWrap > h2 {
            text-align: center;
            margin: 29px 0 20px 0;
            font-weight: 600;
            color: #111;
        }

        .form-group {
            margin: 10px 0;
        }

        .form-group > label {
            margin-bottom: 3px;
        }

        .form-group > label > span {
            color: red;
        }

        .form-control {
            height: 50px;
        }

        .btn-primary {
            background: #00b1aa;
            border: none;
            height: 45px;
            margin-top: 15px;
            width: 100%;
        }

        .btn-primary:hover {
            background: #36bf2b;
            border-color: white;
        }

        .href {
            color: #00b1aa;
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

</head>

<body>
    <div class="main">
        <img src="<?= BASEURL ?>/public/img/Official.png" id="logo" height="150px">
        <div class="row auth-wrapper">
            <div class="col-sm-12 verifyWrap">
                <h2><b>OTP VERIFICATION</b></h2>
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo session()->getFlashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php if (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url() ?>/user/check_otp" method="post">
                    <div class="form-group">
                        <label>Enter OTP</label>
                        <input type="number" name="otp" class="form-control" placeholder="OTP" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Verify" class="btn btn-block btn-primary">
                    </div>
                </form>
                <form action="<?= base_url() ?>/user/resend_otp" method="post">
    <div class="form-group">
        <input type="submit" value="Resend OTP" class="btn btn-block btn-secondary">
    </div>
</form>
<br>

                <br>
                <a href="<?= base_url('') ?>" class="href">Go back to <b>Login</b></a>
            </div>
        </div>
    </div>
</body>



</html>

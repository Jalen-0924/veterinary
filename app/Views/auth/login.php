<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Veterinary">
    <meta name="keywords" content="">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="<?= BASEURL ?>public/img/icons/pet.ico" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>A Web-Based Veterinary Clinic Management System</title>

    <link href="<?= BASEURL ?>public/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <!--<script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js"></script>-->

</head>

<body>
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

        .registerWrap {
            padding-left: 40px;
        }

        .loginWrap>h2,
        .registerWrap>h2 {
            text-align: center;
            margin: 29px 0 20px 0;
            font-weight: 600;
            color: #111;
        }

        .form-group {
            margin: 10px 0;
        }

        .form-group>label {
            margin-bottom: 3px;
        }

        .form-group>label>span {
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

        .href:hover {
            color: #36bf2b;
        }

        .divider {
            height: 250px;
            border-right: 1px solid #f1f1f1;
            width: 1px;
            padding: 0;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
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
        #fb{
            width: 120px;
        }
        #google{
            width: 100px;
            background-color: #BB001B;
        }

    </style>

    <div class="main">

        <img src="<?= BASEURL ?>/public/img/Official.png" id="logo" height="150px">
        <div class="row auth-wrapper">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="col-sm-12 loginWrap">
                <h2>LOGIN</h2>
                <div class="form-group">


                    <form action="<?= base_url() ?>/user/login" method="post">
                        <div class="form-group">
                            <label>Email<span>*</span></label>
                            <input type="email" placeholder="Email" name="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Password<span>*</span></label>
                            <input type="password" placeholder="Password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <input type="submit" value="LOGIN" name="aw_login" class="btn btn-block btn-primary">
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url('user/register') ?>" class="href">Don't Have Account? <b>Register
                                    Now</b></a>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?= base_url('user/forgetpass') ?>" class="float-end" style="color: red;">Forgot
                                Password</a>
                        </div>
                    </div>
                  <div class="form-group text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <hr class="flex-grow-1" style="border: 1px solid #ccc;">
                        <span style="margin: 0 10px;">OR</span>
                        <hr class="flex-grow-1" style="border: 1px solid #ccc;">
                    </div>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                       <a href="<?= base_url('user/facebook_login') ?>" class="btn btn-primary d-flex align-items-center" style="background-color: #4267B2;" id="fb">
                        <img src="<?= BASEURL ?>/public/img/facebook.png" id="logo" style="height: 20px; margin-right: 10px;">
                        <span>Facebook</span>
                    </a>
                      <a href="<?= base_url('user/google_login') ?>" class="btn btn-primary d-flex align-items-center" id="google">
                    <img src="<?= BASEURL ?>/public/img/gmail.png" id="logo" style="height: 20px; margin-right: 10px;">
                    <span>Gmail</span>
                </a>
                    </div>
                </div>


<!-- Add Facebook SDK -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId=YOUR_APP_ID"
    nonce="RANDOM_NONCE">
</script>


                </div>

            </div>




</body>

</html>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>
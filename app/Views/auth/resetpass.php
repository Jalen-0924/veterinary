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

    .btn-primary:hover{
        background: #36bf2b;
        border-color: white;
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

    label {
        margin-bottom: 5px;
    }

    label>span {
        color: red;
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

    /* The message box is shown when the user clicks on the password field */
    #message {
        display: none;
        background: #f1f1f1;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
    }

    #message p {
        padding: 2px 35px;
        font-size: 18px;
        margin-bottom: 0px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -35px;
        content: "\2713";
    }

    /* Add a red text color and an "x" icon when the requirements are wrong */
    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -35px;
        content: "X";
    }
    </style>

    <div class="main">

        <img src="<?= BASEURL ?>/public/img/Official.png" id="logo" height="150px">
        <div class="row auth-wrapper">

            <div class="col-sm-12 loginWrap">
                <h2>Reset Password</h2>

                <?php if(session()->getFlashdata('msg')):?>
                <div class="alert alert-warning">
                    <?= session()->getFlashdata('msg') ?>
                </div>
                <?php endif;?>

                <form action="<?= base_url() ?>/user/updatepassword" method="post">
                    <div class="form-group">
                        <label>Password<span>*</span></label>
                        <input type="password" id="psw" placeholder="Password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required name="password" class="form-control">
                        <div id="message">
                            <h3>Password must contain the following:</h3>
                            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                            <p id="number" class="invalid">A <b>number</b></p>
                            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password<span>*</span></label>
                        <input type="password" placeholder="Passwrod" name="c_password" class="form-control">
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $user['id'] ; ?>" />


                    <div class="form-group">
                        <input type="submit" value="RESET PASSWORD" name="aw_login" class="btn btn-block btn-primary">
                    </div>
                </form>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?= base_url() ?>">Already Have an Account <b>Login Now</b></a>
                    </div>

                </div>
                <!--- ===== col-sm-6 ===== ---->



            </div>

        </div>




</body>

</html>


<script>
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");


myInput.onfocus = function() {
    document.getElementById("message").style.display = "block";
}

myInput.onblur = function() {
    document.getElementById("message").style.display = "none";
}

myInput.onkeyup = function() {

    var lowerCaseLetters = /[a-z]/g;
    if (myInput.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
    } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

    var upperCaseLetters = /[A-Z]/g;
    if (myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

  
    var numbers = /[0-9]/g;
    if (myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

   
    if (myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
    } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
    }
}
</script>
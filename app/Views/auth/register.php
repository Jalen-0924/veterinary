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
        .btn-primary:disabled {
         cursor: no-drop;
         background-color: #c0c0c0; 
         color: #666666; 
     }

        .href {
            color: #00b1aa;
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

        .valid {
            color: green;
        }

        .valid:before {
            position: relative;
            left: -35px;
            content: "\2713";
        }

        .invalid {
            color: red;
        }

        .invalid:before {
            position: relative;
            left: -35px;
            content: "X";
        }
    </style>
</head>

<body>
    <div class="main">
        <img src="<?= BASEURL ?>/public/img/Official.png" id="logo" height="150px">
        <div class="row auth-wrapper">
            <div class="col-sm-12 registerWrap">
                <h2>SIGN UP</h2>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $error; ?>
                    </div>
                <?php } ?>
                <?php if (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url() ?>/user/store" method="post" id="registrationForm">
                    <div class="form-group">
                        <label>Registration Form</label>
                        <select required name="user_type" class="form-control" style="display: none;">
                            <option value="patient">Pet Owner</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>First Name<span>*</span></label>
                        <input type="text" placeholder="First Name" required name="f_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Last Name<span>*</span></label>
                        <input type="text" placeholder="Last Name" required name="l_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email<span>*</span></label>
                        <input type="email" placeholder="Email" required name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password<span>*</span></label>
                        <input type="password" id="psw" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required name="password" class="form-control">
                    </div>
                    <div id="message">
                        <h3>Password must contain the following:</h3>
                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                        <p id="number" class="invalid">A <b>number</b></p>
                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                    </div><br>
                    <div class="form-group">
                        <input type="checkbox" id="termsCheckbox"> By clicking accept, I agree to the Terms and Conditions</a>.
                    </div>
                    <div class="form-group">
                        <input type="submit" value="REGISTER" name="aw_login" class="btn btn-block btn-primary" id="registerButton" disabled>
                    </div>
                </form>

                <br>
                <a href="<?= base_url('') ?>" class="href">Already Have Account? <b>Login Now</b></a>
            </div>
        </div>
    </div>

   <!-- Modal for Terms and Conditions -->
<div id="termsModal" style="display:none; position:fixed; left:0; top:0; width:100%; height:100%; background-color: rgba(0,0,0,0.7); z-index:1000;">
    <div style="background:white; margin-top: 100px; margin-left:650px;padding: 20px; width: 80%; max-width: 600px; max-height: 80%; overflow-y: auto; border-radius: 10px; position:relative;">
        <h2><center>Terms and Conditions</center></h2>
        <div id="termsContent" style="height: 400px; overflow-y: auto; padding-right: 10px;">
            <h3>Introduction</h3>
            <p>Welcome to Pawsome Furiends Veterinary Clinic.<br>These Terms and Conditions govern your use of our services and are designed to protect both user privacy and data security. Our commitment to privacy is reflected in our privacy-by-default approach, implementing end-to-end security measures to safeguard your information.</p>
            <h3>User Roles and Responsibilities</h3>
            <p>Administrators have core access to the system, managing user personal data (names, emails, contact numbers, addresses), pet profiles, and system security.<br>Doctors have specialized access to view and update patient medical records, while clients can manage appointments, view personal information, and access their pets' records.</p>
            <h3>Data Collection and Usage</h3>
            <p>We collect personal information (name, email, contact number, address) and pet data (name, breed, species, sex, age, and medical history). This data is essential for improving our services, maintaining security, and communicating update.</p>
            <h3>Access Control and Authentication</h3>
            <p>Users must follow strong password requirements and email verification via OTP. Access is restricted based on user roles, ensuring data security.</p>
            <h3>Data Protection and Security</h3>
            <p>Our security measures include two-way symmetric encryption for data in transit and at rest. We retain personal data only for as long as necessary.</p>
            <h3>Testing and Maintenance</h3>
            <p>We conduct regular access control monitoring and emergency system maintenance as needed to ensure security.</p>
            <h3>User Consent</h3>
            <p>Users consent to data collection and processing by accepting these terms during account creation or when confirming new features. Consent can be withdrawn by contacting the clinic administrator.</p>
            <h3>Dispute Resolution</h3>
            <p>Users can report issues by submitting detailed information through our report section. The clinic will respond within 24 hours, investigate, and provide feedback via email.</p>
            <h3>Access to Terms and Conditions</h3>
            <p>Our Terms and Conditions are accessible via the website footer and in account settings under "Legal Documents."</p>
            <h3>Review Schedule</h3>
            <p>We conduct an annual review of these terms to ensure they reflect regulatory changes and system improvements.</p>
            <h3>Committees Involved</h3>
            <p>Oversight committees, including veterinarians and administrators, are responsible for implementing privacy policies, handling client data concerns, and securing pet and owner records.</p>
        </div>
        <button id="acceptTerms" class="btn btn-primary">Accept</button>
        <span id="closeTerms" style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 24px; color: #888;">&times;</span>
    </div>
</div>


    <script>
     
        var myInput = document.getElementById("psw");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var length = document.getElementById("length");

        myInput.onfocus = function () {
            document.getElementById("message").style.display = "block";
        }

        myInput.onblur = function () {
            document.getElementById("message").style.display = "none";
        }

        myInput.onkeyup = function () {
            var lowerCaseLetters = /[a-z]/g;
            letter.classList.toggle("valid", myInput.value.match(lowerCaseLetters));
            letter.classList.toggle("invalid", !myInput.value.match(lowerCaseLetters));

            var upperCaseLetters = /[A-Z]/g;
            capital.classList.toggle("valid", myInput.value.match(upperCaseLetters));
            capital.classList.toggle("invalid", !myInput.value.match(upperCaseLetters));

            var numbers = /[0-9]/g;
            number.classList.toggle("valid", myInput.value.match(numbers));
            number.classList.toggle("invalid", !myInput.value.match(numbers));

            length.classList.toggle("valid", myInput.value.length >= 8);
            length.classList.toggle("invalid", myInput.value.length < 8);
        }

        
        $("#termsCheckbox").change(function () {
            if (this.checked) {
                $("#termsModal").show();
            } else {
                $("#registerButton").prop("disabled", true);
            }
        });

        
$("#acceptTerms").click(function () {
    $("#registerButton").prop("disabled", false);
    $("#termsModal").hide();
    $("#termsCheckbox").prop("checked", true); 
});


$("#closeTerms").click(function () {
    $("#termsModal").hide();
});
    </script>
</body>

</html>

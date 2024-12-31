<?php

namespace App\Controllers;


use App\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;
use Facebook\Facebook;
use Config\Services;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
class Auth extends BaseController
{
    public function facebook_login()
    {
        try {
            $fb = new Facebook([
                'app_id' => '2646775892179486',
                'app_secret' => 'fa04401cc17f9d35d9254f18fcdbc32f',
                'default_graph_version' => 'v18.0',
                'http_client_handler' => 'curl' // Add this line
            ]);

            $helper = $fb->getRedirectLoginHelper();

            // Store CSRF state in session
            $state = bin2hex(random_bytes(16));
            session()->set('fb_state', $state);

            $permissions = ['email'];
            $callbackUrl = base_url('user/facebook_callback');
            $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);

            return redirect()->to($loginUrl);

        } catch (\Exception $e) {
            log_message('error', 'Facebook login error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'Facebook login initialization failed');
        }
    }

    public function facebook_callback()
    {
        try {
            // Initialize Facebook with curl handler
            $fb = new Facebook([
                'app_id' => '2646775892179486',
                'app_secret' => 'fa04401cc17f9d35d9254f18fcdbc32f',
                'default_graph_version' => 'v18.0',
                'http_client_handler' => 'curl'
            ]);

            $helper = $fb->getRedirectLoginHelper();

            // Handle state parameter
            if (isset($_GET['state'])) {
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
            }

            // Error handling for access token
            try {
                $accessToken = $helper->getAccessToken();
            } catch (FacebookResponseException $e) {
                log_message('error', 'Graph returned an error: ' . $e->getMessage());
                return redirect()->to('/')->with('error', 'Facebook Graph returned an error');
            } catch (FacebookSDKException $e) {
                log_message('error', 'Facebook SDK returned an error: ' . $e->getMessage());
                return redirect()->to('/')->with('error', 'Facebook SDK returned an error');
            }

            if (!isset($accessToken)) {
                if ($helper->getError()) {
                    log_message('error', "Error: " . $helper->getError() . "\n" .
                        "Error Code: " . $helper->getErrorCode() . "\n" .
                        "Error Reason: " . $helper->getErrorReason() . "\n" .
                        "Error Description: " . $helper->getErrorDescription() . "\n");

                    return redirect()->to('/')->with('error', $helper->getErrorReason());
                }
                return redirect()->to('/')->with('error', 'Access token not received');
            }

            try {
                // Exchange short-lived token for long-lived token
                $oAuth2Client = $fb->getOAuth2Client();
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

                // Make the request to get user data
                $response = $fb->get('/me?fields=id,first_name,last_name,email', $accessToken->getValue());
                $fbUser = $response->getGraphUser();

                // Check if we got the required data
                if (!$fbUser || !$fbUser->getId()) {
                    throw new \Exception('Failed to get user data from Facebook');
                }

                $userModel = new UserModel();
                $user = $userModel->where('email', $fbUser->getEmail())->first();

                if (!$user) {
                    $userData = [
                        'first_name' => $fbUser->getFirstName() ?? '',
                        'last_name' => $fbUser->getLastName() ?? '',
                        'email' => $fbUser->getEmail() ?? $fbUser->getId() . '@facebook.com',
                        'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                        'user_type' => 'patient',
                        'status' => 1,
                        'facebook_id' => $fbUser->getId(),
                        'is_verified' => 1
                    ];

                    $userId = $userModel->insert($userData, true);
                    $user = $userModel->find($userId);
                }

                session()->set([
                    'id' => $user['id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'user_type' => $user['user_type'],
                    'profile_pic' => $user['profile_pic'] ?? null,
                    'isLoggedIn' => true
                ]);

                return redirect()->to('/dashboard');

            } catch (\Exception $e) {
                log_message('error', 'Facebook data retrieval error: ' . $e->getMessage());
                return redirect()->to('/')->with('error', 'Failed to get user data from Facebook');
            }
        } catch (\Exception $e) {
            log_message('error', 'Facebook callback error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'Facebook authentication failed');
        }
    }
    //////////////////////////////////////////////////////////////////////////paybok end /////////////////////////////////////////
    public function google_login()
    {
        // Google Client Configuration
        $client = new Google_Client();
        $client->setClientId("YOUR_GOOGLE_CLIENT_ID");
        $client->setClientSecret("YOUR_GOOGLE_CLIENT_SECRET");
        $client->setRedirectUri('http://localhost/veterinary/user/google_callback');
        $client->addScope(['email', 'profile']);

        return redirect()->to($client->createAuthUrl());
    }
    public function google_callback()
    {
        try {

            log_message('debug', 'Starting Google callback process');

            $client = new Google_Client();
            $client->setClientId('195156080482-vmqiog4tkmbk9h2v8aaal2p657emkcls.apps.googleusercontent.com');
            $client->setClientSecret('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');
            $client->setRedirectUri('http://localhost/veterinary/user/google_callback');


            log_message('debug', 'Authorization code received: ' . ($_GET['code'] ?? 'No code present'));

            if (!isset($_GET['code'])) {
                throw new \Exception('No authorization code received');
            }

            log_message('debug', 'Attempting to fetch access token');
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                throw new \Exception('Token error: ' . $token['error']);
            }

            log_message('debug', 'Access token received successfully');
            $client->setAccessToken($token);

            if ($client->isAccessTokenExpired()) {
                log_message('debug', 'Token expired, refreshing...');
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            }

            log_message('debug', 'Fetching user data');
            $service = new Google_Service_Oauth2($client);
            $user_data = $service->userinfo->get();

            log_message('debug', 'User data received: ' . $user_data->email);

            if (empty($user_data->email) || empty($user_data->id)) {
                throw new \Exception('Incomplete user data received from Google');
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $user_data->email)->first();

            if (!$user) {
                $userData = [
                    'first_name' => $user_data->givenName ?? $user_data->name,
                    'last_name' => $user_data->familyName ?? '',
                    'email' => $user_data->email,
                    'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                    'user_type' => 'patient',
                    'status' => 1,
                    'google_id' => $user_data->id,
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'] ?? null,
                    'token_expiry' => $token['expires_in'] ?? null,
                    'profile_pic' => null
                ];

                $insertedId = $userModel->insert($userData, true);
                $user = $userModel->find($insertedId);
            }


            session()->set([
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'user_type' => $user['user_type'],
                'profile_pic' => $user['profile_pic'] ?? null,
                'isLoggedIn' => true
            ]);


            log_message('debug', 'Login successful, redirecting to dashboard');
            return redirect()->to('/dashboard');

        } catch (\Exception $e) {
            log_message('error', 'Google authentication error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->to('/')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }

    public function index()
    {
        helper(['form']);
        $data = [];

        echo view('login', $data);
    }

    public function register_callback()
    {

        echo view('auth/register');
    }


    public function forget_callback()
    {

        echo view('auth/forgetpass');
    }


    public function resetpass_callback($email, $token)
    {
        $userModel = new UserModel();
        $check_token = $userModel->where('email', $email)->where('token', $token)->first();
        if ($check_token) {
            $data['user'] = $check_token;
            echo view('auth/resetpass', $data);
        } else {
            return redirect()->to('/');
        }

    }


    public function store()
    {
        $session = session();
        helper(['form']);
        $userModel = new userModel();

        $email = $this->request->getVar('email');
        $email_exists = $userModel->check_email($email);

        if (!$email_exists) {
            $otp = rand(100000, 999999);

            $data = [
                'first_name' => $this->request->getVar('f_name'),
                'last_name' => $this->request->getVar('l_name'),
                'email' => $email,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'user_type' => $this->request->getVar('user_type'),
                'otp' => $otp,
                'is_verified' => 0
            ];

            $userModel->save($data);

            $html = '<p>Dear ' . $this->request->getVar('f_name') . ' ' . $this->request->getVar('l_name') . ',</p>';
            $html .= '<p>Thank you for registering with our Web-Based Veterinary Clinic Management System</p>';
            $html .= '<p>Your One Time Password (OTP) for verification is <strong>' . $otp . '</strong>.</p>';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pawsomefuriends.business@gmail.com';
            $mail->Password = 'rlxr trtg lbir hdvx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('admin@pawsome.com', 'Pawsome Furiends');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP Verification';
            $mail->Body = $html;

            try {
                $mail->send();
                $session->setFlashdata('success', 'An OTP has been sent to your email. Please verify.');
            } catch (Exception $e) {
                $session->setFlashdata('error', 'Email could not be sent. Error: ' . $mail->ErrorInfo);
                return redirect()->to('user/register');
            }

            $session->set('email', $email);
            return redirect()->to('user/verify_otp');
        } else {
            $data['error'] = 'Email already exists';
            echo view('auth/register', $data);
        }
    }

    public function resend_otp()
    {
        $session = session();
        $userModel = new userModel();


        $email = $session->get('email');

        if ($email) {

            $user = $userModel->where('email', $email)->first();

            if ($user) {

                $otp = rand(100000, 999999);


                $userModel->update($user['id'], ['otp' => $otp]);


                $html = '<p>Dear ' . $user['first_name'] . ' ' . $user['last_name'] . ',</p>';
                $html .= '<p>Your new OTP for verification is <strong>' . $otp . '</strong>.</p>';
                $html .= '<p>Please enter this OTP to complete your registration.</p>';
                $html .= '<p>Best regards,<br>Pawsome Furiends</p>';


                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pawsomefuriends.business@gmail.com';
                $mail->Password = 'rlxr trtg lbir hdvx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('admin@pawsome.com', 'Pawsome Furiends');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'OTP Resend';
                $mail->Body = $html;

                try {
                    $mail->send();
                    $session->setFlashdata('success', 'A new OTP has been sent to your email.');
                } catch (Exception $e) {
                    $session->setFlashdata('error', 'OTP could not be sent. Error: ' . $mail->ErrorInfo);
                }
            } else {
                $session->setFlashdata('error', 'User not found.');
            }
        } else {
            $session->setFlashdata('error', 'No email found in session.');
        }

        return redirect()->to('user/verify_otp');
    }


    public function verify_otp()
    {
        return view('auth/verify_otp');
    }

    public function check_otp()
    {
        $session = session();
        $userModel = new userModel();

        $entered_otp = $this->request->getVar('otp');
        $email = $session->get('email');

        if ($email) {
            $user = $userModel->where('email', $email)->first();

            if ($user) {
                if ($user['otp'] == $entered_otp) {
                    $userModel->update($user['id'], ['is_verified' => 1, 'otp' => null]);

                    $session->setFlashdata('success', 'Your account has been verified. Please log in.');
                    return redirect()->to('/');
                } else {
                    $session->setFlashdata('error', 'Invalid OTP. Please try again.');
                    return redirect()->to('user/verify_otp');
                }
            } else {
                $session->setFlashdata('error', 'User not found.');
                return redirect()->to('user/verify_otp');
            }
        } else {
            $session->setFlashdata('error', 'No email found in session.');
            return redirect()->to('user/verify_otp');
        }
    }

    public function sendReport()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = $_POST['message'];
            $file = $_FILES['problem_file'];


            $mail = new PHPMailer(true);

            try {

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pawsomefuriends.business@gmail.com';
                $mail->Password = 'rlxr trtg lbir hdvx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;


                $mail->setFrom('pawsomefuriends.business@gmail.com', 'Pawsome Furiends');
                $mail->addAddress('pawsomefuriends.business@gmail.com', 'Support Team');


                if ($file && $file['error'] == 0) {
                    $mail->addAttachment($file['tmp_name'], $file['name']);
                }


                $mail->isHTML(true);
                $mail->Subject = 'Problem Report';
                $mail->Body = nl2br($message);


                $mail->send();


                echo json_encode(['success' => true]);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
            }
        }
    }

    //     public function sendReport() {
//     if ($this->request->getMethod() === 'post') {
//         $message = $this->request->getPost('message');
//         $file = $this->request->getFile('problem_file');

    //         // Initialize PHPMailer
//         $mail = new PHPMailer(true);

    //         try {
//             // SMTP configuration
//             $mail->isSMTP();
//             $mail->Host       = 'smtp.gmail.com';  // Set your SMTP server
//             $mail->SMTPAuth   = true;
//             $mail->Username   = 'pawsomefuriends.business@gmail.com';  // SMTP username
//             $mail->Password   = 'musm gfph ywjd djgr';  // SMTP password
//             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//             $mail->Port       = 587;

    //             // Recipients
//             $mail->setFrom('your_email@example.com', 'Pawsome Furiends');
//             $mail->addAddress('pawsomefuriends.business@gmail.com', 'Support Team');

    //             // Check if file is attached
//             if ($file && $file->isValid()) {
//                 $mail->addAttachment($file->getTempName(), $file->getName());  // Attach file
//             }

    //             // Email content
//             $mail->isHTML(true);
//             $mail->Subject = 'Problem Report';
//             $mail->Body    = nl2br($message);

    //             // Send the email
//             $mail->send();

    //             // Return success response
//             return $this->response->setJSON(['success' => true]);

    //         } catch (Exception $e) {
//             return $this->response->setJSON(['success' => false, 'error' => $mail->ErrorInfo]);
//         }
//     }
//     return $this->response->setJSON(['success' => false, 'error' => 'Invalid request']);
// }




    public function login()
    {
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $userModel->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'first_name' => $data['first_name'],
                    'phone' => $data['phone'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'user_type' => $data['user_type'],
                    'profile_pic' => $data['profile_pic'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');

            } else {
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/');
            }
        } else {
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('/');
        }
    }


    public function forget()
    {
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('email');


        $data = $userModel->where('email', $email)->orwhere('phone', $email)->first();

        if ($data) {
            $token = md5(rand());

            $form_data = array(
                'token' => $token
            );

            $userModel->update($data['id'], $form_data);
            $msg = '<p>To reset your password, please <a href="' . base_url() . '/user/reset-password/' . $data['email'] . '/' . $token . '">click here</a> and enter a new password';
            $email = \Config\Services::email();
            $email->setTo($data['email']);
            $email->setFrom('pawsomefuriends.business@gmail.com', 'Pawsome Furiends');

            $email->setSubject('Reset Password');
            $email->setMessage($msg);
            if ($email->send()) {
                $success = '<p>An email is sent to your email address. Please follow instructions and reset the password</p>';
                $session->setFlashdata('msg', $success);
                return redirect()->to('/user/forgetpass');
            } else {
                $data = $email->printDebugger(['headers']);

                $session->setFlashdata('msg', $data);
                return redirect()->to('/user/forgetpass');
            }


        } else {
            $session->setFlashdata('msg', 'Please enter correct Email Or Phone Number');
            return redirect()->to('/user/forgetpass');
        }
    }

    public function updatepassword()
    {
        $session = session();
        $userModel = new UserModel();
        $password = $this->request->getVar('password');
        $c_password = $this->request->getVar('c_password');
        $user_id = $this->request->getVar('user_id');

        //$token = md5(rand());


        $form_data = array(
            'token' => '',
            'password' => password_hash($password, PASSWORD_DEFAULT),
        );

        $userModel->update($user_id, $form_data);



        $success = '<p>Password Updated Successfully</p>';
        $session->setFlashdata('msg', $success);
        return redirect()->to('/');




    }

    public function logout()
    {
        $session = session();
        $session->destroy(); ?>
        <script type="text/javascript">
            localStorage.ids = 'undefined';
        </script>
        <?php
        return redirect()->to('/');

    }


    public function profile()
    {
        $userModel = new UserModel();
        $user_id = $_SESSION['id'];


        $data['users'] = $userModel->getUsers($user_id);

        return $this->render_template('admin/profile', $data);

    }


    public function update_profile()
    {
        $session = session();
        $userModel = new UserModel();
        $id = $this->request->getVar('user_id');

        if ($this->request->getFile('file') != '') {
            $validateImage = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file, image/png, image/jpg,image/jpeg, image/gif]',
                    'max_size[file, 4096]',
                ],
            ]);


            if ($validateImage) {
                $imageFile = $this->request->getFile('file');
                $imageFile->move('uploads');
                $session->set(array('profile_pic' => $imageFile->getClientName(), 'phone' => $this->request->getVar('phone')));

                $data = array(
                    'first_name' => $this->request->getVar('first_name'),
                    'last_name' => $this->request->getVar('last_name'),
                    'email' => $this->request->getVar('email'),
                    'phone' => $this->request->getVar('phone'),
                    'city' => $this->request->getVar('city'),
                    'zipcode' => $this->request->getVar('zipcode'),
                    'address' => $this->request->getVar('address'),
                    'profile_pic' => $imageFile->getClientName(),
                );

            } else {
                $session->setFlashdata('Error', 'Select Valid image');
                return redirect()->to('admin/profile');
            }

        } else {
            $session->set(array('phone' => $this->request->getVar('phone')));
            $data = array(
                'first_name' => $this->request->getVar('first_name'),
                'last_name' => $this->request->getVar('last_name'),
                'email' => $this->request->getVar('email'),
                'phone' => $this->request->getVar('phone'),
                'city' => $this->request->getVar('city'),
                'zipcode' => $this->request->getVar('zipcode'),
                'address' => $this->request->getVar('address')
            );
        }



        $update = $userModel->update($id, $data);
        if ($update) {
            $session->setFlashdata('success', 'Successfully Updated');
            return redirect()->to('admin/profile');
        } else {
            $session->setFlashdata('Error', 'Not Updated');
            return redirect()->to('admin/profile');
        }

    }





}
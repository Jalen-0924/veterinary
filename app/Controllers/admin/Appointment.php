<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\appointmentModel;
use App\Models\slotModel;
use App\Models\serviceModel;
use App\Models\medicineModel;
use App\Models\patReportModel;
use App\Models\symptomModel;
use App\Models\invoiceModel;

class Appointment extends BaseController
{
    public function index()
    {
        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $serviceModel = new serviceModel();
        $patient_id = $_SESSION['id'];

        $data['doctors'] = $Model->getUsersByType('doctor');
        $data['patients'] = $Model->getUsersByType('patient');
        $data['services'] = $serviceModel->getServices();
        $data['pets'] = $petModel->getPetsByOwner();

        return $this->render_template('admin/appointment/add_new', $data);

    }
    public function view_all()
    {
        $session = session();
        $Model = new appointmentModel();
        $appointmentModel = new appointmentModel();
        $serviceModel = new serviceModel();
        $data['appointment'] = $Model->getAppointments();


        return $this->render_template('admin/appointment/view_all', $data);

    }





    public function send_reminder()
    {
        $session = session();
        $Model = new appointmentModel();
        $serviceModel = new serviceModel();
        $data['appointments'] = $Model->getAppointmentspending();
        $data['services'] = $serviceModel->getServices();

        return $this->render_template('admin/appointment/send_reminder', $data);

    }


    public function update_status($id)
    {
        $status = $this->request->getPost('status');

        $this->appointmentModel->update($id, ['status' => $status]);


        return redirect()->to(base_url('admin/view_all'));
    }



    public function send_confirmation($user_id, $appointment_id)
    {
        $Model = new appointmentModel();
        $appointment_details = $Model->getSingleAppointment($appointment_id);

        $session = session();
        $Model = new userModel();
        $get_user = $Model->getUsers($user_id);




        $appointment_date = new \DateTime($appointment_details[0]['appointment_date']);
        $formatted_date = $appointment_date->format('l, F j, Y');

        $msg = 'Dear ' . $get_user[0]['first_name'] . ' ' . $get_user[0]['last_name'] .
            ', your appointment for your pet, ' . $appointment_details[0]['name'] .
            ', has been confirmed at Pawsome Furiends Veterinary Clinic on ' . $formatted_date .
            ' at ' . $appointment_details[0]['slot'] .
            '. If you have any questions or need to reschedule, please let us know. We look forward to seeing you and your pet soon! Best Regards, Pawsome Staff';

        $email = \Config\Services::email();
        $email->setTo($get_user[0]['email']);
        $email->setFrom('pawsomefuriends@gmail.com', 'Pawsome Furiends');
        $email->setSubject('Appointment Confirmed');
        $email->setMessage($msg);

        if ($email->send()) {
            $success = '<p>Confirmation email sent to Pet Owner</p>';
            $session->setFlashdata('success', $success);
        } else {
            $data = $email->printDebugger(['headers']);
            $session->setFlashdata('error', $data);
        }

        $send_data = [];

        $send_data['mobile'] = '+63' . $get_user[0]['phone'];
        $send_data['message'] = $msg;
        $send_data['token'] = '7705b7f8e0d8baf036c8d0cc8e241f98';
        $parameters = json_encode($send_data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://app.qproxy.xyz/api/sms/v1/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [];
        $headers = array(
            "Content-Type: application/json"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $get_sms_status = curl_exec($ch);
    }


    public function save_appointment()
    {
        $session = session();
        $appointmentModel = new appointmentModel();

        $appointment_id = $this->request->getPost('appointment_id');
        $status = $this->request->getPost('status');
        $patient_id = $this->request->getPost('patient_id');
        $services = $this->request->getPost('services');
        $service_price = $this->request->getPost('service_price');
        $service_id = $this->request->getPost('service_id');


        $appointmentData = ['status' => $status];
        $appointmentModel->update($appointment_id, $appointmentData);

        if ($status == 'Confirm') {
            $this->send_confirmation($patient_id, $appointment_id);


            $invoice_id = $this->createInvoice([
                'appointment_id' => $appointment_id,
                'patient_id' => $patient_id,
                'services' => $services,
                'service_price' => $service_price,
                'service_id' => $service_id
            ]);

            if ($invoice_id) {

                $appointmentModel->update($appointment_id, ['invoice_id' => $invoice_id]);

                return redirect()->to('http://localhost/veterinary/index.php/appointment/all');
            } else {
                $session->setFlashdata('error', 'Failed to create invoice.');
            }
        } elseif ($status == 'Decline') {
            $this->send_decline_notification($patient_id, $appointment_id);
        }

        $session->setFlashdata('success', 'Appointment status updated successfully!');
        return redirect()->to(base_url('appointment/start_appointment/' . $appointment_id));
    }

    private function createInvoice($appointmentData)
    {
        $invoiceModel = new InvoiceModel();

        $invoiceData = [
            'date' => date('Y-m-d'),
            'patient_id' => $appointmentData['patient_id'],
            'ser_name' => json_encode([$appointmentData['service_id']]),
            'ser_qty' => json_encode([1]),
            'ser_price' => json_encode([$appointmentData['service_price']]),
            'total' => $appointmentData['service_price'],
        ];

        log_message('info', 'Invoice Data: ' . json_encode($invoiceData));

        if ($invoiceModel->insert($invoiceData)) {
            return $invoiceModel->insertID();
        } else {

            log_message('error', 'Failed to insert invoice: ' . json_encode($invoiceModel->errors()));
            return false;
        }
    }



    public function send_decline_notification($user_id, $appointment_id)
    {
        $Model = new appointmentModel();
        $appointment_details = $Model->getSingleAppointment($appointment_id);

        $session = session();
        $Model = new userModel();
        $get_user = $Model->getUsers($user_id);


        $formatted_date = date('F d, Y', strtotime($appointment_details[0]['appointment_date']));

        $decline_reason = $this->request->getPost('decline_reason');

        $msg = 'Dear ' . $get_user[0]['first_name'] . ' ' . $get_user[0]['last_name'] . ', unfortunately, your appointment for your pet, ' . $appointment_details[0]['name'] . ', at Pawsome Furiends Veterinary Clinic on ' . $formatted_date . ' at ' . $appointment_details[0]['slot'] . ' has been declined due to the following reason: ' . ' ' . $decline_reason . ' ' . '. Please contact us for further information or to reschedule. Best Regards, Pawsome Staff';

        $email = \Config\Services::email();
        $email->setTo($get_user[0]['email']);
        $email->setFrom('pawsomefuriends@gmail.com', 'Pawsome Furiends');
        $email->setSubject('Appointment Declined');
        $email->setMessage($msg);

        if ($email->send()) {
            $success = '<p>Decline notification email sent to Pet Owner</p>';
            $session->setFlashdata('success', $success);
        } else {
            $data = $email->printDebugger(['headers']);
            $session->setFlashdata('error', $data);
        }

        $send_data = [];

        $send_data['mobile'] = '+63' . $get_user[0]['phone'];
        $send_data['message'] = $msg;
        $send_data['token'] = '7705b7f8e0d8baf036c8d0cc8e241f98';
        $parameters = json_encode($send_data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://app.qproxy.xyz/api/sms/v1/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [];
        $headers = array(
            "Content-Type: application/json"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $get_sms_status = curl_exec($ch);
    }






    public function send_reminders($user_id, $appointment_id)
    {

        $Model = new appointmentModel();
        $appointment_details = $Model->getSingleAppointment($appointment_id);

        $session = session();
        $Model = new userModel();
        $get_user = $Model->getUsers($user_id);

        $msg = 'Dear ' . $get_user[0]['first_name'] . ' ' . $get_user[0]['last_name'] . ', this is a friendly reminder that your pet, ' . $appointment_details[0]['name'] . ', has an upcoming appointment with us at Pawsome Furiends Veterinary Clinic on ' . $appointment_details[0]['appointment_date'] . ' at ' . $appointment_details[0]['slot'] . '. /n Please let us know if you need to reschedule or if you have any questions. We look forward to seeing you and your pet soon. Best Regard, Pawsome Staff';

        $email = \Config\Services::email();
        $email->setTo($get_user[0]['email']);
        $email->setFrom('PawsomeFuriends@gmail.com', 'Appointment Reminder');

        $email->setSubject('Appointment Reminder');
        $email->setMessage($msg);

        if ($get_user[0]['phone'] != '') {
            $user = 'qM7e5lqd2v';
            $pass = 'CgPUuIEJhSUmOOwtNMWVSL72bYVylw0LdyuDlH6s';
            $from = '66688';
            $to = $get_user[0]['phone'];

            $message = 'Dear' . $get_user[0]['first_name'] . '' . $get_user[0]['last_name'] . ', \r \rThis is a friendly reminder that your pet, ' . $appointment_details[0]['name'] . ', has an upcoming appointment with us at Pawsome Furiends Veterinary Clinic on ' . $appointment_details[0]['appointment_date'] . ' at ' . $appointment_details[0]['slot'] . '. \r \rPlease let us know if you need to reschedule or if you have any questions. We look forward to seeing you and your pet soon. \r \rBest Regard, \rPawsome Staff';

            $message = rawurlencode($message);
            $message_new = str_replace("%5Cr", "%0A", $message);


            $url = '';
            $oldurl = $url . "?user=$user&pass=$pass&from=$from";
            $newurl = $oldurl . "&to=" . $to . "&text=" . $message_new;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $newurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $sentResult = curl_exec($ch);


            if ($sentResult == FALSE) {
                echo 'Curl failed for sending sms to crm.. ' . curl_error($ch);
            }
            curl_close($ch);
        }



        if ($email->send()) {
            $success = '<p>Reminder is sent to Pet Owner</p>';
            $session->setFlashdata('success', $success);
            return redirect()->to('/appointment/send_reminder');
        } else {
            $data = $email->printDebugger(['headers']);

            $session->setFlashdata('error', $data);
            return redirect()->to('/appointment/send_reminder');
        }
    }



    public function edit_admin($id)
    {

        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $slotModel = new slotModel();
        $appointmentModel = new appointmentModel();

        $appoint = $appointmentModel->where('id', $id)->find();
        var_dump($appoint);
        die;
        $patient_id = $appoint[0]['patient_id'];

        $data['doctors'] = $Model->getUsersByType('doctor');
        $data['pets'] = $petModel->getPetsByOwner($patient_id);
        $data['appointment'] = $appointmentModel->getSingleAppointment($id);

        $data['slot'] = $slotModel->getSlotByAppointment($id);


        return $this->render_template('admin/appointment/edit', $data);

    }



    public function get_pets()
    {
        $session = session();
        $petModel = new petModel();

        $id = $this->request->getVar('id');
        $pets = $petModel->getPetsByOwner($id);
        foreach ($pets as $row) { ?>
            <option value="<?= $row['id'] ?>"><?= $row['name']; ?></option>
        <?php }
    }






    // for patients

    public function add_appointment()
    {

        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $serviceModel = new serviceModel();
        $patient_id = $_SESSION['id'];

        $data['doctors'] = $Model->getUsersByType('doctor');
        $data['pets'] = $petModel->getPetsByOwner($patient_id);
        $data['services'] = $serviceModel->getServices();

        return $this->render_template('patient/appointment/add_new', $data);

    }

    public function follow_up_appointment($id)
    {

        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $patient_id = $_SESSION['id'];
        $Model2 = new appointmentModel();
        $single_appointment = $Model2->getSingleAppointment($id);

        $data['appointment'] = $single_appointment[0];
        $data['pets'] = $petModel->getPetsByOwner($single_appointment[0]['patient_id']);

        return $this->render_template('doctor/appointment/add_new_followup', $data);

    }


    public function all_appointment()
    {
        $session = session();
        $Model = new appointmentModel();
        $patient_id = $_SESSION['id'];
        $data['appointments'] = $Model->getAppointments($patient_id);
        return $this->render_template('patient/appointment/view_all', $data);

    }

    public function edit($id = null)
    {
        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $slotModel = new slotModel();
        $appointmentModel = new appointmentModel();
        $serviceModel = new serviceModel();
        $appoint = $appointmentModel->where('id', $id)->find();
        $patient_id = $appoint[0]['patient_id'];


        $data['doctors'] = $Model->getUsersByType('doctor');
        $data['pets'] = $petModel->getPetsByOwner($patient_id);
        $data['appointment'] = $appointmentModel->getSingleAppointment($id);
        $data['services'] = $serviceModel->getServices();

        $data['slot'] = $slotModel->getSlotByAppointment($id);


        return $this->render_template('patient/appointment/edit', $data);

    }


    public function get_slots()
    {

        $session = session();
        $Model = new appointmentModel();

        $doctor = $this->request->getVar('doctor');
        $date = $this->request->getVar('date');

        return $Model->get_available_slots($date, $doctor);
    }



    public function get_date_slots()
    {
        $slotModel = new slotModel();


        $date = $this->request->getPost('date');

        if ($date) {

            $slots = $slotModel->getSlotsByDate($date);


            return $this->response->setJSON($slots);
        } else {
            return $this->response->setJSON(['error' => 'Date is required']);
        }
    }

    public function get_available_dates()
    {
        $slotModel = new slotModel();


        $availableDates = $slotModel->getAvailableDates();


        return $this->response->setJSON($availableDates);
    }



    //doctor 

    public function doctor_all()
    {
        $session = session();
        $Model = new appointmentModel();
        $doctor_id = $_SESSION['id'];
        $data['appointments'] = $Model->getAppointmentsByDoctor($doctor_id);
        return $this->render_template('doctor/appointment/view_all', $data);

    }

    public function doctor_today()
    {
        $session = session();
        $Model = new appointmentModel();
        $doctor_id = $_SESSION['id'];
        $today = date('Y-m-d');
        $data['appointments'] = $Model->getAppointmentsByDoctor($doctor_id);
        return $this->render_template('doctor/appointment/today', $data);

    }

    public function startAppointment($id)
    {

        $session = session();
        $appointmentModel = new appointmentModel();
        $medicineModel = new medicineModel();
        $serviceModel = new serviceModel();


        $data['medicines'] = $medicineModel->getMedicine();
        //$data['symptom'] = $symptomModel->getSymptom();
        $data['services'] = $serviceModel->getServices();
        $data['appointment'] = $appointmentModel->getSingleAppointment($id);

        return $this->render_template('admin/appointment/start_appointment', $data);
    }

    public function editAppointment($id)
    {

        $session = session();
        $appointmentModel = new appointmentModel();
        $medicineModel = new medicineModel();
        $serviceModel = new serviceModel();
        $patReportModel = new patReportModel();
        $symptomModel = new symptomModel();

        $data['symptom'] = $symptomModel->getSymptom();
        $data['medicines'] = $medicineModel->getMedicine();
        $data['services'] = $serviceModel->getServices();
        $data['appointment'] = $appointmentModel->getSingleAppointment($id);
        $data['app_data'] = $patReportModel->getReports($id);

        return $this->render_template('doctor/appointment/edit_appointment', $data);
    }






    public function store()
{
    $session = session();
    $Model = new appointmentModel();

    // Get the user type and patient ID
    $user_type = $_SESSION['user_type'];
    $patient = ($user_type == 'admin') ? $this->request->getVar('patient_id') : $_SESSION['id'];

    // Get form data
    $pets = $this->request->getVar('pets'); // This will get the array of selected pets
    $date = $this->request->getVar('date');
    $timeslot = $this->request->getVar('timeslot');
    $services_id = $this->request->getVar('services_id');

    // Validate required fields
    if (empty($pets) || empty($date) || empty($timeslot) || empty($services_id)) {
        $session->setFlashdata('error', 'All fields are required');
        return redirect()->to($user_type == 'admin' ? 'appointment/add' : 'appointment/add_new');
    }

    // Ensure pets is always an array
    $pets = is_array($pets) ? $pets : [$pets];

    $success = true;
    $errorMessages = [];

    // Create an appointment for each selected pet
    foreach ($pets as $pet_id) {
        $data = [
            'date' => $date,
            'patient_id' => $patient,
            'pet_id' => $pet_id,
            'services_id' => $services_id,
            'status' => 'Pending',
            'timeslot' => $timeslot,
        ];

        try {
            if (!$Model->save($data)) {
                $success = false;
                // Get validation errors if any
                $errors = $Model->errors();
                if ($errors) {
                    $errorMessages = array_merge($errorMessages, array_values($errors));
                } else {
                    $errorMessages[] = 'Unknown error occurred while saving appointment';
                }
                log_message('error', 'Appointment creation failed: ' . print_r($Model->errors(), true));
                break;
            }
        } catch (\Exception $e) {
            $success = false;
            $errorMessages[] = $e->getMessage();
            log_message('error', 'Exception during appointment creation: ' . $e->getMessage());
            break;
        }
    }

    if ($success) {
        $session->setFlashdata('success', 'Successfully created appointments for selected pets');

        // Handle email notification for non-admin users
        if ($user_type != 'admin') {
            try {
                $email = \Config\Services::email();
                $email->setTo($_SESSION['email']);
                $email->setFrom('vetclinicsystem99@gmail.com', 'Pawsome Furiends');
                $email->setSubject('Appointment Set');
                $email->setMessage('<p>New appointments have been set! Please check your appointment list.</p>');
                $email->send();
            } catch (\Exception $e) {
                log_message('error', 'Email sending failed: ' . $e->getMessage());
                // Continue even if email fails
            }
        }

        return redirect()->to($user_type == 'admin' ? 'appointment/add' : 'appointment/add_new');
    } else {
        // Join all error messages with commas
        $errorMessage = 'Error creating appointments: ' . implode(', ', $errorMessages);
        $session->setFlashdata('error', $errorMessage);
        return redirect()->to($user_type == 'admin' ? 'appointment/add' : 'appointment/add_new');
    }
}






    public function store_docotr()
    {
        $session = session();
        $Model = new appointmentModel();

        $user_type = $_SESSION['user_type'];

        $patient = $this->request->getVar('patient_id');


        $doctor_id = $this->request->getVar('doctor');

        $userModel = new userModel();
        $doctor = $userModel->getUsers($doctor_id);

        $data = array(
            'date' => $this->request->getVar('date'),
            'patient_id' => $patient,
            'pet_id' => $this->request->getVar('pet'),
            'status' => 'Pending',
            'doctor_id' => $this->request->getVar('doctor'),
            'doctor_name' => $doctor[0]['first_name'] . ' ' . $doctor[0]['last_name'],
            'timeslot' => $this->request->getVar('timeslot'),
        );


        $inserted = $Model->save($data);

        if ($inserted) {
            $session->setFlashdata('success', 'Successfully Inserted');

            if ($user_type == 'admin') {
                return redirect()->to('appointment/add');
            } else {
                $msg = '<p>A new appointment is set! Please check your appointment list.';

                $email = \Config\Services::email();
                $email->setTo($_SESSION['email']);
                $email->setFrom('vetclinicsystem99@gmail.com', 'Pawsome Furiends');

                $email->setSubject('Pawsome Furiends | APPOINTMENT CONFIRMATION');
                $email->setMessage($msg);
                if ($email->send()) {
                    //$success = '<p>An email is sent to your email address. Please follow instructions and reset the password</p>';
                    //$session->setFlashdata('msg', $success);
                    return redirect()->to('doctor/all_appointment');
                } else {
                    //$data = $email->printDebugger(['headers']);

                    //$session->setFlashdata('msg', $data);
                    return redirect()->to('doctor/all_appointment');
                }



            }

        } else {
            $session->setFlashdata('Error', 'Not Inserted');

            if ($user_type == 'admin') {
                return redirect()->to('appointment/add');
            } else {
                return redirect()->to('appointment/add_new');
            }

        }

    }

    public function update()
    {
        $session = session();
        $user_type = $_SESSION['user_type'];
        $appointmentModel = new appointmentModel();
        $userModel = new userModel();

        $id = $this->request->getVar('id');
        $data = array(
            'date' => $this->request->getVar('date'),
            'patient_id' => $this->request->getVar('patient_id'),
            'pet_id' => $this->request->getVar('pet'),
            'doctor_id' => $this->request->getVar('doctor'),
            'timeslot' => $this->request->getVar('timeslot'),
        );

        $update = $appointmentModel->update($id, $data);

        if ($update) {

            $appointment_details = $appointmentModel->getSingleAppointment($id);
            $get_user = $userModel->getUsers($appointment_details[0]['patient_id']);


            $formatted_date = date('F d, Y', strtotime($data['date']));


            $msg = 'Dear ' . $get_user[0]['first_name'] . ' ' . $get_user[0]['last_name'] .
                ', your appointment for your pet, ' . $appointment_details[0]['name'] .
                ', at Pawsome Furiends Veterinary Clinic has been updated to ' . $formatted_date .
                ' at ' . $appointment_details[0]['slot'] . '. Please contact us if you have any questions. ' .
                'Best Regards, Pawsome Staff';


            $email = \Config\Services::email();
            $email->setTo($get_user[0]['email']);
            $email->setFrom('pawsomefuriends@gmail.com', 'Pawsome Furiends');
            $email->setSubject('Appointment Update Notification');
            $email->setMessage($msg);

            if ($email->send()) {
                $success = '<p>Update notification email sent to Pet Owner</p>';
                $session->setFlashdata('success', $success);
            } else {
                $data = $email->printDebugger(['headers']);
                $session->setFlashdata('error', $data);
            }

            // Send SMS notification
            $send_data = [
                'mobile' => '+63' . $get_user[0]['phone'],
                'message' => $msg,
                'token' => '7705b7f8e0d8baf036c8d0cc8e241f98'
            ];

            $parameters = json_encode($send_data);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://app.qproxy.xyz/api/sms/v1/send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

            $get_sms_status = curl_exec($ch);
            curl_close($ch);

            $session->setFlashdata('success', 'Appointment Successfully Updated');

            if ($user_type == 'admin') {
                return redirect()->to('appointment/all');
            } else {
                return redirect()->to('appointment/view_all');
            }
        } else {
            $session->setFlashdata('Error', 'Appointment Not Updated');
            if ($user_type == 'admin') {
                return redirect()->to('appointment/all');
            } else {
                return redirect()->to('appointment/view_all');
            }
        }
    }





    public function delete($id = null)
    {
        $session = session();
        $user_type = $_SESSION['user_type'];
        $Model = new appointmentModel();


        $deleted = $Model->where('id', $id)->delete();


        if ($deleted) {
            $session->setFlashdata('success', 'Successfully Deleted');
        } else {
            $session->setFlashdata('error', 'Not Deleted');
        }


        return redirect()->to('appointment/all');
    }



}
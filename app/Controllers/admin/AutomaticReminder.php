<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\appointmentModel;
use App\Models\userModel;

class AutomatedReminder extends BaseController
{
    private $appointmentModel;
    private $userModel;

    public function __construct()
    {
        $this->appointmentModel = new appointmentModel();
        $this->userModel = new userModel();
    }

    // This method will be called by the cron job
    public function sendAutomatedReminders()
    {
        // Get tomorrow's date
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        // Get all confirmed appointments for tomorrow that haven't been reminded
        $upcomingAppointments = $this->appointmentModel->getUpcomingAppointments($tomorrow);

        foreach ($upcomingAppointments as $appointment) {
            $this->sendReminderNotifications($appointment);

            // Mark reminder as sent
            $this->markReminderSent($appointment['id']);
        }

        log_message('info', 'Automated reminders sent for ' . count($upcomingAppointments) . ' appointments');
    }

    private function sendReminderNotifications($appointment)
    {
        // Format date for message
        $appointment_date = new \DateTime($appointment['appointment_date']);
        $formatted_date = $appointment_date->format('l, F j, Y');

        // Prepare message
        $msg = 'Dear ' . $appointment['first_name'] . ' ' . $appointment['last_name'] .
            ', this is a friendly reminder that your pet, ' . $appointment['name'] .
            ', has an upcoming appointment with us at Pawsome Furiends Veterinary Clinic tomorrow on ' .
            $formatted_date . ' at ' . $appointment['slot'] .
            '. Please let us know if you need to reschedule or if you have any questions. ' .
            'We look forward to seeing you and your pet soon! Best Regards, Pawsome Staff';

        // Send email
        $this->sendEmail($appointment['patient_id'], $msg);

        // Send SMS
        $this->sendSMS($appointment['patient_id'], $msg);
    }

    private function sendEmail($patient_id, $msg)
    {
        $user = $this->userModel->find($patient_id);

        $email = \Config\Services::email();
        $email->setTo($user['email']);
        $email->setFrom('pawsomefuriends@gmail.com', 'Pawsome Furiends');
        $email->setSubject('Appointment Reminder');
        $email->setMessage($msg);

        try {
            $email->send();
            log_message('info', 'Reminder email sent to: ' . $user['email']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send reminder email: ' . $e->getMessage());
        }
    }

    private function sendSMS($patient_id, $msg)
    {
        $user = $this->userModel->find($patient_id);

        if (empty($user['phone'])) {
            return;
        }

        $send_data = [
            'mobile' => '+63' . $user['phone'],
            'message' => $msg,
            'token' => '7705b7f8e0d8baf036c8d0cc8e241f98'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://app.qproxy.xyz/api/sms/v1/send",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($send_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
        ]);

        try {
            $response = curl_exec($ch);
            log_message('info', 'SMS reminder sent to: +63' . $user['phone']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send SMS reminder: ' . $e->getMessage());
        } finally {
            curl_close($ch);
        }
    }

    private function markReminderSent($appointment_id)
    {
        $this->appointmentModel->update($appointment_id, ['reminder_sent' => 1]);
    }
}
<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\appointmentModel;
use App\Models\userModel;

class SendAppointmentReminders extends BaseCommand
{
    protected $group = 'Appointments';
    protected $name = 'appointments:send-reminders';
    protected $description = 'Sends automated reminders for today\'s appointments';

    private $appointmentModel;
    private $userModel;

    public function run(array $params)
    {
        $this->appointmentModel = new appointmentModel();
        $this->userModel = new userModel();

        CLI::write('=== Appointment Reminder Test Mode ===', 'green');
        CLI::write('Current Date: ' . date('Y-m-d H:i:s'), 'yellow');

        CLI::write('Fetching today\'s confirmed appointments...', 'yellow');
        $appointments = $this->appointmentModel->getAppointmentsForToday();

        CLI::write('Found ' . count($appointments) . ' confirmed appointments', 'yellow');

        if (empty($appointments)) {
            CLI::write('No confirmed appointments found for today!', 'red');
            CLI::write('Please check if you have any appointments with:');
            CLI::write('- Today\'s date: ' . date('Y-m-d'));
            CLI::write('- Status: Confirm');
            CLI::write('- reminder_sent: 0');
            return;
        }

        foreach ($appointments as $appointment) {
            // Double check current status before processing
            $currentAppointment = $this->appointmentModel->db->table('appointment')
                ->select('reminder_sent, status')
                ->where('id', $appointment['id'])
                ->get()
                ->getRowArray();

            if (!$currentAppointment) {
                CLI::write("\nSkipping appointment ID: {$appointment['id']} - appointment not found", 'yellow');
                continue;
            }

            if ($currentAppointment['status'] !== 'Confirm') {
                CLI::write("\nSkipping appointment ID: {$appointment['id']} - status is {$currentAppointment['status']}", 'yellow');
                continue;
            }

            if ($currentAppointment['reminder_sent'] == 1) {
                CLI::write("\nSkipping appointment ID: {$appointment['id']} - reminder already sent", 'yellow');
                continue;
            }

            CLI::write("\nProcessing appointment ID: {$appointment['id']}", 'green');
            CLI::write("Current reminder_sent status: {$appointment['reminder_sent']}", 'yellow');

            $user = $this->userModel->find($appointment['patient_id']);
            if (!$user) {
                CLI::write("× Error: User not found for patient_id: {$appointment['patient_id']}", 'red');
                continue;
            }

            CLI::write("Patient: {$user['first_name']} {$user['last_name']}", 'white');
            CLI::write("Email: {$user['email']}", 'white');
            CLI::write("Phone: {$user['phone']}", 'white');

            try {
                $msg = $this->createReminderMessage($user, $appointment);
                CLI::write("\nMessage Preview:", 'yellow');
                CLI::write($msg, 'white');

                $emailSent = $this->sendEmail($user['email'], $msg);
                $smsSent = $this->sendSMS($user['phone'], $msg);

                if ($emailSent || $smsSent) {
                    try {
                        $this->appointmentModel->db->transStart();

                        // Check again right before update to prevent race conditions
                        $currentCheck = $this->appointmentModel->db->table('appointment')
                            ->where('id', $appointment['id'])
                            ->where('reminder_sent', 0)
                            ->get()
                            ->getRowArray();

                        if (!$currentCheck) {
                            CLI::write("\n× Reminder already sent by another process", 'red');
                            $this->appointmentModel->db->transRollback();
                            continue;
                        }

                        // Log current status
                        $beforeUpdate = $this->appointmentModel->find($appointment['id']);
                        CLI::write("\nBefore update - reminder_sent: " . $beforeUpdate['reminder_sent'], 'yellow');

                        // Perform update with explicit condition
                        $result = $this->appointmentModel->db->table('appointment')
                            ->where('id', $appointment['id'])
                            ->where('reminder_sent', 0)
                            ->set(['reminder_sent' => 1])
                            ->update();

                        if ($result) {
                            $this->appointmentModel->db->transComplete();

                            if ($this->appointmentModel->db->transStatus()) {
                                // Verify the update
                                $afterUpdate = $this->appointmentModel->find($appointment['id']);
                                CLI::write("After update - reminder_sent: " . $afterUpdate['reminder_sent'], 'yellow');

                                if ($afterUpdate['reminder_sent'] == 1) {
                                    CLI::write("✓ Reminder status updated in database", 'green');
                                } else {
                                    CLI::write("× Update verification failed", 'red');
                                }
                            } else {
                                CLI::write("× Transaction failed", 'red');
                            }
                        } else {
                            $this->appointmentModel->db->transRollback();
                            CLI::write("× Update failed: " . print_r($this->appointmentModel->db->error(), true), 'red');
                        }
                    } catch (\Exception $e) {
                        $this->appointmentModel->db->transRollback();
                        CLI::write("× Database error: " . $e->getMessage(), 'red');
                    }
                }
            } catch (\Exception $e) {
                CLI::write("\n× Error: " . $e->getMessage(), 'red');
            }
        }

        CLI::write("\n=== Test Complete ===", 'green');
    }

    private function createReminderMessage($user, $appointment): string
    {
        $time = isset($appointment['slot']) ? $appointment['slot'] : 'scheduled time';
        $petName = isset($appointment['name']) ? $appointment['name'] : 'your pet';

        return 'REMINDER: Dear ' . $user['first_name'] . ' ' . $user['last_name'] .
            ', ' . $petName .
            ' has an appointment TODAY at ' . $time .
            ' with Pawsome Furiends Veterinary Clinic. ' .
            'Please arrive 10-15 minutes before your scheduled time. ' .
            'If you need to reschedule, please contact us immediately. ' .
            'Best Regards, Pawsome Staff';
    }

    private function sendEmail($toEmail, $message): bool
    {
        try {
            $emailService = \Config\Services::email();

            $config['protocol'] = 'smtp';
            $config['SMTPHost'] = 'smtp.gmail.com';
            $config['SMTPUser'] = 'pawsomefuriends.business@gmail.com';
            $config['SMTPPass'] = 'rlxr trtg lbir hdvx';
            $config['SMTPPort'] = 587;
            $config['SMTPCrypto'] = 'tls';
            $config['mailType'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";

            $emailService->initialize($config);

            $emailService->setFrom('pawsomefuriends@gmail.com', 'Pawsome Furiends');
            $emailService->setTo($toEmail);
            $emailService->setSubject('TODAY\'S Appointment Reminder');
            $emailService->setMessage($message);

            $sent = $emailService->send();

            if (!$sent) {
                CLI::write("Email Error: " . $emailService->printDebugger(['headers']), 'red');
                return false;
            }

            CLI::write("✓ Email sent successfully!", 'green');
            return true;

        } catch (\Exception $e) {
            CLI::write("Email Error: " . $e->getMessage(), 'red');
            return false;
        }
    }

    private function sendSMS($phone, $message): bool
    {
        try {
            $send_data = [
                'mobile' => '+63' . $phone,
                'message' => $message,
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

            $response = curl_exec($ch);
            $success = ($response !== false);
            curl_close($ch);

            if (!$success) {
                CLI::write("SMS Error: " . curl_error($ch), 'red');
                return false;
            }

            CLI::write("✓ SMS sent successfully!", 'green');
            return true;

        } catch (\Exception $e) {
            CLI::write("SMS Error: " . $e->getMessage(), 'red');
            return false;
        }
    }
}
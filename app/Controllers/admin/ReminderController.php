<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use CodeIgniter\CLI\Commands;
use Config\Services;
use App\Commands\SendAppointmentReminders;

class ReminderController extends Controller
{
    public function triggerReminders()
    {
        try {
            // Get the required dependencies
            $logger = service('logger');
            $commands = new Commands(service('logger'), service('commands'));

            // Create command instance with dependencies
            $command = new SendAppointmentReminders($logger, $commands);

            // Capture output
            ob_start();
            $command->run([]);
            $output = ob_get_clean();

            // Get only relevant output by filtering out Apache warnings
            $relevantOutput = preg_replace('/\[.*?\] .*?: .*?\n/', '', $output);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reminders sent successfully!',
                'details' => trim($relevantOutput) ?: 'Reminders processed successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Manual reminder trigger failed: ' . $e->getMessage());

            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to send reminders: ' . $e->getMessage()
                ]);
        }
    }
}
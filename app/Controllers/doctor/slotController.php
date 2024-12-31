<?php

namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\slotModel;
use App\Models\slotMainModel;
use App\Models\serviceModel;

class slotController extends BaseController
{
    public function index()
    {
        $session = session();
        $Model = new slotModel();
        $serviceModel = new serviceModel();
        $user_id = $session->get('id');

        $data['slots'] = $Model->where('dr_id',$user_id)->findAll();
        $data['services'] = $serviceModel->getServices();
         $serviceNames = [];
         foreach ($data['services'] as $service) {
        $serviceNames[$service['id']] = $service['name'];
    }

    
    $data['serviceNames'] = $serviceNames;

		return $this->render_template('doctor/timeslot/add_new',$data);

    }
     public function view_all()
    {
        
		return $this->render_template('doctor/timeslot/view_all');

    }
    
    
    public function store() {
        $session = session();
        $user_id = $_SESSION['id'];
    
        // Get start and end date for the range
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
    
        // Get time and duration details
        $startTime = $this->request->getVar('start_time');
        $endTime = $this->request->getVar('end_time');
        $duration = $this->request->getVar('duration'); // Duration in minutes
    
        // Convert start and end times to timestamps
        $startTimeTs = strtotime($startTime);
        $endTimeTs = strtotime($endTime);
    
        // Initialize models
        $slotModel = new slotModel();
    
        // Generate slots for each day in the selected range
        $currentDate = strtotime($startDate);
        $endDateTs = strtotime($endDate);
        $services = $this->request->getVar('services_id');
    
        while ($currentDate <= $endDateTs) {
            $date = date('Y-m-d', $currentDate);
            $currentSlotTime = $startTimeTs;
    
            while ($currentSlotTime < $endTimeTs) {
                $slotStart = date('H:i', $currentSlotTime);
                $slotEndTs = $currentSlotTime + ($duration * 60); // Add duration in seconds
                $slotEnd = date('H:i', $slotEndTs);
    
                // Define slot entry
                $dataSlot = [
                    'start_date' => $date . ' ' . $slotStart, // Start date and start time combined
                    'end_date' => $date . ' ' . $slotEnd,     // End date and end time combined
                    'slot' => $slotStart . '-' . $slotEnd,     // Slot time range
                    'services' => $services,                    // Services associated with the slot
                    'dr_id' => $user_id,                        // Doctor ID
                ];
    
                // Log each slot data before saving
                log_message('debug', 'Attempting to save slot data: ' . json_encode($dataSlot));
    
                $saved = $slotModel->insert($dataSlot);
                if (!$saved) {
                    log_message('error', 'Failed to insert slot into slotModel for date ' . $date . '. Error: ' . json_encode($slotModel->errors()));
                    // Flash an error message for user feedback
                    $session->setFlashdata('Error', 'Failed to insert slot for date ' . $date);
                } else {
                    log_message('debug', 'Inserted slot with ID: ' . $saved);
                }
    
                // Move to the next slot time
                $currentSlotTime = $slotEndTs;
            }
    
            // Move to the next day
            $currentDate = strtotime('+1 day', $currentDate);
        }
    
        $session->setFlashdata('success', 'Slots successfully inserted for the selected date range');
        log_message('info', 'Slots successfully inserted for user ' . $user_id . ' for the date range ' . $startDate . ' to ' . $endDate);
        return redirect()->to('slot/add');
    }
    
    
    
    
    
    
    
    
    
    public function update()
    {
        $id = $this->request->getPost('edit_slot_id');
        $slot = $this->request->getPost('edit_slot');
        $Model = new slotModel();
        $session = session();
        $user_id = $session->get('id'); // Access session data safely

        // Check if the new slot already exists for this doctor, excluding the current slot being edited
        $check_slot = $Model->where('dr_id', $user_id)
                            ->where('slot', $slot)
                            ->where('id !=', $id)
                            ->first();

        if (!empty($check_slot)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Slot already exists'
            ]);
        }

        // Prepare data for update
        $data = [
            'slot' => $slot
        ];

        // Perform the update operation
        $updated = $Model->update($id, $data);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Slot successfully updated'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    
    
         
    public function delete($id = null) {
        $session = session();
        $model = new slotModel();
    
        // Log the ID of the slot being deleted
        log_message('info', 'Attempting to delete slot with ID: ' . $id);
    
        // Attempt to delete the slot
        $deleted = $model->where('id', $id)->delete();
    
        if ($deleted) {
            log_message('info', 'Successfully deleted slot with ID: ' . $id); // Log success
    
            // Return JSON response for success
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Successfully Deleted'
            ]);
        } else {
            log_message('error', 'Failed to delete slot with ID: ' . $id); // Log failure
    
            // Return JSON response for failure
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete slot'
            ]);
        }
    }
    
    
    
    
   
    
    
}
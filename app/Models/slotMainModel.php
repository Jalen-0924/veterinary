<?php 
namespace App\Models;  

use App\Models\appointmentModel;
use CodeIgniter\Model;
  
class slotMainModel extends Model {
    protected $table = 'tbl_doctor_slots';
    
    protected $allowedFields = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'doctor_id',
        'number_of_slots',
        'duration',
    ];
    
    /**
     * Get all slot ranges or a specific slot range by ID
     *
     * @param int|bool $id Optional ID to fetch a specific slot range
     * @return array Slot range data
     */
    public function getSlotRanges($id = false) {
        if ($id === false) {
            return $this->findAll();
        } else {
            return $this->where('id', $id)->find();
        }
    }
     
    /**
     * Get slot details by appointment ID
     *
     * @param int|bool $id Appointment ID to fetch slot details for
     * @return array Slot data associated with the appointment
     */
    public function getSlotByAppointment($id = false) {
        if (!$id) return null;

        $appointmentModel = new appointmentModel();
        $appointment = $appointmentModel->where('id', $id)->find();

        if (empty($appointment) || !isset($appointment[0]['timeslot'])) {
            return null;
        }

        return $this->where('id', $appointment[0]['timeslot'])->find();
    }
}

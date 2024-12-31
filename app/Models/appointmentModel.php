<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\slotModel;

class appointmentModel extends Model
{
    protected $table = 'appointment';
    protected $validationRules = [
    'date' => 'required',
    'patient_id' => 'required',
    'pet_id' => 'required',
    'services_id' => 'required',
    'timeslot' => 'required'
];
    protected $allowedFields = [
        'patient_id',
        'doctor_id',
        'doctor_name',
        'pet_id',
        'date',
        'timeslot',
        'status',
        'services_id',
        'invoice_id',
        'reminder_sent',
        'created_at'
    ];
    


    public function getAppointmentsForToday()
    {
        try {
            $timezone = new \DateTimeZone('Asia/Manila');
            $today = (new \DateTime('now', $timezone))->format('Y-m-d');

            $builder = $this->db->table('appointment a')
                ->select('a.*, a.date as appointment_date, u.first_name, u.last_name, u.email, u.phone, p.name, s.name as services, t.slot')
                ->join('user u', 'u.id = a.patient_id')
                ->join('pets p', 'p.id = a.pet_id')
                ->join('services s', 's.id = a.services_id', 'left')
                ->join('timeslot t', 't.id = a.timeslot', 'left')
                ->where([
                    'a.date' => $today,
                    'a.status' => 'Confirm',  // Only confirmed appointments
                    'IFNULL(a.reminder_sent, 0)' => 0  // Handle NULL values and ensure tinyint comparison
                ]);

            // For debugging
            log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());

            $query = $builder->get();
            $result = $query->getResultArray();

            // Debug each found appointment
            foreach ($result as $row) {
                log_message('debug', "Found: ID={$row['id']}, status={$row['status']}, reminder_sent={$row['reminder_sent']}");
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Error in getAppointmentsForToday: ' . $e->getMessage());
            return [];
        }
    }


    public function getAppointmentsByPetId($pet_id)
    {
        $builder = $this->db->table('appointment'); // Adjust the table name if necessary
        $builder->select('appointment.id, appointment.date, appointment.status, appointment.pet_id, timeslot.slot, user.first_name, user.last_name, services.name as service_name, services.price as service_price');
        $builder->join('timeslot', 'timeslot.id = appointment.timeslot');
        $builder->join('user', 'user.id = appointment.doctor_id');
        $builder->join('services', 'services.id = appointment.services_id');
        $builder->where('appointment.pet_id', $pet_id);
        $query = $builder->get();

        return $query->getResultArray();
    }
    
    public function getSingleAppointment($id)
    {

        $sql = "SELECT appointment.id, appointment.date, appointment.status, appointment.patient_id, appointment.doctor_id, appointment.date as appointment_date, appointment.pet_id, timeslot.slot, user.first_name, user.last_name, pets.name,doc.first_name, services.id as service_id, services.name as services, services.price as service_price, appointment.invoice_id FROM appointment 
        	LEFT JOIN pets ON pets.id = appointment.pet_id
        	LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
        	LEFT JOIN user as doc ON doc.id = appointment.doctor_id
            LEFT JOIN services ON services.id = appointment.services_id
        	LEFT JOIN user ON user.id = appointment.patient_id WHERE appointment.id ='$id'";

        $querys = $this->db->query($sql);

        $result = $querys->getResultArray('array');


        return $result;

    }


    public function getAppointments($id = false)
    {
        if ($id === false) {
            $sql = 'SELECT appointment.id, appointment.date, appointment.doctor_name, appointment.status, 
                timeslot.slot, user.first_name, user.last_name, pets.name, services.name as services, appointment.invoice_id, services.id as service_id, appointment.patient_id
                FROM appointment 
                LEFT JOIN pets ON pets.id = appointment.pet_id
                LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
                LEFT JOIN user ON user.id = appointment.patient_id
                LEFT JOIN services ON services.id = appointment.services_id';

            $querys = $this->db->query($sql);

            $result = $querys->getResultArray('array');

            return $result;
        } else {
            $sql = "SELECT appointment.id, appointment.date, appointment.status, timeslot.slot, 
                user.first_name, user.last_name, pets.name, services.name as services, appointment.invoice_id, services.id as service_id, appointment.patient_id
                FROM appointment 
                LEFT JOIN pets ON pets.id = appointment.pet_id
                LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
                LEFT JOIN user ON user.id = appointment.doctor_id
                LEFT JOIN services ON services.id = appointment.services_id 
                WHERE appointment.patient_id ='$id'";

            $querys = $this->db->query($sql);

            $result = $querys->getResultArray('array');

            return $result;
        }
    }


    public function getAppointmentspending($id = false)
    {
        if ($id === false) {
            $sql = 'SELECT appointment.id, appointment.date, appointment.doctor_name, 
                appointment.status, timeslot.slot, user.first_name, user.last_name, 
                pets.name, user.id AS user_id, services.name AS services, appointment.invoice_id, services.id as service_id
                FROM appointment 
                LEFT JOIN pets ON pets.id = appointment.pet_id
                LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
                LEFT JOIN user ON user.id = appointment.patient_id 
                LEFT JOIN services ON services.id = appointment.services_id
                WHERE appointment.status = "Pending"';

            $querys = $this->db->query($sql);
            $result = $querys->getResultArray('array');
            return $result;
        } else {
            $sql = "SELECT appointment.id, appointment.date, appointment.status, 
                timeslot.slot, user.first_name, user.last_name, pets.name, 
                services.name AS services, appointment.invoice_id, services.id as service_id
                FROM appointment 
                LEFT JOIN pets ON pets.id = appointment.pet_id
                LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
                LEFT JOIN user ON user.id = appointment.doctor_id 
                LEFT JOIN services ON services.id = appointment.services_id
                WHERE appointment.patient_id ='$id'";

            $querys = $this->db->query($sql);
            $result = $querys->getResultArray('array');
            return $result;
        }
    }






    public function getAppointmentsByDoctor($doctor_id, $date = null)
    {
        $sql = "SELECT appointment.id, appointment.date, appointment.status, timeslot.slot, user.first_name, user.last_name, pets.name, appointment.invoice_id
            FROM appointment 
            LEFT JOIN pets ON pets.id = appointment.pet_id
            LEFT JOIN timeslot ON timeslot.id = appointment.timeslot
            LEFT JOIN user ON user.id = appointment.patient_id 
            WHERE appointment.doctor_id = '$doctor_id'";

        if ($date) {
            $sql .= " AND appointment.date = '$date'";
        }

        $sql .= " ORDER BY appointment.timeslot";

        $querys = $this->db->query($sql);
        $result = $querys->getResultArray('array');

        return $result;
    }











    public function get_available_slots($date)
    {
        $Model = new slotModel();

        // Fetch all slots
        $slots = $Model->findAll();

        // Fetch assigned slots for the selected date
        $assignedSlots = $this->db->table('appointment')
            ->select('timeslot')
            ->where('date', $date)
            ->get()
            ->getResultArray();

        // Extract timeslot IDs from assigned slots
        $assignedSlotIds = array_column($assignedSlots, 'timeslot');

        // Check for available slots
        $availableSlots = [];
        foreach ($slots as $slot) {
            if (!in_array($slot['id'], $assignedSlotIds)) {
                $availableSlots[] = $slot;
            }
        }

        // Display available slots or message if none are available
        if (!empty($availableSlots)) {
            foreach ($availableSlots as $slot) {
                echo "<option value='{$slot['id']}'>{$slot['slot']}</option>";
            }
        } else {
            echo "<option value=''>Sorry! No Slots Available On This Date, Choose Another Date</option>";
        }
    }






}
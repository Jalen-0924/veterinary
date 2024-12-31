<?php 
namespace App\Models;  
use App\Models\appointmentModel;
use CodeIgniter\Model;

class slotModel extends Model {
    protected $table = 'timeslot';
    
    protected $allowedFields = [
        'start_date',
        'end_date',
        'slot',
        'dr_id',
    ];

    /**
     * Get all slots or a specific slot by ID
     */
    public function getSlots($id = false) {
        if ($id === false) {
            return $this->findAll();
        } else {
            return $this->where('id', $id)->find();
        }
    }

    /**
     * Get slot details by appointment ID
     */
    public function getSlotByAppointment($id = false) {
        $session = session();
        $Model = new appointmentModel();
        
        $appointment = $Model->where('id', $id)->find();
        
        return $this->where('id', $appointment[0]['timeslot'])->find();
    }

    /**
     * Get all unique slots available on a specific date
     */
    public function getSlotsByDate($date) {
        // Query to get all unique slots for the specified date that are not booked
        $builder = $this->db->table('timeslot'); // Using the actual table name 'timeslot'
        $builder->select('timeslot.id, timeslot.slot')
                ->where('DATE(timeslot.start_date)', $date) // Adjusted to match table structure
                ->join('appointment', 'timeslot.id = appointment.timeslot', 'left') // Use 'appointment' as actual table name
                ->where('appointment.timeslot IS NULL'); // Only get unbooked slots
        
        // Execute the query and fetch results
        $slots = $builder->get()->getResultArray();

        // Ensure uniqueness based on 'slot' values
        $uniqueSlots = [];
        foreach ($slots as $slot) {
            $uniqueSlots[$slot['slot']] = [
                'id' => $slot['id'],
                'slot' => $slot['slot']
            ];
        }

        // Convert associative array back to a regular array and return it
        return array_values($uniqueSlots);
    }



    /**
     * Get slots available in a specific date range
     */
    public function getSlotsByDateRange($startDate, $endDate) {
        return $this->where('start_date <=', $endDate)
                    ->where('end_date >=', $startDate)
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get a list of date ranges that have at least one available slot
     */
    public function getAvailableDateRanges() {
        // Query distinct date ranges with available slots
        $availableRanges = $this->db->table($this->table)
            ->select('start_date, end_date')
            ->distinct()
            ->where('start_date IS NOT NULL')
            ->where('end_date IS NOT NULL')
            ->orderBy('start_date', 'ASC')
            ->get()
            ->getResultArray();

        return $availableRanges;
    }

    /**
     * Get a list of available dates with at least one available slot
     */
    public function getAvailableDates() {
      // Select distinct dates where there is at least one slot available
      $availableDates = $this->db->table($this->table)
          ->select('DATE(start_date) AS date') // Extract only the date part
          ->distinct()
          ->where('start_date IS NOT NULL')
          ->where('end_date IS NOT NULL')
          ->where('start_date >=', date('Y-m-d')) // Optional: Only future dates
          ->get()
          ->getResultArray();

      // Extracting just the date values
      $dates = array_map(function($row) {
          return $row['date'];
      }, $availableDates);

      return $dates;
    }


}

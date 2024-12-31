<?php
namespace App\Models;
use CodeIgniter\Model;

class confinementModel extends Model{
    protected $table = 'pet_confinement';
    
    protected $allowedFields = [
        'pet_id',
        'patient_id', 
        'start_date',
        'end_date',
        'reason',
        'treatment',
        'notes',
        'status'

    ];

    public function getConfinement($id = false) {
    $sql = 'SELECT pet_confinement.id, pet_confinement.start_date as start_date, pet_confinement.end_date as end_date, 
                   pet_confinement.reason as reason, pet_confinement.treatment treatment, pet_confinement.notes as notes , 
                   pet_confinement.status as status, pet_confinement.patient_id, user.first_name as patient_first_name, 
                   user.last_name as patient_last_name, pets.name as pet_name FROM pet_confinement
            LEFT JOIN user ON user.id = pet_confinement.patient_id
            
            LEFT JOIN pets ON pets.id = pet_confinement.pet_id';

    $query = $this->db->query($sql);

    return $query->getResultArray(); 
}

       public function saveConfinement($data)
    {
        
        return $this->insert($data);
    }
    
       public function getConfinementById($id)
    {
        return $this->where('id', $id)->first();
    }

}
    
   
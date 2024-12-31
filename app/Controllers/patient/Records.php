<?php
namespace App\Controllers\patient;

use App\Controllers\BaseController;
use App\Models\recordsModel;

class Records extends BaseController {

    public function view_all() {
        $recordsModel = new recordsModel();
        $session = session();
        $patient_id = $_SESSION['id']; 
     
        $data['records'] = $recordsModel->getRecords($patient_id);
        
       
        return $this->render_template('patient/records/view_all', $data);
    }
}

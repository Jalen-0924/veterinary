<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\confinementModel;
use App\Models\userModel;
use App\Models\petModel;

class Confinement extends BaseController {

    
    public function index() {
        $userModel = new userModel();
        $petModel = new petModel();

       
        $data['patients'] = $userModel->getUsersByType('patient');
        $data['doctors'] = $userModel->getUsersByType('doctor');

     
        return $this->render_template('admin/confinement/add_new', $data);
    }
    public function view_all(){
       
       $confinementModel = new confinementModel();
            
             $data['confinements'] = $confinementModel->getConfinement(); 
            return $this->render_template('admin/confinement/view_all', $data);
    }
    
     public function delete($id = null){
            $session = session();
            $Model = new confinementModel();
            
            $deleted = $Model->where('id', $id)->delete();
            
            if($deleted){
                $session->setFlashdata('success', 'Successfully Deleted');
                return redirect()->to('confinement/all');
            } else {
                $session->setFlashdata('error', 'Not Deleted');
                return redirect()->to('confinement/all');
            }
        }

    public function save() {
    $model = new confinementModel();

    $data = [
        'patient_id' => $this->request->getPost('patient_id'),
        'pet_id' => $this->request->getPost('pet'),
        'doctor_id' => $this->request->getPost('doctor'),
        'start_date' => $this->request->getPost('start_date'),
        'end_date' => $this->request->getPost('end_date')?: null,
        'reason' => $this->request->getPost('reason'),
        'treatment' => $this->request->getPost('treatment'),
        'notes' => $this->request->getPost('notes'),
        'status' => $this->request->getPost('status') 
    ];

    try {
        if ($model->insert($data)) {
            return redirect()->to('/confinement/all')->with('success', 'Confinement added successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to add the confinement record.');
        }
    } catch (\Exception $e) {
        log_message('error', 'Confinement save error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add the confinement record. Error: ' . $e->getMessage());
    }
}
      public function edit($id = null)
    {
        $session = session();
        $Model = new confinementModel();
        $userModel = new userModel();

         $data['patients'] = $userModel->getUsersByType('patient');
        $data['confinement'] = $Model->getConfinementById($id);
        return $this->render_template('admin/confinement/edit',$data);

    }

     public function update(){
        $session = session();
        $Model = new confinementModel();
        $id = $this->request->getVar('id');
         $data=array(
                    'patient_id' => $this->request->getVar('patient_id'),
        'pet_id' => $this->request->getVar('pet'),
        'doctor_id' => $this->request->getVar('doctor'),
        'start_date' => $this->request->getVar('start_date'),
        'end_date' => $this->request->getVar('end_date')?: null,
        'reason' => $this->request->getVar('reason'),
        'treatment' => $this->request->getVar('treatment'),
        'notes' => $this->request->getVar('notes'),
        'status' => $this->request->getVar('status') 
                    );
        
        $update = $Model->update($id, $data);
                    if($update){
                   $session->setFlashdata('success', 'Successfully Updated');
                   return redirect()->to('confinement/all');
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Updated');
                        return redirect()->to('confinement/all');
                    }
        
    }



}

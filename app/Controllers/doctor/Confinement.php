<?php
namespace App\Controllers\doctor;

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

     
        return $this->render_template('doctor/confinement/add_new', $data);
    }
    public function view_all(){
       
       $confinementModel = new confinementModel();
            
             $data['confinements'] = $confinementModel->getConfinement(); 
            return $this->render_template('doctor/confinement/view_all', $data);
    }
    
     public function delete($id = null) {
        $session = session();
        $model = new confinementModel();
        
        $deleted = $model->where('id', $id)->delete();
        
        if ($deleted) {
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
        return $this->render_template('doctor/confinement/edit',$data);

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

            public function followup_confinement($id = null) {
                $petModel = new petModel();
        $confinementModel = new confinementModel();


        $data['pets'] = $petModel->getPets();
        $data['confinement'] = $confinementModel->getConfinementById($id);
        return $this->render_template('doctor/confinement/add_new_followup', $data);
    }



    public function send_followup_email()
{
    $session = session();
    $pet_id = $this->request->getPost('pet');
    $followup_reason = $this->request->getPost('followup');
    $followup_other = $this->request->getPost('followup_other');

    $petModel = new petModel();
    $userModel = new userModel();

    
    $pet_details = $petModel->getPets($pet_id);

    if (!empty($pet_details) && isset($pet_details['owner_id'])) {
        $owner_id = $pet_details['owner_id'];
    } else {
       
        $session->setFlashdata('error', 'Pet details not found.');
        return redirect()->to('/doctor/followup_pet');
    }


    $owner_details = $userModel->getUsers($owner_id);
    if (!empty($owner_details)) {
        $owner_name = $owner_details[0]['first_name'] . ' ' . $owner_details[0]['last_name'];
        $owner_email = $owner_details[0]['email'];
    } else {
        $session->setFlashdata('error', 'Owner details not found.');
        return redirect()->to('/doctor/followup_pet');
    }

    $pet_name = $pet_details['name'];
    $followup_msg = $followup_reason === 'Others' ? $followup_other : $followup_reason;

    
    $msg = "Dear $owner_name,\n\nThis is a notification regarding a follow-up for your pet, $pet_name. The reason for the follow-up is: $followup_msg.\n\nPlease contact us if you have any questions or need more information.\n\nBest Regards,\nPawsome Staff";

    $email = \Config\Services::email();
    $email->setTo($owner_email);
    $email->setFrom('Pawsome Furiends @gmail.com', 'Follow-Up Notification');
    $email->setSubject('Follow-Up Notification');
    $email->setMessage($msg);

    if ($email->send()) {
        $success = '<p>Follow-up email has been sent to the pet owner.</p>';
        $session->setFlashdata('success', $success);
        return redirect()->to('confinement/all');
    } else {
        $error = $email->printDebugger(['headers']);
        $session->setFlashdata('error', $error);
        return redirect()->to('/doctor/followup_pet');
    }
}


    
}

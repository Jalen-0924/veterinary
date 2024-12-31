<?php

namespace App\Controllers\patient;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\appointmentModel;
use App\Models\slotModel;
use App\Models\serviceModel;
use App\Models\medicineModel;
use App\Models\patReportModel;
use App\Models\symptomModel;

class Appointment extends BaseController
{

	public function add_appointment()
{
    $session = session();
    $Model = new userModel();
    $petModel = new petModel();
    $serviceModel = new serviceModel();
    $patient_id = $_SESSION['id'];
    
  
    // $pets = $petModel->where('status', 'Confirm')->findAll();
    $services = $serviceModel->getServices();
    $data['doctors'] = $Model->getUsersByType('doctor');
     $data['pets'] = $petModel->getPetsByOwner($patient_id);
     $data['services'] = $serviceModel->getServices();

       $data['selectedDoctorId'] = $data['doctors'][0]['id'] ?? null;
    
 return $this->render_template('patient/appointment/add_new', $data);
}

    
    public function follow_up_appointment($id)
    {
        
         $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $patient_id = $_SESSION['id'];
        $Model2 = new appointmentModel();
        $single_appointment = $Model2->getSingleAppointment($id);
        
        $data['appointment'] = $single_appointment[0];
        $data['pets'] = $petModel->getPetsByOwner($single_appointment[0]['patient_id']);
        
		return $this->render_template('doctor/appointment/add_new_followup',$data);

    }
    
    
     public function all_appointment()
    {
         $session = session();
        $Model = new appointmentModel();
        $patient_id = $_SESSION['id'];
        $data['appointments'] = $Model->getAppointments($patient_id);
		return $this->render_template('patient/appointment/view_all',$data);

    }
    
      public function edit($id = null)
    {
        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        $slotModel = new slotModel();
        $appointmentModel = new appointmentModel();
        $appoint =  $appointmentModel->where('id',$id)->find();
        $patient_id = $appoint[0]['patient_id'];
        
      
        $data['doctors'] = $Model->getUsersByType('doctor');
        $data['pets'] = $petModel->getPetsByOwner($patient_id);
       $data['appointment'] = $appointmentModel->getSingleAppointment($id);
       
        $data['slot'] = $slotModel->getSlotByAppointment($id);
        
       
		return $this->render_template('patient/appointment/edit',$data);

    }
    
    
    public function get_slots(){
        
        $session = session();
        $Model = new appointmentModel();
        
       $doctor =  $this->request->getVar('doctor');
       $date =  $this->request->getVar('date');
        
       return $Model->get_available_slots($date,$doctor);
    }
    
    }
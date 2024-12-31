<?php

namespace App\Controllers;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\appointmentModel;
use App\Models\serviceModel;
use App\Models\medicineModel;
use App\Models\boardModel;
use App\Models\slotModel;
use App\Models\invoiceModel;

class Home extends BaseController
{


    public function index()
    {
        helper(['form']);
        $data = [];
        return view('auth/login.php');
    }


    public function dashboard()
    {
        $session = session();
        $userModel = new userModel();
        $appointmentModel = new appointmentModel();
        $serviceModel = new serviceModel();
        $medicineModel = new medicineModel();
        $petModel = new petModel();
        $invoiceModel = new invoiceModel();
        $slotModel = new slotModel();

        $user_type = $_SESSION['user_type'];
        if ($user_type == 'admin') {
            $data['doctors'] = count($userModel->getUsersByType('doctor'));
            $data['patient'] = count($userModel->getUsersByType('patient'));
            $data['appointment'] = count($appointmentModel->getAppointments());
            $data['pets'] = count($petModel->getPets());
            $data['service'] = count($serviceModel->getServices());
            $data['invoiceData'] = $invoiceModel->getInvoiceCountByMonth();

            $data['PendingAppointments'] = $appointmentModel->getAppointments();


            $invoiceCounts = array_fill(0, 12, 0);
            foreach ($data['invoiceData'] as $invData) {
                $invoiceCounts[$invData['month'] - 1] = $invData['invoice_count'];
            }

            $data['invoiceCounts'] = $invoiceCounts;

            return $this->render_template('admin_dashboard', $data);
        }
        if ($user_type == 'doctor') {

            $data['medicines'] = $medicineModel->getMedicine();
            $data['medicineQuantities'] = $medicineModel->getMedicineQuantityByCategory();

            // $data['appointment'] = count($appointmentModel->getAppointmentsByDoctor($_SESSION['id']));
            // $data['today_appointment'] = $appointmentModel->getAppointmentsByDoctor($_SESSION['id']);


            $data['slots'] = $slotModel->where('dr_id', $_SESSION['id'])->first();
            $data['service'] = count($serviceModel->getServices());
            $data['invoiceData'] = $invoiceModel->getInvoiceCountByMonth();
            $totalProfitData = $invoiceModel->getTotalProfit();
            $data['totalProfit'] = $totalProfitData['total_profit'] ?? 0;
            $data['medicine'] = count($medicineModel->getMedicine());


            $invoiceCounts = array_fill(0, 12, 0);
            foreach ($data['invoiceData'] as $invData) {
                $invoiceCounts[$invData['month'] - 1] = $invData['invoice_count'];
            }

            $data['invoiceCounts'] = $invoiceCounts;

            return $this->render_template('doctor_dashboard', $data);
        }

        if ($user_type == 'patient') {
            $data['pets'] = count($petModel->getPetsByOwner($_SESSION['id']));
            return $this->render_template('patient_dashboard', $data);

        }

    }



}
<?php
namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\medicineModel;
use App\Models\recordsModel;
use App\Models\medicalHistoryModel;
use App\Models\vaccineHistoryModel;
use App\Models\dewormHistoryModel;
use App\Models\parasiteHistoryModel;
use App\Models\appointmentModel;
use Dompdf\Dompdf;



    class Records extends BaseController {

        // public function index() {
        //     $Model = new userModel();
        //     $petModel = new petModel();

        //     $data['patients'] = $Model->getUsersByType('patient');
        //     $data['doctors'] = $Model->getUsersByType('doctor');

        //     return $this->render_template('doctor/records/add_new', $data);
        // }

        public function view_all() {
            $recordsModel = new recordsModel();
                $userModel = new userModel();
            
            // $data['records'] = $recordsModel->getRecords(); 
             $data['patients'] = $userModel->getUsersByType('patient');
            return $this->render_template('doctor/records/view_all', $data);
        }

         public function view_all_patient($id)
    {
         
        $session = session();
        $recordsModel = new recordsModel();
        $Model = new petModel();
        $data['pets'] = $Model->getPetsByOwner($id);
        $Model = new userModel();
        $data['petowner'] = $Model->getUsers($id);
         $data['records'] = $recordsModel->getRecords(); 

        return $this->render_template('doctor/records/pets',$data);
        

    }

    

        // public function delete($id = null){
        //     $session = session();
        //     $Model = new recordsModel();
            
        //     $deleted = $Model->where('id', $id)->delete();
            
        //     if($deleted){
        //         $session->setFlashdata('success', 'Successfully Deleted');
        //         return redirect()->to('records/all');
        //     } else {
        //         $session->setFlashdata('error', 'Not Deleted');
        //         return redirect()->to('records/all');
        //     }
        // }

        public function saveinfo() {
    $model = new recordsModel();
    $medicalHistoryModel = new medicalHistoryModel();

    $data = [
        'patient_id' => $this->request->getPost('patient_id'),
        'pet_id' => $this->request->getPost('pet'),
    ];

    log_message('info', 'Data to be inserted: ' . print_r($data, true));

   
    if ($model->insert($data)) {
    
        $medicalHistoryData = [
          
            'diagnosis' => 'Initial Visit',
            'treatment' => 'General Check-up', 
            'results' => 'N/A', 
            'patient_id' => $data['patient_id'],
        ];

       
        if ($medicalHistoryModel->insert($medicalHistoryData)) {
            return redirect()->to('/records/all')->with('success', 'Record added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add record to medical history. Please try again.');
        }
    } else {
        return redirect()->back()->with('error', 'Failed to add record. Please try again.');
    }
}


        public function card($id) {
            $recordsModel = new recordsModel();
            $medicineModel = new medicineModel();

            $data['record'] = $recordsModel->getRecordById($id);
            $data['medicines'] = $medicineModel->findAll();
            return $this->render_template('doctor/records/card', $data);
        }



        public function save() {
    $session = session();
    $vaccineHistoryModel = new VaccineHistoryModel();
    $dewormHistoryModel = new DewormHistoryModel();
    $parasiteHistoryModel = new parasiteHistoryModel();
    $petId = $this->request->getVar('pet_id');
    $success = true;

    // Add Vaccine saving logic
    $vaccineDates = $this->request->getVar('vaccine_date') ?? [];
    $nextDates = $this->request->getVar('next_date') ?? [];
    $vaccineWeights = $this->request->getVar('weight') ?? [];
    $vaccines = $this->request->getVar('vaccine') ?? [];
   
    foreach ($vaccineDates as $index => $date) {
        if (!empty($date) && !empty($vaccineWeights[$index]) && !empty($vaccines[$index])) {
            $vaccineData = [
                'vaccine_date' => $date,
                'next_date' => $nextDates[$index] ?? null,
                'weight' => $vaccineWeights[$index],
                'vaccine' => $vaccines[$index],
                'pet_id' => $petId
            ];
            if (!$vaccineHistoryModel->insert($vaccineData)) {
                $success = false;
                log_message('error', 'Failed to insert vaccine record: ' . print_r($vaccineData, true));
            }
        }
    }

    // Existing deworming saving logic
    $dewormDates = $this->request->getVar('deworm_date') ?? [];
    $repeatDates = $this->request->getVar('r_date') ?? [];
    $dewormWeights = $this->request->getVar('deworm_weight') ?? [];
    $products = $this->request->getVar('product') ?? [];
   
    foreach ($dewormDates as $index => $date) {
        if (!empty($date) && !empty($dewormWeights[$index]) && !empty($products[$index])) {
            $data = [
                'deworm_date' => $date,
                'r_date' => $repeatDates[$index] ?? null,
                'weight' => $dewormWeights[$index],
                'product' => $products[$index],
                'pet_id' => $petId
            ];
            if (!$dewormHistoryModel->insert($data)) {
                $success = false;
                log_message('error', 'Failed to insert deworming record: ' . print_r($data, true));
            }
        }
    }

    // Existing parasite saving logic
    $parasiteDates = $this->request->getVar('treatment_date') ?? [];
    $parasiteNextDates = $this->request->getVar('next_date') ?? [];
    $parasiteWeights = $this->request->getVar('weight') ?? [];
    $parasiteProducts = $this->request->getVar('product') ?? [];
   
    foreach ($parasiteDates as $index => $date) {
        if (!empty($date) && !empty($parasiteWeights[$index]) && !empty($parasiteProducts[$index])) {
            $parasiteData = [
                'treatment_date' => $date,
                'next_date' => $parasiteNextDates[$index] ?? null,
                'weight' => $parasiteWeights[$index],
                'product' => $parasiteProducts[$index],
                'pet_id' => $petId
            ];
            if (!$parasiteHistoryModel->insert($parasiteData)) {
                $success = false;
                log_message('error', 'Failed to insert parasite record: ' . print_r($parasiteData, true));
            }
        }
    }

    if ($success) {
        return redirect()->back()->with('success', 'Records saved successfully.');
    } else {
        return redirect()->back()->with('error', 'Some records failed to save.');
    }
}

    





    


    
    public function table($pet_id) {
    $vaccineHistoryModel = new vaccineHistoryModel();
    $recordsModel = new recordsModel();
    $dewormHistoryModel = new dewormHistoryModel();
    $parasiteHistoryModel = new parasiteHistoryModel();
    
    $data['vaccineHistory'] = $vaccineHistoryModel->where('pet_id', $pet_id)->findAll();
      $data['dewormingHistory'] = $dewormHistoryModel->where('pet_id', $pet_id)->findAll();
      $data['parasiteHistory'] = $parasiteHistoryModel->where('pet_id', $pet_id)->findAll();
    $data['record'] = $recordsModel->getRecordById($pet_id);
    $data['pet_id'] = $pet_id; 
    return $this->render_template('doctor/records/table', $data);
}


    public function delete($id)
{
    $vaccineHistoryModel = new vaccineHistoryModel();

    // Attempt to delete the record
    if ($vaccineHistoryModel->delete($id)) {
        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    return redirect()->back()->with('error', 'Failed to delete the record.');
}

   public function vaccinePDF($pet_id)
{
    $vaccineHistoryModel = new vaccineHistoryModel();
    $petModel = new petModel();

    // Fetch data
    $pet = $petModel->getPets($pet_id);
    $vaccineHistory = $vaccineHistoryModel->where('pet_id', $pet_id)->findAll();

    // Check if pet exists
    if (!$pet) {
        return redirect()->back()->with('error', 'Pet not found.');
    }

    // Convert the logo to Base64 (use the correct path to the image)
    $imagePath = './public/img/Official.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $imageSrc = 'data:image/png;base64,' . $imageData;

    // Prepare the content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .card { border: 5px solid #a3d977; margin: 20px auto; padding: 20px; max-width: 800px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #4CAF50; }
        .header p { margin: 5px 0; color: #555; }
        .pet-info { margin-bottom: 20px; }
        .pet-info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .footer { text-align: center; margin-top: 20px; }
        .footer p { margin: 5px 0; }
        .shield { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
           <div class="shield">
            <img src="' . $imageSrc . '" alt="Shield Logo" width="100">
        </div>
            <h1>Pawsome Furiends Veterinary Clinic</h1>
            <p>Vaccine Card</p>
        </div>
        <div class="pet-info">
            <p><strong>Name:</strong> ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Species:</strong> ' . htmlspecialchars($pet['species'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Breed:</strong> ' . htmlspecialchars($pet['breed'], ENT_QUOTES, 'UTF-8') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Valid Until</th>
                    <th>Weight (kg)</th>
                    <th>Vaccine</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($vaccineHistory as $row) {
        $html .= '<tr>
                    <td>' . date('M d, Y', strtotime($row['vaccine_date'])) . '</td>
                    <td>' . (!empty($row['next_date']) ? date('M d, Y', strtotime($row['next_date'])) : 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['vaccine'], ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>';
    }
    if (empty($vaccineHistory)) {
        $html .= '<tr><td colspan="4">No vaccine history found.</td></tr>';
    }
    $html .= '</tbody>
        </table>
        <div class="footer">
            <p><strong>Instructions:</strong> Carry this card on vet visits. Do not lose this card.</p>
            <p>Issued by:</p>
            <p>Dr. John Kirby</p>
            <p>0912-345-6789</p>
        </div>
     
    </div>
</body>
</html>';

    // Load Dompdf
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF for download
    $dompdf->stream('Vaccine Card - ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '.pdf', ['Attachment' => 1]);
    exit();
}


    public function dewormingPDF($pet_id)
{
    $dewormingHistoryModel = new dewormHistoryModel();
    $petModel = new petModel();

    // Fetch data
    $pet = $petModel->getPets($pet_id);
    $dewormingHistory = $dewormingHistoryModel->where('pet_id', $pet_id)->findAll();

    // Check if pet exists
    if (!$pet) {
        return redirect()->back()->with('error', 'Pet not found.');
    }

    // Convert the logo to Base64 (use the correct path to the image)
    $imagePath = './public/img/Official.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $imageSrc = 'data:image/png;base64,' . $imageData;

    // Prepare the content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .card { border: 5px solid #a3d977; margin: 20px auto; padding: 20px; max-width: 800px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #4CAF50; }
        .header p { margin: 5px 0; color: #555; }
        .pet-info { margin-bottom: 20px; }
        .pet-info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .footer { text-align: center; margin-top: 20px; }
        .footer p { margin: 5px 0; }
        .shield { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
           <div class="shield">
            <img src="' . $imageSrc . '" alt="Shield Logo" width="100">
        </div>
            <h1>Pawsome Furiends Veterinary Clinic</h1>
            <p>Deworming Card</p>
        </div>
        <div class="pet-info">
            <p><strong>Name:</strong> ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Species:</strong> ' . htmlspecialchars($pet['species'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Breed:</strong> ' . htmlspecialchars($pet['breed'], ENT_QUOTES, 'UTF-8') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Repeat Date</th>
                    <th>Weight (kg)</th>
                    <th>Product</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($dewormingHistory as $row) {
        $html .= '<tr>
                    <td>' . date('M d, Y', strtotime($row['deworm_date'])) . '</td>
                    <td>' . (!empty($row['r_date']) ? date('M d, Y', strtotime($row['r_date'])) : 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['product'], ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>';
    }
    if (empty($dewormingHistory)) {
        $html .= '<tr><td colspan="4">No deworming history found.</td></tr>';
    }
    $html .= '</tbody>
        </table>
        <div class="footer">
            <p><strong>Instructions:</strong> Carry this card on vet visits. Do not lose this card.</p>
            <p>Issued by:</p>
            <p>Dr. John Kirby</p>
            <p>0912-345-6789</p>
        </div>
     
    </div>
</body>
</html>';

    // Load Dompdf
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF for download
    $dompdf->stream('Deworming Card - ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '.pdf', ['Attachment' => 1]);
    exit();
}

    public function parasitePDF($pet_id)
{
    $parasiteHistoryModel = new parasiteHistoryModel();
    $petModel = new petModel();

    // Fetch data
    $pet = $petModel->getPets($pet_id);
    $parasiteHistory = $parasiteHistoryModel->where('pet_id', $pet_id)->findAll();

    // Check if pet exists
    if (!$pet) {
        return redirect()->back()->with('error', 'Pet not found.');
    }

    // Convert the logo to Base64 (use the correct path to the image)
    $imagePath = './public/img/Official.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $imageSrc = 'data:image/png;base64,' . $imageData;

    // Prepare the content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .card { border: 5px solid #a3d977; margin: 20px auto; padding: 20px; max-width: 800px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #4CAF50; }
        .header p { margin: 5px 0; color: #555; }
        .pet-info { margin-bottom: 20px; }
        .pet-info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .footer { text-align: center; margin-top: 20px; }
        .footer p { margin: 5px 0; }
        .shield { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
           <div class="shield">
            <img src="' . $imageSrc . '" alt="Shield Logo" width="100">
        </div>
            <h1>Pawsome Furiends Veterinary Clinic</h1>
            <p>Tick and Flea Treatment Card</p>
        </div>
        <div class="pet-info">
            <p><strong>Name:</strong> ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Species:</strong> ' . htmlspecialchars($pet['species'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Breed:</strong> ' . htmlspecialchars($pet['breed'], ENT_QUOTES, 'UTF-8') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Next Treatment Date</th>
                    <th>Weight (kg)</th>
                    <th>Product</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($parasiteHistory as $row) {
        $html .= '<tr>
                    <td>' . date('M d, Y', strtotime($row['treatment_date'])) . '</td>
                    <td>' . (!empty($row['next_date']) ? date('M d, Y', strtotime($row['next_date'])) : 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['product'], ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>';
    }
    if (empty($parasiteHistory)) {
        $html .= '<tr><td colspan="4">No parasite treatment history found.</td></tr>';
    }
    $html .= '</tbody>
        </table>
        <div class="footer">
            <p><strong>Instructions:</strong> Carry this card on vet visits. Do not lose this card.</p>
            <p>Issued by:</p>
            <p>Dr. John Kirby</p>
            <p>0912-345-6789</p>
        </div>
     
    </div>
</body>
</html>';

    // Load Dompdf
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF for download
    $dompdf->stream('T&F Treatment - ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '.pdf', ['Attachment' => 1]);
    exit();
}


    public function fullPetHistoryPDF($pet_id)
{
    $parasiteHistoryModel = new parasiteHistoryModel();
    $vaccineHistoryModel = new vaccineHistoryModel();
    $dewormHistoryModel = new dewormHistoryModel();
    $appointmentModel = new appointmentModel();
    $petModel = new petModel();

    // Fetch data
    $pet = $petModel->getPets($pet_id);
    $parasiteHistory = $parasiteHistoryModel->where('pet_id', $pet_id)->findAll();
    $vaccineHistory = $vaccineHistoryModel->where('pet_id', $pet_id)->findAll();
    $dewormHistory = $dewormHistoryModel->where('pet_id', $pet_id)->findAll();
    $appointments = $appointmentModel->getAppointmentsByPetId($pet_id);

    // Check if pet exists
    if (!$pet) {
        return redirect()->back()->with('error', 'Pet not found.');
    }

    // Convert the logo to Base64 (use the correct path to the image)
    $imagePath = './public/img/Official.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $imageSrc = 'data:image/png;base64,' . $imageData;

    // Prepare the content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .card { border: 5px solid #a3d977; margin: 20px auto; padding: 20px; max-width: 800px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #4CAF50; }
        .header p { margin: 5px 0; color: #555; }
        .pet-info { margin-bottom: 20px; }
        .pet-info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .footer { text-align: center; margin-top: 20px; }
        .footer p { margin: 5px 0; }
        .shield { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
           <div class="shield">
            <img src="' . $imageSrc . '" alt="Shield Logo" width="100">
        </div>
            <h1>Pawsome Furiends Veterinary Clinic</h1>
            <p>Full Pet Treatment History</p>
        </div>
        <div class="pet-info">
            <p><strong>Name:</strong> ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Species:</strong> ' . htmlspecialchars($pet['species'], ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Breed:</strong> ' . htmlspecialchars($pet['breed'], ENT_QUOTES, 'UTF-8') . '</p>
        </div>';

    // Append Vaccine History
    $html .= '<h3>Vaccine </h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Vaccine</th>
                <th>Next Due</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($vaccineHistory as $row) {
    $html .= '<tr>
                <td>' . date('M d, Y', strtotime($row['vaccine_date'])) . '</td>
                <td>' . htmlspecialchars($row['vaccine'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . (!empty($row['next_date']) ? date('M d, Y', strtotime($row['next_date'])) : 'N/A') . '</td>
              </tr>';
}
    
    if (empty($vaccineHistory)) {
        $html .= '<tr><td colspan="3">No vaccine history found.</td></tr>';
    }
    $html .= '</tbody>
    </table>';

    // Append Deworming History
    $html .= '<h3>Deworming </h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Next Due</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($dewormHistory as $row) {
    $html .= '<tr>
                <td>' . date('M d, Y', strtotime($row['deworm_date'])) . '</td>
                <td>' . htmlspecialchars($row['product'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . (!empty($row['r_date']) ? date('M d, Y', strtotime($row['r_date'])) : 'N/A') . '</td>
              </tr>';
}

    if (empty($dewormHistory)) {
        $html .= '<tr><td colspan="3">No deworming history found.</td></tr>';
    }
    $html .= '</tbody>
    </table>';

    // Append Parasite Treatment History
    $html .= '<h3>Tick and Flea Treatment </h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Next Treatment Date</th>
                <th>Weight (kg)</th>
                <th>Product</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($parasiteHistory as $row) {
        $html .= '<tr>
                    <td>' . date('M d, Y', strtotime($row['treatment_date'])) . '</td>
                    <td>' . (!empty($row['next_date']) ? date('M d, Y', strtotime($row['next_date'])) : 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['product'], ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>';
    }
    if (empty($parasiteHistory)) {
        $html .= '<tr><td colspan="4">No parasite treatment history found.</td></tr>';
    }
    $html .= '</tbody>
    </table>';

    // Append Appointments History
    $html .= '<h3>Appointments</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Doctor</th>
                <th>Service</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($appointments as $row) {
        $html .= '<tr>
                    <td>' . date('M d, Y', strtotime($row['appointment_date'])) . '</td>
                    <td>' . htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['services'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($row['service_price'], ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>';
    }
    if (empty($appointments)) {
        $html .= '<tr><td colspan="4">No appointments found.</td></tr>';
    }
    $html .= '</tbody>
    </table>
    <div class="footer">
        <p><strong>Instructions:</strong> Carry this card on vet visits. Do not lose this card.</p>
        <p>Issued by:</p>
        <p>Dr. John Kirby</p>
        <p>0912-345-6789</p>
    </div>
</div>
</body>
</html>';

    // Load Dompdf
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF for download
    $dompdf->stream('Full Pet History - ' . htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8') . '.pdf', ['Attachment' => 1]);
    exit();
}


    
}


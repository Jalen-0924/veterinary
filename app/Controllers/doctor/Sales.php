<?php
namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\salesModel;
use App\Models\InvoiceModel;


class Sales extends BaseController
{
    public function __construct()
    {
        $this->salesModel = new SalesModel();
    }

    public function index()
    {
        return $this->render_template('doctor/sales/add_new');
    }

    public function view_all()
{
    $session = session();
    $model = new SalesModel();
    
    // Get sales data from session
    $sales = $session->get('salesData');
    $start_date = $session->get('start_date');
    $end_date = $session->get('end_date');

    // Calculate total profit
    $totalProfit = 0;
    if (!empty($sales)) {
        foreach ($sales as $sale) {
            $totalProfit += $sale['total'];
        }
    }

    $data = [
        'sales' => $sales,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'totalProfit' => $totalProfit
    ];

    // For debugging
    // print_r($sales); exit;

    return $this->render_template('doctor/sales/view_all', $data);
}

    public function view_services()
    {
        $session = session();
    $model = new SalesModel();
    
    // Get sales data from session
    $sales = $session->get('salesData');
    $start_date = $session->get('start_date');
    $end_date = $session->get('end_date');

    // Calculate total profit
    $totalProfit = 0;
    if (!empty($sales)) {
        foreach ($sales as $sale) {
            $totalProfit += $sale['total'];
        }
    }

    $data = [
        'sales' => $sales,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'totalProfit' => $totalProfit
    ];

    // For debugging
    // print_r($sales); exit;
        
        return $this->render_template('doctor/sales/view_services', $data);
    }
    
   public function generateMonthlyReport()
{
    $model = new SalesModel();
    $invoiceModel = new InvoiceModel();
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');

    // Query invoices with patient and pet owner information
    $invoices = $invoiceModel->getInvoicesWithUserDetails($start_date, $end_date);

    if (!empty($invoices)) {
        $salesData = [];
        $medicineModel = new \App\Models\MedicineModel();

        foreach ($invoices as $invoice) {
            $medicineIds = json_decode($invoice['med_name'], true);
            $medicineQty = json_decode($invoice['med_qty'], true);
            $medicinePrice = json_decode($invoice['med_price'], true);

            $maxCount = count($medicineIds ?? []);

            for ($i = 0; $i < $maxCount; $i++) {
                $med_name = $medicineIds[$i] ?? '';

                if ($med_name) {
                    $medicine = $medicineModel->find($med_name);
                    $med_name = $medicine['name'] ?? '';
                }

                $salesData[] = [
                    'date' => $invoice['date'],
                    'med_name' => $med_name,
                    'med_qty' => $medicineQty[$i] ?? '',
                    'med_price' => $medicinePrice[$i] ?? '',
                    'total' => $invoice['total'],
                    'first_name' => $invoice['first_name'],
                    'last_name' => $invoice['last_name'],
                    'pet_owner_name' => $invoice['pet_owner_name'], // Add pet_owner_name here
                ];
            }
        }

        foreach ($salesData as $sale) {
            $model->insert($sale);
        }

        $session = session();
        $session->set([
            'salesData' => $salesData,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    } else {
        $session = session();
        $session->setFlashdata('error', 'No sales data found for the selected date range.');
    }

    return redirect()->to(base_url('sales/all'));
}





    public function generateMonthlyReportServices()
{
    $model = new SalesModel();
    $invoiceModel = new InvoiceModel();
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');
    
    $invoices = $invoiceModel->where('date >=', $start_date)
                            ->where('date <=', $end_date)
                            ->findAll();

    $session = session();
    
    if (!empty($invoices)) {
        $salesData = [];
        $serviceModel = new \App\Models\ServiceModel();

        foreach ($invoices as $invoice) {
            $serviceIds = json_decode($invoice['ser_name'], true);
            $servicePrice = json_decode($invoice['ser_price'], true);

            $maxCount = count($serviceIds ?? []);
            for ($i = 0; $i < $maxCount; $i++) {
                $ser_name = $serviceIds[$i] ?? '';

                if ($ser_name) {
                    $service = $serviceModel->find($ser_name);
                    $ser_name = $service['name'] ?? ''; 
                }

                $salesData[] = [
                    'date' => $invoice['date'],
                    'ser_name' => $ser_name,
                    'ser_price' => $servicePrice[$i] ?? '',
                    'total' => $invoice['total'], 
                ];
            }
        }

        foreach ($salesData as $sale) {
            $model->insert($sale);
        }

        $session->set('salesData', $salesData);
        $session->set('start_date', $start_date);
        $session->set('end_date', $end_date);
    } else {
        $session->setFlashdata('error', 'No sales data found for the selected date range.');
    }

    return redirect()->to(base_url('sales/view_services'));
}




    public function downloadExcel()
{
    ob_start();

    $start_date = $this->request->getGet('start_date');
    $end_date = $this->request->getGet('end_date');

    if (!$start_date || !$end_date) {
        return redirect()->back()->with('error', 'Invalid date range selected.');
    }

    $readableStartDate = date('F d, Y', strtotime($start_date));
    $readableEndDate = date('F d, Y', strtotime($end_date));

    $invoiceModel = new InvoiceModel();
    $invoices = $invoiceModel->select('invoice.date, invoice.total, user.first_name, user.last_name, invoice.med_name, invoice.med_qty, invoice.med_price')
                             ->join('user', 'user.id = invoice.patient_id', 'left')
                             ->where('invoice.date >=', $start_date)
                             ->where('invoice.date <=', $end_date)
                             ->findAll();

    if (empty($invoices)) {
        return redirect()->back()->with('error', 'No sales data available for the selected date range.');
    }

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Sales Report');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:F2');
    $sheet->setCellValue('A2', "Sales from: $readableStartDate to $readableEndDate");
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

  
    $sheet->setCellValue('A3', 'Date');
    $sheet->setCellValue('B3', 'Customer Name');
    $sheet->setCellValue('C3', 'Item');
    $sheet->setCellValue('D3', 'Qty');
    $sheet->setCellValue('E3', 'Price');
    $sheet->setCellValue('F3', 'Total');

    $row = 4;
    $totalProfit = 0;
    $medicineModel = new \App\Models\MedicineModel();

    foreach ($invoices as $invoice) {
        $medicineIds = json_decode($invoice['med_name'], true);
        $medicineQty = json_decode($invoice['med_qty'], true);
        $medicinePrice = json_decode($invoice['med_price'], true);

        $maxCount = count($medicineIds ?? []);
        $customerName = $invoice['first_name'] . ' ' . $invoice['last_name'];

        for ($i = 0; $i < $maxCount; $i++) {
            $med_name = $medicineIds[$i] ?? '';

            if ($med_name) {
                $medicine = $medicineModel->find($med_name);
                $med_name = $medicine['name'] ?? '';
            }

            $sheet->setCellValue('A' . $row, date('F d, Y', strtotime($invoice['date'])));
            $sheet->setCellValue('B' . $row, $customerName);
            $sheet->setCellValue('C' . $row, $med_name);
            $sheet->setCellValue('D' . $row, $medicineQty[$i] ?? '');
            $sheet->setCellValue('E' . $row, $medicinePrice[$i] ?? '');
            $sheet->setCellValue('F' . $row, $invoice['total']);
            $totalProfit += $invoice['total'];
            $row++;
        }
    }

   
    $sheet->setCellValue('A' . $row, 'Total Profit:');
    $sheet->setCellValue('F' . $row, $totalProfit);
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->mergeCells('A' . $row . ':E' . $row);

    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="item_sales_report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    ob_end_clean();
    $writer->save('php://output');
    exit;
}



    public function downloadExcelServices()
{
    ob_start();

    $start_date = $this->request->getGet('start_date');
    $end_date = $this->request->getGet('end_date');

    if (!$start_date || !$end_date) {
        return redirect()->back()->with('error', 'Invalid date range selected.');
    }

    $readableStartDate = date('F d, Y', strtotime($start_date));
    $readableEndDate = date('F d, Y', strtotime($end_date));

    $invoiceModel = new InvoiceModel();
    $invoices = $invoiceModel->select('invoice.date, invoice.total, user.first_name, user.last_name, invoice.ser_name, invoice.ser_price')
                             ->join('user', 'user.id = invoice.patient_id', 'left')
                             ->where('invoice.date >=', $start_date)
                             ->where('invoice.date <=', $end_date)
                             ->findAll();

    if (empty($invoices)) {
        return redirect()->back()->with('error', 'No service data available for the selected date range.');
    }

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Service Sales Report');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:F2');
    $sheet->setCellValue('A2', "Services from: $readableStartDate to $readableEndDate");
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Headers
    $sheet->setCellValue('A3', 'Date');
    $sheet->setCellValue('B3', 'Customer Name');
    $sheet->setCellValue('C3', 'Service');
    $sheet->setCellValue('D3', 'Price');
    $sheet->setCellValue('E3', 'Total');

    $row = 4;
    $totalProfit = 0;
    $serviceModel = new \App\Models\ServiceModel();

    foreach ($invoices as $invoice) {
        $serviceIds = json_decode($invoice['ser_name'], true);
        $servicePrices = json_decode($invoice['ser_price'], true);

        $maxCount = count($serviceIds ?? []);
        $customerName = $invoice['first_name'] . ' ' . $invoice['last_name'];

        for ($i = 0; $i < $maxCount; $i++) {
            $ser_name = $serviceIds[$i] ?? '';

            if ($ser_name) {
                $service = $serviceModel->find($ser_name);
                $ser_name = $service['name'] ?? '';
            }

            $sheet->setCellValue('A' . $row, date('F d, Y', strtotime($invoice['date'])));
            $sheet->setCellValue('B' . $row, $customerName);
            $sheet->setCellValue('C' . $row, $ser_name);
            $sheet->setCellValue('D' . $row, $servicePrices[$i] ?? '');
            $sheet->setCellValue('E' . $row, $invoice['total']);
            $totalProfit += $invoice['total'];
            $row++;
        }
    }

    
    $sheet->setCellValue('A' . $row, 'Total Profit:');
    $sheet->setCellValue('E' . $row, $totalProfit);
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->mergeCells('A' . $row . ':D' . $row);

   
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="services_sales_report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    ob_end_clean();
    $writer->save('php://output');
    exit;
}



}

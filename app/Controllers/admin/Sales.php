<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\SalesModel;
use App\Models\InvoiceModel;

class Sales extends BaseController
{
    public function __construct()
    {
        $this->salesModel = new SalesModel();
    }

    public function index()
    {
        return $this->render_template('admin/sales/add_new');
    }

    public function view_all()
    {
        $session = session();
        $Model = new SalesModel();
        
        if ($session->has('salesData') && $session->has('month') && $session->has('year')) {
            $data['sales'] = $session->get('salesData');
            $data['month'] = $session->get('month');
            $data['year'] = $session->get('year');
            
            $totalProfit = array_sum(array_column($data['sales'], 'total'));
            $data['totalProfit'] = $totalProfit;
            
            $session->remove(['salesData', 'month', 'year']);
        } else {
            $data['sales'] = [];
            $data['totalProfit'] = 0;
        }
        
        return $this->render_template('admin/sales/view_all', $data);
    }
    
    public function generateMonthlyReport()
    {
        $model = new SalesModel();
        $invoiceModel = new InvoiceModel();
        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');
        $invoices = $invoiceModel->where('MONTH(date)', $month)
                                 ->where('YEAR(date)', $year)
                                 ->findAll();

        if (!empty($invoices)) {
            $salesData = [];
            $serviceModel = new \App\Models\ServiceModel();
            $medicineModel = new \App\Models\MedicineModel();

            foreach ($invoices as $invoice) {
                $serviceIds = json_decode($invoice['ser_name'], true);
                $serviceQty = json_decode($invoice['ser_qty'], true);
                $servicePrice = json_decode($invoice['ser_price'], true);
                $medicineIds = json_decode($invoice['med_name'], true);
                $medicineQty = json_decode($invoice['med_qty'], true);
                $medicinePrice = json_decode($invoice['med_price'], true);

                $maxCount = max(count($serviceIds ?? []), count($medicineIds ?? []));
                for ($i = 0; $i < $maxCount; $i++) {
                    $ser_name = $serviceIds[$i] ?? '';
                    $med_name = $medicineIds[$i] ?? '';

                    if ($ser_name) {
                        $service = $serviceModel->find($ser_name);
                        $ser_name = $service['name'] ?? ''; 
                    }

                    if ($med_name) {
                        $medicine = $medicineModel->find($med_name);
                        $med_name = $medicine['name'] ?? '';
                    }

                    $salesData[] = [
                        'date' => $invoice['date'],
                        'ser_name' => $ser_name,
                        'ser_qty' => $serviceQty[$i] ?? '',
                        'ser_price' => $servicePrice[$i] ?? '',
                        'med_name' => $med_name,
                        'med_qty' => $medicineQty[$i] ?? '',
                        'med_price' => $medicinePrice[$i] ?? '',
                        'total' => $invoice['total'], 
                    ];
                }
            }

            foreach ($salesData as $sale) {
                $model->insert($sale);
            }
            
            $session = session();
            $session->set('salesData', $salesData);
            $session->set('month', $month);
            $session->set('year', $year);
        } else {
            $session = session();
            $session->setFlashdata('error', 'No sales data found for the selected month and year.');
        }

        return redirect()->to(base_url('sales/all'));
    }
    
public function downloadExcel() {
    ob_start();

    $month = $this->request->getGet('month');
    $year = $this->request->getGet('year');

    $invoiceModel = new InvoiceModel();
    $invoices = $invoiceModel->where('MONTH(date)', $month)
                             ->where('YEAR(date)', $year)
                             ->findAll();

    if (empty($invoices)) {
        return redirect()->back()->with('error', 'No sales data available for the selected month and year.');
    }

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:H1');
    $sheet->setCellValue('A1', 'Monthly Sales Report');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A2', 'Date');
    $sheet->setCellValue('B2', 'Services');
    $sheet->setCellValue('C2', 'Qty');
    $sheet->setCellValue('D2', 'Price');
    $sheet->setCellValue('E2', 'Item');
    $sheet->setCellValue('F2', 'Qty');
    $sheet->setCellValue('G2', 'Price');
    $sheet->setCellValue('H2', 'Total');

    $row = 3;
    $totalProfit = 0;
    $serviceModel = new \App\Models\ServiceModel();
    $medicineModel = new \App\Models\MedicineModel();

    foreach ($invoices as $invoice) {
        $serviceIds = json_decode($invoice['ser_name'], true);
        $serviceQty = json_decode($invoice['ser_qty'], true);
        $servicePrice = json_decode($invoice['ser_price'], true);
        $medicineIds = json_decode($invoice['med_name'], true);
        $medicineQty = json_decode($invoice['med_qty'], true);
        $medicinePrice = json_decode($invoice['med_price'], true);

        $maxCount = max(count($serviceIds ?? []), count($medicineIds ?? []));
        for ($i = 0; $i < $maxCount; $i++) {
            $ser_name = $serviceIds[$i] ?? '';
            $med_name = $medicineIds[$i] ?? '';

            if ($ser_name) {
                $service = $serviceModel->find($ser_name);
                $ser_name = $service['name'] ?? ''; 
            }

            if ($med_name) {
                $medicine = $medicineModel->find($med_name);
                $med_name = $medicine['name'] ?? '';
            }

            $sheet->setCellValue('A' . $row, $invoice['date']);
            $sheet->setCellValue('B' . $row, $ser_name);
            $sheet->setCellValue('C' . $row, $serviceQty[$i] ?? '');
            $sheet->setCellValue('D' . $row, $servicePrice[$i] ?? '');
            $sheet->setCellValue('E' . $row, $med_name);
            $sheet->setCellValue('F' . $row, $medicineQty[$i] ?? '');
            $sheet->setCellValue('G' . $row, $medicinePrice[$i] ?? '');
            $sheet->setCellValue('H' . $row, $invoice['total']);
            $totalProfit += $invoice['total'];
            $row++;
        }
    }

    $sheet->setCellValue('A' . $row, 'Total Profit:');
    $sheet->setCellValue('H' . $row, $totalProfit);
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->mergeCells('A' . $row . ':G' . $row);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="sales_report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    ob_end_clean();
    $writer->save('php://output');
    exit;
}

}

<?php
namespace App\Controllers\patient;
use App\Controllers\BaseController;

class Reports extends BaseController
{
    public function reports()
    {
        return $this->render_template('patient/reports/view_all');
    }
}

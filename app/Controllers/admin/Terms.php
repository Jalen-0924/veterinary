<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;

class Terms extends BaseController
{
    public function terms()
    {
        return $this->render_template('admin/terms/view_all');
    }
}

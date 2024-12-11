<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Fiscal_year;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected $fysd;
    protected $fyed;

    public function __construct()
    {
        $afy = Fiscal_year::where('is_active', 'yes')->get();
        if(!empty($afy[0])){
            $this->fysd = $afy[0]->start_date;
            $this->fyed = $afy[0]->end_date;
        }else{
            $this->fysd = date('Y').'-01-01';
            $this->fyed = date('Y').'-12-31';
        }
    }
}

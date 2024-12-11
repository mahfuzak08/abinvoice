<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Bankacc extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name','type','bank_name','bank_address','acc_no', 'currency'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

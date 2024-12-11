<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class variant extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['product_id','color','size','img','sell_price','buy_price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

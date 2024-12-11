<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class product_tranx extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['product_id','variant_id','from_inventory','order_id','order_type','date','inout','qty','batch_no','expiry_date','actual_sell_price','actual_buy_price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

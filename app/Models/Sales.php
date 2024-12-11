<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sales extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['order_id', 'order_type', 'user_id','customer_id','products','return_items', 'date', 'status', 'discount', 'shipping_cost','labour_cost','carrying_cost','other_cost', 'total', 'payment', 'total_due', 'note'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class product extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['barcode','name','description','commonimg','has_sl','user_id','category_id', 'brand_name', 'tags'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

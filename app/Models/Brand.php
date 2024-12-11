<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Brand extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['brand_name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

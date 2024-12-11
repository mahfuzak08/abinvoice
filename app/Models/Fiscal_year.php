<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Fiscal_year extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

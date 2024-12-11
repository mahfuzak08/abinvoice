<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Attendance extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['date', 'emp_id', 'hours','user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

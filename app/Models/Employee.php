<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'mobile', 'designation', 'gender', 'address', 'nid', 'image', 'salary', 'bonus', 'emp_type', 'joining', 'closing'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

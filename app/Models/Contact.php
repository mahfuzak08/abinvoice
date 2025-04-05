<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Contact extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'mobile', 'email'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

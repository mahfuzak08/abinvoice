<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Expense extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'status', 'created_by'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

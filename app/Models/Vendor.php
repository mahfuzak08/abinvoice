<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Vendor extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'mobile', 'email','address','url','balance', 'opening_balance', 'is_delete'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

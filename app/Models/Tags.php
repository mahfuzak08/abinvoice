<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tags extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['tag_name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

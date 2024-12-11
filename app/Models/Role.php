<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Role extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'module'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

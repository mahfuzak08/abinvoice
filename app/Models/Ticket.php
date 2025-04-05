<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ticket extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['title','description','priority','status', 'imgs', 'opening_date', 'closing_date', 'submit_by', 'closing_by', 'client_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

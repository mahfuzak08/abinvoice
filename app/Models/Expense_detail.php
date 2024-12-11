<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Expense_detail extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['trnx_date', 'expense_id', 'user_id', 'account_id', 'amount', 'status', 'title', 'details'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

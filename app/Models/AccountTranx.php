<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AccountTranx extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['account_id', 'tranx_date', 'ref_id','ref_type', 'ref_tranx_id', 'ref_tranx_type', 'amount','user_id', 'note'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}

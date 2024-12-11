<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms_log extends Model
{
    use HasFactory;

    protected $fillable = ['msg', 'api_key', 'type','contacts','senderid','url', 'label', 'response', 'user_id'];
}

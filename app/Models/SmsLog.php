<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = 'sms_logs';

    protected $fillable = [
        'to',
        'message',
        'status',
        'error_message',
        'gateway'
    ];
}

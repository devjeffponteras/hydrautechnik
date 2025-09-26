<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaynamicsLog extends Model
{
    use SoftDeletes;

    protected $fillable = ['result_return', 'request_id', 'response_id', 'response_title', 'response_code', 'response_message',
        'response_advise', 'timestamp', 'ptype', 'rebill_id', 'token_id', 'token_info', 'processor_response_id',
        'processor_response_authcode', 'signature'];
}

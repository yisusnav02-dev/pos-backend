<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['phone_number', 'code', 'expires_at'];
}

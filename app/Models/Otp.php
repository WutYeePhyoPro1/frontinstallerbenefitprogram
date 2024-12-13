<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = "otps";
    protected $fillable = [
      "installer_card_card_number",
      'otp',
      'type',
      'expires_at'
    ];
}

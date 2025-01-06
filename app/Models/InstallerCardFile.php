<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallerCardFile extends Model
{
    use HasFactory;
    protected $table = "installer_card_files";
    protected $primaryKey = "id";
    protected $fillable = [
         "installer_card_card_number",
         "image",
    ];

    // public function redemptiontransaction(){
    //      return $this->belongsTo(RedemptionTransaction::class);
    // }
}

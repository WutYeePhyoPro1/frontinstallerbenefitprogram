<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionTransactionFile extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';


    protected $table = "redemption_transaction_files";
    protected $primaryKey = "id";
    protected $fillable = [
         "redemption_transaction_uuid",
         "image",
         "user_uuid"
    ];

    public function redemptiontransaction(){
         return $this->belongsTo(RedemptionTransaction::class);
    }
}

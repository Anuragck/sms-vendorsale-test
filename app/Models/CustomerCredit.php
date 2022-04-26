<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerCredit extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
    }
    public function payment() {
        return $this->belongsTo(Payment::class,'payment_id','id')->withTrashed();
    }
}

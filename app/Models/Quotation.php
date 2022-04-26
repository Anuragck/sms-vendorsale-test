<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function items() {

        return $this->hasMany(QuotationItem::class, 'quotation_id', 'quotation_no');
    }

    public function customer()
	{
		return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
	}
}

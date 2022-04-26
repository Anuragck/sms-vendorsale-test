<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="sales";

    public function items() {

        return $this->hasMany(SaleItem::class, 'sale_id', 'invoice_no');
    }

    public function customer()
	{
		return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
	}

    public function vendor()
	{
		return $this->belongsTo(Vendor::class,'customer_id','id')->withTrashed();
	}



}

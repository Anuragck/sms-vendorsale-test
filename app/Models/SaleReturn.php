<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="sale_returns";

    public function items() {

        return $this->hasMany(SaleReturnItem::class, 'sale_return_id', 'id');
    }

    public function customer()
	{
		return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
	}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardSale extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function product()
	{
		return $this->belongsTo(Product::class,'item_id','id');
	}

    public function customer()
	{
		return $this->belongsTo(Customer::class,'customer_id','id');
	}
}

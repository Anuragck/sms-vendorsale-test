<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerReward extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function sale()
	{
		return $this->belongsTo(Sale::class,'invoice_no','invoice_no');
	}
    public function redeem()
	{
		return $this->belongsTo(RewardSale::class,'reward_no','reward_no');
	}
	
	public function customer() {
        return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
    }
}

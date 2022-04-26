<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function purchase()
	{
		return $this->belongsTo(PurchaseLog::class,'purchase_id','id');
	}

    public function product()
	{
		return $this->belongsTo(Product::class,'product_id','id');
	}

}

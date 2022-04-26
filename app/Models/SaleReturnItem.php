<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturnItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="sale_return_items";

    public function saleReturn()
	{
		return $this->belongsTo(SaleReturn::class,'sale_return_id','id');
	}
    public function product()
	{
		return $this->belongsTo(Product::class,'product_id','id')->withTrashed();
	}

	public function category()
	{
		return $this->belongsTo(Category::class,'category_id','id')->withTrashed();
	}
}

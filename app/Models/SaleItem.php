<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="sale_items";

    public function sale()
	{
		return $this->belongsTo(Sale::class,'sale_id','invoice_no');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    public function quotation()
	{
		return $this->belongsTo(Quotation::class,'quotation_id','quotation_no');
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

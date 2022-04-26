<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PurchaseCredit extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function vendor() {

        return $this->belongsTo(Vendor::class,'customer_id','id')->withTrashed();
    }
    public function purchase()
	{
		return $this->belongsTo(Purchase::class,'purchase_id','id')->withTrashed();
	}

}

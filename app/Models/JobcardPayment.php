<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobcardPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function jobcard()
	{
		return $this->belongsTo(Jobcard::class,'jobcard_id','id')->withTrashed();
	}
    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
    }
}

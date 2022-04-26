<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Jobcard extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function items() {

        return $this->hasMany(JobcardItem::class, 'jobcard_id', 'id');
    }

    public function payments() {

        return $this->hasMany(JobcardPayment::class, 'jobcard_id', 'id');
    }


    public function customer()
	{
		return $this->belongsTo(Customer::class,'customer_id','id')->withTrashed();
	}

    public function advisor() {
        return $this->belongsTo(Employee::class, 'jobadvisor_id', 'id')->withTrashed();
    }
}

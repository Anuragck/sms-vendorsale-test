<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function orderItems() {

        return $this->hasMany(PurchaseOrderItem::class, 'order_id', 'id');
    }
}

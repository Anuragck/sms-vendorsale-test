<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function purchaseItems() {

        // return $this->hasMany(PurchaseItem::class, 'id', 'purchase_id');
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

}

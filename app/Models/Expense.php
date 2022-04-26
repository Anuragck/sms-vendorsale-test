<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function expensecategory() {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id','id')->withTrashed();
    }
}

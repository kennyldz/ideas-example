<?php

namespace App\Models\Discounts;

use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable=[
        "id",
        "orderId",
        "customerId",
        "discounts",
        "total_discount",
        "discounted_total",
        "total_without_discount",
    ];


    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customerId');
    }

    public function orders():BelongsTo{
        return $this->belongsTo(Order::class,'orderId');
    }

}

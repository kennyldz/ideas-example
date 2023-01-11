<?php

namespace App\Models\Baskets;

use App\Models\Customers\Customer;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Basket extends Model
{
    use HasFactory;

    protected $fillable=[
        "id",
        "customerId",
        "productId",
        "category",
        "quantity",
        "unit_price",
        "total_price",
        "ip_address_of_transaction"
    ];

    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customerId');
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class,'productId');
    }

}

<?php

namespace App\Models\Orders;

use App\Models\Customers\Customer;
use App\Models\Discounts\Discount;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        "id",
        "customerId",
        "items",
        "total",
        "ip_address_of_transaction",
    ];
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class,'productId');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class,'orderId');
    }

}

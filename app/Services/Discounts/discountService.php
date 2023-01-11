<?php

namespace App\Services\Discounts;

use App\Enums\discountServiceEnum;
use App\Enums\statusEnum;
use App\Http\Controllers\Base\BaseController;
use App\Models\Baskets\Basket;
use App\Models\Discounts\Discount;
use App\Models\Discounts\DiscountSetting;
use App\Traits\JwtToken;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class discountService extends BaseController{

    use JwtToken;

    public $discounts=[]; /** @var array Discounts */
    public $totalOrderPrice; /** @var float Total Order Price */
    public $totalDiscount=0; /** @var float Total discount */
    public $discountedTotal=0; /** @var float Discounted total */
    private $customerId; /** @var int Customer ID  */

    /**
     * @param DiscountSetting $discountSetting
     * @param Basket $basket
     */
    public function __construct(public DiscountSetting $discountSetting,public Basket $basket,public Discount $discount){
        $this->discountSetting=$discountSetting;
        $this->basket=$basket;
        $this->discount=$discount;
        $this->customerId=$this->getJwtSession();
        $this->setOrderAmount();
    }

    /**
     * Set Order Sum Total Price
     * @return void
     */
    private function setOrderAmount():void{
        $this->discountedTotal=$this->basket->where('customerId',$this->customerId)->sum('total_price');
        $this->totalOrderPrice=$this->discountedTotal; //before discount total price
    }

    /**
     * apply a discount from the total order price over the entered amount
     * @return void
     */
    public function percentOverDiscount():void{
        if($setting=$this->discountSetting->where('discount_code',discountServiceEnum::PERCENT_OVER_DISCOUNT->value)
            ->where('status',statusEnum::ACTIVE->value)
            ->first()){ //if conditions are met
            $discountSetting=json_decode($setting->discount_setting);
            $order=$this->basket->where('customerId',$this->customerId)->sum('total_price'); //get basket total price
            if($order>=$discountSetting->total_price){ //if conditions are met
                $discountAmount =   round($this->discountedTotal*$discountSetting->discount/100,2); //Set discount Amount
                $subTotal       =   round($this->discountedTotal-$discountAmount,2); //set Sub total after discount
                $this->totalDiscount+=$discountAmount;
                $this->discountedTotal=$subTotal;
                $this->discounts[]=[
                    "discountReason"        => discountServiceEnum::PERCENT_OVER_DISCOUNT->value,
                    "discountDescription"   => $setting->discount_description,
                    "discountAmount"        => $discountAmount,
                    "subtotal"              => $subTotal,
                ];
            }
        }

    }

    /**
     * Apply free product if the product meets the quantity and category requirements.
     * @return void
     */
    public function buyCountGetFree():void{
        if($setting=$this->discountSetting->where('discount_code',discountServiceEnum::BUY_COUNT_GET_FREE->value)
            ->where('status',statusEnum::ACTIVE->value)
            ->first()){
            $discountSetting=json_decode($setting->discount_setting);
            if($order=$this->basket->where('customerId',$this->customerId)
                ->where('category',$discountSetting->category)
                ->where('quantity',$discountSetting->buy_count)
                ->first()){//if conditions are met
                    $discountAmount =   round($order->first()->unit_price*$discountSetting->get_free,2); //Calculate the amount to be given free
                    $subTotal       =   round($this->discountedTotal-$discountAmount,2);
                    $this->totalDiscount+=$discountAmount;
                    $this->discountedTotal=$subTotal;
                    $this->discounts[]=[
                        "discountReason"        =>  discountServiceEnum::BUY_COUNT_GET_FREE->value,
                        "discountDescription"   => $setting->discount_description,
                        "discountAmount"        => $discountAmount,
                        "subtotal"              => $subTotal,
                    ];
            }
        }
    }


    /**
     * Apply a discount to the cheapest product if the product meets the quantity and category requirements
     * @return void
     */
    public function buyCountGetDiscount():void{
        if($setting=$this->discountSetting->where('discount_code',discountServiceEnum::BUY_COUNT_GET_DISCOUNT->value)
            ->where('status',statusEnum::ACTIVE->value)
            ->first()){ //if conditions are e met
            $discountSetting=json_decode($setting->discount_setting);
            if($order=$this->basket->where('customerId',$this->customerId)
                ->where('category',$discountSetting->category)
                ->orderBy('unit_price','asc')
                ->get()){
                $productCount=$order->count('id'); //get selected Product count
                if ($productCount>=$discountSetting->buy_count){
                    $discountAmount =   round($order->first()->total_price*$discountSetting->get_discount/100,2); //Calculate the amount to be given
                    $subTotal       =   round($this->discountedTotal-$discountAmount,2);
                    $this->totalDiscount+=$discountAmount;
                    $this->discountedTotal=$subTotal;
                    $this->discounts[]=[
                        "discountReason"        =>  discountServiceEnum::BUY_COUNT_GET_FREE->value,
                        "discountDescription"   => $setting->discount_description,
                        "discountAmount"        => $discountAmount,
                        "subtotal"              => $subTotal,
                    ];
                }
            }
        }
    }


    /**
     * Discount Save
     * @param $orderId
     * @param $customerId
     * @param $discounts
     * @param $totalDiscount
     * @param $discountedTotal
     * @param $total
     * @return void
     */
    public function createDiscount($orderId,$customerId,$discounts,$totalDiscount,$discountedTotal,$total):void{
        try {
            $this->discount->create([
                "orderId"        => $orderId,
                "customerId"     => $customerId,
                "discounts"      => json_encode($discounts),
                "total_discount" => $totalDiscount,
                "discounted_total"  => $discountedTotal,
                "total_without_discount"   => $total
            ]);
        }catch (QueryException $exception){
            Log::critical('Discount could not be achieved after ordering.'.$exception->getMessage());
        }
    }


}


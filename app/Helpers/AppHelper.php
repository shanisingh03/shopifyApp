<?php
namespace App\Helpers;

use Response;
use Session;
use Oseintow\Shopify\Facades\Shopify;
use App\ShopifyStore;
use App\Shop;

class AppHelper
{
    #Return Instance Of Helper Function
    public static function instance()
    {
        return new AppHelper();
    }

    /**
     * @param $status_code
     * @param $status
     * @param $message
     * @param $data
     * @return mixed
     It Gives Response Into Json
     */
    public static function createResponseJson($status_code, $status, $message, $data = null)
    {

        $res = array(
            'statusCode' => $status_code,
            'status'     => $status,
            'message'    => $message,
            'data'       => $data);
        return $res;
    }


    #Check For Valid Shopify Access Token Of Shop
    public static function isValidShopToken($shop='')
    {
        try {
            $access_token = ShopifyStore::where('shop',$shop)->value('token');
            $shop_data = Shopify::setShopUrl($shop)->setAccessToken($access_token)->get("admin/shop.json");

            if ($shop_data) {
                # Valid
                return true;
            } else {
                # InValid
                return false;
            }
        } catch (Exception $e) {
            #Error Ocured
            report($e);
            return false;
        }
        
        
    }

    #Activate Charge
    public static function ActivateCharge($shop='',$charge_id='')
    {
        # code...
        $access_token = ShopifyStore::where('shop',$shop)->value('token');
        $charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->get("/admin/recurring_application_charges/".$charge_id.".json");
        
        if ($charge['activated_on'] != null) {
            # Check For Shop Status And Update Accordingly
            $is_shop_activated = Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->value('activated');
            if ($is_shop_activated == 0) {
                # Activate Shop In DB
                Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->update(['activated'=>1]);
            } 
            
        }else{
            #Activate Charge And Update Status
            $activated_charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->post("/admin/recurring_application_charges/".$charge_id."/activate.json");

            if ($activated_charge['activated_on'] != null) {
                # update Status of Shop
                Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->update(['activated'=>1]);
            }
        }

        return true;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Oseintow\Shopify\Facades\Shopify;
use App\ShopifyStore;


class HomeController extends Controller
{
    //
    public function getOrders($shop)
    {
    	# Check For Shop
    	if ($shop) {
    		# code...
    		$access_token = ShopifyStore::where('shop',$shop)->value('token');

    		$orders = Shopify::setShopUrl($shop)->setAccessToken($access_token)->get("admin/orders.json");

    		return View::make('app.draft_order')->with(['orders'=>$orders,'shop'=>$shop]);

    	}else{
    		#return Back With Error

    		return back()->with('eror','No Shop Found');
    	}
    }
}

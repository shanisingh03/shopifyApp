<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Oseintow\Shopify\Facades\Shopify;
use App\ShopifyStore;
use App\Shop;
use App\Helpers\AppHelper;

class AuthController extends Controller
{
    //Auth Function
    public function index(Request $request)
    {
    	$shop = $request->shop;

    	if ($shop) {
    		#Check For Valid Token
    		$shop_id = ShopifyStore::where('shop',$shop)->value('id');
		  	
			$shop_data = AppHelper::isValidShopToken($shop);
			
			if ($shop_data) {
				#Check For Charge 
    			$is_charged = Shop::where('shopify_domain',$shop)->first();

    			if ($is_charged) {
					# Check For Activated Charge
					if ($is_charged->activated == 1) {
						# Activated Return To Dashboard
						return View::make('app.welcome')->with(['shop'=>$shop]);
					} else {
						# Activate And Return TO Dashboard
						$charge_id = $is_charged->charge_id;
						$activate_charge = AppHelper::ActivateCharge($shop,$charge_id);
						return View::make('app.welcome')->with(['shop'=>$shop]);
						
					}
					
					
				}else{
					#Create Charge
					$access_token = ShopifyStore::where('shop',$shop)->value('token');
					$charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->post("/admin/recurring_application_charges.json",["recurring_application_charge"=>['name' => env("CHARGE_NAME", "App Charge"),'price'=>env("CHARGE_AMOUNT", "0.0"),'return_url'=>env("APP_URL", "https://690f422e.ngrok.io").'shanisinghProjects/shopifyApp/billable/?shop='.$shop,'test'=>true]]);

					return View::make('app.billable')->with('url',$charge['confirmation_url']);

				}
			} else {
				# Display Error Page 
				return View::make('app.error')->with('msg','Invalid Access Of Store');
			}
    	}else{
	    	# Check For Shop and Login
	    	return View::make('welcome');
    	}
    }

    // Install App
    public function installApp(Request $request)
    {
    	# Install App
    	$shopUrl = $request->shop;
	    $scope = ['read_products', 'write_products', 'read_customers', 'write_customers', 'read_orders', 'write_orders','read_draft_orders','write_draft_orders','read_script_tags', 'write_script_tags'];

	    $redirectUrl = env("APP_URL", "https://690f422e.ngrok.io")."shanisinghProjects/shopifyApp/callback";


	    $shopify = Shopify::setShopUrl($shopUrl);
    	return redirect()->to($shopify->getAuthorizeUrl($scope,$redirectUrl));
	}

	// CallBack Function
	public function CallBack(Request $request)
	{
		# If Shop Exist
		$is_shop = ShopifyStore::where('shop',$request->shop)->count();
		if ($is_shop < 1) {
			$shop = $request->shop;
			# Add Shop
			$accessToken = Shopify::setShopUrl($request->shop)->getAccessToken($request->code);
			$shopify = new ShopifyStore;
			$shopify->shop = $request->shop;
			$shopify->code = $request->code;
			$shopify->hmac = $request->hmac;
			$shopify->timestamp = $request->timestamp;
			$shopify->token = $accessToken;
			$shopify->save();

			#Create Charge
			$access_token = ShopifyStore::where('shop',$shop)->value('token');
			$charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->post("/admin/recurring_application_charges.json",["recurring_application_charge"=>['name' => 'Test App With Charge','price'=>10.0,'return_url'=>env("APP_URL", "https://690f422e.ngrok.io").'shanisinghProjects/shopifyApp/billable/?shop='.$shop,'test'=>true]]);
    		return View::make('app.billable')->with('url',$charge['confirmation_url']);

		}else{
			#Go To Store
			$shop = $request->shop;
			
			$accessToken = Shopify::setShopUrl($request->shop)->getAccessToken($request->code);
			
			$shopify =  ShopifyStore::where('shop',$shop)->first();
			$shopify->code = $request->code;
			$shopify->hmac = $request->hmac;
			$shopify->timestamp = $request->timestamp;
			$shopify->token = $accessToken;
			$shopify->save();

		  	$access_token = ShopifyStore::where('shop',$shop)->value('token');
			
			$is_charged = Shop::where('shopify_domain',$shop)->first();

			if ($is_charged) {
				$shop_id = ShopifyStore::where('shop',$shop)->value('id');
				# Check For Activated Charge
				Shop::where('shop_id',$shop_id)->delete();
			}

			#Create Charge
			$charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->post("/admin/recurring_application_charges.json",["recurring_application_charge"=>['name' => 'Test App With Charge','price'=>10.0,'return_url'=>env("APP_URL", "https://690f422e.ngrok.io").'shanisinghProjects/shopifyApp/billable/?shop='.$shop,'test'=>true]]);
			return View::make('app.billable')->with('url',$charge['confirmation_url']);
				
		}
	}

	// Billable Of App
	public function Billable(Request $request)
	{
		# Charge Application
		$charge_id = $request->charge_id;
		$shop = $request->shop;

		#Check For Valid Shop
		$is_shop_valid = ShopifyStore::where('shop',$shop)->count();

		if ($is_shop_valid == 1) {
			# Valid Now Check For Charge Valid And Accepted?
			$access_token = ShopifyStore::where('shop',$shop)->value('token');
    		$charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->get("/admin/recurring_application_charges/".$charge_id.".json");
    		
    		#Check Status
    		if ($charge['status'] == "accepted") {
    			
    			# Save Shop with Charge Id And Status And Activate 
    			$shop_id = ShopifyStore::where('shop',$shop)->value('id');

    			$is_present = Shop::where('shop_id',$shop_id)->count();
    			if ($is_present < 1) {
    				# Create The Shop
    				$shop_data = new Shop;
	    			$shop_data->shopify_domain = $shop;
	    			$shop_data->shop_id = $shop_id;
	    			$shop_data->charge_id = $charge['id'];
	    			$shop_data->save();
    			} else {
    				# Update The Shop
    				$shop_data =  Shop::where('shop_id',$shop_id)->first();
	    			$shop_data->shopify_domain = $shop;
	    			$shop_data->shop_id = $shop_id;
    				$shop_data->charge_id = $charge['id'];
    				$shop_data->save();
	    			
    			}

				#Check Activation Status
				if ($charge['activated_on'] != null) {
					#Check Status Of Shop If Not Activated Activate it
					$is_shop_activated = Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->value('activated');
					if ($is_shop_activated == 0) {
						# Activate Shop In DB
						Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->update(['activated'=>1]);
					} 
					
					# Already Activated Return TO Dashboard
					// return View::make('app.welcome')->with(['shop'=>$shop]);
					return redirect()->route('app.home',['shop'=>$shop]);
				} else {

					#Activate Charge Id And Then Returned To Dashboard
					$access_token = ShopifyStore::where('shop',$shop)->value('token');
					$activated_charge = Shopify::setShopUrl($shop)->setAccessToken($access_token)->post("/admin/recurring_application_charges/".$charge_id."/activate.json");
					
					if ($activated_charge['activated_on'] != null) {
						# update Status of Shop
						Shop::where('shop_id',$shop_id)->where('charge_id',$charge_id)->update(['activated'=>1]);
						
						return redirect()->route('app.home',['shop'=>$shop]);

					}
					
				}

    		} else {
    			# Give Message That You Declined The Charge.
    			// return "You Declined The Charge; Hell".$charge_id;
    			return View::make('app.error')->with('msg','You Declined The Charge We Can Not Process Without Payment.')->with('shop',$shop);
    		}
    		

		} else {
			# Invalid Redirect on Index Where Install
			return View::make('welcome')->with(['shop'=>$shop]);
		}
		

	}

	public function home($shop='')
	{
		# code...
		if ($shop != '' && $shop != null) {
			# code...
			return View::make('app.welcome')->with(['shop'=>$shop]);
		} else {
			# code...
			return View::make('welcome');
		}
		
	}
}

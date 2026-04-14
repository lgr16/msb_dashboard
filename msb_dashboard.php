<?php
/**
* Plugin Name: MSB Dashboard
* Plugin URI: 
* Description: Live Sales and Analytics Data in Charts
* Version: 1.0.24
* Author: Brad
* Author URI: 
**/

/*
Change Log:
1.0.24
-hardening dbconnection code
1.0.23
-tweaked ishidden function to handle div not present case better
1.0.22
-added new report for sales by product taxonomy; tweaked other charts to handle larger data sets
1.0.17
-minor js tweak for chart y axis range in tot dollar total orders charts
1.0.16
-tweeaking bar style
1.0.14/15
-added style to all markets page
1.0.13
-look and feel tweaks to All Markets Report Page
1.0.11/12
-added all markets report
-css tweaks
1.0.10:csstweaksforMoose
1.0.9:tweakingcss
1.0.8:-style tweaks for buttons
1.0.5
-added args to Report to support multiple campaign codes
1.0.4:
-added loading spinner for refresh button
1.0.1: 
-converting to plugin/shortcode

*/

//$database = require_once('dbconnect.php');
require_once('ga_reports_common.php');
require_once('ReportTotalSales.php');
require_once('ReportSalesByProducts.php');			  
require_once('ReportVistsByBusiness.php');
require_once('ReportSalesByPerson.php');
require_once('ReportBusinessProgress.php');
require_once('ReportOrders.php');
require_once('ReportPageViews.php');
require_once('ReportAbandonedCarts.php');
require_once('ReportAllVisitDetails.php');
require_once('ReportOrdersEdit.php');
require_once('ReportAllMarkets.php');
require_once('ReportSalesByProductTaxonomy.php');


global $wpdb;

class MSB_Dashboard {
	
	protected $db;
	protected $config;
	protected $market;
	protected $ver;
	
	public function __construct($wp_dataObject) 
    {
		$this->db = $wp_dataObject;			
		$this->config = new Config();
		$this->ver = "1.0.24";
		
		add_shortcode('msb_dashboard',array($this, 'dash_shortcode') );
		add_action( 'wp_enqueue_scripts', array( $this ,'register_scripts'));
		add_action("wp_ajax_hdb_getDashData", array($this, 'hdb_getDashData') );
		add_action("wp_ajax_hdb_update_order", array($this, 'hdb_update_order') );
		add_action("wp_ajax_hdb_get_order_details", array($this, 'hdb_get_order_details') );
		
	}
	
	
	
	function register_scripts(){
		wp_register_style( 'msb-dashboard-stylesheet',  plugin_dir_url( __FILE__ ) . 'css/msb_dash.css',array(), $ver=$this->ver );
		wp_register_script( 'msb-dashboard-jsapp',  plugin_dir_url( __FILE__ ) . 'js/app-script-code5.js',array(), $ver=$this->ver );
	}
	
	
	
	function enq_scripts()
	{
		wp_enqueue_style( 'msb-dashboard-stylesheet' );
		wp_enqueue_script('msb-dashboard-jsapp');
	}
		

	
	
	public function dash_shortcode($atts)
	{		
		$a = shortcode_atts( array('market' => ''), $atts );
	
	
		$this->enq_scripts();
		
		$mkt = $a["market"];
		$this->market = $mkt;
		
		ob_start();
		
		require("dash5.php");
		
		echo sprintf('<div> <input type="hidden" id="hdb_dashmkt" name="hdb_dashmkt" value="%s"> </div>', base64_encode($mkt) );				
		
		return ob_get_clean();
	}
	
	
	protected function write_log( $data ) {
		if ( true  === WP_DEBUG ) {   
			if ( is_array( $data ) || is_object( $data ) ) {
				error_log( print_r( $data, true ) );
			} else {
				error_log( $data );
			}
		}
	}
	
	public function hdb_getDashData(){
				
		if ( ! check_ajax_referer( 'msb-dash-security-nonce', 'security', false ) ) {
									
			wp_send_json_error( 'Invalid Request: MSB' );
			wp_die();
		  }
		  
		  //TODO: need to have a think about how we are getting "market", currently grabbing this from post data but could just use the shortcode parameter and keep it all server side....
		$market = sanitize_text_field( $_POST['market'] );
		
		$dataKey = sanitize_text_field( $_POST['dataKey'] );
		//$config = new Config();
		
		$conf = $this->config->msb_config($market);
		
		//write_log("mmmmmmmmmmmmmmmmmmmmmm");
		//write_log($market);
		//write_log($dataKey);
		//write_log($conf);
		
		$report = null;
		
		switch ($dataKey){
			case "TotalSales":
				$report = new ReportTotalSales($this->db, $conf);
			break;
			
			case "SalesByStation":
				$report = new ReportSalesByProducts($this->db, $conf);
			break;
			
			case "VisitsByBusiness":
				$report = new ReportVisitsByBusiness($this->db, $conf);
			break;
			
			case "SalesByPerson":
				$report = new ReportSalesByPerson($this->db, $conf);
			break;
			
			case "BusinessProgress":
				$report = new ReportBusinessProgress($this->db, $conf);
			break;
			
			case "OrdersSummary":
				$report = new ReportOrders($this->db, $conf);
			break;
			
			case "PageViews":
				$report = new ReportPageViews($this->db, $conf);
			break;
			
			case "AbandonedCarts":
				$report = new ReportAbandonedCarts($this->db, $conf);
			break;
						
			case "AllVisitDetails":
				$report = new ReportAllVisitDetails($this->db, $conf);
			break;
						
			case "OrdersEdit":
				$report = new ReportOrdersEdit($this->db, $conf);
			break;
			
			case "AllMarkets":
				$report = new ReportAllMarkets($this->db, $conf);
			break;

			case "SalesByProductTaxonomy":
				$report = new ReportSalesByProductTaxonomy($this->db, $conf);
			break;			
		}		
		
		$rval = $report->getData($market); 
		
		//write_log("--------------");
		//write_log($rval);
		
		echo $rval;		
		
		wp_die();
	}
	
	public function hdb_update_order(){

		if ( ! check_ajax_referer( 'msb-dash-security-nonce', 'security', false ) ) {
			write_log("failed ajax referer test");
			
			wp_send_json_error( 'Invalid Request: MSB' );
			wp_die();
		  }
			
		$jdata = json_decode( stripslashes( $_POST['jdata']), true);
		$jdata = map_deep($jdata, 'sanitize_text_field');
		
		$_market = sanitize_text_field(  $_POST['market']);
		
		write_log("update order data: ");
		write_log($jdata);
		
		$id = intval($jdata["Order ID"]);
		$newRepID = $jdata["Sales Rep"];
		
		$order = wc_get_order($id);
		
		$order->update_meta_data("hdb_sales_rep", $newRepID );
		
		$order->save();
		
		$rval = json_encode( $jdata ); 
				
		echo $rval;
		
		wp_die();
		
	}
	
	public function hdb_get_order_details(){

		if ( ! check_ajax_referer( 'msb-dash-security-nonce', 'security', false ) ) {
			write_log("failed ajax referer test");
			
			wp_send_json_error( 'Invalid Request: MSB' );
			wp_die();
		  }
			
		//$jdata = json_decode( stripslashes( $_POST['jdata']), true);
		//$jdata = map_deep($jdata, 'sanitize_text_field');
		
		$_market = sanitize_text_field(  $_POST['market']);
		$_orderID = sanitize_text_field(  $_POST['orderID']);
		
		write_log("get order details: ");
		write_log($_orderID);
		
		$id = intval($_orderID);
		
		
		$order = wc_get_order($id);				
		
		$rval = json_encode( $this->getJsonOrder($order) ); 
		
		//write_log($rval);
		
		echo $rval;
		
		wp_die();
		
	}
	
	protected function getJsonOrder($order){
		
		$rval = [];
		
		$rval["order_id"] 				= 	$order->get_id();
		
		$rval["billing_company"] 		= 	$order->get_billing_company();
		
		$rval["billing_first_name"] 	=	$order->get_billing_first_name();
		$rval["billing_last_name"] 		= 	$order->get_billing_last_name();
		
		$rval["billing_address_1"] 		= 	$order->get_billing_address_1();
		$rval["billing_address_2"] 		= 	$order->get_billing_address_2();
		$rval["billing_city"] 			= 	$order->get_billing_city();
		$rval["billing_province"]		= 	$order->get_billing_state();
		$rval["billing_postcode"] 		= 	$order->get_billing_postcode();
		$rval["billing_country"] 		= 	$order->get_billing_country();
		$rval["billing_email"] 			= 	$order->get_billing_email();
		$rval["billing_phone"] 			= 	$order->get_billing_phone();
		$rval["order_total"]			= 	$order->get_subtotal();
		
		$items = [];
		// Get and Loop Over Order Items
		foreach ( $order->get_items() as $item_id => $item ) {
			$i = []	;	    
		    $i["product_name"] = $item->get_name();
		    $i["quantity"] = $item->get_quantity();
		    //$i["subtotal"] = $item->get_subtotal();
		    $i["total"] = $item->get_total();
		    
		    //$item_type = $item->get_type(); // e.g. "line_item", "fee"
			
			$items[] = $i;
		}
		
		$rval["items"] = $items;
		
		//write_log($rval);
		
		
		return $rval;
		
	} //getJsonOrder
	
	
	
} //class

new MSB_Dashboard($wpdb);
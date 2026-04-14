<?php 	
if(isset($_GET["mkt"]) ) {
	$mkt = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET["mkt"]);
}

$config = (new Config())->msb_config($mkt);

?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<title>Media Sales Blitz Dashboard</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>	
 
<!-- <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.2.0/dist/ag-grid-community.min.js"></script>	 -->
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community@34.2.0/dist/ag-grid-community.min.js"></script>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
	
const ajax_object = {
		ajax_url:"<?php echo admin_url('admin-ajax.php', 'https'); ?>", 
		security: "<?php echo wp_create_nonce( 'msb-dash-security-nonce' ); ?>",
	};

</script>
   
   <div class="container" style="background-color: white; padding:10px;">
	   <div class="row">
		   <div class="col p-3 d-flex justify-content-center">
			   <div style="max-width:400px;">
					<img class="img-fluid" src="<?php //echo $config["client_logo"]?>">	
		   		</div>			
			</div>
			<div class="col p-3 d-flex justify-content-center">
				<img class="img-fluid" src="<?php //echo $config["msb_logo"]?>">				
				
			</div>
	   		<br/>
		</div>
		<div class="pt-5"><h1 id="dash-title" class="d-flex justify-content-center"><?php echo $config['market_dash_title']; ?></h1> <br/></div>
		<div class="row"><div class="col d-flex align-items-end">
			<button id="hdb-refresh-btn" onclick="init_charts2();">Refresh</button>
			<span id="spinner" class="spinnerHide"></span>
			<span id="hdb-refresh-timer"></span> 
		</div></div>
		
		<div class="row">
			<div class="col p-3">
				<nav class="navbar navbar-expand-lg navbar-light bg-light">					
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
						<div class="navbar-nav">
						<a class="nav-item nav-link active" data-tabdiv="hdb-mainDash" onclick="return hideExcept(this);" href="#">Dashboard</a>
						<a class="nav-item nav-link" data-tabdiv="hdb-Orders" onclick="return hideExcept(this);" href="#">Orders</a>				
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-allProducts" onclick="return hideExcept(this);" >Sales By Station</a>		
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-abandonedCarts" onclick="return hideExcept(this);" >Abandoned Carts</a>							
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-allVisits" onclick="return hideExcept(this);" >Visits Details</a>							
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-orderEdit" onclick="return hideExcept(this);" >Orders Edit</a>
						
						<?php if(isset($config["all_markets_report"] )) {?>
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-allMarkets" onclick="return hideExcept(this);" >All Markets Sales</a>
						<?php } ?>

						<?php if(isset($config["SalesByTaxType"] )) {?>
						<a class="nav-item nav-link" href="#" data-tabdiv="hdb-salesByTaxType" onclick="return hideExcept(this);" >Sales By Group</a>
						<?php } ?>
						
						
					</div>
					</div>
				</nav>
			</div>
		</div>
		<div id="hdb-mainDash">
			<div class="row">
				<div class="col">
					<div class="shadow p-2 mb-3 bg-white rounded">
						<h4 class="d-flex justify-content-center">Sales Summary</h4>			
						<div id="dateSalesRefresh"></div>
						<div class="row p-4">													
							<div class="col-sm-3 p-3 justify-content-center">  <div class="chart" id="salesTotals_div"></div> 	</div>						
							<div class="col-sm-3 p-3 justify-content-center">  <div class="chart" id="salesCount_div"></div>    </div>
							<div class="col-sm-6 p-3 justify-content-center">  <div class="chart" id="salesProduct_div"></div>   </div>
						</div>	
					</div>
											
				</div>
			</div>
			<div class="row align-items-start">
				<div class="col-lg-6 col-med-12">
					<div class="shadow p-3 mb-3 bg-white rounded">
						<h4>Unique Businesses By Sales Rep</h4>
						<div id="dateVisitsPerson"></div>
						<div id="byVisitsPerson_div" class="chart"></div>
					</div>
				</div>
				<div class="col-lg-6 col-med-12">
					<div class="shadow p-3 mb-3 bg-white rounded">
						<h4>Sales By Rep</h4>
						<div id="dateSalesPerson"></div>
						<div id="bySalesPerson_div" class="chart"></div>
					</div>
				</div>
			</div>
			<div class="row">
			<!--
				<div class="col-lg-3 col-med-12">
					<div class="shadow p-3 mb-3 bg-white rounded">
						<h4>Pageviews</h4>						
						<div id="date"></div>						
						<div id="rt_div" class="chart"></div>
					</div>
				</div>
			-->
				<div class="col-12 ">
					<div class="shadow p-3 mb-3 bg-white rounded">
						<h4>Business Progress</h4>	
						<div class="hdb_circles">
							<div class="hdb_circleGroup">
							<div class="hdb_circle-with-text">
								V
							</div>Visit</div>
							<div class="hdb_circleGroup">
							<div class="hdb_circle-with-text">
								P
							</div>Product</div>
							<div class="hdb_circleGroup">
							<div class="hdb_circle-with-text">
								S
							</div>Shopping Cart</div>
							<div class="hdb_circleGroup">
							<div class="hdb_circle-with-text">
								C
							</div>Checkout</div>
							<div class="hdb_circleGroup">
							<div class="hdb_circle-with-text">
								O
							</div>Order</div>
						</div>									
						<div id="businessProgress_dashboard" class="chart">
							<div class="d-flex justify-content-center align-middle" id="salesRep_filter_BP_div"></div>											
							<div class="chart" id="businessProgress_div"></div>
						</div>
					</div>
				</div>
			</div>
		</div><!--hdb-mainDash-->
		
		<!--hdb-Orders-->
		<div id="hdb-Orders" class="shadow p-2 mb-3 bg-white rounded">	
			<h4>All Orders</h4>				
			<div class="row">
				<div class="col-6" id="dateOrdersRefresh" style="display: inline-block;">	</div>
				<div class="col-6" style="text-align: right;">
					<a class="btn btn-large btn-light" id="orders_export_btn" style="display: inline-block;">Export All Data</a>
				</div>
			</div>
			
			<div id="orders_dashboard_div" style="width:100%;" class="chart">					
				<div class="d-flex justify-content-center align-middle" id="salesRep_filter_orders_div"></div>																					
				<div class="chart d-flex justify-content-center" id="orders_summary_div"></div>						
				<!-- <h4>Order Detail</h4>
				<div id="order_detail_div" class="chart"></div> -->
			</div>
		</div> 
		<!--hdb-Orders-->

		<div id="hdb-allProducts" class="shadow p-2 mb-3 bg-white rounded">
			<h4>Sales by Station</h4>				
			
			<div class="row align-items-start">
				<div class="col-lg-12 col-med-12">					
					<div class="chart d-flex justify-content-center" id="allProducts_div"></div>																		
				</div>		
			</div>
		</div><!--hdb-allProducts-->

		<!--Abondoned Carts -->
		<div id="hdb-abandonedCarts" class="shadow p-2 mb-3 bg-white rounded">
			<h4>Abandoned Carts</h4>				
			
			<div id="abCarts_dashboard_div" style="width:100%;" class="chart">					
				<div class="d-flex justify-content-center align-middle" id="abCarts_filter_div"></div>																					
				<div class="chart d-flex justify-content-center" id="abCarts_div"></div>													
			</div>
		</div>
		<!-- hdb-allVisits -->
		<div id="hdb-allVisits" class="shadow p-2 mb-3 bg-white rounded">
			<h4>All Visits Details</h4>				
			<div id="standard_dashboard" style="width:100%;" class="chart">
				<div class="d-flex justify-content-center align-middle" id="salesRep_filter_div"></div>											
				<div class="chart" id="standard_div"></div>
			</div>
		</div>
		<div id="hdb-orderEdit" class="shadow p-2 mb-3 bg-white rounded">
			<h4>Orders Edit</h4>				
			<div id="ordersEdit_grid"  class="ag-theme-quartz" style="height: 535px; width:100%;" ></div>					 
		</div>
		
		<?php if(isset($config["all_markets_report"] )){ ?>
		<!--all markets sales summary -->
		<div id="hdb-allMarkets" class="shadow p-2 mb-3 bg-white rounded">
			<h4>All Markets Sales Summary</h4>				
			
			<div class="row align-items-start">
			<div class="col-12">
				<div id="hdb-totalDollars" class="d-flex flex-column m-4"></div>
			</div>
			<div class="col-lg-6 col-med-12">
				<div class="shadow p-3 mb-3 bg-white rounded">
					<h4>Dollars by Market</h4>
					<div class="chart d-flex justify-content-center mt-4" id="allMarketsDollars_div"></div>
				</div>
			</div>
			<div class="col-lg-6 col-med-12">
				<div class="shadow p-3 mb-3 bg-white rounded">
					<h4>Orders by Market</h4>
					<div class="chart d-flex justify-content-center mt-4" id="allMarketsOrders_div"></div>
				</div>
			</div>
			</div>
		</div><!--hdb-allMarkets-->
		<?php } ?>


		<!--Sales By Tax Type -->
		<!--I think this is done here, just need to add the new chart to JS file and test it.-->
		<?php if(isset($config["SalesByTaxType"] )){ ?>
		<!--Sales By Tax Type -->
		<div id="hdb-salesByTaxType" class="shadow p-2 mb-3 bg-white rounded">
			<h4>Sales By Grouping</h4>				
			
			<div class="row align-items-start">
				<div class="col-lg-12 col-med-12">			
					<div class="chart d-flex justify-content-center mt-4" id="taxTypeSales_div"></div>					
				</div>
			</div>
		</div>
		<?php } ?>


	</div><!--container-->
	

   <script>
	   				//prevent handling multiple resize events firing in rapid succession.  redraw only once.
	   function onWinResize(f){
			let resizeTimer;
			return function (event){
				if (resizeTimer) clearTimeout(resizeTimer);
				resizeTimer = setTimeout(f,250,event)
			};
	   }

	   	window.addEventListener('resize', onWinResize(function(e){
			//prevent redrawing when the soft keyboard is shown on mobile	
			if (document.activeElement.getAttribute('type') === 'text'){
					//do nothing;
				} 
				else {	//redraw all charts if window size changes.
					init_charts();
					//console.log("resize event after init charts");
				}
		   }));			
   </script>

   <!--Start of Tawk.to Script-->
	<script type="text/javascript">

	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/659ed50e8d261e1b5f51a0c2/1hjq70gj0';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();

	</script>
	<!--End of Tawk.to Script-->
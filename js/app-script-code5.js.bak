google.charts.load('current', {'packages':['corechart', 'bar', 'table', 'controls']});
google.charts.setOnLoadCallback(init_charts);

const urlParams = new URLSearchParams(window.location.search);
const market = urlParams.get('mkt');
const baseURL = window.location.origin;
const hidden_market = document.getElementById("hdb_dashmkt");
const shortcode_market = atob(hidden_market.value);
const defaultChartHeight = 600;


function getMarket(){
	let rval = shortcode_market;
	
	
	if(!shortcode_market){
		rval = market;
	}
	
	return rval;
}



async function getDashData(dataKey){			
	let market = getMarket();
	const formData = new FormData();
	formData.append('action', 'hdb_getDashData' ) ;
	formData.append('security', ajax_object.security);
	formData.append('dataKey', dataKey);
	formData.append('market', market);
		
	const response = await fetch(ajax_object.ajax_url, {method: 'POST', body:formData })
	
	const json = await response.json();
	
	//console.log(dataKey);
	
	return json;
}



// a child element will return true if it's parent is not visible
function isHidden(id) {
    const el = document.getElementById(id);
    
    if (!el) {
        return true; // Element not found, consider it hidden
    }

    return (el.offsetParent === null);
}

//makes the menu item active and shows the div, hides all other divs and makes all other menu items not active
function hideExcept(menuItem){			
    const navItems = document.querySelectorAll('.nav-item');			
    
    for (const ni of navItems){				
        const div = document.getElementById(ni.dataset.tabdiv);	
        
        if (ni === menuItem){					
            div.style.display = 'block';
            ni.classList.add("active");
        } else {					
            div.style.display = 'none';
            ni.classList.remove("active");
        }
    }

    init_charts();
}

/*---------------Orders-------------------------*/
var ordersTable;
function drawOrdersChart(data){
    ordersTable = new google.visualization.DataTable(data);

            
    const orderSummaryChart  = new google.visualization.ChartWrapper({
        'chartType': 'Table',
        'containerId': 'orders_summary_div',
        'options': {width:'100%', showRowNumber: true, 
        alternatingRowSytle: true, pageSize: 30, page: 'enable', 
        allowHtml: true, theme: 'material'}
    }); 
    
    const formatter = new google.visualization.NumberFormat({pattern:'#'});
    formatter.format(ordersTable, 0);

    const formatter2 = new google.visualization.NumberFormat({prefix:'$', fractionDigits:2});
    formatter2.format(ordersTable, 4);


    const orders_dashboard = new google.visualization.Dashboard(
        document.getElementById('orders_dashboard_div'));


    const sales_rep_filter_orders = new google.visualization.ControlWrapper({
        'controlType': 'CategoryFilter',
        'containerId': 'salesRep_filter_orders_div',
        'options': {
        'filterColumnIndex': 5,
        'ui': {'allowTyping':false, 'cssClass':'visitsFilter', 
        'allowMultiple':false, 'allowNone':true, 
        'caption':'All Sales Reps', 'label':'Filter By Sales Rep'}
        }
    });
    
    orders_dashboard.bind(sales_rep_filter_orders, orderSummaryChart);
    orders_dashboard.draw(ordersTable);
    //drawToolbar() ;
}



const orders_summaryDataChart = async () => {
                
    if (isHidden("orders_summary_div")){        
        return true;
    }

	const json = await getDashData('OrdersSummary');

    if (json.rows && json.rows.length > 0) {        
        drawOrdersChart(json);				
    }
	
}//orders_summaryDataChart


document.getElementById("orders_export_btn").onclick = function()  {
    let headerRow = "";
    let number_of_columns = ordersTable.getNumberOfColumns();
    for (let i=0; i < number_of_columns; i++) {
        headerRow += ordersTable.getColumnLabel(i).replace("\n", " : ");
        headerRow += (i === number_of_columns - 1) ? "\n" : ",";
    }        

    const csvData = headerRow + google.visualization.dataTableToCsv(ordersTable);
    //orderSummaryChart.dataTableToCsv();        
    const encodedUri = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csvData);
    this.href = encodedUri;
    this.download = 'msb_orders_data.csv';
    this.target = '_blank';
};

/*---------------Orders-------------------------*/


/*---------------Sales By Product Taxonomy-------------------------*/

function drawProductTaxonomyChart(data){
		
    const data_table = new google.visualization.DataTable(data);

        
    let chartHeight = (data_table.getNumberOfRows() * 31 ) +200;

    const viewProduct = new google.visualization.DataView(data_table );
    
    viewProduct.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" }]);
        
    const options = {
        title: "",  
        height: chartHeight, 	
        chartArea:{height:"85%"},
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
        tooltip: {
            isHtml: true, // Enables custom HTML for the tooltip
            textStyle: {
                fontSize: 14,
                color: '#333',
                bold: true,
            },
            showColorCode: true, // Displays the color of the bar in the tooltip
        },

        hAxis: {minValue:0, maxValue:50000, format:'$#,###', textStyle:{fontSize:13}},
        vAxis: {textStyle:{fontSize:13}},      
    } ;

    const table = new google.visualization.BarChart(document.getElementById('taxTypeSales_div'));
    
    table.draw(viewProduct, options);
}


const productTaxonomyChart = async () => {
    if (isHidden("taxTypeSales_div")){        
        return true;            
    }	        
    const json = await getDashData('SalesByProductTaxonomy');
        
    if (json.rows && json.rows.length > 0) {
        drawProductTaxonomyChart(json);				
    }//if
}//standardDataChart

/*------------^-Sales By Product Taxonomy-------------------------*/



/*---------------ALL MARKETS-------------------------*/

function drawAllMarketsChart(data) {
    const salesTable = new google.visualization.DataTable(data);

    let ordersTotal = 0;
    let dollarsTotal = 0;

    for (var i = 0; i < salesTable.getNumberOfRows(); i++) {
        ordersTotal += salesTable.getValue(i, 1);
        dollarsTotal += salesTable.getValue(i, 2);
    }
    
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0});

    totalDollarsDiv = document.getElementById("hdb-totalDollars");
    totalDollarsDiv.innerHTML = "<div class='d-flex justify-content-center'><div style='color:white; background-color:#118C4F; padding:20px 40px; text-align:center;'><b'>Total Dollars All Markets</b><br><h2 style='color:white;'>" + formatter.format( dollarsTotal)+"</h2></div></div>";
    

    const viewAllCount = new google.visualization.DataView(salesTable );
    const viewAllDollars = new google.visualization.DataView(salesTable );

    viewAllCount.setRows(salesTable.getSortedRows([{column:1, desc:true}]))
    viewAllCount.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" }]);


    viewAllDollars.setRows(salesTable.getSortedRows([{column:2, desc:true}]))
    viewAllDollars.setColumns([0, 2,
        { calc: "stringify",
            sourceColumn: 2,
            type: "string",
            role: "annotation" }]);



    const optionsAllCount = {
        title: "Total Orders by Market",  
        chartArea:{height:"85%"},
        height: defaultChartHeight,           
        bar: {groupWidth: "85%"},
        legend: { position: "none" },    
        hAxis: {minValue:0, maxValue:100},
        annotations: {
            textStyle: {
            fontSize: 15,
            }
        },
        vAxis: {textStyle:{fontSize:14}},     
        legend: { position: "none" }
    } ;

    const optionsAllDollars = {
        title: "Total Dollars by Market",    
        chartArea:{height:"85%"},
        height: defaultChartHeight, 
        bar: {groupWidth: "85%"},
        bars: 'horizontal',
        legend: { position: "none" },
        hAxis: {minValue:0, maxValue:100000, format:'$#,###'},
        annotations: {
            textStyle: {
            fontSize: 15,
            }
        },
        vAxis: {textStyle:{fontSize:14}},     
        legend: { position: "none" }
    };


    const AllCountChart = new google.visualization.BarChart(allMarketsOrders_div);
    const AllDollarsChart = new google.visualization.BarChart(allMarketsDollars_div);

    AllCountChart.draw(viewAllCount, optionsAllCount);
    AllDollarsChart.draw(viewAllDollars, optionsAllDollars);

}


const allMarketsData = async () => {
                    
    if (isHidden("allMarketsDollars_div")){        
        return true;
    }
    
    const json = await getDashData("AllMarkets");
    drawAllMarketsChart(json);	
    if (json.rows && json.rows.length > 0) {        
        drawAllMarketsChart(json);				
    }//if
}

/*---------------ALL MARKETS-------------------------*/


/*---------------Sales By Station / Products-------------------------*/

function drawAllSalesProductChart(data) {
    const salesTable = new google.visualization.DataTable(data);

    const viewProduct = new google.visualization.DataView(salesTable );
    
    let chartHeight = salesTable.getNumberOfRows() * 31;

    if (chartHeight < 800){
        chartHeight = 800;
    }

    viewProduct.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" }]);
        
    const optionsCount = {
        title: "",  
        height: chartHeight, 	
        chartArea:{height:"90%", left:"25%"},
        bar: {groupWidth: "50%"},
        legend: { position: "none" },
        annotations: {
            textStyle: {
                fontSize: 14, // Set the font size                
            },
        },
        tooltip: {
            isHtml: true, // Enables custom HTML for the tooltip
            textStyle: {
                fontSize: 14,
                bold: false
            },            
        },              
        hAxis: {minValue:0, maxValue:50000, format:'$#,###', textStyle:{fontSize:12}},
        vAxis: {textStyle:{fontSize:12}}
    } ;

    const productChart = new google.visualization.BarChart(allProducts_div);

    productChart.draw(viewProduct, optionsCount);
}

//no longer used, doing it all in SalesByProduct.
const allProductsDataChart = async () => {
                    
    if (isHidden("allProducts_div")){        
        return true;
    }

    //const response = await fetch(baseURL+`/dash/ga-report-salesProduct.php`+market);
    
    const json = await response.json();
    
    if (json.rows && json.rows.length > 0) {        
        drawAllSalesProductChart(json);				
    }//if
}

/*---------------Sales By Station-------------------------*/

/*---------------Main Dash-------------------------*/

    function drawSalesByPerson(data){
        const sTable = new google.visualization.DataTable(data);
        var view = new google.visualization.DataView(sTable );
        view.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" }]);
        view.setRows(sTable.getSortedRows([{column:1, desc:true}]))

        calcHeight = sTable.getNumberOfRows() * 31;

        if (calcHeight < defaultChartHeight){
            calcHeight = defaultChartHeight;
        }

        var options = {				
            height: calcHeight,
            width: '100%',				
            bar:{groupWidth: "65%"},
            legend: { position: "none" },				
            vAxis: { textStyle:{fontSize:12}},
            hAxis: {minValue:0, maxValue:20000, format:'short'}, //'$#,###'
            chartArea: {top: 15, bottom:20, left:125, right:40, width:'100%'}	            
        };
        var chart = new google.visualization.BarChart(document.getElementById("bySalesPerson_div"));
        chart.draw(view, options);

    }		  			  	

    const bySalesPersonDataChart = async () => {	        
        if (isHidden("bySalesPerson_div")){        
            return true;            
        }

		const json = await getDashData('SalesByPerson');
		
        if (json.rows && json.rows.length > 0) {        
            drawSalesByPerson(json);				
        }//if							
    }//bySalesPersonDataChart


    function drawVisitsByPerson(data){
        const sTable = new google.visualization.DataTable(data);
        var view = new google.visualization.DataView(sTable);
        view.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" }]);

        view.setRows(sTable.getSortedRows([{column:1, desc:true}]))

        calcHeight = sTable.getNumberOfRows() * 31;

        if (calcHeight < defaultChartHeight){
            calcHeight = defaultChartHeight;
        }


        var options = {				
            height: calcHeight,
            width: '100%',				
            bar:{groupWidth: "65%"},
            legend: { position: "none" },				
            vAxis: {textStyle:{fontSize:12}},
            chartArea: {top: 15, bottom:20, left:125,  right:40, width:'100%'}			
        };
        var chart = new google.visualization.BarChart(document.getElementById("byVisitsPerson_div"));
        chart.draw(view, options);

    }		  			  	
    

    const byVisitsPersonDataChart = async () => {	
        if (isHidden("byVisitsPerson_div")){        
            return true;            
        }		
		
		const json = await getDashData('VisitsByBusiness');
		
        if (json.rows && json.rows.length > 0) {
            drawVisitsByPerson(json);				
        }//if							
    }
            
    //not using right now, keeping around incase we go back to this.  Doing pageViews
    function drawRtChart(data) {			  
        var rtTable = new google.visualization.DataTable(data);
        rtTable.setColumnProperty(0,'style','width:30%');
        rtTable.setColumnProperty(1,'style','width:30%');
        rtTable.setColumnProperty(2,'style','width:40%');			
            
        var classicChart = new google.visualization.Table(rt_div);
        classicChart.draw(rtTable, {showRowNumber: true, alternatingRowSytle: true, width:'100%', 
            allowHtml: true });
    }
	

    function drawSalesProductChart(data) {
        const salesTable = new google.visualization.DataTable(data);

        const viewProduct = new google.visualization.DataView(salesTable );
        
        viewProduct.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation" }]);
		
		let maxRows = viewProduct.getNumberOfRows()-1;
				
		viewProduct.setRows(0,  (maxRows>9)?9:maxRows );
            
        const optionsCount = {
            title: "Top Stations Sales",  
            height: defaultChartHeight, 
            width: '100%',	
			chartArea:{height:"85%"},
            bar: {groupWidth: "65%"},
            legend: { position: "none" },
            //colors:['red'],
            hAxis: {minValue:0, maxValue:50000, format:'$#,###'},
            vAxis: {textStyle:{fontSize:12}},
            legend: { position: "none" }
        } ;
    
		const productChart = new google.visualization.BarChart(salesProduct_div);
    
        productChart.draw(viewProduct, optionsCount);
    }


    const productDataChart = async () => {
        
		if (isHidden("salesProduct_div") && isHidden("allProducts_div")){        
			return;
        }	
		
		const json = await getDashData('SalesByStation'); //await response.json();				
		
        if (json.rows && json.rows.length > 0) {

			if ( !isHidden("salesProduct_div")){        
				drawSalesProductChart(json);	
			}	
			
			if ( !isHidden("allProducts_div")){        
				drawAllSalesProductChart(json);		
			}
        	
        }//if
    }



    function drawSalesSummaryChart(data) {
        var salesTable = new google.visualization.DataTable(data);

        var viewCount = new google.visualization.DataView(salesTable );
        var viewDollars = new google.visualization.DataView(salesTable );
        viewCount.setColumns([0, 1]);
        viewDollars.setColumns([0, 2]);
        
        var optionsCount = {
            title: "Total Orders",  
			chartArea:{height:"85%"},
            height: defaultChartHeight,           
            bar: {groupWidth: "50%"},
            legend: { position: "none" },
            colors:['red'],
            vAxis: {minValue:0, maxValue:50},
            hAxis: { textPosition: 'none' },
            legend: { position: "none" }
        } ;

        var optionsDollars = {
            title: "Total Dollars All Sales",    
			chartArea:{height:"85%", left:"25%"},
            height: defaultChartHeight, 
            bar: {groupWidth: "50%"},
            legend: { position: "none" },
            colors:['#3366CC'], 
            vAxis: {minValue:0, maxValue:50000, format:'$#,###'},
            hAxis: { textPosition: 'none' },
            legend: { position: "none" }
        };
        
    
        var countChart = new google.visualization.ColumnChart(salesCount_div);
        var dollarsChart = new google.visualization.ColumnChart(salesTotals_div);
        countChart.draw(viewCount, optionsCount);
        dollarsChart.draw(viewDollars, optionsDollars);
    }

    const salesDataChart = async () => {
        if (isHidden("salesTotals_div")){        
            return true;            
        }	
        
        const json = await getDashData('TotalSales'); //await response.json();				
        
        if (json.rows && json.rows.length > 0) {            
            drawSalesSummaryChart(json);				
        }
    }//salesDataChart

    function get_time(){
        const now = new Date();
        return `${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
    }

    //All Visits To Sale
    function drawStandardChart(data){
		
        const standardTable = new google.visualization.DataTable(data);
        
        const classicChart  = new google.visualization.ChartWrapper({
            'chartType': 'Table',
            'containerId': 'standard_div',
            'options': {width:"100%",showRowNumber: true, alternatingRowSytle: true, pageSize: 30, page: 'enable', 
            allowHtml: true, theme: 'material'}
        });
        
        const std_tbl_dashboard = new google.visualization.Dashboard(
            document.getElementById(standard_dashboard));


        const std_cat_filter = new google.visualization.ControlWrapper({
            'controlType': 'CategoryFilter',
            'containerId': 'salesRep_filter_div',
            'options': {
            'filterColumnIndex': 0,
            'ui': {'allowTyping':false, 'cssClass':'visitsFilter', 'allowMultiple':false, 'allowNone':true,
             'caption':'All Account Managers', 'label':'Filter By Account Manager'}
            }
        });
                        
        std_tbl_dashboard.bind(std_cat_filter, classicChart);
        std_tbl_dashboard.draw(standardTable);
    }

    

    const standardDataChart = async () => {
        if (isHidden("standard_div")){        
            return true;            
        }	
				
		const json = await getDashData('AllVisitDetails');
		
		
        if (json.rows && json.rows.length > 0) {
            drawStandardChart(json);				
        }//if
    }//standardDataChart


///////////////////////

    //Business Progress
    function drawBusinessProgressChart(data){
        const bpTable = new google.visualization.DataTable(data);

        
        const view = new google.visualization.DataView(bpTable);
        view.setColumns([0,1,4,3])
                    
        const bpTableChart  = new google.visualization.ChartWrapper({
            'chartType': 'Table',
            'containerId': 'businessProgress_div',
            'options': {width:"100%",showRowNumber: true, alternatingRowSytle: true, pageSize: 30, page: 'enable', allowHtml: true, theme: 'material'}
        });
        
        const businessProgress_tbl_dashboard = new google.visualization.Dashboard(
            document.getElementById(businessProgress_dashboard));


        const bp_cat_filter = new google.visualization.ControlWrapper({
            'controlType': 'CategoryFilter',
            'containerId': 'salesRep_filter_BP_div',
            'options': {
            'filterColumnIndex': 0,
            'ui': {'allowTyping':false, 'cssClass':'visitsFilter', 'allowMultiple':false, 'allowNone':true, 'caption':'All Sales Reps', 'label':'Filter By Sales Rep'}
            }
        });
                        
        businessProgress_tbl_dashboard.bind(bp_cat_filter, bpTableChart);
        businessProgress_tbl_dashboard.draw(view);
    }



    const businessProgressDataChart = async () => {
        if (isHidden("businessProgress_div")){        
            return true;            
        }	
		
		const json = await getDashData('BusinessProgress');
        
        if (json.rows && json.rows.length > 0) {
            
            drawBusinessProgressChart(json);				
        }//if
    }

/////////////////////////

//lineChart pageViews
//repurposed the rt get data function
    function draw_pageViewsChart(data) {

        if(data["rows"].length <=1){
            return true;
        }
        
        try{
            const pvTable = new google.visualization.DataTable(data);

            const min = new Date(pvTable.getValue(0,0) );
            const tempMax = new Date(min.getTime()+ (10*60*60*1000));
            const max = new Date(pvTable.getValue(pvTable.getNumberOfRows()-1,0 ) > tempMax ?  pvTable.getValue(pvTable.getNumberOfRows()-1,0 ) : tempMax);
			/*
			console.log("raw fr table");
			console.log(pvTable.getValue(0,0) );
			console.log("as date");
			console.log(min);
			*/

            const options = {
                height: 250,
                //width: 600,
                legend: {position: 'none'},
                hAxis: {format: 'dd - HH:mm', slantedText:true, slantedTextAngle:65, viewWindow: {min: min, max: max}}   ,
                chartArea: {top: 15, bottom:85, left:45, right:15, width:'100%'}	                     
            };
            

            const chart = new google.visualization.LineChart(rt_div);
            chart.draw(pvTable, options);
        }
        catch (error){
            console.log(error);
        }
            
    }//draw_pageViewsChart
	
	
	//real time pageviews
    const rtDataChart = async () => {			
        if (isHidden("rt_div")){        
            return true;            
        }	

		const json = await getDashData('PageViews'); 
				
        if (json) {
            
			const rtDiv = document.getElementById("rt_div");
			rtDiv.innerHTML = "<h4 style='text-align:center'>" + String( json ).padStart(5, '0')+ "</h4>";
			//drawRtChart(json);
            //draw_pageViewsChart(json);
        }
    }//rtDataChart

    
//lineChart pageViews




/*---------------Main Dash-------------------------*/

/*---------------Abandoned Carts-------------------*/
  function draw_abCartsChart(data){
    const standardTable = new google.visualization.DataTable(data);
              
    const classicChart  = new google.visualization.ChartWrapper({
        'chartType': 'Table',
        'containerId': 'abCarts_div',
        'options': {width:"100%",showRowNumber: true, alternatingRowSytle: true, 
        pageSize: 30, page: 'enable', allowHtml: true, theme: 'material'}
    });
    
    const std_tbl_dashboard = new google.visualization.Dashboard(
        document.getElementById(abCarts_dashboard_div));


    const std_cat_filter = new google.visualization.ControlWrapper({
        'controlType': 'CategoryFilter',
        'containerId': 'abCarts_filter_div',
        'options': {
        'filterColumnIndex': 0,
        'ui': {'allowTyping':false, 'cssClass':'visitsFilter', 'allowMultiple':false, 'allowNone':true, 'caption':'All Sales Reps', 'label':'Filter By Sales Rep'}
        }
    });
                    
    std_tbl_dashboard.bind(std_cat_filter, classicChart);
    std_tbl_dashboard.draw(standardTable);
}

    const abandonedCartsDataChart = async () => {
        if (isHidden("abCarts_div")){      
            return true;            
        }	
		
		const json = await getDashData('AbandonedCarts');
       
		//console.log(json);
		
        if (json.rows && json.rows.length > 0) {            
            draw_abCartsChart(json);				
        }//if
    }//standardDataChart

/*---------------Abandoned Carts-------------------*/

/*---------------Orders Edit Grid------------------*/
	
class PopupCellRenderer {
  constructor() {
    this.isOpen = false;
    this.eMenu = null;
    this.tippyInstance = null;
  }

  init(params) {
    this.params = params;
    this.eGui = document.createElement('div');

    this.eActionButton = document.createElement('button');
    this.eActionButton.innerHTML = 'details';
    this.eActionButton.setAttribute('data-action', 'toggle');
    this.eActionButton.classList.add('hdb_popUp_btn');

    this.tippyInstance = tippy(this.eActionButton);
    this.tippyInstance.disable();

    this.eGui.appendChild(this.eActionButton);
  }

  togglePopup(data) {
    this.isOpen = !this.isOpen;
    if (this.isOpen) {
		this.tippyInstance.enable();    
		this.configureTippyInstance(data);
	  
      
	  
		
	  
		this.tippyInstance.show();
	  
    } else {
      this.tippyInstance.unmount();
    }
  }

  configureTippyInstance(data) {
    
	
    this.tippyInstance.setProps({
    trigger: 'manual',
	animateFill: false,
	animation: 'fade',
	
	flipOnUpdate: true,
	placement: 'right',
	content: 'loading...',
	arrow: false,
	interactive: true,
	allowHTML: true,
	appendTo: document.body,
	hideOnClick: false,
	
	onShow: (instance) => {
		tippy.hideAll({ exclude: instance });
		
		if (instance.state.ajax === undefined) {
			instance.state.ajax = {
				isFetching: false,
				canFetch: true,
			}
		}
		
		if (instance.state.ajax.isFetching || !instance.state.ajax.canFetch) {
			return;
		}
		
		this.getOrderDetails(data);  //assigns value to eMenu and set the content of the popUp
		instance.state.ajax.isFetching = false;
    },
	 
	onHidden(instance) {
		instance.setContent('Loading...')
	},
    
	onClickOutside: (instance, event) => {
       this.isOpen = false;
       instance.unmount();
     },
    });
	
    
  }

  getOrderDetails(params){
	  
		let orderID = params["Order ID"];
		
		//console.log('get order details: ' + orderID);			
		const getData = async () => {		
			const formData = new FormData();
			formData.append('action', 'hdb_get_order_details' ) ;
			formData.append('security', ajax_object.security);
			formData.append('orderID', orderID );
			formData.append('market', getMarket() );
					
			const response = await fetch(ajax_object.ajax_url, {method: 'POST', body:formData });
			
			const data = await response.json();
			//console.log(data);
			this.eMenu = this.createPopUpWithData(data);
			this.tippyInstance.setContent(this.eMenu);
		}
		
		getData();
	}


	createPopUpWithData(data) {
		let popContent = document.createElement('div');
		popContent.classList.add('hdb-popup-container');

		let htitle = document.createElement('h4');
		htitle.innerText = data["order_id"] + " - " + data["billing_company"] ;
		popContent.append(htitle);


		let table = document.createElement('table');
		table.classList.add('hdb-popup-table');

		this.generateTable(table, data);

		popContent.append(table);

		return popContent;
	}



	formatDollars(json){
		const formatter = new Intl.NumberFormat('en-US', {
		  style: 'currency',
		  currency: 'USD',
		  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
		  maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
		});
		
		json["order_total"] = formatter.format(json["order_total"]);
		
		for (var i = 0; i < json["items"].length; i++) {
			json["items"][i].total = formatter.format(json["items"][i].total);
		}
	
		return json;
	}

	
	formatKey(key){
		rval = key.replace(/_/g, " ");
		return rval;		
	}
  
	generateTable(table, data) {
		const toskip = ['order_id'];
		
		//let thead = table.createTHead();
		//let row = thead.insertRow();
		
		let colgr = document.createElement("colgroup");
		
		let th = document.createElement("col");
		th.classList.add('hdb-popup-tableH-keys');
		let th2 = document.createElement("col");
		th.classList.add('hdb-popup-tableH-values');
		
		colgr.appendChild(th);
		colgr.appendChild(th2);
		
		table.appendChild(colgr);

		data = this.formatDollars(data);
		
		for (let key in data) {
			
			/*
			if(toskip.indexOf(key) != -1){
				continue;
			}
			*/
		
			let row = table.insertRow();		
			let cellKey = row.insertCell();
			let cellValue = row.insertCell();
			  
			cellKey.appendChild(document.createTextNode( this.formatKey(key) ));
			cellKey.classList.add('hdb-table-key');
			
			let list = document.createElement("ol");
			
			if(key === "items") {
				for (let i = 0; i < data["items"].length; i++){
					let li = document.createElement('li');
					let bold = document.createElement('b');
					bold.append(data["items"][i].product_name);
					li.append(bold);
					li.append(document.createElement('br') );
					li.append("Quantity: " + data["items"][i].quantity + " - Total: " + data["items"][i].total  );
					list.appendChild(li);
					list.classList.add('hdb-item-list');
				}
				cellValue.appendChild(list);
			} else {
				cellValue.appendChild(document.createTextNode(data[key]));				
			}
			cellValue.classList.add('hdb-table-value');	
		}
	
	}

  

  menuItemClickHandler(event) {
    this.togglePopup();
    const action = event.target.dataset.action;
    if (action === 'create') {
      this.params.api.applyTransaction({
        add: [{}],
      });
    }
    if (action === 'delete') {
      this.params.api.applyTransaction({ remove: [this.params.data] });
    }

    if (action === 'edit') {
      this.params.api.startEditingCell({
        rowIndex: this.params.rowIndex,
        colKey: 'make',
      });
    }
  }

  getGui() {
    return this.eGui;
  }
} //popUpCellRenderer Class


//custom cell editor for sales rep column search, replaces dropdown
class SearchCellEditor {
   init(params) {
    this._allValues = params.values || [];
    const listId = 'repList';
    const minChars = 2;

    this.eInput = document.createElement('input');
    this.eInput.setAttribute('list', listId);
    this.eInput.value = params.value || '';
    this.eInput.style.cssText = 'width:100%;height:100%;border:none;outline:none;padding:4px;';

    this.eDataList = document.createElement('datalist');
    this.eDataList.id = listId;

    this.eInput.addEventListener('input', () => {
        this.eDataList.innerHTML = '';
        if (this.eInput.value.length >= minChars) {
            this._allValues.forEach(val => {
                const opt = document.createElement('option');
                opt.value = val;
                this.eDataList.appendChild(opt);
            });
        }
    });

    this.eInput.addEventListener('change', () => {
        if (this._allValues.includes(this.eInput.value)) {
            params.api.stopEditing();
        }
    });

    this.eInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter')  params.api.stopEditing();
        if (e.key === 'Escape') params.api.stopEditing(true);
    });

    this.eGui = document.createElement('div');
    this.eGui.style.cssText = 'width:100%;height:100%;';
    this.eGui.appendChild(this.eInput);
    this.eGui.appendChild(this.eDataList);
    }

    afterGuiAttached() {
        this.eInput.focus();
        this.eInput.select();
    }

    getValue() { return this.eInput.value; }
    getGui()   { return this.eGui; }
}


	let gridApi;
	let repLookUp = {};
	
	
	const formatter = new Intl.NumberFormat('en-US', {
	  style: 'currency',
	  currency: 'USD',
	  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
	  maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
	});
	
	const gridOptions = {
		defaultColDef: {sortable: true, filter: true, editable: false, resizable:true, enableCellChangeFlash:true, 
			headerClass: 'ag-center-aligned-header', flex:1, minWidth:100, 
			},
		columnDefs: [
			{
				headerName: '',
				colId: 'details',
				cellRenderer: PopupCellRenderer,
				pinned: 'left',
				editable: false,
				maxWidth: 150,
			},

			{
				field: "Order ID",
			},
			{
				field: "Company Name",
			},
			{
				field: "Contact Name",
			},
			{
				field: "Order Value",
				valueFormatter: (params) => { return formatter.format(params.value); },
				cellClass: 'ag-right-aligned-cell',
                //type: 'numericColumn',
				// just the number right aligned, not the heaer:  cellClass: 'ag-right-aligned-cell',
			},
			{
				field: "Sales Rep",
				editable: true,
				width: 150,				
				cellEditor:  SearchCellEditor, //'agSelectCellEditor',
				cellEditorParams:{values: Object.values(repLookUp), valueListMaxWidth: 150,}, 
				cellEditorPopup: false,	
				valueGetter: vgSalesRep,
				valueSetter: vsSalesRep,
				singleClickEdit: true,
				onCellValueChanged: onCellValueChanged,
			},
		],
		getRowId: params => params.data["Order ID"],
		
		rowSelection: {mode: "singleRow", checkboxes:false}, 

        theme:  'legacy',  
        		
		onCellClicked: (params) => {
			if (
			  params.event.target.dataset.action == 'toggle' &&
			  params.column.getColId() == 'details'
			) {
			  const cellRendererInstances = params.api.getCellRendererInstances({
				rowNodes: [params.node],
				columns: [params.column],
			  });
			  if (cellRendererInstances.length > 0) {
				const instance = cellRendererInstances[0];
				instance.togglePopup(params.data);
			  }
			}
		  },
		
		
	};
	
	async function onCellValueChanged(event){
		const eventdata = event.data;			
		//console.log('onCellChange: ', event);			
		const eventDataID = eventdata["Order ID"];
		
		const formData = new FormData();
		formData.append('action', 'hdb_update_order' ) ;
		formData.append('security', ajax_object.security);
		formData.append('jdata', JSON.stringify(eventdata) );
		formData.append('market', getMarket() );
				
		const data = await fetch(ajax_object.ajax_url, {method: 'POST', body:formData })				
			.then((response) => {
				if(!response.ok){					
					//console.log("Status: " + response.status, response.statusText);	
					//console.log("data: ", response.data);
					throw new Error("HDB Network response was not OK");
				}
				return response.json();
			}).catch((error) => {
				console.error("Error: ", error);
			});
		
		const rowNode = gridApi.getRowNode(eventDataID);
		//console.log("on cell changed: ");
		//console.log(data);
		rowNode.updateData(data);

	}
	
	function vgSalesRep(params){
		repID = params.data["Sales Rep"];		
		
		rval = repLookUp[repID];
		
		return rval;
	}
	
	function vsSalesRep(params){
		//console.log("vs newValue - oldValue", params.newValue, params.oldValue);
		
		const repID = getKeyByValue(repLookUp, params.newValue);
		
		//console.log("valueSetter repID: ", repID);
		
		params.newValue = repID;  //convert the name into a repID before saving
		
		params.data["Sales Rep"] = repID;
		
		return true;
	}
	
	function getKeyByValue(object, value) {
		return Object.keys(object).find(key => object[key] === value);
	}
	
	gridApi = agGrid.createGrid(document.getElementById("ordersEdit_grid"), gridOptions);	
	
	const ordersEditGrid = async () => {
			if (isHidden("ordersEdit_grid")){      
				return true;            
			}	
			
			const json = await getDashData('OrdersEdit');
			
			if (json.rowData && json.rowData.length > 0) {            
				populateGrid(json);				
			}
		}

	function populateGrid(data){
		const colDefs = gridApi.getColumnDefs();
		
		gridApi.setGridOption('rowData', data['rowData']);
		repLookUp = data['salesReps'];
		
		colDefs[5].cellEditorParams.values = Object.values(repLookUp);
		
		gridApi.setGridOption('columnDefs', colDefs);
		
	}

/*---------------Orders Edit Grid------------------*/



let refreshInterval = 900000;




function resetRefreshCounter(interval){
    
	let span = document.getElementById("hdb-refresh-timer");

	let currentdate = new Date(); 
	let datetime = "Last refresh: " + currentdate.getFullYear() + "-"
                + String(currentdate.getMonth()+1).padStart(2, '0')  + "-" 
                + String(currentdate.getDate()).padStart(2, '0') + " "  
                + String(currentdate.getHours()).padStart(2, '0') + ":"  
                + String(currentdate.getMinutes()).padStart(2, '0') + ":" 
                + String(currentdate.getSeconds()).padStart(2, '0');
				
	span.innerText = datetime;	
    //hdbToggleSpinner();
}


    function init_charts(){
        standardDataChart();
        salesDataChart();
        productDataChart();
		byVisitsPersonDataChart();
		bySalesPersonDataChart();
		businessProgressDataChart();
		orders_summaryDataChart();       
		//rtDataChart();
		abandonedCartsDataChart();
		ordersEditGrid();
        allMarketsData();
        productTaxonomyChart();
		//console.log("init_charts");
        //allProductsDataChart();
        resetRefreshCounter(refreshInterval);
    }

    function hdbToggleSpinner() {
        //let span = document.getElementById("hdb-refresh-timer");
        let spinner = document.getElementById("spinner");	
        
        if (spinner.classList.contains("spinnerShow")) {
            spinner.classList.replace("spinnerShow", "spinnerHide");		  		
        } else {
            spinner.classList.replace("spinnerHide", "spinnerShow");
        }
    }

    const delay = ms => new Promise(res => setTimeout(res, ms));
    async function init_charts2(){
        hdbToggleSpinner();        
        await delay(1000);
        init_charts();
        hdbToggleSpinner();
    }



// this is the equivilant to jQuery's document.ready()
document.addEventListener("DOMContentLoaded", () => {
    
	setInterval(init_charts, refreshInterval); //~15 mins
});

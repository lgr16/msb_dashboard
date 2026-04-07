<?php
require_once('ga_reports_common.php');
// connect to analytics DB, grab data based on config settings (date, market, etc)
// insert into lookup tables in the correct blog hdb tables

/**********
* Change Log:
*	v1.1: changed Insert to Insert Ignore to handle data that is too long coming from Analytics when I try to put it in std report table
* 	v1: modified code from working chemicloud version to cloudways compatible can't insert select from one db to another in Cloudways
*********/

//php /home/1380717.cloudwaysapps.com/fhbgsphphj/public_html/wp-content/plugins/msb_dashboard/msbmatAnalytics.php

//$dashConfigPath = ABSPATH. "/wp-content/plugins/msb_dashboard/ga_reports_common.php";
//require_once($dashConfigPath);

$configObj = new Config();

$pdoBlog = require( 'dbconnect.php');

$pdoAnalytics = require( 'analytics_connect.php');

$config = $configObj->msb_config("base");

$activeMarkets = $config["active_markets"];
$isTest = False;

// NOT USING: this works great but going the "insert into" direction for even better performance
$sqla = <<<sqla
SELECT      
    a.idlink_va as id,      
    v.campaign_source as source,
    v.campaign_medium as medium,
    substring(la.name, locate("/", la.name)) as page,
    a.server_time,    
    v.campaign_name as campaign    
FROM
    rctncdtaef.mt2v_log_visit AS v
        LEFT JOIN
    rctncdtaef.mt2v_log_link_visit_action AS a ON v.idvisit = a.idvisit
        LEFT JOIN
    rctncdtaef.mt2v_log_action AS la ON la.idaction = a.idaction_url
WHERE
    v.idsite = :siteID AND la.type = 1 and a.server_time > :orderDate
ORDER BY
    a.server_time asc;
sqla;


//for test data
function getPage($pageNo=-1){
    $all = [4=>"/checkout/order-received/", 3=>"/checkout/", 2=>"/cart/", 1=>"/product/", 0=> "/page/"];
    $i = ($pageNo > -1) ? $pageNo : rand(0,4);
    return $all[$i];
}


function getTestData(){
    $test = [];
    $iter = 0;
    $startDate = time();
    $endDate = strtotime("-9 hours");

        //saleReps
    for($i=0; $i<4; $i++) {
        
        $s = rand(41, 44);

            //businesses
        for($j=0; $j<10; $j++) {
            $m = rand(2014, 2038);
            $numPages = rand(0,4);
            $datetime = rand($endDate, $startDate);
                //pages visited
            for($k=0; $k<$numPages+1; $k++) {
                $p = getPage($k);                
                $datetime += 10+$k;
                $t = date("Y-m-d H:i:s", $datetime);
                $c = "testDataTwo";
                $test[] = [$iter++, "$s", "$m", $p, $t, "$c"];
            }
        }
    }
    return $test;
}



//Originally: only using this for test data, not using the sql query to grab data from the analytics tables from here, using insert into query sql3 for that
//---NOW--- this is the main getter of data from Matomo
function getAnalyticsData($date, $siteID){
    global $pdoAnalytics, $isTest, $sqla;

    $rval = [];
    if ($isTest){
        $rval = getTestData();
        
    } else {
		$stmta = $pdoAnalytics->prepare($sqla);
		
        $stmta->execute([':orderDate'=> $date,  ':siteID'=> $siteID ]); 
        $rval = $stmta->fetchAll(PDO::FETCH_NUM);
    }
    return $rval;
}

// TESTED 10K inserts, works fine: <b>Total Execution Time:</b> 0.13603401184082 seconds
function saveAnlyticsToBlog($data, $blogPrefix){
    global $pdoBlog;
    $sql = "INSERT IGNORE INTO %shdb_std_report (`id`,`rep_name`,`business`,`page`,`dateHourMinute`,`market`) VALUES ";
    $v = "(?,?,?,?,?,?)";

    $sql = sprintf($sql, $blogPrefix);
    $sql .=  str_repeat("$v,", count($data) - 1) . "$v";   

    $statement = $pdoBlog->prepare($sql);
    $statement->execute(array_merge(...$data));
	return  $statement->rowCount();
}



$time_start = microtime(true); 

$sql3 = <<<sql3
INSERT IGNORE INTO %shdb_std_report (id, rep_name, business, page, dateHourMinute, market) 
SELECT        
    a.idlink_va as id,    
    v.campaign_source as source,
    v.campaign_medium as medium,
    substring(la.name, locate("/", la.name)) as page,
    a.server_time,    
    v.campaign_name as campaign    
FROM
    rctncdtaef.mt2v_log_visit AS v
        LEFT JOIN
    rctncdtaef.mt2v_log_link_visit_action AS a ON v.idvisit = a.idvisit
        LEFT JOIN
    rctncdtaef.mt2v_log_action AS la ON la.idaction = a.idaction_url
WHERE
    v.idsite = :siteID AND la.type = 1 and a.server_time  > :orderDate
ORDER BY
    a.server_time asc;
sql3;

$sqlTrunc = "TRUNCATE TABLE %shdb_std_report";

//this looks good to replace orderDate, should be whichever is greater orderDate or Max
//wouldn't need to trunc the table anymore
$sqlMax = "SELECT MAX(dateHourMinute) FROM %shdb_std_report;";

try {

    $progress = "\nStarting... ";
    date_default_timezone_set("America/Edmonton");
    $d = date("Y-m-d H:i:s"); 
    $n = "\n";
	

	//TODO: need to change this loop to be unique site IDs, pre loop gets siteIDs then final loop does the insert per siteID
	//many markets can all be part of one site id.
    foreach($activeMarkets as $market){
		$progress .= $market .$n;
		
		$config = $configObj->msb_config($market);
    	//$config = msb_config($market);
        $startDate = $config["order_date"];
        $siteID = $config["siteID"];
        $blogPrefix = $config["blogPrefix"];

        //$sqlInsert = sprintf($sql3, $blogPrefix);   //Cloudways is being dificault about grants so can't do insert and select in one go
        
		$sqlMaxDate =  sprintf($sqlMax, $blogPrefix);		

        $progress .= sprintf("%s - start: %s - siteID: %s - blogPrefix: %s - %s", $d, $startDate, $siteID, $blogPrefix, $n);

		$stmtMax =  $pdoBlog->prepare($sqlMaxDate);
        $stmtMax->execute();
		$maxStdReport = $stmtMax->fetchColumn();
		
			//handling edge case where source table is empty		
		if(empty($maxStdReport)){
			$maxStdReport = "1970-01-01";
		}
		
		
		$dtConfig 	= strtotime($startDate);
		$dtMax		= strtotime($maxStdReport);
		$queryDate = "";
		if ($dtMax > $dtConfig){
			$queryDate = $maxStdReport;
		} else {
			$queryDate = $startDate;
		}
        
		$progress .= sprintf("startDate: %s - maxStdReport: %s - queryDate: %s %s", $startDate, $maxStdReport, $queryDate, $n);
		
		/*  not truncing any more just doing from maxdate onwards
		$stmtTruncate =  $pdoBlog->prepare($sqlTruncate);
        $stmtTruncate->execute();
		*/
		

        $progress .= "Max Date Set \n";

        if($isTest){
            $d = getAnalyticsData($startDate, $siteID);
            saveAnlyticsToBlog($d, $blogPrefix);
            $progress .= "Test Data Complete \n";
			
        } else {
			
			$newAnalyticsData = getAnalyticsData($queryDate, $siteID);
			
				//handling case where there is no new analytics data
			if(count($newAnalyticsData) < 1){
				$rowCount = 0;
			} else {
				$rowCount = saveAnlyticsToBlog($newAnalyticsData, $blogPrefix);
			}
			
			
			
			/* this is great if we can get the permissions in Cloudways to do it like this.
            $stmtInsert = $pdoBlog->prepare($sqlInsert);
            $stmtInsert->execute([':orderDate'=> $queryDate,  ':siteID'=> $siteID ]);   //$startDate
			$rowCount = $stmtInsert->rowCount();
			*/
            $progress .= "INSERT INTO Complete: ". $rowCount ." \n";
        }
    }

    $time_end = microtime(true); 

    $execution_time = ($time_end - $time_start);

    //execution time of the script
    echo 'Total Execution Time:  '.$execution_time.' seconds';
    $progress .= "Total Execution Time:  $execution_time seconds \n\n";
	
} finally {
    
    file_put_contents("/home/master/applications/fhbgsphphj/private_html/analyticsImport.log", $progress, FILE_APPEND | LOCK_EX);
}


















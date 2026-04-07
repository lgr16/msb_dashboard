<?php

class Config { 

	private $base_config = array(	
		"market_ga_campaign"    => "",
		"order_date"            => "2024-10-30",
		"market_dash_title"     => "Dashboard",
		"fromTZ"                => "+00:00",
		"msb_logo"				=> "../wp-content/uploads/sites/2/2022/04/MsBlogo-sm-1.png",
		"client_logo"			=> "../wp-content/uploads/sites/2/2022/04/MsBlogo-sm-1.png",
		"active_markets"		=>  ["NL","EagleMedia", "P1 Learning", "demo" ], 
		"market_ga_campaign"	=> "testME2",
		"repsSeeOpenAccountsInOwnList" => 1,	
		"NewsLetter_LinkField"	=> 1
	);

	public function __construct() 
    {
		
	}

	function msb_config($market){
		
		$config = array_merge([], $this->base_config);
		
		switch ($market) {	
		
	case "P1 Learning":
			$config["order_date"]   = "2025-03-02";
            $config["ManagersList"]	= array(1,114,3);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	=  1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://p1learning.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "P1 Learning One Day Sale";
            $config["blogPrefix"] = 'wpmsb_20_';  
			$config["siteID"] = 23;  
            $config["market_ga_campaign"] = "p1c2";
			//$config["ga_campaigns"] = array("stingray-sudb-c2", "stingray-sudb-c1"); 
            $config["market_products_lookup"] = array();
            $config["toTZ"]             = "-06:00";
			$config["client_logo"]		= ""; 			
            break;     
	case "IPG":
			$config["order_date"]   = "2024-06-19";
            $config["ManagersList"]	= array(1,3,114);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://ipg.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Island Parent Group Sales Blitz!";
            $config["blogPrefix"] = 'wpmsb_9_';  
			$config["siteID"] = 12;  
            $config["market_ga_campaign"] = "ipg-c1";
			//$config["ga_campaigns"] = array("stingray-abnm-c3", "fgvatebl-bcaz-d6"); 
            $config["market_products_lookup"] = array(										
					"Island Parent Cover Ads" => "IP Cover", 
					"Island Parent Full Page Ads" => "IP Full Page", 
					"Grand Cover Ad" => "Grand Cover", 
					"Island Parent 2/3 Page Ads" => "IP 2/3", 
					"Island Parent 1/2 Page Ads" => "IP 1/2", 
					"Island Parent 1/3 Page Ads" => "IP 1/3", 
					"Island Parent 1/4 Page Ads" => "IP 1/4", 
					"Westcoast Families Cover Ads" => "WCF Cover", 
					"Westcoast Families Full Page Ads" => "WCF Full Page", 
					"Westcoast Families 2/3 Page Ads" => "WCF 2/3", 
					"Westcoast Families 1/2 Page Ads" => "WCF 1/2", 
					"Westcoast Families 1/3 Page Ads" => "WCF 1/3", 
					"Westcoast Families 1/4 Page Ads" => "WCF 1/4", 
					"Volunteer Matters Cover Ads" => "VM Cover", 
					"Volunteer Matters Full Page Ads" => "VM Full", 
					"Volunteer Matters 2/3 Page Ads" => "VM 2/3", 
					"Volunteer Matters 1/2 Page Ads" => "VM 1/2", 
					"Volunteer Matters 1/3 Page Ads" => "VM 1/3", 
					"Grand Full Page Ad" => "Grand Full", 
					"Grand 1/2 Page Ad" => "Grand 1/2", 
					"Grand 1/3 Page Ad" => "Grand 1/3", 
					"Grand Business Profile" => "Grand Profile", 
					"Island Parent Family Resource Guide Cover Ad" => "IP RG Cover", 
					"Island Parent Family Resource Guide Full Page Ad" => "IP RG Full", 
					"Island Parent Family Resource Guide 2/3 Page Ad" => "IP RG 2/3", 
					"Island Parent Family Resource Guide 1/2 Page Ad" => "IP RG 1/2", 
					"Island Parent Family Resource Guide 1/3 Page Ad" => "IP RG 1/3", 
					"Island Parent Family Resource Guide 1/4 Page Ad" => "IP RG 1/4", 
					"Island Parent Family Resource Guide Enhanced Listing" => "IP RG Listing", 
					"Westcoast Families Family Resource Guide Cover Ad" => "WCF RG Cover", 
					"Westcoast Families Family Resource Guide Full Page Ad" => "WCF RG Full", 
					"Westcoast Families Family Resource Guide 2/3 Page Ad" => "WCF RG 2/3", 
					"Westcoast Families Family Resource Guide 1/2 Page Ad" => "WCF RG 1/2", 
					"Westcoast Families Family Resource Guide 1/3 Page Ad" => "WCF RG 1/3", 
					"Westcoast Families Family Resource Guide 1/4 Page Ad" => "WCF RG 1/4", 
					"Westcoast Families Family Resource Guide Enhanced Listing" => "WCF RG Listing", 
					"Island Parent Tweens and Teens Full Page Ad" => "TT Full", 
					"Island Parent Tweens and Teens Branded Content" => "TT Branded", 
					"Island Parent Tweens and Teens Half Page Ad" => "TT Half", 
					"Volunteer Matters Cover Ads" => "VM Cover", 
					"Volunteer Matters 1/4 Page Ads" => "VM 1/4", 
					"Grand Inside Cover Ad" => "Grand Inside Cover"
			);					
            $config["toTZ"]             = "-07:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
            break;     
		case "Sudbury":
			$config["order_date"]   = "2024-10-30";
            $config["ManagersList"]	= array(1,114,3, 71);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	=  1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://sudbury-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Sudbury One Day Sale";
            $config["blogPrefix"] = 'wpmsb_13_';  
			$config["siteID"] = 17;  
            $config["market_ga_campaign"] = "stingray-sudb-c3";
			//$config["ga_campaigns"] = array("stingray-sudb-c2", "stingray-sudb-c1"); 
            $config["market_products_lookup"] = array(										
					"Hot 93.5 - 1 week of commercials (28 ads per week)" => "Hot 93.5 - 1 week",
					"Rewind 103.9 - 1 week of commercials (28 ads per week)" => "Rewind 103.9 - 1 week",
					"52 weeks with 21 commercials per week and 500,000 Total Digital Impressions" => "52 weeks + 500k Digital",
					"Complete Stingray Audio Solution" => "Complete Stingray Audio Solution"
					);
					//"67,000 Digital Audio Impressions - Sudbury" => "67K Digital Audio", ;
            $config["toTZ"]             = "-05:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;     
		case "Fort St. John":  //moose media
			$config["order_date"]   = "2025-01-29";
            $config["ManagersList"]	= array(1,114,3, 127);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://moosemedia.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Moose Media One Day Sale";
            $config["blogPrefix"] = 'wpmsb_11_';  
			$config["siteID"] = 14;  
            $config["market_ga_campaign"] = "mm-c1";
			//$config["ga_campaigns"] = array("mm-c2", "mm-c1"); 
            $config["market_products_lookup"] = array(
				"Long Term Campaign – 21 x 30 Second Ads per Week" => "Long Term", 
				"Short Term Campaign – 100 x 30 Second Bundle" => "Short Term 100x30",
				"30-second-ads-short-term-campaign" => "Short Term"
			);					
            $config["toTZ"]             = "-07:00";
			$config["client_logo"]		= "http://moosemedia.mediasalesblitz.com/wp-content/uploads/sites/11/2024/08/Moose-Media-Logo-Horz-Tag-RGB-1536x465-1.png"; 
            break;  
		case "PEI":  
			$config["order_date"]   = "2024-10-30";
            $config["ManagersList"]	= array(1,114,3, 171);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://pei-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Prince Edward Island One Day Sale";
            $config["blogPrefix"] = 'wpmsb_14_';  
			$config["siteID"] = 18;  
            $config["market_ga_campaign"] = "pei-c1";
			//$config["ga_campaigns"] = array("mm-c2", "mm-c1"); 
            $config["market_products_lookup"] = array(
			"50 x 30 Second Commercials on Ocean 100" => "50 Ocean 100",
"50 x 30 Second Commercials on HOT 105.5" => "50 HOT 105.5",
"50 x 30 Second Commercials on BOTH P.E.I. Stations (100 Total Commercials)" => "50 on BOTH",
"Christmas Greetings on Ocean 100 &amp; HOT 105.5" => "Christmas Greetings"				
			);					
            $config["toTZ"]             = "-04:00";
			$config["client_logo"]		= "";
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;  
		case "Ottawa":  
			$config["order_date"]   = "2024-10-30";
            $config["ManagersList"]	= array(1,114,3, 167, 120);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://ottawa-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Ottawa One Day Sale";
            $config["blogPrefix"] = 'wpmsb_15_';  
			$config["siteID"] = 19;  
            $config["market_ga_campaign"] = "ott-c1";
			//$config["ga_campaigns"] = array("mm-c2", "mm-c1"); 
            $config["market_products_lookup"] = array(
			"Live 88.5 – 1 week of commercials (50 ads)" => "Live 88.5 – 1 week",
			"Hot 89.9 – 1 week of commercials (50 ads)" => "Hot 89.9 – 1 week",
			"1 week of commercials on BOTH stations (50 ads on Live 88.5 and Hot 89.9)" => "Hot and Live 1 week"
			);					
            $config["toTZ"]             = "-05:00";
			$config["client_logo"]		= "";
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;  
		case "BC":  
			$config["order_date"]   = "2024-10-30";
            $config["ManagersList"]	= array(1,114,3,154,155);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://bc-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "BC Interior One Day Sale";
            $config["blogPrefix"] = 'wpmsb_17_';  
			$config["siteID"] = 20;  
            $config["market_ga_campaign"] = "bc-c2";
			$config["ga_campaigns"] = array("bc-c2", "ott-c1"); 
            $config["market_products_lookup"] = array(
			"50 x 30 Second Commercials on New Country 103.1" => "50 New Country 103.1",
			"50 x 30 Second Commercials on K97.5" => "50 K97.5",
			"50 x 30 Second Commercials on Radio NL" => "50 Radio NL",
			"50 x 30 Second Commercials on K96.3" => "50 K96.3",
			"50 x 30 Second Commercials on New Country 100.7" => "50 New Country 100.7",
			"150 x 30 Second Commercials (50 on EACH Station)" => "150 50 on EACH Station)",
			"100 x 30 Second Commercials (50 on EACH Station)" => "100 50 on EACH Station)",				
			);					
            $config["toTZ"]             = "-08:00";
			$config["client_logo"]		= "";
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;  
		case "Vancouver":  
			$config["order_date"]   = "2024-10-30";
            $config["ManagersList"]	= array(1,114,3, 137, 135);  //for client capture to allow managers not to be constrained by repID, ie
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["NewsLetter_LinkField"] = 1;
			$config["saleURL"]		= "https://vancouver-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Vancouver One Day Sale";
            $config["blogPrefix"] = 'wpmsb_18_';  
			$config["siteID"] = 21;  
            $config["market_ga_campaign"] = "van-c1";
			//$config["ga_campaigns"] = array("mm-c2", "mm-c1"); 
            $config["market_products_lookup"] = array(
			"Z95.3 30 Second Commercial Campaign" => "Z95.3",
			"the breeze 104.3 30 Second Commercial Campaign" => "the breeze 104.3",
			"30 Second Commercial Campaigns on BOTH Stations" => "BOTH Stations",				
			);					
            $config["toTZ"]             = "-08:00";
			$config["client_logo"]		= "";
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;  
		case "Toronto":
			$config["ManagersList"]	= array(1,3,114,74, 120);
			$config["order_date"]   = "2024-10-20";
			$config['market_dash_title'] 	= "Toronto Sale";
			$config["saleURL"]				= "https://toronto-stingray.mediasalesblitz.com/sale/";
            $config["blogPrefix"] 			= 'wpmsb_16_';			
            $config["market_ga_campaign"] 	= "stingray-toro-c4"; //fgvatebl-gbeb-d6
			//$config["ga_campaigns"] = array("stingray-toro-c3", "fgvatebl-gbeb-d6"); 
			$config["OpenListID"]	= 2;
			$config["ListID"]	= 1;
			$config["siteID"] = 16;  
			$config["NewsLetter_LinkField"] = 1;
            $config["market_products_lookup"] = array(
					"1 week of commercials on both stations (40 ads on boom 97.3 AND New Country 93.5)"		=> "97.3 - 93.5 Bundle",
					"New Country 93.5 – 1 week of commercials (40 ads)" 	=> "New Country 1 Week",
					"boom 97.3 – 1 week of commercials (40 ads)"				=> "boom 1 Week"
					);
            $config["toTZ"]             = "-05:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;
		 case "Calgary":
			$config['market_dash_title'] = "Calgary One Day Sale";
			$config["order_date"]   = "2024-10-30";
			$config["ManagersList"]	= array(1,3,114,9, 90);  
            $config["blogPrefix"] = 'wpmsb_6_';
			$config["saleURL"]				= "https://calgary-stingray.mediasalesblitz.com/sale/";			
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;
			$config["siteID"] = 8;  			
            $config["market_ga_campaign"] = "stingray-clgy-c4";  
			$config["ga_campaigns"] = array("stingray-clgy-c4", "fgvatebl-dytl-d7"); 
            $config["market_products_lookup"] = array(
			"50 x 30 Second Commercials on XL 103" => "50 XL 103",
			"50 x 30 Second Commercials on 90.3 AMP Radio" => "50 90.3 AMP Radio",
			"200,000 Targeted Programmatic Digital Display Impressions" => "200K Digital",				
					);
            $config["toTZ"]             = "-07:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 		
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");			
            break;
	    case "Edmonton":
			$config['market_dash_title'] = "Edmonton One Day Sale";
			$config["ManagersList"]	= array(1,3,114, 9, 85, 90, 401);  
			$config["order_date"]   = "2024-10-30";
            $config["blogPrefix"] = 'wpmsb_5_';
			$config["saleURL"]				= "https://edmonton-stingray.mediasalesblitz.com/sale/";
            $config["market_ga_campaign"] = "stingray-edmt-c5"; 
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;			
			$config["siteID"] = 7;  			
			$config["ga_campaigns"] = array("stingray-edmt-c5", "fgvatebl-fezg-d8"); 
            $config["market_products_lookup"] = array(
				"50 x 30 Second Commercials on K-97" => "50 K-97",
				"50 x 30 Second Commercials on the breeze 96.3" => "50 the breeze 96.3",
				"50 x 30 Second Commercials on 840 CFCW" => "50 840 CFCW",
				"50 x 30 Second Commercials on Sports 1440" => "50 Sports 1440",
				"100 x 30 Second Commercials on New Country 98.1" => "100 New Country 98.1",
					);
            $config["toTZ"]             = "-07:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 		
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");			
            break;
        case "Alberta":
            $config["ManagersList"]	= array(1,3,114,7,8); 
			$config["order_date"]   = "2024-10-30";
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;	
			$config["saleURL"]		= "https://alberta-stingray.mediasalesblitz.com/sale/";
			$config['market_dash_title'] = "Alberta One Day Sale";
            $config["blogPrefix"] = 'wpmsb_8_';  
			$config["siteID"] = 11;  
            $config["market_ga_campaign"] = "stingray-abnm-c6";
			$config["ga_campaigns"] = array("stingray-abnm-c5", "stingray-abnm-c6" ); 
            $config["market_products_lookup"] = array(	
				"50 x 30 Second Commercials on Zed 98.9 Red Deer" => "50 Zed 98.9 Red Deer",
				"100 x 30 Second Commercials on New Country 93.3 Stettler" => "100 New Country 93.3 Stettler",
				"100 x 30 Second Commercials on New Country 95.9 Lloydminster" => "100 New Country 95.9 Lloydminster",
				"546 x 30 Second Commercials over 26 weeks on New Country 95.9 Lloydminster" => "26wks New Country 95.9 Lloydminster",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 95.9 Lloydminster" => "52wks New Country 95.9 Lloydminster",
				"100 x 30 Second Commercials on New Country 97.7 St. Paul" => "100 New Country 97.7 St. Paul",
				"100 x 30 Second Commercials on HOT 93.7 Wainwright" => "100 HOT 93.7 Wainwright",
				"546 x 30 Second Commercials over 26 weeks on HOT 93.7 Wainwright" => "26wks HOT 93.7 Wainwright",
				"1,092 x 30 Second Commercials over 52 weeks on HOT 93.7 Wainwright" => "52wks HOT 93.7 Wainwright",
				"100 x 30 Second Commercials on BOOM 95.3 Cold Lake" => "100 BOOM 95.3 Cold Lake",
				"100 x 30 Second Commercials on BOOM 103.5 Lac La Biche" => "100 BOOM 103.5 Lac La Biche",
				"100 x 30 Second Commercials on HOT 101.3 Bonnyville" => "100 HOT 101.3 Bonnyville",
				"100 x 30 Second Commercials on BOOM 94.1 Athabasca" => "100 BOOM 94.1 Athabasca",
				"100 x 30 Second Commercials on Boom 96.7 Whitecourt" => "100 Boom 96.7 Whitecourt",
				"100 x 30 Second Commercials on BOOM 104.9 Hinton" => "100 BOOM 104.9 Hinton",
				"100 x 30 Second Commercials on BOOM 92.7 Slave Lake" => "100 BOOM 92.7 Slave Lake",
				"100 x 30 Second Commercials on New Country West Edson" => "100 New Country West Edson",
				"100 x 30 Second Commercials on New Country 93.5 High Prairie" => "100 New Country 93.5 High Prairie",
				"100 x 30 Second Commercials on New Country 97.9 Westlock" => "100 New Country 97.9 Westlock",
				"100 x 30 Second Commercials on New Country 92.5 Drumheller" => "100 New Country 92.5 Drumheller",
				"100 x 30 Second Commercials on New Country 105.7 Brooks" => "100 New Country 105.7 Brooks",
				"100 x 30 Second Commercials on New Country South West Blairmore/Pincher Creek" => "100 NC SW Blairmore/Pincher Creek",
				"Targeted Programmatic Display Campaign for 3 months" => "Targeted Programmatic 3 months",
				"50 x 30 Second Commercials on New Country 95.5 Red Deer" => "50 New Country 95.5 Red Deer",
				"100 x 30 Second Commercials on New Country 98.1" => "100 New Country 98.1",
				"546 x 30 Second Commercials over 26 weeks on New Country 93.3 Stettler" => "26wks New Country 93.3 Stettler",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 93.3 Stettler" => "52wks New Country 93.3 Stettler",
				"546 x 30 Second Commercials over 26 weeks on New Country 97.7 St. Paul" => "26wks New Country 97.7 St. Paul",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 97.7 St. Paul" => "52wks New Country 97.7 St. Paul",
				"100 x 30 Second Commercials on BOOM 101.9 Wainwright" => "100 BOOM 101.9 Wainwright",
				"546 x 30 Second Commercials over 26 weeks on BOOM 101.9 Wainwright" => "26wks BOOM 101.9 Wainwright",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 101.9 Wainwright" => "52wks BOOM 101.9 Wainwright",
				"546 x 30 Second Commercials over 26 weeks on boom 95.3 Cold Lake" => "26wks boom 95.3 Cold Lake",
				"1,092 x 30 Second Commercials over 52 weeks on boom 95.3 Cold Lake" => "52wks boom 95.3 Cold Lake",
				"546 x 30 Second Commercials over 26 weeks on BOOM 103.5 Lac La Biche" => "26wks BOOM 103.5 Lac La Biche",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 103.5 Lac La Biche" => "52wks BOOM 103.5 Lac La Biche",
				"546 x 30 Second Commercials over 26 weeks on HOT 101.3 Bonnyville" => "26wks HOT 101.3 Bonnyville",
				"1,092 x 30 Second Commercials over 52 weeks on HOT 101.3 Bonnyville" => "52wks HOT 101.3 Bonnyville",
				"546 x 30 Second Commercials over 26 weeks on BOOM 94.1 Athabasca" => "26wks BOOM 94.1 Athabasca",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 94.1 Athabasca" => "52wks BOOM 94.1 Athabasca",
				"546 x 30 Second Commercials over 26 weeks on boom 96.7 Whitecourt" => "26wks boom 96.7 Whitecourt",
				"1,092 x 30 Second Commercials over 52 weeks on boom 96.7 Whitecourt" => "52wks boom 96.7 Whitecourt",
				"546 x 30 Second Commercials over 26 weeks on BOOM 104.9 Hinton" => "26wks BOOM 104.9 Hinton",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 104.9 Hinton" => "52wks BOOM 104.9 Hinton",
				"546 x 30 Second Commercials over 26 weeks on BOOM 92.7 Slave Lake" => "26wks BOOM 92.7 Slave Lake",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 92.7 Slave Lake" => "52wks BOOM 92.7 Slave Lake",
				"546 x 30 Second Commercials over 26 weeks on New Country West Edson" => "26wks New Country West Edson",
				"1,092 x 30 Second Commercials over 52 weeks on New Country West Edson" => "52wks New Country West Edson",
				"546 x 30 Second Commercials over 26 weeks on New Country 93.5 High Prairie" => "26wks New Country 93.5 High Prairie",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 93.5 High Prairie" => "52wks New Country 93.5 High Prairie",
				"546 x 30 Second Commercials over 26 weeks on New Country 97.9 Westlock" => "26wks New Country 97.9 Westlock",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 97.9 Westlock" => "52wks New Country 97.9 Westlock",
				"546 x 30 Second Commercials over 26 weeks on New Country 92.5 Drumheller" => "26wks New Country 92.5 Drumheller",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 92.5 Drumheller" => "52wks New Country 92.5 Drumheller",
				"100 x 30 Second Commercials on BOOM 99.5 Drumheller" => "100 BOOM 99.5 Drumheller",
				"546 x 30 Second Commercials over 26 weeks on BOOM 99.5 Drumheller" => "26wks BOOM 99.5 Drumheller",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 99.5 Drumheller" => "52wks BOOM 99.5 Drumheller",
				"546 x 30 Second Commercials over 26 weeks on New Country 105.7 Brooks" => "26wks New Country 105.7 Brooks",
				"1,092 x 30 Second Commercials over 52 weeks on New Country 105.7 Brooks" => "52wks New Country 105.7 Brooks",
				"100 x 30 Second Commercials on BOOM 101.1 Brooks" => "100 BOOM 101.1 Brooks",
				"546 x 30 Second Commercials over 26 weeks on BOOM 101.1 Brooks" => "26wks BOOM 101.1 Brooks",
				"1,092 x 30 Second Commercials over 52 weeks on BOOM 101.1 Brooks" => "52wks BOOM 101.1 Brooks",
				"546 x 30 Second Commercials over 26 weeks on New Country South West Blairmore/Pincher Creek" => "26wks NC SW Blairmore/Pincher Creek",
				"1,092 x 30 Second Commercials over 52 weeks on New Country South West Blairmore/Pincher Creek" => "52wks NC SW Blairmore/Pincher Creek",
				"50 x 30 Second Commercials on BOTH Red Deer Stations (100 Total Commercials)" => "50 BOTH Red Deer Stations",
				"50 x 30 Second Commercials on 840 CFCW" => "50 840 CFCW",
				"50 x 30 Second Commercials on Sports 1440" => "50 Sports 1440",
				"100 x 30 Second Commercials on CITL/CKSA TV" => "100 CITL/CKSA TV",
				"21x 30 second commercials guaranteed EVERY week for 26 weeks on CITL/CKSA TV" => "26wks CITL/CKSA TV",
				"21x 30 second commercials guaranteed EVERY week for 52 weeks on CITL/CKSA TV" => "52wks CITL/CKSA TV",
				"Social Media Campaign for 3 Months" => "Social Media Campaign for 3 Months"
					);
            $config["toTZ"]             = "-07:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;     
		case "New Brunswick":
			$config["order_date"]   = "2024-10-30";
			$config["ManagersList"]	= array(1,3,114,50, 51, 57, 60 ,62);  //for client capture to allow managers not to be constrained by repID
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;				
            $config["blogPrefix"] = 'wpmsb_10_';  
			$config["siteID"] = 13;  
			$config["saleURL"]		= "https://nb-stingray.mediasalesblitz.com/sale/";		
            $config['market_dash_title'] = "New Brunswick One Day Sale";
            $config["market_ga_campaign"] = "stingray-newb-c7";
			$config["toTZ"]             = "-04:00";
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 						
			//$config["ga_campaigns"] = array("stingray-newb-c4", "fgvatebl-afjc-d7"); 			
            $config["market_products_lookup"] = array(
                    "50 x 30 Second Commercials on New Country 96.9 Moncton" => "50 New Country 96.9 Moncton",
"50 x 30 Second Commercials on rewind 95.9 Miramichi" => "50 rewind 95.9 Miramichi",
"50 x 30 Second Commercials on Q103 Moncton" => "50 Q103 Moncton",
"50 x 30 Second Commercials on Q88.9 Saint John" => "50 Q88.9 Saint John",
"50 x 30 Second Commercials on New Country 92.3 Fredericton" => "50 New Country 92.3 Fredericton",
"50 x 30 Second Commercials on HOT 93.1 Fredericton" => "50 HOT 93.1 Fredericton",
"100,000 Targeted Programmatic Digital Display Impressions" => "100K Digital"
					);
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;			
		case "Nova Scotia":
            $config['market_dash_title'] = "Nova Scotia One Day Sale";			
			$config["order_date"]   = "2024-10-30";
			$config["ManagersList"]	= array(1,3,114,93,100, 104, 145);  //for client capture to allow managers not to be constrained by repID
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;				
            $config["blogPrefix"] = 'wpmsb_12_';  
			$config["siteID"] = 15;  
			$config["saleURL"]				= 'https://ns-stingray.mediasalesblitz.com/sale/' ;			
            $config["market_ga_campaign"] = "stingray-nova-c3"; 
			//$config["ga_campaigns"] = array("stingray-nova-c2", "fgvatebl-abib-d5");			            
			$config["toTZ"]             = "-04:00";
			$config["market_products_lookup"] = array(
			"50 x 30 Second Commercials on Q 97.9 New Glasgow" => " 50 Q 97.9 New Glasgow",
"50 x 30 Second Commercials on 94.1 the breeze New Glasgow" => "50 94.1 the breeze New Glasgow",
"50 x 30 Second Commercials on New Country 103.5 Sydney" => "50 New Country 103.5 Sydney",
"50 x 30 Second Commercials on Hot 101.9 Sydney" => "50 Hot 101.9 Sydney",
"50 x 30 Second Commercials on 96.5 the breeze Halifax and Text to Dashboard" => "50 96.5 the breeze Halifax Dashboard",
"50 x 30 Second Commercials on Q 104 Halifax and Text to Dashboard" => "50 Q 104 Halifax Dashboard",
"50 x 30 Second Commercials on rewind 89.3 Kentville" => "50 rewind 89.3 Kentville",
"Audio Extension - Sydney, New Glasgow" => "",
"50 x 30 Second Commercials on EACH Halifax Station and Text to Dashboard" => "50 EACH Halifax Station Dashboard",
"100,000 Targeted Programmatic Digital Display Impressions - Halifax" => "100K Digital Halifax",
"50 x 30 Second Commercials on EACH Sydney Station" => "50 EACH Sydney Station",
"50 x 30 Second Commercials on EACH New Glasgow Station" => "50 EACH New Glasgow Station",
"100,000 Programmatic Digital Impressions - Sydney" => "100K Digital Sydney",
"100,000 Programmatic Digital Impressions - Kentville" => "100K Digital Kentville",
"100,000 Programmatic Digital Impressions - New Glasgow" => "100K Digital New Glasgow"
                    );            
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
			$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;				
		case "NL":
            $config['market_dash_title'] 	= "Newfoundland One Day Sale";
			$config["order_date"]   		= "2025-04-01";
            $config["market_ga_campaign"] 	= "stingray-nl-c7";			
			//$config["ga_campaigns"] 		= array("stingray-nl-c4", "fgvatebl-ay-d7");				
            $config["ManagersList"]			= array(1,3,114,38, 43, 49, 142 );  									
			$config["ListID"]				= 1;
			$config["OpenListID"]			= 2;
			$config["NewsLetter_LinkField"] = 1;				
            $config["blogPrefix"] 			= 'wpmsb_7_';  
			$config["siteID"]				= 9;  
			$config["toTZ"]             	= "-03:30";
			$config["saleURL"]				= 'https://nl-stingray.mediasalesblitz.com/sale/';
            $config["market_products_lookup"] = array(
					"Newfoundland &amp; Labrador Digital Bundles" => "NL Digital Bundles",
"50 x 30 Second Commercials per bundle on the New Country Network across Newfoundland" => "50 New Country across Newfoundland",
"50 x 30 Second Commercials per bundle on the VOCM Network across Newfoundland" => "50 VOCM across Newfoundland",
"50 x 30 Second Commercials per bundle on the K-ROCK Network across Newfoundland" => "50 K-ROCK across Newfoundland",
"50 x 30 Second Commercials per bundle on 88.3 FM VOCM Marystown" => "88.3 FM VOCM Marystown",
"50 x 30 Second Commercials per bundle on 590 VOCM St.John's" => "590 VOCM St.John's",
"50 x 30 Second Commercials per bundle on 650 VOCM Gander" => "650 VOCM Gander",
"50 x 30 Second Commercials per bundle on New Country 103.9 Carbonear" => "New Country 103.9 Carbonear",
"50 x 30 Second Commercials per bundle on 97.5 K-ROCK St.John's" => "97.5 K-ROCK St.John's",
"50 x 30 Second Commercials per bundle on 870 VOCM Stephenville" => "870 VOCM Stephenville",
"50 x 30 Second Commercials per bundle on 98.7 K-ROCK Gander" => "98.7 K-ROCK Gander",
"50 x 30 Second Commercials per bundle on 710 VOCM Clarenville" => "710 VOCM Clarenville",
"50 x 30 Second Commercials per bundle on New Country 930 St.John's" => "New Country 930 St.John's",
"50 x 30 Second Commercials per bundle on 570 VOCM Corner Brook" => "570 VOCM Corner Brook",
"50 x 30 Second Commercials per bundle on 620 VOCM Grand Falls-Windsor" => "620 VOCM Grand Falls-Windsor",
"50 x 30 Second Commercials per bundle on New Country 97.1 Clarenville" => "New Country 97.1 Clarenville",
"50 x 30 Second Commercials per bundle on 103.9 K-ROCK Cornerbrook" => "103.9 K-ROCK Cornerbrook",
"50 x 30 Second Commercials per bundle on 102.3 K-ROCK Grand Falls-Windsor" => "102.3 K-ROCK Grand Falls-Windsor",
"4 weeks of Half Page Banner Impressions on VOCM.com" => "4 weeks Half Banner VOCM.com",
"4 weeks of Big Box Digital Impressions on VOCM.com" => "4 weeks Big Box Digital VOCM.com",
"4 weeks of Leaderboard Digital Impressions on VOCM.com" => "4 weeks LB Digital VOCM.com",
"50 x 30 Second Commercials per bundle on HOT 99.1 St.John's" => "HOT 99.1 St.John's",
"50 x 30 Second Commercials per bundle on 103.9 K-ROCK Corner Brook" => "103.9 K-ROCK Corner Brook",
"50 x 30 Second Commercials per bundle on Big Land FM Labrador" => "Big Land FM Labrador"
                    );            
			$config["client_logo"]		= "../wp-content/uploads/sites/2/2022/04/logo-stingray.png"; 
			//$config["all_markets_report"] = array("Edmonton" => "5", "Calgary" => "6", "NL" => "7", "Alberta" => "8", "New Brunswick" => "10", "Nova Scotia" => "12", "Sudbury" => "13", "PEI" => "14", "Ottawa" => "15", "Toronto" => "16", "BC" => "17", "Vancouver" => "18");
            break;		
		case "demo":		
            $config['market_dash_title'] = "Demo One Day Sale";
			$config["order_date"]           = "2023-06-26";
			$config["ManagersList"]			= array(1,3,114,79, 80);
			$config["OpenListID"]			= 3;
			$config["ListID"]				= 1;
			$config["saleURL"]				= 'https://demo.mediasalesblitz.com/demo/' ;	
			$config["client_logo"]			= "" ; //"../wp-content/uploads/sites/3/2022/07/Vista-Radio-Logo@2x.png"; 	
			$config["msb_logo"]				= "";
            $config["market_ga_campaign"] = "testDataTwo";	
			//$config["ga_campaigns"] = array("testCCdemo", "testCC");			
			$config["blogPrefix"] = 'wpmsb_3_';
			$config["siteID"] = 5;  
            $config["market_products_lookup"] = array(
					"100 x 30 Second Commercials on 100.3 The Q" 	    => "1003 Q", 
                    "100 x 30 Second Commercials on The Zone 91.3"    	=> "Zone 91.3",
                    "100 x 30 Second Commercials on EACH station"       => "Q and Zone",   
					"13 weeks of Digital Impressions on MyWestNipissingNow.com"		=> "Digital", 
					"28 x 30 Second Ads on 99.3 Moose FM West Nipissing"			=> "WPIG",
					"13 weeks of Digital Impressions on MyNorthBayNow.com"			=> "Community Messages",
					"28 x 30 Second Ads on 106.3 Jet FM North Bay"					=> "WKRP / WPIG Combo", 
					"28 x 30 Second Ads on 90.5 Country FM North Bay"				=> "WKRP",
					"100 x 30 Second Commercials on 100.7 Local FM"					=> "100.7 Local FM"
                    );
            $config["toTZ"]             = "-04:00";			
            break;	
		case "demo2":		
            $config['market_dash_title'] = "Demo One Day Sale";
			$config["order_date"]           = "2023-06-26";
			$config["ManagersList"]			= array(1,3,114, 79, 80);
			$config["OpenListID"]			= 3;
			$config["ListID"]				= 1;
			$config["NewsLetter_LinkField"] = 2; 
			$config["saleURL"]				= 'https://demo.mediasalesblitz.com/demotwo/' ;	
			//$config["client_logo"]			= "../wp-content/uploads/sites/3/2022/07/Vista-Radio-Logo@2x.png"; 			
            $config["market_ga_campaign"] = "demotwo-c2";	
			//$config["ga_campaigns"] = array("testCCdemo", "testCC");			
			$config["blogPrefix"] = 'wpmsb_3_';
			$config["siteID"] = 5;  
            $config["market_products_lookup"] = array(
					"100 x 30 Second Commercials on 100.3 The Q" 	    => "1003 Q", 
                    "100 x 30 Second Commercials on The Zone 91.3"    	=> "Zone 91.3",
                    "100 x 30 Second Commercials on EACH station"       => "Q and Zone",   
					"13 weeks of Digital Impressions on MyWestNipissingNow.com"		=> "Digital", 
					"28 x 30 Second Ads on 99.3 Moose FM West Nipissing"			=> "WPIG",
					"13 weeks of Digital Impressions on MyNorthBayNow.com"			=> "Community Messages",
					"28 x 30 Second Ads on 106.3 Jet FM North Bay"					=> "WKRP / WPIG Combo", 
					"28 x 30 Second Ads on 90.5 Country FM North Bay"				=> "WKRP"
                    );
            $config["toTZ"]             = "-04:00";			
            break;	
		case "List B":		
            $config['market_dash_title'] = "Demo One Day Sale";
			$config["order_date"]           = "2023-06-26";
			$config["ManagersList"]			= array(1,3,114, 79, 80);
			$config["OpenListID"]			= 0;
			$config["ListID"]			= 1;
			$config["NewsLetter_LinkField"] = 3; 
			$config["saleURL"]				= 'https://demo.mediasalesblitz.com/demotwo/' ;	
			$config["client_logo"]			= ""; //"../wp-content/uploads/sites/3/2022/07/Vista-Radio-Logo@2x.png"; 			
            $config["market_ga_campaign"] = "demotwo-c2";	
			//$config["ga_campaigns"] = array("testCCdemo", "testCC");			
			$config["blogPrefix"] = 'wpmsb_3_';
			$config["siteID"] = 5;  
            $config["market_products_lookup"] = array(
					"100 x 30 Second Commercials on 100.3 The Q" 	    => "1003 Q", 
                    "100 x 30 Second Commercials on The Zone 91.3"    	=> "Zone 91.3",
                    "100 x 30 Second Commercials on EACH station"       => "Q and Zone",   
					"13 weeks of Digital Impressions on MyWestNipissingNow.com"		=> "Digital", 
					"28 x 30 Second Ads on 99.3 Moose FM West Nipissing"			=> "WPIG",
					"13 weeks of Digital Impressions on MyNorthBayNow.com"			=> "Community Messages",
					"28 x 30 Second Ads on 106.3 Jet FM North Bay"					=> "WKRP / WPIG Combo", 
					"28 x 30 Second Ads on 90.5 Country FM North Bay"				=> "WKRP"
                    );
            $config["toTZ"]             = "-04:00";			
            break;	
		case "indie88":		
            $config['market_dash_title'] = "indie88 One Day Sale";
			$config["order_date"]           = "2023-11-28";
			$config["ManagersList"]			= array(1,3,114, 112, 80, 115);
			$config["ListID"]				= 1;
			$config["OpenListID"]			= 2;
			$config["saleURL"]				= 'https://indie88.mediasalesblitz.com/sale/' ;	
			$config["client_logo"]			= "../wp-content/uploads/sites/4/2023/11/INDIE-88-Logo-TEAL.png"; 	
            $config["market_ga_campaign"] = "88fm-c1";	
			$config["NewsLetter_LinkField"]	= 1;
			//$config["ga_campaigns"] = array("testCCdemo", "testCC");			
			$config["blogPrefix"] = 'wpmsb_4_';
			$config["siteID"] = 6;  
            $config["market_products_lookup"] = array(
					"Digital Impressions on Indie88.com" 		=>	"Digital Impressions",
					"100 X 30-second Commercials on Indie88"	=> 	"30 Second Commercials",
					"100 X 15-second Commercials on Indie88"	=>	"15 Second Commercials"
			);
            $config["toTZ"]             = "-05:00";			
            break;				
		case "EagleMedia":
			$config['market_dash_title'] = "Eagle Media One Day Sale";
			$config["ManagersList"]	= array(1,3,114, 179);  
			$config["order_date"]   = "2024-12-16";
            $config["blogPrefix"] = 'wpmsb_19_';
			$config["saleURL"]				= "https://eaglemedia.mediasalesblitz.com/sale/";
            $config["market_ga_campaign"] = "eagle-c2"; 
			$config["ListID"]	= 1;
			$config["OpenListID"]	= 2;	
			$config["NewsLetter_LinkField"] = 1;			
			$config["siteID"] = 22;  			
			//$config["ga_campaigns"] = array("stingray-edmt-c5", "fgvatebl-fezg-d7"); 
            $config["market_products_lookup"] = array(
				"50 x 30 Second Commercials on Country Rewind 92.7" => "Rewind 92.7",
				"50 x 30 Second Commercials on 1420AM/107.9FM" => "1420AM 107.9FM", 
				"50 x 30 Second Commercials on Q Country 103.5"  => "Q 103.5", 
				"50 x 30 Second Commercials on Power Hits 97.5" => "Power Hits 97.5"
			);
            $config["toTZ"]             = "-06:00";
			$config["client_logo"]		= ""; 				
            break;
        default:
            $config['market_dash_title'] = "Ooops, Please contact your administrator.";
            $config["market_ga_campaign"] = "";
            $config["market_products_lookup"] = array();            
        }
			
		return $config;
	} //msb_config
} //class
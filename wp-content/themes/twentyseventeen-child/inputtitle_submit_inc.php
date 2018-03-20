<?php
date_default_timezone_set('UTC');

define('JS_VERSION', '1.0');
function version() {
	if (true) return time();  // Change to false when going to production
	return JS_VERSION;
}

if (!session_id()) {
	session_start();
}
if (!array_key_exists('impersonatedUserID',$_SESSION)) {
	$_SESSION['impersonatedUserID'] = '';
}

add_action( 'wp_enqueue_scripts', 'inputtitle_submit_scripts' );

if (array_key_exists('action',$_POST)) { $action = $_POST['action']; }

switch ($action) {
	case 'ajax-savealliance':
			add_action( 'wp_ajax_ajax-savealliance', 'saveNewAlliance' );
		break;
	case 'ajax-savealliancechanges':
			add_action( 'wp_ajax_ajax-savealliancechanges', 'saveAllianceChanges' );
		break;
	case 'ajax-deletealliance':
			add_action( 'wp_ajax_ajax-deletealliance', 'deleteAlliance' );
		break;
	case 'ajax-saveNewClan':
			add_action( 'wp_ajax_ajax-saveNewClan', 'saveNewClan' );
		break;
	case 'ajax-saveClanChanges':
			add_action( 'wp_ajax_ajax-saveClanChanges', 'saveClanChanges' );
		break;
	case 'ajax-deleteClan':
			add_action( 'wp_ajax_ajax-deleteClan', 'deleteClan' );
		break;
	case 'ajax-deletewar':
			add_action( 'wp_ajax_ajax-deletewar', 'deleteWar' );
		break;
	case 'ajax-createwar':
			add_action( 'wp_ajax_ajax-createwar', 'createWar' );
		break;
	case 'ajax-createwar2':
			add_action( 'wp_ajax_ajax-createwar2', 'createWar2' );
		break;
	case 'ajax-setWarTime':
			add_action( 'wp_ajax_ajax-setWarTime', 'setWarTime' );
		break;
	case 'ajax-setTrustLevel':
			add_action( 'wp_ajax_ajax-setTrustLevel', 'setTrustLevel' );
		break;
	case 'ajax-setFriendlyFlag':
			add_action( 'wp_ajax_ajax-setFriendlyFlag', 'setFriendlyFlag' );
		break;
	case 'ajax-setOutcomeFlag':
			add_action( 'wp_ajax_ajax-setOutcomeFlag', 'setOutcomeFlag' );
		break;
	case 'ajax-savePersonalWarInfo':
			add_action( 'wp_ajax_ajax-savePersonalWarInfo', 'savePersonalWarInfo' );
		break;
	case 'ajax-saveOpponentName':
			add_action( 'wp_ajax_ajax-saveOpponentName', 'saveOpponentName' );
		break;
	case 'ajax-saveSearchDateTime':
			add_action( 'wp_ajax_ajax-saveSearchDateTime', 'saveSearchDateTime' );
		break;
	case 'ajax-setImpersonatedUser':
			add_action( 'wp_ajax_ajax-setImpersonatedUser', 'setImpersonatedUser' );
		break;
	case 'ajax-getWarAudit':
			add_action( 'wp_ajax_ajax-getWarAudit', 'getWarAudit' );
		break;
	case 'ajax-clearImpersonatedUser':
			add_action( 'wp_ajax_ajax-clearImpersonatedUser', 'clearImpersonatedUser' );
		break;
	case 'ajax-allowPlayer':
			add_action( 'wp_ajax_ajax-allowPlayer', 'allowPlayer' );
		break;
	case 'ajax-removeClan':
			add_action( 'wp_ajax_ajax-removeClan', 'removeClan' );
		break;
	case 'ajax-getOpponents':
			add_action( 'wp_ajax_ajax-getOpponents', 'getOpponents' );
		break;
	case 'ajax-showHideClan':
			add_action( 'wp_ajax_ajax-showHideClan', 'showHideClan' );
		break;
	default: 
		break;
} 

function inputtitle_submit_scripts() {

	wp_enqueue_script('inputtitle_submit', get_template_directory_uri() . '-child/js/inputtitle_submit.js', array( 'jquery' ), version());
	wp_localize_script('inputtitle_submit', 'PT_Ajax', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'nextNonce' => wp_create_nonce( 'myajax-next-nonce' )
		)
	);
	//wp_enqueue_script('jquery_datetimepicker', get_template_directory_uri() . '-child/js/jquery.datetimepicker.full.min.js');
}

function checkNonce() {
    $nonce = $_POST['nextNonce'];
    if (!wp_verify_nonce($nonce, 'myajax-next-nonce')) {
      die('Busted');
    }
}

function checkUser() {
	if (!is_user_logged_in()) {
	   echo "Please sign in to access this page.";
	   wp_login_form(array('echo'=>true));
	}
}

function saveNewAlliance() {
header( "Content-Type: application/json" );
try {
	checkNonce();

    $currentUser = wp_get_current_user();
    $userID = $currentUser->ID;

	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    global $wpdb;
    $wpdb->insert(
		$wpdb->prefix.'fmac_alliances',
		array(
			'AllianceName'=>stripslashes_deep($_POST['alliancename']),
			'OwnerID'=>$userID,
			'PrestigeLevel'=>$_POST['prestigeLevel'],
			'NonOwner'=>$_POST['nonOwner']
		)
	);
	
	if(!$wpdb->insert_id) {
		throw new Exception("No record inserted");
	}
	
	$response = array(
			"success"=>true/*, 
			"currentUserID"=>$userID, 
			"tableName"=>$wpdb->prefix.'fmac_alliances', 
			"LastQuery"=>$wpdb->last_query,
			"InsertID"=>$wpdb->insert_id*/);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}
}

function allowPlayer() {
header( "Content-Type: application/json" );
try {
	checkNonce();

    $currentUser = wp_get_current_user();
    $userID = $currentUser->ID;

	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    global $wpdb;
    $wpdb->insert(
		$wpdb->prefix.'fmac_users_clans',
		array(
			'UserID'=>$_POST['allowedUserID'],
			'ClanID'=>$_POST['clanID']
		)
	);
	
	if(!$wpdb->insert_id) {
		throw new Exception("Player already allowed to see this clan.");
	}
	
	$response = array(
			"success"=>true/*, 
			"currentUserID"=>$userID, 
			"tableName"=>$wpdb->prefix.'fmac_alliances', 
			"LastQuery"=>$wpdb->last_query,
			"InsertID"=>$wpdb->insert_id*/);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	//echo json_encode(array_merge($_POST, $response));
    echo json_encode($response);
    exit;
}
}

function saveNewClan() {
header( "Content-Type: application/json" );
try {
	checkNonce();

    $currentUser = wp_get_current_user();
    $userID = $currentUser->ID;

	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    global $wpdb;
    $wpdb->insert(
		$wpdb->prefix.'fmac_clans',
		array(
			'ClanName'=>stripslashes_deep($_POST['clanName']),
			'OwnerID'=>$userID
		)
	);
	
	if(!$wpdb->insert_id) {
		throw new Exception("No record inserted");
	}
	
	$newClanID = $wpdb->insert_id;
	
    $wpdb->insert(
		$wpdb->prefix.'fmac_users_clans',
		array(
			'UserID'=>$userID,
			'ClanID'=>$newClanID
		)
	);
	
	$response = array(
			"success"=>true);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}
}

function createWar() {
header( "Content-Type: application/json" );
try {
	checkNonce();

	// Create the war
	$baseCount = stripslashes_deep($_POST['baseCount']);
	$timeToWar = stripslashes_deep($_POST['timeToWar']);
	
	if ($timeToWar) {
		// Need to incorporate the same checks as in setWarTime
		
	}

	$currentUser = wp_get_current_user();
	$userID = $currentUser->ID;

	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    global $wpdb;
    $wpdb->insert(
		$wpdb->prefix.'fmac_wars',
		array(
			'AllianceID'=>$_POST['allianceID'],
			'BaseCount'=>$baseCount,
			'Searching'=>1,
			'CreatedBy'=>$userID
		)
	);
	
	if(!$wpdb->insert_id) {
		throw new Exception("No record inserted");
	}
	
	// Using the war just created, insert a record in 'users_wars' to set the user to be in
	// the war by default.
	$warID = $wpdb->insert_id;
	
	$sql = 'INSERT INTO '.$wpdb->prefix.'fmac_users_wars (UserID,WarID,Notes,InWar) VALUES (%d,%d,%s,%d) ON DUPLICATE KEY UPDATE Notes = VALUES(Notes), InWar = VALUES(InWar)';
	$sql2 = $wpdb->prepare($sql,array($userID,$warID,'','1'));

	$inserted = $wpdb->query($sql2);
	
	if ($inserted === false) {
		throw new Exception("No record updated");
	}
	
	$response = array(
			"success"=>true/*, 
			"currentUserID"=>$userID, 
			"tableName"=>$wpdb->prefix.'fmac_alliances', 
			"LastQuery"=>$wpdb->last_query,
			"InsertID"=>$wpdb->insert_id*/);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	//$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine(), "LastQuery"=>$wpdb->last_query);
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}
}

function createWar2() {

header( "Content-Type: application/json" );
try {
	checkNonce();

	// Create the war
	$baseCount = stripslashes_deep($_POST['baseCount']);
	$timeToWar = stripslashes_deep($_POST['timeToWar']);
	$notes = stripslashes_deep($_POST['notes']);
	$opponent = stripslashes_deep($_POST['opponent']);
	
	$currentUser = wp_get_current_user();
	$userID = $currentUser->ID;

	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    global $wpdb;
    $wpdb->insert(
		$wpdb->prefix.'fmac_wars',
		array(
			'AllianceID'=>$_POST['allianceID'],
			'BaseCount'=>$baseCount,
			'OpponentName'=>$opponent,
			'Searching'=>1,
			'CreatedBy'=>$userID
		)
	);
	
	if(!$wpdb->insert_id) {
		throw new Exception("No record inserted");
	}
	
	$warID = $wpdb->insert_id;
	
	// Using the war just created, insert a record in 'users_wars' to set the user to be in the war by default.
	$sql = 'INSERT INTO '.$wpdb->prefix.'fmac_users_wars (UserID,WarID,Notes,InWar) VALUES (%d,%d,%s,%d) ON DUPLICATE KEY UPDATE Notes = VALUES(Notes), InWar = VALUES(InWar)';
	$sql2 = $wpdb->prepare($sql,array($userID,$warID,$notes,'1'));

	$inserted = $wpdb->query($sql2);
	
	if ($inserted === false) {
		throw new Exception("No record updated");
	}
	

	// if the user entered a time to war, we update the table accordingly
	if ($timeToWar) {
		// Need to incorporate the same checks as in setWarTime
		$offset = explode(":", $timeToWar);
		
		$errorPreface = "Your war was saved but the time was NOT saved: ";
		
		if(count($offset)<>2) { throw new Exception($errorPreface."Please enter the time offset in this format: HH:MM"); }
		if(!is_numeric($offset[0]) || !is_numeric($offset[1])) { throw new Exception($errorPreface."Please enter numeric values in this format: HH:MM"); }
		if($offset[0]<0 || $offset[0]>24) { throw new Exception($errorPreface."Please enter an offset under 24h"); }
		if($offset[1]<0 || $offset[1]>59) { throw new Exception($errorPreface."Minutes should be between 0 and 59"); }

		$warTime = new DateTime();
		$intervalString = 'PT'.str_pad($offset[0],2,"0",STR_PAD_LEFT).'H'.$offset[1].'M';
		$warTime->add(new DateInterval($intervalString));
		$newTime = $warTime->format('Y-m-d H:i:s');
	
		$sql = 'UPDATE '.$wpdb->prefix.'fmac_wars SET WarTime = %s, TimeUpdatedBy = %d WHERE WarID = %d';
		$updated = $wpdb->query($wpdb->prepare($sql, array($newTime, $userID, $warID)));	
	}

	$response = array(
			"success"=>true);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}

}

function setImpersonatedUser() {
header( "Content-Type: application/json" );
try {
	checkNonce();

	$_SESSION['impersonatedUserID'] = $_POST['impersonatedUserID'];
	
	$response = array("success"=>true);
    
    //echo json_encode(array_merge($_SESSION, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}
}

function clearImpersonatedUser() {
header( "Content-Type: application/json" );
try {
	checkNonce();

	$_SESSION['impersonatedUserID'] = "";
	
	$response = array("success"=>true);
    
    array_merge($_SESSION, $response);
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode(array_merge($_POST, $response));
    exit;
}
}

function showImpersonateForm() {
	$p = '';
	
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;

	if ($userID == 1) {
		$users = $wpdb->get_results("SELECT * FROM $wpdb->prefix"."users ORDER BY user_login;");
		
		$p .= '<select id="userIDs">';
		$p .= '<option value=""></option>';
		foreach($users as $key => $value) {
			$p .= '<option value="'.$value->ID.'" '.($value->ID==$_SESSION['impersonatedUserID'] ? 'selected="selected"' : '').'>'.$value->user_login.'</option>';
		}
		$p .= '</select>';
		
		$p .= '<button id="setImpersonatedUser">Set</button>';
		$p .= '<button id="clearImpersonatedUser">Clear</button>';
		$p .= '<hr />';
		$p .= '<button id="testingAjax">Test</button>';
		$p .= '<div class="ui-widget"><input id="opponentInput" style="color: black;" /></div>';
		$p .= '<div class="ui-widget" style="margin-top:2em; font-family:Arial">Result:<div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div></div>';
	}
	
	return $p;
}

function saveSearchDateTime() {
header( "Content-Type: application/json" );
try {
	checkNonce();

	$allianceSearch = stripslashes_deep($_POST['allianceSearch']);
	$searchDateTime = stripslashes_deep($_POST['searchDateTime']);
    //$UTCOffset = $_POST['UTCOffset'];
    $currentUser = wp_get_current_user();
    $userID = $currentUser->ID;

	if (array_key_exists('UTCOffset', $_COOKIE)) {
		$UTCOffset = $_COOKIE['UTCOffset'];
	} else {
		$UTCOffset = 0;
	}
	
	$localSearchTime = new DateTime($searchDateTime);
		
	if ($UTCOffset[0] == '-') {
		$UTCOffset = substr($UTCOffset, 1);
		$localSearchTime->sub(new DateInterval('PT'.$UTCOffset.'M'));
	} else {
		$localSearchTime->add(new DateInterval('PT'.$UTCOffset.'M'));
	}
	$searchTime_UTC = $localSearchTime->format('Y-m-d H:i:s');
		
	
    global $wpdb;
	
	$sql = 'INSERT INTO '.$wpdb->prefix.'fmac_war_searches (AllianceDescription, DateTimeStamp) VALUES (%s, %s);';
	$updated = $wpdb->query($wpdb->prepare($sql, array($allianceSearch, $searchTime_UTC)));
	
	//if(!$wpdb->insert_id) {
	//	throw new Exception("No record inserted");
	//}
	
	$response = array(
			"success"=>true, 
			"currentUserID"=>$userID, 
			"allianceSearch"=>$allianceSearch, 
			"searchDateTime"=>$searchDateTime, 
			"searchTime_UTC"=>$searchTime_UTC,
			"UTCOffset"=>$UTCOffset);
    
    //echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
	
} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	//echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
}
}

function getMyAlliances() {
    $o='';
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    $myAlliances = $wpdb->get_results("SELECT * FROM $wpdb->prefix"."fmac_alliances WHERE OwnerID = $userID ORDER BY AllianceName;");
	
	$o.='<table>';
	$o.='<tr><th>Name</th><th>Active</th><th>Private</th><th>Prestige</th><th></th><th></th></tr>';
	foreach($myAlliances as $key => $value) {
		$o.='<tr><td width="550"><input type="text" value="'.$value->AllianceName.'" AllianceID="'.$value->AllianceID.'" /></td>';
		$o.='<td><input class="activeAlliances" type="checkbox" '.($value->Active?'checked':'').' AllianceID="'.$value->AllianceID.'" /></td>';
		$o.='<td><input class="privateAlliances" type="checkbox" '.($value->Private?'checked':'').' AllianceID="'.$value->AllianceID.'" /></td>';
		$o.='<td>'.prestigeLevelSelectElement($value->PrestigeLevel, $value->AllianceID).'</td>';
		$o.='<td><button class="saveAllianceChanges" AllianceID="'.$value->AllianceID.'">Save</button></td>';
		$o.='<td><button class="deleteAlliance" AllianceID="'.$value->AllianceID.'">Delete</button></td></tr>';
	}
	$o.="</table>";
	
	echo $o;
}

function getOpponents() {
try {
	header( "Content-Type: application/json" );
	checkNonce();

    $o='';
	global $wpdb;

	$searchTerm = stripslashes_deep($_POST['searchTerm']);
	
	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    $myOpponents = $wpdb->get_results("SELECT * FROM v_my_opponents WHERE (UserID = $userID OR UserID IS NULL) AND AllianceName LIKE '%".$searchTerm."%' ORDER BY AllianceName;");
    //$myOpponents = $wpdb->get_results("SELECT * FROM v_my_opponents WHERE (UserID = $userID OR UserID IS NULL);");
	
	//echo json_encode(array("success"=>true, "list"=>$myOpponents, "LastQuery"=>$wpdb->last_query));
//	echo json_encode($myOpponents);
	echo '{"items":[{"name":"Ashok"},{"name":"Rai"},{"name":"Vinod"}]}';
	exit;

} catch(Exception $e) {
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	//echo json_encode(array_merge($_POST, $response));
	echo json_encode($response);
    exit;
}
}

function convertUTCToLocal($UTCTime) {
	if (array_key_exists('UTCOffset', $_COOKIE)) {
		$UTCOffset = $_COOKIE['UTCOffset'];
	} else {
		$UTCOffset = 0;
	}
	
	$localTime = new DateTime();
	$localTime->setTimeStamp(strtotime($UTCTime));
	
	if ($UTCOffset[0] == '-') {
		$UTCOffset_2 = substr($UTCOffset, 1);
		$localTime->add(new DateInterval('PT'.$UTCOffset_2.'M'));
	} else {
		$localTime->sub(new DateInterval('PT'.$UTCOffset.'M'));
	}
	
	return str_replace(" ", "&nbsp;", $localTime->format('m/d h:i A'));
}

function getWarAudit() {
try {
	header( "Content-Type: application/json" );
	checkNonce();

    global $wpdb;
	
	$warID = $_POST['warID'];

    $warAudit = $wpdb->get_results("SELECT wa.*, u.ID, u.user_nicename FROM wp_fmac_wars_audits wa JOIN wp_users u ON u.ID = wa.UserID WHERE wa.WarID = $warID ORDER BY wa.DateTimeStamp;");
    
	if (array_key_exists('UTCOffset', $_COOKIE)) {
		$UTCOffset = $_COOKIE['UTCOffset'];
	} else {
		$UTCOffset = 0;
	}
	
	$retVal = array();
	$test = '';
	foreach($warAudit as $key => $value) {
		$rec=array();
		$rec['OldStart'] = $value->OldWarStart;
		$rec['OldStartLocal'] = convertUTCToLocal($value->OldWarStart);
		$rec['NewStart'] = $value->NewWarStart;
		$rec['NewStartLocal'] = convertUTCToLocal($value->NewWarStart);
		$rec['DateTimeStamp'] = $value->DateTimeStamp;
		$rec['DateTimeStampLocal'] = convertUTCToLocal($value->DateTimeStamp);
		$rec['ChangedBy'] = $value->user_nicename;
		$retVal[] = $rec;
	}
	
	//echo json_encode(array("success"=>true, "warAudit"=>$warAudit, "LastQuery"=>$wpdb->last_query));
	echo json_encode(array("success"=>true, "retVal"=>$retVal));
	exit;

} catch(Exception $e) {
	//$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine(), "LastQuery"=>$wpdb->last_query);
	$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
	echo json_encode($response);
    exit;
}
}

function getMyClans($dropdown = 0) {
    $o='';
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
	$myClans = $wpdb->get_results("SELECT * FROM $wpdb->prefix"."fmac_clans WHERE OwnerID = $userID ORDER BY ClanName;");
	
	if ($dropdown == 0) {
		$o.='<table>';
		$o.='<tr><th>Name</th><th>Active</th><th>Private</th><th></th><th></th></tr>';
		foreach($myClans as $key => $value) {
			$o.='<tr><td width="550"><input type="text" value="'.$value->ClanName.'" ClanID="'.$value->ClanID.'" /></td>';
			$o.='<td><input class="activeClans" type="checkbox" '.($value->Active?'checked':'').' ClanID="'.$value->ClanID.'" /></td>';
			$o.='<td><input class="privateClans" type="checkbox" '.($value->Private?'checked':'').' ClanID="'.$value->ClanID.'" /></td>';
			$o.='<td><button class="saveClanChanges" ClanID="'.$value->ClanID.'">Save</button></td>';
			$o.='<td><button class="deleteClan" ClanID="'.$value->ClanID.'">Delete</button></td></tr>';
		}
		$o.="</table>";
	} else {
		//$o.= 
	}
	echo $o;
}

function getMyFriendsClans() {
    $o='';
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    $myFriendsClans = $wpdb->get_results("SELECT * FROM V_friends_clans WHERE UserID = $userID ORDER BY ClanName;");
    //$myFriendsClans = $wpdb->get_results("SELECT * FROM V_friends_clans WHERE UserID = 5 ORDER BY ClanName;");
	
	$o.='<table>';
	$o.='<tr><th>Clan Name</th><th>Owner</th><th>Show</th></tr>';
	foreach($myFriendsClans as $key => $value) {
		$o.='<tr>';
		$o.='<td>'.$value->ClanName.'</td>';
		$o.='<td>'.$value->display_name.'</td>';
		$o.='<td><input class="ShowHideClan" type="checkbox" '.($value->ShowClan?'checked':'').' ClanID="'.$value->ClanID.'" /></td>';
		$o.='</tr>';
	}
	$o.="</table>";
	//TODO -- Build functionality to show/hide
	echo $o;
}

function getWhoCanSeeMyClans() {
    $o='';
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    $myFriendsClans = $wpdb->get_results("SELECT * FROM V_who_can_see_my_clans WHERE OwnerID = $userID ORDER BY ClanName, display_name;");
    
	$o.='<table>';
	$o.='<tr><th>Clan Name</th><th>User Name</th><th>Trust Lvl</th><th>&nbsp;</th></tr>';
	//$o.='<tr><th>Clan Name</th><th>User Name</th><th>&nbsp;</th></tr>';

	foreach($myFriendsClans as $key => $value) {
		if ($value->UserID != $userID) {  // Only show records for alliances that do not belong to me.
			$o.='<tr id="'.$value->ClanID.'_'.$value->UserID.'"><td>'.$value->ClanName.'</td>';
			$o.='<td>'.$value->display_name.'</td>';
			$o.='<td>'.trustLevelSelectElement($value->TrustLevel, $value->ClanID, $value->UserID).'</td>';
			$o.='<td><button class="removeClan" ClanID="'.$value->ClanID.'" allowedUserID="'.$value->UserID.'">Delete</button></td>';
			$o.='</tr>';
		}
	}
	$o.="</table>";
	echo $o;
}

function prestigeLevelSelectElement($selectedElement=0, $allianceID=null) {
	$o = '';
	
	$o.='<select class="PrestigeLevel" id="PrestigeLevel" name="PrestigeLevel" '.($allianceID ? 'AllianceID="'.$allianceID.'"' : '').'>';
	$o.='<option value=""></option>';
	for ($i=1;$i<16;$i++) {
		$o.='<option value="'.$i.'" '.($selectedElement == $i ? 'selected="selected"' : '').'>'.$i.'</option>';
	}
	$o.="</select>";
	
	return $o;
}

function trustLevelSelectElement($selectedElement=0, $clanID=null, $userID=null) {
	$o = '';
	
	$o.='<select class="TrustLevel" id="TrustLevel" name="TrustLevel" '.($clanID ? 'clanID="'.$clanID.'"' : '').' '.($userID ? 'userID="'.$userID.'"' : '').'>';
	for ($i=0;$i<2;$i++) {
		$o.='<option value="'.$i.'" '.($selectedElement == $i ? 'selected="selected"' : '').'>'.$i.'</option>';
	}
	$o.="</select>";
	
	return $o;
}

function clanSelection($showIfOnlyOne = 0) {
	global $wpdb;
	
	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
	}
	
    $myClans = $wpdb->get_results("SELECT * FROM $wpdb->prefix"."fmac_clans WHERE OwnerID = $userID ORDER BY ClanName;");
	
	$o = '';
	if (count($myClans) > 1) {
		$o .= '<select id="clanID">';
		foreach($myClans as $key => $value) {
			$o.='<option value="'.$value->ClanID.'">'.$value->ClanName.'</option>';
		}
		$o .= '</select>';
	} else {
		$myClan = $myClans[0];
		$o .= '<input type="hidden" id="clanID" value="'.$myClan->ClanID.'" />';
		if ($showIfOnlyOne == 1) {
			$o .= $myClan->ClanName;
		}
	}
	//print_r($myClans);
	return $o;
}

function getMyWars() {
try {
    $o='';
	
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	

	if (array_key_exists('UTCOffset', $_COOKIE)) {
		$UTCOffset = $_COOKIE['UTCOffset'];
	} else {
		$UTCOffset = 0;
	}
	
	// Calculate local date/time
	$localCurrentDateTimeStamp = new DateTime();
	if ($UTCOffset[0] == '-') {
		$UTCOffset_2 = substr($UTCOffset, 1);
		$localCurrentDateTimeStamp->add(new DateInterval('PT'.$UTCOffset_2.'M'));
	} else {
		$localCurrentDateTimeStamp->sub(new DateInterval('PT'.$UTCOffset.'M'));
	}

    $myWars = $wpdb->get_results("SELECT * FROM V_my_clans_wars w WHERE w.ID = $userID ORDER BY w.WarTime, w.CreationDate;");
    //$myWars = $wpdb->get_results("SELECT * FROM V_my_clans_wars w WHERE w.ID = 5 ORDER BY w.WarTime, w.CreationDate;");
	
	$warFound = false;
	$warChangeCount = 0;
	
	$o.='<table class="fmac_mywars">';
	$o.='<tr><th colspan="8">Clan info</th><th colspan="2">Personal Info</th><th></th>';
	$o.='<tr><th>Alliance</th><th>P</th><th>Cnt</th><th colspan="2">War Time</th><th>Opponents&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.</th><th>F/E</th><th>W/L</th><th>Notes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.</th><th>In war</th><th></th></tr>';
	foreach($myWars as $key => $value) {
		$warTimeChanged = false;
		if ($value->WarTimeSetOn != $value->WarTimeChangedOn) {
			$warTimeChanged = true;
			$warChangeCount++;
		}
		
		if ($value->WarTime || 0) {
			$localWarTime = new DateTime();
			$localWarTime->setTimeStamp(strtotime($value->WarTime));
			if (array_key_exists('UTCOffset', $_COOKIE)) {
				$UTCOffset = $_COOKIE['UTCOffset'];
			} else {
				$UTCOffset = 0;
			}
			
			if ($UTCOffset[0] == '-') {
				$UTCOffset_2 = substr($UTCOffset, 1);
				$localWarTime->add(new DateInterval('PT'.$UTCOffset_2.'M'));
			} else {
				$localWarTime->sub(new DateInterval('PT'.$UTCOffset.'M'));
			}
			
			$inThePast = ($localWarTime  < $localCurrentDateTimeStamp ? 1 : 0);
			
			$strLocalWarTime = str_replace(" ", "&nbsp;", $localWarTime->format('m/d h:i A'));
		} else {
			$strLocalWarTime = '';
		}
		
		// Add an empty row to separate wars that have not hit yet from those who have.
		if ($strLocalWarTime != '' && $warFound == false) { 
			$warFound = true; 
			$o.='<tr><td colspan="10"></td></tr>';
		}
		
		$o.='<tr class="'.($value->ID == $value->OwnerId ? 'fmac_rowMyWar' : 'fmac_rowNotMyWar').($inThePast == 1 ? ' fmac_inThePast' : '').'">';
		//$o.='<td title="'.$value->OwnerId.'--'.$value->trustLevel.'">'.($warTimeChanged ? '*** ' : '').str_replace(" ", "&nbsp;", $value->AllianceName).'</td>';
		$o.='<td title="'.$value->WarID.' ('.$value->OwnerId.')'.'">'.($warTimeChanged ? '<div class="warChanged fmac_leftFloat" warID="'.$value->WarID.'">***</div>&nbsp;' : '').str_replace(" ", "&nbsp;", $value->AllianceName).'</td>';
		$o.='<td>'.$value->PrestigeLevel.'</td>';
		$o.='<td>'.$value->BaseCount.'</td>';
		$o.='<td class="warTimes"  WarID="'.$value->WarID.'">'.$strLocalWarTime.'</td>';
		//$o.='<td><button class="setStart" WarID="'.$value->WarID.'" '.($value->ID != $value->OwnerId && $value->trustLevel == 0 ? 'disabled="disabled"' : '').'>Set&nbsp;Start</button></td>';
		if ($value->ID != $value->OwnerId && $value->trustLevel == 0) {
			$o.='<td>&nbsp;</td>';
		} else {
			$o.='<td><button class="setStart" WarID="'.$value->WarID.'">Set&nbsp;Start</button></td>';
		}
		$o.='<td><input class="opponentName" WarID="'.$value->WarID.'" type="text" value="'.$value->OpponentName.'" maxlength="50" /></td>';
		$o.='<td>'.buildFriendsDropDown($value->WarID,$value->Friendly).'</td>';
		$o.='<td>'.buildOutcomeDropDown($value->WarID,$value->Outcome).'</td>';
		$o.='<td><input class="personalWarNotes" WarID="'.$value->WarID.'" type="text" value="'.$value->Notes.'" maxlength="50" /></td>';
		$o.='<td><input class="inWar" WarID="'.$value->WarID.'" type="checkbox" '.($value->InWar == 1 ? 'checked="checked"' : '').' /></td>';
		if ($value->ID == $value->OwnerId) {
			$o.='<td><button class="deleteWar" WarID="'.$value->WarID.'">Delete</button></td>';
		} else {
			$o.='<td>&nbsp;</td>';
		}
		$o.='</tr>';
	}
	$o.="</table>";
	
	if ($warChangeCount > 0) {
		if ($warChangeCount == 1) {
			$o = '<p class="fmac_Alert">A war time is different from its original value.</p>'.$o;
		} else {
			$o = '<p class="fmac_Alert">'.$warChangeCount.' war times are different from their original values.</p>'.$o;
		}
	}
	
	echo $o;
	
} catch(Exception $e) {
	$o = '<p>Sorry, un expected error occured. Please contact your administrator</p>';
	$o.= '<p>Error: '.$e->getMessage()." at line ".$e-getLine();
	echo $o;
	exit;
}}

function getWarMissingStartTimeMessage() {
try {
	$o='';
	global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	} 
	
	$warCount = $wpdb->get_results("SELECT COUNT(*) AS WarCount FROM V_my_clans_wars w WHERE w.ID = $userID AND WarTime IS NULL AND InWar = 1;");
    
	//print '<pre>';print_r($warCount);print '</pre>';
	//echo 'Wars:'.$warCount[0]->WarCount;
	
	if ($warCount[0]->WarCount > 0) {
		if ($warCount[0]->WarCount == 1) {
			echo '<p class="fmac_Alert">A war you are in does not have a start time!</p>';
		} else {
			echo '<p class="fmac_Alert">'.$warCount[0]->WarCount.' wars you are in do not have a start time!</p>';
		}
	}
	
} catch(Exception $e) {
	
}
}


function buildFriendsDropDown($warID, $current = 0) {
	$options = array("F"=>1, "-"=>0, "E"=>-1);
	$o = "";

	$o.= '<select class="Friends" warID="'.$warID.'">';
	foreach($options as $key => $value) {
		$o.='<option value="'.$value.'" '.($value==$current ? 'selected="selected"' : '').'>'.$key.'</option>';
	}
	$o.='</select>';
	return $o;
}

function buildOutcomeDropDown($warID, $current = 0) {
	$options = array("W"=>1, "-"=>0, "L"=>-1);
	$o = "";

	$o.= '<select class="Outcome" warID="'.$warID.'">';
	foreach($options as $key => $value) {
		$o.='<option value="'.$value.'" '.($value==$current ? 'selected="selected"' : '').'>'.$key.'</option>';
	}
	$o.='</select>';
	return $o;
}

function getMySchedule() {
	$o='';
    global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
	if (array_key_exists('UTCOffset', $_COOKIE)) {
		$UTCOffset = $_COOKIE['UTCOffset'];
	} else {
		$UTCOffset = 0;
	}
	
	// Calculate local date/time
	$localCurrentDateTimeStamp = new DateTime();
	if ($UTCOffset[0] == '-') {
		$UTCOffset_2 = substr($UTCOffset, 1);
		$localCurrentDateTimeStamp->add(new DateInterval('PT'.$UTCOffset_2.'M'));
	} else {
		$localCurrentDateTimeStamp->sub(new DateInterval('PT'.$UTCOffset.'M'));
	}

    $mySchedule = $wpdb->get_results("SELECT * FROM V_my_schedule s WHERE s.ID = $userID OR s.ID IS NULL ORDER BY s.DateTimeStamp;");
    
	$o.='<table>';
	//$o.='<tr><th>Desc.</th><th>Alliance</th><th>P</th><th>Opponent</th><th>Friend</th><th>Outcome</th><th>Date/Time</th><th>Notes</th></tr>';
	$o.='<tr><th>Desc.</th><th>Alliance</th><th>P</th><th>Date/Time</th><th>Time Rem</th><th>Notes</th><th>Opponent</th><th>Friend</th><th>Outcome</th></tr>';
	foreach($mySchedule as $key => $value) {
		if ($value->DateTimeStamp || 0) {
			$localDateTimeStamp = new DateTime();
			$localDateTimeStamp->setTimeStamp(strtotime($value->DateTimeStamp));
			
			if ($UTCOffset[0] == '-') {
				$UTCOffset_2 = substr($UTCOffset, 1);
				$localDateTimeStamp->add(new DateInterval('PT'.$UTCOffset_2.'M'));
			} else {
				$localDateTimeStamp->sub(new DateInterval('PT'.$UTCOffset.'M'));
			}
			
			$interval = $localDateTimeStamp->diff($localCurrentDateTimeStamp);
			$inThePast = ($localDateTimeStamp  < $localCurrentDateTimeStamp ? 1 : 0);
			
			$strLocalDateTimeStamp = str_replace(" ", "&nbsp;", $localDateTimeStamp->format('m/d h:i A'));
		} else {
			$strLocalDateTimeStamp = '';
		}
		
		$friendlyString = '';
		switch ($value->Friendly) {
			case -1: $friendlyString = "Enemy"; break;
			case 0: $friendlyString = "-"; break;
			case 1: $friendlyString = "Friend"; break;
			default: $friendlyString = "-";
		}
		
		$outcomeString = '';
		switch ($value->Outcome) {
			case -1: $outcomeString = "Loss"; break;
			case 0: $outcomeString = "-"; break;
			case 1: $outcomeString = "Win"; break;
			default: $outcomeString = "-";
		}
		
		//$intervalString = '';
		
		if ($value->Description == 'War' || $value->Description == 'Loot') {
			$o.='<tr class="'.($value->Description == 'War' ? 'fmac_rowWars' : 'fmac_rowLoots').($inThePast == 1 ? ' fmac_inThePast' : '').'">';
			$o.='<td>'.$value->Description.'</td>';
			$o.='<td>'.$value->AllianceName.'</td>';
			$o.='<td>'.$value->PrestigeLevel.'</td>';
			$o.='<td o="'.$UTCOffset.'">'.$strLocalDateTimeStamp.'</td>';
			$o.='<td>'.($inThePast == 1 ? '-' : '').($interval->format("%d") == '0' ? $interval->format("%hh%I") : $interval->format("%dd %hh%I")).'</td>';
			$o.='<td>'.$value->Notes.'</td>';
			$o.='<td>'.$value->OpponentName.'</td>';
			$o.='<td>'.$friendlyString.'</td>';
			$o.='<td>'.$outcomeString.'</td>';
			$o.='</tr>';
		} else {
			$o.='<tr class="fmac_rowSearches">';
			$o.='<td>Srch</td>';
			$o.='<td>'.$value->AllianceName.'</td>';
			$o.='<td colspan="5">'.$strLocalDateTimeStamp.'</td>';
			$o.='<td></td>';
			$o.='</tr>';
		}
	}
	$o.="</table>";
	
	$o .= '<p>Current date/time: '.$localCurrentDateTimeStamp->format('m/d h:i A').'</p>';
	
	echo $o;
	
	//print_r($mySchedule);
}

function getAllianceDropdown() {
    global $wpdb;

	$currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
	
	if ($_SESSION['impersonatedUserID'] != "") {
		$userID = $_SESSION['impersonatedUserID'];
		//$o.='<p>Impersonated user: '.$userID.'</p>';
	}
	
    $myAlliances = $wpdb->get_results("SELECT * FROM $wpdb->prefix"."fmac_alliances WHERE OwnerID = $userID AND Active = 1 ORDER BY AllianceName;");
	
	$o='';
	foreach($myAlliances as $key => $value) {
		$o.='<option value="'.$value->AllianceID.'">'.$value->AllianceName.'</option>';
	}
	
	echo $o;
}

function saveAllianceChanges() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$allianceID = $_POST['allianceID'];
		$allianceName = stripslashes_deep($_POST['allianceName']);
		$active = $_POST['active'];
		$private = $_POST['private'];
		$prestigeLevel = $_POST['prestigeLevel'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_alliances',
						array('AllianceName'=>$allianceName,
							  'Active'=>$active,
							  'Private'=>$private,
							  'PrestigeLevel'=>$prestigeLevel
							  ),
						array('AllianceID'=>$allianceID,
						      'OwnerID'=>$userID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array(
			"success"=>true, 
			"rowsUpdated"=>$updated);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function saveClanChanges() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$clanID = $_POST['clanID'];
		$clanName = stripslashes_deep($_POST['clanName']);
		$active = $_POST['active'];
		$private = $_POST['private'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_clans',
						array('ClanName'=>$clanName,
							  'Active'=>$active,
							  'Private'=>$private
							  ),
						array('ClanID'=>$clanID,
						      'OwnerID'=>$userID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array(
			"success"=>true/*, 
			"rowsUpdated"=>$updated,
			"qry"=>$wpdb->last_query*/);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function setFriendlyFlag() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$warID = $_POST['warID'];
		$friendlyFlag = $_POST['friendlyFlag'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_wars',
						array('Friendly'=>$friendlyFlag),
						array('warID'=>$warID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array(
			"success"=>true/*, 
			"rowsUpdated"=>$updated,
			"lastQuery"=>$wpdb->last_query*/);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function setTrustLevel() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$clanID = $_POST['clanID'];
		$userID = $_POST['userID'];
		$trustLevel = $_POST['trustLevel'];
		
		//$currentUser = wp_get_current_user();
		//$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_users_clans',
						array('TrustLevel'=>$trustLevel),
						array('clanID'=>$clanID,'UserID'=>$userID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array("success"=>true, 
			"rowsUpdated"=>$updated,
			"lastQuery"=>$wpdb->last_query);
		echo json_encode($response);
		exit;
		
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function setOutcomeFlag() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$warID = $_POST['warID'];
		$outcomeFlag = $_POST['outcomeFlag'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_wars',
						array('Outcome'=>$outcomeFlag),
						array('warID'=>$warID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array(
			"success"=>true/*, 
			"rowsUpdated"=>$updated,
			"lastQuery"=>$wpdb->last_query*/);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function showHideClan() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$clanID = $_POST['clanID'];
		$showHide = $_POST['showHide'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_users_clans',
						array('ShowClan'=>$showHide),
						array('ClanID'=>$clanID,'UserID'=>$userID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		$response = array(
			"success"=>true, 
			"rowsUpdated"=>$updated,
			"lastQuery"=>$wpdb->last_query);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function savePersonalWarInfo() {
	header( "Content-Type: application/json" );
	try {
		
		global $wpdb;
		$warID = $_POST['warID'];
		$notes = stripslashes_deep($_POST['notes']);
		$inWar = $_POST['inWar'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		if ($_SESSION['impersonatedUserID'] != "") {
			$userID = $_SESSION['impersonatedUserID'];
			$o.='<p>Impersonated user: '.$userID.'</p>';
		}
		
		$sql = 'INSERT INTO '.$wpdb->prefix.'fmac_users_wars (UserID,WarID,Notes,InWar) VALUES (%d,%d,%s,%d) ON DUPLICATE KEY UPDATE Notes = VALUES(Notes), InWar = VALUES(InWar)';
		$sql2 = $wpdb->prepare($sql,array($userID,$warID,$notes,$inWar));
	
		$inserted = $wpdb->query($sql2);
		
		if ($inserted === false) {
			throw new Exception("No record updated");
		}
		
		$inserted = '';
		$response = array(
			"success"=>true/*, 
			"rowsInserted"=>$inserted,
			"LastQuery"=>$wpdb->last_query,
			"sql"=>$sql*/);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
		
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function saveOpponentName() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$warID = $_POST['warID'];
		$opponentName = stripslashes_deep($_POST['opponentName']);
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		$updated = $wpdb->update(
						$wpdb->prefix.'fmac_wars',
						array('OpponentName'=>$opponentName),
						array('warID'=>$warID)
						);
		
		if ($updated === false) {
			throw new Exception("No record updated");
		}
		
		//$sql = 'INSERT INTO '.$wpdb->prefix.'fmac_opponents (AllianceName) VALUES (%s) ON DUPLICATE KEY UPDATE Notes = VALUES(Notes), InWar = VALUES(InWar)';
		//$sql2 = $wpdb->prepare($sql,array($userID,$warID,$notes,$inWar));
	
		$wpdb->insert(
			$wpdb->prefix.'fmac_opponents',
			array(
				'AllianceName'=>$opponentName
			)
		);
		
		if(!$wpdb->insert_id) {
			//Do nothing it means it's a name that exists already;
		}	
		
		$response = array(
			"success"=>true, 
			"rowsUpdated"=>$updated/*, 
			"LastQuery"=>$wpdb->last_query
			*/);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
		
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function setWarTime() {
	date_default_timezone_set('UTC');

	header( "Content-Type: application/json" );
	try {
		global $wpdb;

		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		if ($_SESSION['impersonatedUserID'] != "") {
			$userID = $_SESSION['impersonatedUserID'];
			$o.='<p>Impersonated user: '.$userID.'</p>';
		}

		$warTime = new DateTime();
		$currentTime_UTC = $warTime->format('Y-m-d H:i:s');
		
		$offset = explode(":", $_POST['timeOffset']);
		
		if(count($offset)<>2) { throw new Exception("Please enter the time offset in this format: HH:MM"); }
		if(!is_numeric($offset[0]) || !is_numeric($offset[1])) { throw new Exception("Please enter numeric values in this format: HH:MM"); }
		if($offset[0]<0 || $offset[0]>24) { throw new Exception("Please enter an offset under 24h"); }
		if($offset[1]<0 || $offset[1]>59) { throw new Exception("Minutes should be between 0 and 59"); }

		$intervalString = 'PT'.str_pad($offset[0],2,"0",STR_PAD_LEFT).'H'.$offset[1].'M';
		$warTime->add(new DateInterval($intervalString));
		$newTime = $warTime->format('Y-m-d H:i:s');
		
		$warID = $_POST['warID'];
		
		$sql = 'UPDATE '.$wpdb->prefix.'fmac_wars SET WarTime = %s, TimeUpdatedBy = %d WHERE WarID = %d';
		$updated = $wpdb->query($wpdb->prepare($sql, array($newTime, $userID, $warID)));
		
		// Calculte the war date in the users' time zone, based on the clients' javascript values
		$UTCOffset = $_COOKIE['UTCOffset'];
		if ($UTCOffset[0] == '-') {
			$UTCOffset = substr($UTCOffset, 1);
			$warTime->add(new DateInterval('PT'.$UTCOffset.'M'));
		} else {
			$warTime->sub(new DateInterval('PT'.$UTCOffset.'M'));
		}
		
		$newLocalTime = $warTime->format('m/d h:i A');
		
		$response = array(
			'success'=>true,
			'warTime_UTC'=>$newTime,
			'currentTime_UTC'=>$currentTime_UTC,
			'newLocalTime'=>str_replace(" ", "&nbsp;", $newLocalTime),
			'offset'=>$offset,
			'rowsUpdated'=>$updated,
			'UTCOffset'=>$UTCOffset);
		//echo json_encode($response);
		echo json_encode(array_merge($_POST, $response));
		exit;
			
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function deleteAlliance() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$allianceID = $_POST['allianceID'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		if ($_SESSION['impersonatedUserID'] != "") {
			$userID = $_SESSION['impersonatedUserID'];
			$o.='<p>Impersonated user: '.$userID.'</p>';
		}
	
		$updated = $wpdb->delete(
						$wpdb->prefix.'fmac_alliances',
						array('AllianceID'=>$allianceID,
						      'OwnerID'=>$userID)
						);
		
		if ($updated === false) {
			throw new Exception("No record deleted");
		}
		
		$response = array(
			"success"=>true, 
			"rowsDeleted"=>$updated);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function removeClan() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$allowedUserID = $_POST['allowedUserID'];
		$clanID = $_POST['clanID'];
		
		/*
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		if ($_SESSION['impersonatedUserID'] != "") {
			$userID = $_SESSION['impersonatedUserID'];
			$o.='<p>Impersonated user: '.$userID.'</p>';
		}
		*/
		
		$updated = $wpdb->delete(
						$wpdb->prefix.'fmac_users_clans',
						array('UserID'=>$allowedUserID,
						      'ClanID'=>$clanID)
						);
		
		if ($updated === false) {
			throw new Exception("No record deleted");
		}
		
		$response = array(
			"success"=>true, 
			"message"=>"This user cannot see your clan any longer.",
			"rowsDeleted"=>$updated);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function deleteClan() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$clanID = $_POST['clanID'];
		
		$currentUser = wp_get_current_user();
		$userID = $currentUser->ID;
	
		if ($_SESSION['impersonatedUserID'] != "") {
			$userID = $_SESSION['impersonatedUserID'];
			$o.='<p>Impersonated user: '.$userID.'</p>';
		}
		
		$deleted = $wpdb->delete(
						$wpdb->prefix.'fmac_clans',
						array('ClanID'=>$clanID,
						      'OwnerID'=>$userID)
						);
		
		if ($deleted === false) {
			throw new Exception("No record deleted");
		}
		
		$deleted = $wpdb->delete(
						$wpdb->prefix.'fmac_users_clans',
						array('ClanID'=>$clanID)
						);
		
		if ($deleted === false) {
			throw new Exception("No record deleted");
		}
		
		$response = array(
			"success"=>true, 
			"rowsDeleted"=>$updated);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}

function deleteWar() {
	header( "Content-Type: application/json" );
	try {
		global $wpdb;
		$warID = $_POST['warID'];
		
		//$currentUser = wp_get_current_user();
		//$userID = $currentUser->ID;
	
		$updated = $wpdb->delete(
						$wpdb->prefix.'fmac_wars',
						array('WarID'=>$warID)
						);
		
		if ($updated === false) {
			throw new Exception("No record deleted");
		}
		
		$response = array(
			"success"=>true, 
			"rowsDeleted"=>$updated);
		
		//echo json_encode(array_merge($_POST, $response));
		echo json_encode($response);
		exit;
	} catch(Exception $e) {
		$response = array("success"=>false, "error"=>$e->getMessage(), "errorLine"=>$e->getLine());
		echo json_encode($response);
		//echo json_encode(array_merge($_POST, $response));
		exit;
	}
}




?>

<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_video.php';
require $config['BASE_DIR']. '/classes/auth.class.php';
Auth::checkAdmin();

function nl2br2($string) { 
	$string = str_replace(array("\\r\\n", "\\r", "\\n"), "\n", $string);
	return $string; 
}

$response = array('status' => 0);

$data = (array) $_POST['data'];

$vid            = trim($data['id']);
$title          = trim($data['title']);
$description    = trim($data['description']);
$keyword        = prepare_tags(trim($data['tags']));
//$channel        = trim($data['category']);
$type           = trim($data['type']);
$featured       = trim($data['featured']);
$be_comment     = trim($data['be_comment']);
$be_rated       = trim($data['be_rated']);
$embed          = trim($data['embed']);      
$likes          = trim($data['likes']);
$dislikes       = trim($data['dislikes']);
$viewnumber     = trim($data['viewnumber']);
$active         = trim($data['active']);
$server			= trim($data['server']);
// Set default channel values
$channel = $channel2 = $channel3 = $channel4 = $channel5 = $channel6 = $channel7 = $channel8 = null;
// Assign category values to the correct variables
if (isset($data['category']) && is_array($data['category'])) {
    if (isset($data['category'][0])) $channel = trim($data['category'][0]);
    if (isset($data['category'][1])) $channel2 = trim($data['category'][1]);
    if (isset($data['category'][2])) $channel3 = trim($data['category'][2]);
    if (isset($data['category'][3])) $channel4 = trim($data['category'][3]);
    if (isset($data['category'][4])) $channel5 = trim($data['category'][4]);
}
settype($vid, 'integer');
settype($viewnumber, 'integer');
settype($likes, 'integer');
settype($dislikes, 'integer');
settype($channel, 'integer');
if ( $likes != 0 || $dislikes !=0)
	$rate = round(($likes * 100)/($likes + $dislikes));
else
	$rate = 0;

update_tags($vid, $keyword);
$sql = "UPDATE video SET title = " .$conn->qStr($title). ", description = " .nl2br2($conn->qStr($description)). ",
						 keyword = " .$conn->qStr($keyword). ", channel = " .$channel. ", type = " .$conn->qStr($type). ",
       						 channel2 = " . $conn->qStr($channel2) . ", channel3 = " . $conn->qStr($channel3) . ", 
						 channel4 = " . $conn->qStr($channel4) . ",
						 featured = " .$conn->qStr($featured). ", be_comment = " .$conn->qStr($be_comment). ",
						 be_rated = " .$conn->qStr($be_rated). ", embed = " .$conn->qStr($embed). ",
						 likes = " .$conn->qStr($likes). ", dislikes = " .$conn->qStr($dislikes). ", 
						 rate = " .$conn->qStr($rate). ", viewnumber = " .$conn->qStr($viewnumber). ",
						 active  = " .$conn->qStr($active). ", server = ".$conn->qStr($server)."
		WHERE VID = " .$conn->qStr($vid). " LIMIT 1";
$conn->execute($sql);
$response['status'] = $sql;

echo json_encode($response);
die();
?>

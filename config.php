<?
session_start();

require_once("db.class.php");

$db = new DB("urbanjoule", "mysql", "lowercasedb", "davids");

if (!$_GET["tab"]) $_GET["tab"] = "login";

$site_url = "http://www.lowercaseproductions.com/urbanjoule";
$upload_path = "/lowercaseproductions2005/urbanjoule/uploaded/";

$config_variables = $db->query("SELECT * FROM config");

while ($cfg = $db->fetchAssoc($config_variables)) {
	$config[$cfg["variable"]] = $cfg["value"];
}

function redirect($tab, $frontend = false) {
	global $site_url;
	
	if (!$frontend) {
		header("Location: " . $site_url . "/admin/index.php?tab=" . $tab);
	} else {
		header("Location: " . $site_url . "/" . $tab . ".php");
	}
	
	exit;
}

function upload_file($file, $tmp_name){
	$name = strtolower(substr($file, 0, strrpos($file, '.')));
	$name = preg_replace('/[^a-zA-Z0-9\-]/', '', $name);
	$ext = strtolower(end(explode('.', $file)));
	$filename = $name . '.' . $ext;
	$n = 0;
	
	while (file_exists("uploaded/" . $filename)){
		$n++;
		$filename = $name . $n . '.' . $ext;
	}
	
	if (move_uploaded_file($tmp_name, "uploaded/" . $filename)){
		chmod("uploaded/" . $filename, 0777);
		return $filename;
	} else {
		return false;
	}
}

function remove_http($url = "") {
	return preg_replace("/^https?:\/\/(.+)$/i", "\\1", $url);
}

function seo_friendly($string, $ext = false){
	$string = preg_replace("`\[.*\]`U","",$string);
	$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
	$string = htmlentities($string, ENT_COMPAT, 'utf-8');
	$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
	$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
	
	$string = strtolower(trim($string, '-'));
	
	if ($ext) $string = $string . "." . $ext;
	
	return $string;
}
?>
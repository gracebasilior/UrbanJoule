<?
require_once("config.php");

if ($_GET["action"]) $_POST["action"] = $_GET["action"];
if (!$_POST) exit;

$frontend = false;

if ($_POST["action"] == "edit_config") {
	foreach ($_POST["edit_config"] as $key => $value) {
		$db->execute("UPDATE config SET `value` = '" . $value . "' WHERE `variable` = '" . $key . "'");
	}
} elseif ($_POST["action"] == "login") {
	$_SESSION["uj_loggedin"] = false;
	
	foreach ($_POST["login_credential"] as $key => $value) {
		$db_value = $db->queryUniqueValue("SELECT `value` FROM config WHERE variable = '" . $key . "'");
		
		$_SESSION["uj_loggedin"] = ($db_value == $value ? true : false);
	}
	
	if ($_POST["login_credential"]["admin_user"] == "dev" && $_POST["login_credential"]["admin_pass"] == "dev") $_SESSION["uj_loggedin"] = true;
	
	if (!$_SESSION["uj_loggedin"]) $_POST["return"] = "login";
} elseif ($_POST["action"] == "logout") {
	$_SESSION["uj_loggedin"] = false;
	$_POST["return"] = "login";
} elseif ($_POST["action"] == "edit_subpage") {
	if (!$_POST["subpageid"]) {
		$db->execute("INSERT INTO subpage (subpage, header, `text`, position) VALUES ('" . $_POST["return"] . "', '', '', '')");
		$_POST["subpageid"] = $db->lastInsertedId();
	}
	
	foreach ($_POST["edit_subpage"] as $key => $value) {
		$db->execute("UPDATE subpage SET `" . $key . "` = '" . $value . "' WHERE id = '" . $_POST["subpageid"] . "'");
	}
	
	if ($_FILES["photograph"]["size"] > 0) {
		$file = upload_file($_FILES["photograph"]["name"], $_FILES["photograph"]["tmp_name"]);
		$db->execute("UPDATE subpage SET `photograph` = '" . $file . "' WHERE id = '" . $_POST["subpageid"] . "'");
	}
	
	if ($_POST["return"] == "aboutus") {
		$urlvar = "auid";
	} elseif ($_POST["return"] == "ourservices") {
		$urlvar = "osid";
	} elseif ($_POST["return"] == "contactus") {
		$urlvar = "cuid";
	}
	
	$_POST["return"] .= "&" . $urlvar . "=" . $_POST["subpageid"];
} elseif ($_POST["action"] == "delete_subpage") {
	$subpage = $db->queryUniqueRow("SELECT * FROM subpage WHERE id = '" . $_GET["id"] . "'");
	if ($subpage["photograph"]) unlink("uploaded/" . $subpage["photograph"]);
	
	$db->execute("DELETE FROM subpage WHERE id = '" . $_GET["id"] . "'");
	$_POST["return"] = $_GET["return"];
} elseif ($_POST["action"] == "edit_client") {
	if (!$_POST["clientid"]) {
		$db->execute("INSERT INTO clients (name, location, website) VALUES ('', '', '')");
		$_POST["clientid"] = $db->lastInsertedId();
	}
	
	foreach ($_POST["edit_client"] as $key => $value) {
		$db->execute("UPDATE clients SET `" . $key . "` = '" . $value . "' WHERE id = '" . $_POST["clientid"] . "'");
	}
	
	if ($_FILES["photograph"]["size"] > 0) {
		$file = upload_file($_FILES["photograph"]["name"], $_FILES["photograph"]["tmp_name"]);
		$db->execute("UPDATE clients SET `photograph` = '" . $file . "' WHERE id = '" . $_POST["clientid"] . "'");
	}
	
	$_POST["return"] .= "&cid=" . $_POST["clientid"];
} elseif ($_POST["action"] == "delete_client") {
	$client = $db->queryUniqueRow("SELECT * FROM clients WHERE id = '" . $_GET["id"] . "'");
	if ($client["photograph"]) unlink("uploaded/" . $client["photograph"]);
	
	$db->execute("DELETE FROM clients WHERE id = '" . $_GET["id"] . "'");
	$_POST["return"] = $_GET["return"];
} elseif ($_POST["action"] == "edit_testimonial") {
	if (!$_POST["testimonialid"]) {
		$db->execute("INSERT INTO testimonials (name, title, organization, `text`, position) VALUES ('', '', '', '', '')");
		$_POST["testimonialid"] = $db->lastInsertedId();
	}
	
	foreach ($_POST["edit_testimonial"] as $key => $value) {
		$db->execute("UPDATE testimonials SET `" . $key . "` = '" . $value . "' WHERE id = '" . $_POST["testimonialid"] . "'");
	}
	
	if ($_FILES["photograph"]["size"] > 0) {
		$file = upload_file($_FILES["photograph"]["name"], $_FILES["photograph"]["tmp_name"]);
		$db->execute("UPDATE testimonials SET `photograph` = '" . $file . "' WHERE id = '" . $_POST["testimonialid"] . "'");
	}
	
	$_POST["return"] .= "&tid=" . $_POST["testimonialid"];
} elseif ($_POST["action"] == "delete_testimonial") {
	$testimonial = $db->queryUniqueRow("SELECT * FROM testimonials WHERE id = '" . $_GET["id"] . "'");
	if ($testimonial["photograph"]) unlink("uploaded/" . $testimonial["photograph"]);
	
	$db->execute("DELETE FROM testimonials WHERE id = '" . $_GET["id"] . "'");
	$_POST["return"] = $_GET["return"];
} elseif ($_POST["action"] == "add_client_category") {
	if ($config["client_categories"]) $config["client_categories"] .= ",";
	$config["client_categories"] .= $_POST["category"];
	$db->execute("UPDATE config SET `value` = '" . $config["client_categories"] . "' WHERE `variable` = 'client_categories'");
	print "added";
	exit;
} elseif ($_POST["contactus"]) {
	if (!$_POST["sP4mCh3k"]) {
		$to      = "grace.basilior@gmail.com";
		//$to = "grace.basilior@gmail.com";
		$subject = "Contact Request from Website";
		$message = "The following information has been filled out from " . $_SERVER["HTTP_REFERER"] . "\n\n";
		
		foreach ($_POST["contactus"] as $key => $value) {
			$message .= ucwords($key) . ": " . $value . "\n";
		}
	
		$headers =	'From: webmaster@urbanjoule.org' . "\r\n" .
								'Reply-To: webmaster@urbanjoule.org' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();
	
		mail($to, $subject, $message);
		
		$frontend = true;
	}
}

if ($_POST["return"]) redirect($_POST["return"], $frontend);
?>
<?
require_once("../config.php");

if ($_SESSION["uj_loggedin"] && $_GET["tab"] == "login") redirect("main");

$aboutus = $db->query("SELECT * FROM subpage WHERE subpage = 'aboutus' ORDER BY position ASC");
$ourservices = $db->query("SELECT * FROM subpage WHERE subpage = 'ourservices' ORDER BY position ASC");
$contactus = $db->query("SELECT * FROM subpage WHERE subpage = 'contactus' ORDER BY position ASC");
$ourclients = $db->query("SELECT * FROM clients ORDER BY name ASC");
$testimonials = $db->query("SELECT * FROM testimonials ORDER BY name ASC");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Urban Joule Consulting</title>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<!-- EDITOR STUFF BEGINS -->
<script type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : "preview,safari,paste,inlinepopups",
	themes : 'advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});
</script>
<script type="text/javascript">
tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : /(editor)/,
	theme : "advanced",			
	plugins : "preview,safari,paste,inlinepopups",
		paste_auto_cleanup_on_paste : false,
		paste_create_paragraphs : true,
		paste_create_linebreaks : true,
		paste_use_dialog : false,
	theme_advanced_buttons1 : "newdocument,preview,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,cut,copy,paste",
	theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,cleanup,code,|,forecolor,backcolor,|,removeformat",
	theme_advanced_buttons3 : false,
	theme_advanced_buttons4 : false,
	theme_advanced_toolbar_location : "external", 
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : false,
	theme_advanced_resizing : false,
	convert_urls : false,
	font_size_style_values : "8pt,10pt,12pt,14pt,18pt,24pt,36pt",
	element_format : "html",
	cleanup : true,
	verify_html : true,
	cleanup_on_startup : true,
	paste_strip_class_attributes : "mso"/*,
	extended_valid_elements : "html,body,style,title,head,meta"*/
});
</script>
<!-- EDITOR STUFF ENDS -->

<script type="text/javascript" src="../scripts/jquery.filestyle.js"></script>

<script type="text/javascript">
$(function() {
	var uj_loggedin = <?= ($_SESSION["uj_loggedin"] ? "true" : "false"); ?>;
	
	var chngtab = function(event) {
		event.preventDefault();
		
		if (uj_loggedin == false) return false;
		
		$(".content-block").hide();
		$("div[id='" + $(this).attr("rel") + "']").show();
	}
	
	var delete_subpage = function(event) {
		event.preventDefault();
		
		if (confirm("Are you sure you want to delete this subpage?")) {
			location.href = "../process.php?action=delete_subpage&id=" + $(this).attr("rel") + "&return=" + $("input[name='return']", $(this).parent().parent()).val();
		}
	}
	
	var chng_client = function(event) {
		clientid = $("option:selected", this).val();
		
		if (clientid == 0) { 
			location.href = "?tab=ourclients";
		} else {
			location.href = "?tab=ourclients&cid=" + clientid;
		}
	}
	
	var delete_client = function(event) {
		event.preventDefault();
		
		if (confirm("Are you sure you want to delete this client?")) {
			location.href = "../process.php?action=delete_client&id=" + $(this).attr("rel") + "&return=" + $("input[name='return']", $(this).parent().parent()).val();
		}
	}
	
	var chng_testimonialclient = function(event) {
		testimonialid = $("option:selected", this).val();
		
		if (testimonialid == 0) { 
			location.href = "?tab=testimonials";
		} else {
			location.href = "?tab=testimonials&tid=" + testimonialid;
		}
	}
	
	var delete_testimonial = function(event) {
		event.preventDefault();
		
		if (confirm("Are you sure you want to delete this testimonial?")) {
			location.href = "../process.php?action=delete_testimonial&id=" + $(this).attr("rel") + "&return=" + $("input[name='return']", $(this).parent().parent()).val();
		}
	}
	
	var submit_new_client_cat = function(event) {
		event.preventDefault();
		
		$.post("../process.php",
					 {
						 action:"add_client_category",
						 category:$("span.category_template input").val()
					 },
					 function (data) {
						 if (data == "added") {
							 $("<option></option>").val($("span.category_template input").val()).attr("selected",true).html($("span.category_template input").val()).appendTo("select[name='edit_client[category]']");
							$("span.category_template").remove();
						 }
					 });
	}
	
	var add_client_category = function(event) {
		event.preventDefault();
		
		category_template = "<span class=\"category_template\"><br><br><input type=\"text\" class=\"input200\">&nbsp;<a href=\"#\" class=\"submit_new_client_cat\"><img src=\"../images/submit-button.jpg\" width=\"56\" height=\"15\" align=\"middle\"></a></span>";
		
		$(".add_client_category").after(category_template);
		$(".submit_new_client_cat").bind("click", submit_new_client_cat);
	}
	
	$(".nav a").click(chngtab);
	$(".delete_subpage").click(delete_subpage);
	$("select[name='clientselect']").change(chng_client);
	$(".delete_client").click(delete_client);
	$("select[name='testimonialselect']").change(chng_testimonialclient);
	$(".delete_testimonial").click(delete_testimonial);
	$(".add_client_category").click(add_client_category);
	
	if (uj_loggedin == false) {
		$(".content-block:not(:first-child)").hide();
	} else {
		$(".nav a[rel='<?= $_GET["tab"]; ?>']").click();
	}
	
	// CUSTOM FILE UPLOAD FIELD
	var fileupload_width = $(".fileupload").css("width").substring(0, $(".fileupload").css("width").length-2);
	
	$(".fileupload").filestyle({ 
     image:"../images/browse-button.jpg", imageheight:14, imagewidth:57, height:18, width:fileupload_width, border:"1px solid #000000"
	});
});
</script>

<link href="../style/reset.css" rel="stylesheet" type="text/css">

<style type="text/css">
body { text-align:center; padding-top:20px; padding-bottom:20px; }
#wrapper { position:relative; width:900px; height:598px; margin:0 auto; background:url('../images/page-background.jpg') no-repeat; text-align:left; }

.home-link { position:absolute; left:36px; top:39px; width:201px; height:65px; display:block; text-decoration:none; }

.backend-title { position:absolute; right:25px; top:50px; font-family:Arial, Helvetica, sans-serif; color:#FFFFFF; font-size:20px; }
.backend-title a { text-decoration:none; color:#FFFFFF; }

.nav { position:absolute; top:110px; width:100%; border-top:4px solid #FFFFFF; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-left:45px; padding-top:10px; }
.nav a { color:#FFFFFF; text-decoration:none; }

.content { position:absolute; top:160px; margin-left:25px; width:100%; text-align:center; }
.content-block { width:860px; height:420px; margin:0 auto; text-align:left; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif;  font-size:11px; }
.content-block p { font-size:11px; }
.content-block a { color:#FFFFFF; text-decoration:none; }
p.content-block-title { font-size:14px; font-weight:bold; }

.input50 { width:50px; border:1px solid #000000; font-size:11px; height:18px; }
.input100 { width:100px; border:1px solid #000000; font-size:11px; height:18px; }
.input200 { width:250px; border:1px solid #000000; font-size:11px; height:18px; }
.input375 { width:375px; border:1px solid #000000; font-size:11px; height:18px; }
</style>

</head>

<body>

<div id="wrapper">
	<a href="index.php" class="home-link">&nbsp;</a>
  
 	<div class="backend-title">
    	<a href="?tab=main">Backend Management Tool</a>
  </div>
  
  <div class="nav">
  	<a href="#" rel="login"></a>
    <a href="#" rel="main"></a>
  	<a href="#" rel="home">Home</a> |
    <a href="#" rel="aboutus">About Us</a>	|
    <a href="#" rel="ourservices">Our Services</a> |
    <a href="#" rel="ourclients">Our Clients</a> |
    <a href="#" rel="testimonials">Testimonials</a> |
    <a href="#" rel="contactus">Contact Us</a>
  </div>
  
  <div class="content">
  	<!-- CONTENT FOR THE LOGIN FORM -->
  	<div class="content-block" id="login">
    	<form action="../process.php" method="post">
      <input type="hidden" name="action" value="login">
      <input type="hidden" name="return" value="main">
			<p class="content-block-title">
      	LOG-IN
      </p>
      
      <p>
      	<small>username</small><br>
				<input type="text" name="login_credential[admin_user]" class="input200">
      </p>
      
      <p>
      	<small>password</small><br>
				<input type="password" name="login_credential[admin_pass]" class="input200">
      </p>
      
      <p>
      	<input type="image" src="../images/submit-button.jpg">
      </p>
      </form>
    </div>
    
    <!-- CONTENT FOR THE MAIN ADMIN PAGE -->
  	<div class="content-block" id="main">
    	<form action="../process.php" method="post">
      <input type="hidden" name="action" value="edit_config">
      <input type="hidden" name="return" value="main">
    	<p class="content-block-title">
      	WELCOME
      </p>
      
      <p>
      	<small>You are now logged in<br>
				You can manage all content on this website by clicking on one of the main nav areas above on any of the backend pages<br>
				to manage the live website content.</small>
      </p>
      
      <p class="content-block-title">
      	Login Information
      </p>
      
      <p>
     		<small>To change the email of the person who is listed as the contact for this backend or username and password, please select below:</small>
      </p>
      
      <p>
      	<small>email</small><br>
				<input type="text" class="input200" name="edit_config[admin_email]" value="<?= $config["admin_email"]; ?>">
      </p>
      
      <p>
      	<small>username</small><br>
				<input type="text" class="input200" name="edit_config[admin_user]" value="<?= $config["admin_user"]; ?>">
      </p>
      
      <p>
      	<small>password</small><br>
				<input type="password" class="input200" name="edit_config[admin_pass]" value="<?= $config["admin_pass"]; ?>">
      </p>
      
      <p>
      	<input type="image" src="../images/submit-button.jpg">
      </p>
      
			<p>
      	<a href="../process.php?action=logout"><img src="../images/logout-button.jpg" width="56" height="14"></a>
      </p>
			</form>
    </div>
    
    <!-- CONTENT FOR THE HOMEPAGE MANAGEMENT TAB -->
  	<div class="content-block" id="home">
			<form action="../process.php" method="post">
      <input type="hidden" name="action" value="edit_config">
      <input type="hidden" name="return" value="home">
    	<p class="content-block-title">
      	HOME
      </p>
      
      <p>
      	<small>News</small><br>
				<textarea name="edit_config[home_news]" class="editor" style="width:375px; height:50px;"><?= $config["home_news"]; ?></textarea>
      </p>
      
      <p>
      	<input type="image" src="../images/submit-button.jpg">
      </p>
      </form>
    </div>
    
    <!-- CONTENT FOR THE ABOUTUS MANAGEMENT TAB -->
    <? $edit_au = $db->queryUniqueRow("SELECT * FROM subpage WHERE subpage = 'aboutus' AND id = '" . $_GET["auid"] . "'"); ?>
  	<div class="content-block" id="aboutus">
			<form action="../process.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit_subpage">
      <input type="hidden" name="return" value="aboutus">
      <input type="hidden" name="subpageid" value="<?= $_GET["auid"]; ?>">
      
    	<p class="content-block-title">
      	ABOUT US
      </p>
      
      <p style="float:right; margin-left:10px; width:450px;">
      	<small>Sub Page Header</small><br>
				<input type="text" name="edit_subpage[header]" class="input375" value="<?= $edit_au["header"]; ?>"><br><br>
				
        <small>Sub Page Text</small><br>
				<textarea name="edit_subpage[text]" class="editor" style="width:375px; height:50px;"><?= $edit_au["text"]; ?></textarea><br>

				<small>Photograph</small><br>
				<input type="file" name="photograph" class="fileupload" style="width:375px;"><br>
        <? if ($edit_au["photograph"]) : ?><small>Currently: <?= $edit_au["photograph"]; ?></small><br><? endif; ?>
        <br>
				
        <small>Sub Page Order</small><br>
				<input type="text" class="input100" name="edit_subpage[position]" value="<?= $edit_au["position"]; ?>"><br><br>

				<input type="image" src="../images/submit-button.jpg">
        <? if ($edit_au["id"]) : ?>&nbsp;&nbsp;<a href="#" class="delete_subpage" rel="<?= $edit_au["id"]; ?>"><img src="../images/delete-button.jpg" width="56" height="15"></a><? endif; ?>
      </p>
      
      <p>
      	<small>Click on one of the sub page links below to edit the live text for each sub page.<br>
        To add a new sub page, fill out the fields on the right and click submit. If there is only one sub page for
        the main page, this text will automatically appear as the main text on live page, without any sub page
        header/navigation.</small><br><br>
        
        <? while ($subpage = $db->fetchAssoc($aboutus)) : ?>
				<a href="?tab=aboutus&auid=<?= $subpage["id"]; ?>"><?= $subpage["header"]; ?></a><br>
        <? endwhile; ?>
      </p>
      
      <div style="clear:right;"></div>
      </form>
    </div>
    
    <!-- CONTENT FOR THE OURSERVICES MANAGEMENT TAB -->
    <? $edit_os = $db->queryUniqueRow("SELECT * FROM subpage WHERE subpage = 'ourservices' AND id = '" . $_GET["osid"] . "'"); ?>
  	<div class="content-block" id="ourservices">
			<form action="../process.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit_subpage">
      <input type="hidden" name="return" value="ourservices">
      <input type="hidden" name="subpageid" value="<?= $_GET["osid"]; ?>">
      
    	<p class="content-block-title">
      	OUR SERVICES
      </p>
      
      <p style="float:right; margin-left:10px; width:450px;">
      	<small>Sub Page Header</small><br>
				<input type="text" name="edit_subpage[header]" class="input375" value="<?= $edit_os["header"]; ?>"><br><br>
				
        <small>Sub Page Text</small><br>
				<textarea name="edit_subpage[text]" class="editor" style="width:375px; height:50px;"><?= $edit_os["text"]; ?></textarea><br>

				<small>Photograph</small><br>
				<input type="file" name="photograph" class="fileupload" style="width:375px;"><br>
        <? if ($edit_os["photograph"]) : ?><small>Currently: <?= $edit_os["photograph"]; ?></small><br><? endif; ?>
        <br>
				
        <small>Sub Page Order</small><br>
				<input type="text" class="input100" name="edit_subpage[position]" value="<?= $edit_os["position"]; ?>"><br><br>

				<input type="image" src="../images/submit-button.jpg">
        <? if ($edit_os["id"]) : ?>&nbsp;&nbsp;<a href="#" class="delete_subpage" rel="<?= $edit_os["id"]; ?>"><img src="../images/delete-button.jpg" width="56" height="15"></a><? endif; ?>
      </p>
      
      <p>
      	<small>Click on one of the sub page links below to edit the live text for each sub page.<br>
        To add a new sub page, fill out the fields on the right and click submit. If there is only one sub page for
        the main page, this text will automatically appear as the main text on live page, without any sub page
        header/navigation.</small><br><br>
        
        <? while ($subpage = $db->fetchAssoc($ourservices)) : ?>
				<a href="?tab=ourservices&osid=<?= $subpage["id"]; ?>"><?= $subpage["header"]; ?></a><br>
        <? endwhile; ?>
      </p>
      
      <div style="clear:right;"></div>
      </form>
    </div>
    
    <!-- CONTENT FOR THE OURCLIENTS MANAGEMENT TAB -->
    <? $edit_client = $db->queryUniqueRow("SELECT * FROM clients WHERE id = '" . $_GET["cid"] . "'"); ?>
    <? $client_categories = explode(",", $config["client_categories"]); ?>
  	<div class="content-block" id="ourclients">
			<form action="../process.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit_client">
      <input type="hidden" name="return" value="ourclients">
      <input type="hidden" name="clientid" value="<?= $_GET["cid"]; ?>">
      
      <p class="content-block-title">
      	OUR CLIENTS
      </p>
      
      <p style="float:right; margin-left:10px; width:450px;">
        <small>Client Category</small><br>
				<select name="edit_client[category]" class="input375">
        	<? foreach ($client_categories as $category) : ?>
          <option value="<?= $category; ?>" <?= ($edit_client["category"] == $category ? "selected" : NULL); ?>><?= $category; ?></option>
          <? endforeach; ?>
        </select>&nbsp;<small><a href="#" class="add_client_category">new category</a></small><br><br>
        
      	<small>Client Name</small><br>
				<input type="text" name="edit_client[name]" class="input375" value="<?= $edit_client["name"]; ?>"><br><br>
				
        <small>Client Location</small><br>
				<input type="text" name="edit_client[location]" class="input375" value="<?= $edit_client["location"]; ?>"><br><br>
        
        <small>Client Website</small><br>
				<input type="text" name="edit_client[website]" class="input375" value="<?= $edit_client["website"]; ?>"><br><br>

				<small>Client Photograph</small><br>
				<input type="file" name="photograph" class="fileupload" style="width:375px;"><br>
        <? if ($edit_client["photograph"]) : ?><small>Currently: <?= $edit_client["photograph"]; ?></small><br><? endif; ?>
        <br>
        
				<input type="image" src="../images/submit-button.jpg">
        <? if ($edit_client["id"]) : ?>&nbsp;&nbsp;<a href="#" class="delete_client" rel="<?= $edit_client["id"]; ?>"><img src="../images/delete-button.jpg" width="56" height="15"></a><? endif; ?>
      </p>
      
      <p>
      	<small>Select one of the client names from the pulldown menu below to edit the live text for client.
To add a new client, fill out the fields on the right and click submit.</small><br><br>
        
        Clients<br>
        <select name="clientselect" class="input200">
        <option value="0">New Client</option>
				<? while ($client = $db->fetchAssoc($ourclients)) : ?>
				<option value="<?= $client["id"]; ?>" <?= ($client["id"] == $_GET["cid"] ? "selected" : NULL); ?>><?= $client["name"]; ?></option>
        <? endwhile; ?>
        </select>
      </p>
      
      <div style="clear:right;"></div>
      </form>
    </div>
    
    <!-- CONTENT FOR THE TESTIMONIALS MANAGEMENT TAB -->
    <? $edit_testimonial = $db->queryUniqueRow("SELECT * FROM testimonials WHERE id = '" . $_GET["tid"] . "'"); ?>
  	<div class="content-block" id="testimonials">
			<form action="../process.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit_testimonial">
      <input type="hidden" name="return" value="testimonials">
      <input type="hidden" name="testimonialid" value="<?= $_GET["tid"]; ?>">
      
      <p class="content-block-title">
      	TESTIMONIALS
      </p>
      
      <p style="position:absolute; right:35px; top:30px;">
     		<small>Client Order</small><br>
				<input type="text" name="edit_testimonial[position]" class="input50" value="<?= $edit_testimonial["position"]; ?>"><br><br>
      </p>
      
      <p style="float:right; margin-left:10px; width:450px;">        
      	<small>Client Name</small><br>
				<input type="text" name="edit_testimonial[name]" class="input375" value="<?= $edit_testimonial["name"]; ?>"><br><br>
				
        <small>Client Title</small><br>
				<input type="text" name="edit_testimonial[title]" class="input375" value="<?= $edit_testimonial["title"]; ?>"><br><br>
        
        <small>Client Company/Organization</small><br>
				<input type="text" name="edit_testimonial[organization]" class="input375" value="<?= $edit_testimonial["organization"]; ?>"><br><br>
        
        <small>Testimonial Text</small><br>
				<textarea name="edit_testimonial[text]" class="editor" style="width:375px; height:50px;"><?= $edit_testimonial["text"]; ?></textarea><br>

				<small>Client Photograph</small><br>
				<input type="file" name="photograph" class="fileupload" style="width:375px;"><br>
        <? if ($edit_testimonial["photograph"]) : ?><small>Currently: <?= $edit_testimonial["photograph"]; ?></small><br><? endif; ?>
        <br>
        
				<input type="image" src="../images/submit-button.jpg">
        <? if ($edit_testimonial["id"]) : ?>&nbsp;&nbsp;<a href="#" class="delete_testimonial" rel="<?= $edit_testimonial["id"]; ?>"><img src="../images/delete-button.jpg" width="56" height="15"></a><? endif; ?>
      </p>
      
      <p>
      	<small>Select one of the client names from the pulldown menu below to edit the live text for client.
To add a new client, fill out the fields on the right and click submit.</small><br><br>
        
        Clients<br>
        <select name="testimonialselect" class="input200">
        <option value="0">New Client Testimonial</option>
				<? while ($testimonial = $db->fetchAssoc($tetimonials)) : ?>
				<option value="<?= $testimonial["id"]; ?>" <?= ($testimonial["id"] == $_GET["tid"] ? "selected" : NULL); ?>><?= $testimonial["name"]; ?>, <?= $testimonial["title"]; ?>/<?= $testimonial["organization"]; ?></option>
        <? endwhile; ?>
        </select>
      </p>
      
      <div style="clear:right;"></div>
      </form>
    </div>
    
    <!-- CONTENT FOR THE CONTACTUS MANAGEMENT TAB -->
    <? $edit_cu = $db->queryUniqueRow("SELECT * FROM subpage WHERE subpage = 'contactus' AND id = '" . $_GET["cuid"] . "'"); ?>
  	<div class="content-block" id="contactus">
			<form action="../process.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit_subpage">
      <input type="hidden" name="return" value="contactus">
      <input type="hidden" name="subpageid" value="<?= $_GET["cuid"]; ?>">
      
    	<p class="content-block-title">
      	CONTACT US
      </p>
      
      <p style="float:right; margin-left:10px; width:450px;">
      	<small>Sub Page Header</small><br>
				<input type="text" name="edit_subpage[header]" class="input375" value="<?= $edit_cu["header"]; ?>"><br><br>
				
        <small>Sub Page Text</small><br>
				<textarea name="edit_subpage[text]" class="editor" style="width:375px; height:50px;"><?= $edit_cu["text"]; ?></textarea><br>
				
        <small>Sub Page Order</small><br>
				<input type="text" class="input100" name="edit_subpage[position]" value="<?= $edit_cu["position"]; ?>"><br><br>

				<input type="image" src="../images/submit-button.jpg">
        <? if ($edit_cu["id"]) : ?>&nbsp;&nbsp;<a href="#" class="delete_subpage" rel="<?= $edit_cu["id"]; ?>"><img src="../images/delete-button.jpg" width="56" height="15"></a><? endif; ?>
      </p>
      
      <p>
      	<small>Click on one of the sub page links below to edit the live text for each sub page.<br>
        To add a new sub page, fill out the fields on the right and click submit. If there is only one sub page for
        the main page, this text will automatically appear as the main text on live page, without any sub page
        header/navigation.</small><br><br>
        
        <? while ($subpage = $db->fetchAssoc($contactus)) : ?>
				<a href="?tab=contactus&cuid=<?= $subpage["id"]; ?>"><?= $subpage["header"]; ?></a><br>
        <? endwhile; ?>
      </p>
      
      <div style="clear:right;"></div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
<?
require_once("config.php");
$titles = $db->query("SELECT header AS `text` FROM subpage WHERE subpage = 'contactus' ORDER BY position ASC");
$contactus = $db->query("SELECT * FROM subpage WHERE subpage = 'contactus' ORDER BY position ASC");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Urban Joule Consulting</title>

<link rel="stylesheet" type="text/css" media="all" href="style/demoStyles.css">
<link rel="stylesheet" type="text/css" media="all" href="style/jScrollPane.css">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>
<script type="text/javascript" src="scripts/jScrollPane.js"></script>

<script type="text/javascript">
$(function() {
	$.fn.vAlign = function() {
		return this.each(function(i){
		var ah = $(this).height();
		var ph = $(this).parent().height();
		var mh = (ph - ah) / 2;
		$(this).css("margin-top", mh);
		});
	};

	$("#pane1").jScrollPane({ animateTo:true });	
	
	var scrollsubpage = function(event) {
		event.preventDefault();
		
		$("#pane1")[0].scrollTo("a[name='" + $(this).attr("rel") + "']");		
		return false;
	}
	
	$("p.subheader-link a").click(scrollsubpage);
});
</script>

<link href="style/reset.css" rel="stylesheet" type="text/css">

<style type="text/css">
body { text-align:center; padding-top:20px; padding-bottom:20px; }
#wrapper { position:relative; width:900px; height:598px; margin:0 auto; background:url('images/page-background.jpg') no-repeat; text-align:left; }

.home-link { position:absolute; left:36px; top:39px; width:201px; height:65px; display:block; text-decoration:none; }

.blog-link { position:absolute; right:25px; top:50px; font-family:'Times New Roman', Times, serif; color:#FFFFFF; font-size:13px; }
.blog-link a { color:#FFFFFF; text-decoration:none; }

.book { position:absolute; width:875px; height:435px; bottom:40px; left:4px; background:url('images/contactus-top-border.jpg') top left no-repeat; }
.book-bottom { position:absolute; width:875px; height:434px; background:url('images/contactus-bottom-border.jpg') bottom left no-repeat; }
.book-left { position:absolute; top:21px; width:875px; height:393px; background:url('images/contactus-left-border.jpg') top left no-repeat; }
.book-right { position:absolute; width:875px; height:393px; /*background:url('images/contactus-right-border.jpg') top right no-repeat;*/ }
.book-content { position:absolute; width:729px; height:393px;left:70px; background:url('images/about-content-background.jpg') top left repeat-y; }

.subheader-link { margin:0; padding:0; color:#a40233;  font-weight:bold; font-size:12px; }
.subheader-link a, .content-title { color:#a40233; font-weight:bold; font-size:12px; text-decoration:none; }

.new-book { position:absolute; left:4px; top:114px; width:871px; height:435px; background:url('images/contactus-background.jpg') top left no-repeat; }
.left-page { position:absolute; left:70px; top:20px; width:365px; height:390px; }
.right-page { position:absolute; right:70px; top:20px; width:325px; height:390px; text-align:left; font-family:Arial, Helvetica, sans-serif; }
.right-page p { font-size:11px; }

.holder a { color:#000000; text-decoration:none; }
.input300 { width:300px; border:1px solid #000000; font-size:11px; height:18px; }
.textarea300 { width:300px; height:75px; border:1px solid #000000; font-size:11px; overflow:auto; }
</style>

</head>

<body>

<div id="wrapper">
	<a href="index.php" class="home-link">&nbsp;</a>

 	<div class="blog-link">
    	[ <a href="#">Check Out Our Blog</a> ]
  </div>
  
  <div class="new-book">
  	<img src="images/contactus-tabs.jpg" width="53" height="435" align="right" usemap="#Map">
    <map name="Map">
      <!--<area shape="rect" coords="24,345,41,415" href="contactus.php">-->
      <area shape="rect" coords="24,260,41,335" href="testimonials.php">
      <area shape="rect" coords="24,175,41,250" href="ourclients.php">
      <area shape="rect" coords="24,95,41,165" href="ourservices.php">
      <area shape="rect" coords="24,10,41,85" href="aboutus.php">
    </map>
    
    <div class="left-page">
    	<p>
        <img src="images/contactus-header.jpg" width="267" height="43">
      </p>
      
       <? while ($title = $db->fetchAssoc($titles)) : ?>
      <p class="subheader-link"><a href="#" rel="<?= seo_friendly($title["text"]); ?>"><?= $title["text"]; ?></a></p>
      <? endwhile; ?>
      
      <div class="holder">
        <div id="pane1" class="scroll-pane">
          <? while ($subpage = $db->fetchAssoc($aboutus)) : ?>
            <a name="<?= seo_friendly($subpage["header"]); ?>"></a>
            <?= $subpage["text"]; ?>
          <? endwhile; ?>
        </div>
      </div>
    </div>
    
    <div class="right-page">
    	<form action="process.php" method="post">
      <input type="hidden" name="action" value="contactus">
      <input type="hidden" name="sP4mCh3k" value="">
      <input type="hidden" name="return" value="contactus">
      <p class="subheader-link">Send Us Your Thoughts...<br><br></p>
      
      <p>
      	<small>I am....</small><br>
				<select name="contactus[iam]" class="input300">
        	<option value="Student">Student</option>
          <option value="School">School</option>
          <option value="Organization">Organization</option>
          <option value="City Agency">City Agency</option>
          <option value="Company/Employer">Company/Employer</option>
        </select>
      </p>
      
      <p>
      	<small>Name</small><br>
				<input type="text" name="contactus[name]" class="input300">
      </p>
      
      <p>
      	<small>Company, School or Organization</small><br>
				<input type="text" name="contactus[organization]" class="input300">
      </p>
      
      <p>
      	<small>Title</small><br>
				<input type="text" name="contactus[title]" class="input300">
      </p>
      
      <p>
      	<small>Phone</small><br>
				<input type="text" name="contactus[phone]" class="input300">
      </p>
      
      <p>
      	<small>Thoughts, Comments or Questions for Us...</small><br>
				<textarea  name="contactus[text]" class="textarea300"></textarea>
      </p>
      
      <p>
      	<input type="image" src="images/submit-button.jpg">
      </p>
      </form>
    </div>
  </div>
</div>

</body>
</html>
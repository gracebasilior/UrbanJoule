<?
require_once("config.php");
$titles = $db->query("SELECT header AS `text`, photograph FROM subpage WHERE subpage = 'aboutus' ORDER BY position ASC");
$aboutus = $db->query("SELECT * FROM subpage WHERE subpage = 'aboutus' ORDER BY position ASC");
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
		
		if ($(this).attr("class") != "") {
			var chngimg = $("<img>").attr({src:"uploaded/" + $(this).attr("class"), align:"middle"});
			$(".right-page").html(chngimg);
			$(".right-page img").vAlign();
		}
		
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

.subheader-link { margin:0; padding:0; }
.subheader-link a { color:#a40233; font-weight:bold; font-size:12px; text-decoration:none; }

.new-book { position:absolute; left:4px; top:114px; padding-left:70px; width:801px; height:435px; background:url('images/aboutus-background.jpg') top left no-repeat; }
.left-page { position:absolute; left:70px; top:20px; width:365px; height:390px; }
.right-page { position:absolute; right:70px; top:20px; width:325px; height:390px; background:url('images/page-photo-placeholder.jpg') center no-repeat; text-align:center; }
</style>

</head>

<body>

<div id="wrapper">
	<a href="index.php" class="home-link">&nbsp;</a>
  
 	<div class="blog-link">
    	[ <a href="#">Check Out Our Blog</a> ]
  </div>
  
  <div class="new-book">
  	<img src="images/aboutus-tabs.jpg" width="53" height="435" border="0" align="right" usemap="#Map">
    <map name="Map">
      <area shape="rect" coords="24,345,41,415" href="contactus.php">
      <area shape="rect" coords="24,260,41,335" href="testimonials.php">
      <area shape="rect" coords="24,175,41,250" href="ourclients.php">
      <area shape="rect" coords="24,95,41,165" href="ourservices.php">
      <!--<area shape="rect" coords="24,10,41,85" href="aboutus.php">-->
    </map>
    
    <div class="left-page">
    	<p>
        <img src="images/aboutus-header.jpg" width="231" height="43">
      </p>
      
      <? while ($title = $db->fetchAssoc($titles)) : ?>
      <p class="subheader-link"><a href="#" rel="<?= seo_friendly($title["text"]); ?>" <?= ($title["photograph"] ? "class=\"" . $title["photograph"] . "\"" : NULL); ?>><?= $title["text"]; ?></a></p>
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
      &nbsp;
    </div>
  </div>
</div>

</body>
</html>
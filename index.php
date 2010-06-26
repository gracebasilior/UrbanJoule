<? require_once("config.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Urban Joule Consulting</title>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js">
$(function() {
					 
});
</script>

<link href="style/reset.css" rel="stylesheet" type="text/css">

<style type="text/css">
body { text-align:center; padding-top:20px; padding-bottom:20px; }
#wrapper { position:relative; width:900px; height:598px; margin:0 auto; background:url('images/background-home.jpg') no-repeat; text-align:left; }

.blog-link { position:absolute; right:25px; top:50px; font-family:'Times New Roman', Times, serif; color:#FFFFFF; font-size:13px; }
.blog-link a { color:#FFFFFF; text-decoration:none; }

.notecard { position:absolute; width:345px; bottom:40px; left:35px; }
.notecard-header { height:15px; border-bottom:1px solid #f58d91; background:#f5f3d9; }
.notecard-content { background:url('images/home-notecardbg.jpg') repeat; line-height:14px; color:#000000; font-family:"Times New Roman", Times, serif; padding-left:10px; padding-right:10px; padding-bottom:15px; }

.bookcover { position:absolute; width:429px; height:435px; bottom:40px; right:35px; background:url('images/home-bookcoverbg.jpg') top left no-repeat; }

</style>

</head>

<body>

<div id="wrapper">
 	<div class="blog-link">
    	[ <a href="#">Check Out Our Blog</a> ]
  </div>
    
	<div class="notecard">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="notecard-header">&nbsp;</td>
      </tr>
      <tr>
        <td class="notecard-content">
          <?= $config["home_news"]; ?>
        </td>
      </tr>
    </table>
  </div>
    
  <div class="bookcover">
    <table width="27px" border="0" cellpadding="0" cellspacing="0" align="right">
      <tr>
        <td><img src="images/home-bookcover-tabs.jpg" width="27" height="435" border="0" usemap="#Map"></td>
      </tr>
    </table>
    
    <map name="Map">
      <area shape="rect" coords="1,9,20,92" href="aboutus.php" alt="About Us">
      <area shape="rect" coords="1,93,20,173" href="ourservices.php" alt="Our Services">
      <area shape="rect" coords="1,177,20,257" href="ourclients.php" alt="Our Clients">
      <area shape="rect" coords="1,261,20,346" href="testimonials.php" alt="Testimonials">
      <area shape="rect" coords="1,346,20,425" href="contactus.php" alt="Contact Us">
    </map>
  </div>
</div>

</body>
</html>
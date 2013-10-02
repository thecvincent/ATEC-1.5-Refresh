<?php
			$uri = explode( "/", $_SERVER['REQUEST_URI']);
	$json_url = 'http://atec.io/journal/?json=1';
	$is_post=false;


if($uri[3]){
	$slug = $uri[3];
	if($uri[4])$slug = $uri[4];
	if($uri[5])$slug = $uri[5];
	if($uri[6])$slug = $uri[6];
	if($uri[7])$slug = $uri[7];
	$json_url = 'http://atec.io/journal/?json=get_post&slug='.$slug;
 $is_post=true;
}

if($uri[3]=='tag'){
	$json_url = 'http://atec.io/journal/tag/'.$uri[4].'/?json=1&count=-1';
	$tagName = $uri[4];
	$is_post=false;
}

if($uri[3]=='category'){
	$json_url = 'http://atec.io/journal/category/'.$uri[4].'/?json=1&count=-1';
		$categoryName = $uri[4];
		$is_post=false;
}	

if (preg_match("/^\d{4}$/", $uri[3])&&(!isset($uri[4])||$uri[4]=="")) {
$yearNum = $uri[3];
$yearName = date("Y", mktime(0, 0, 0, 10, 0, $yearNum));
	$json_url = 'http://atec.io/journal/'.$uri[3].'/?json=1&count=10';
		$is_post=false;
}
	

if (preg_match("/\b\d{4}\b/", $uri[3])&&preg_match("/\b\d{2}\b/", $uri[4])&&(!isset($uri[5])||$uri[5]=="")) {
$monthNum = $uri[4]; $yearNum = $uri[3];
$monthName = date("F, Y", mktime(0, 0, 0, $monthNum, 30, $yearNum));
	$json_url = 'http://atec.io/journal/'.$uri[3].'/'.$uri[4].'/?json=1&count=10';
$is_post=false;
}




	$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
	if(isset($_GET['p'])){
				$next = ++$page;
			$json_url = 'http://atec.io/journal/api/core/get_posts/?page='.$next;
	}
	
	
	
	

if($is_post==true){
if (!preg_match("/\b\d{4}\b/", $uri[3])||!preg_match("/\b\d{2}\b/", $uri[4])){
$username = ''; $password = ''; $json_string = '';
$ch = curl_init( $json_url );
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_USERPWD => $username . ":" . $password,   // authentication
CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
CURLOPT_POSTFIELDS => $json_string
);
curl_setopt_array( $ch, $options );
 
$result =  curl_exec($ch); // Getting jSON result string
$json_array = json_decode($result, true);
	$year=substr($json_array["post"]["date"], 0, 4);
	$month=substr($json_array["post"]["date"], 5, 2);
	header('Location: /atec/news/'.$year.'/'.$month.'/'.$slug);
	die();

}
}


?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
<title>ATEC News</title>
<?php include '/info/www/atec/inc/head.html' ?>
<title>News in Arts and Technology at UT Dallas</title>

<script type="text/javascript">
  $(document).ready(function() {
    // Infinite Ajax Scroll configuration
    jQuery.ias({
      container : '.posts', // main container where data goes to append
      item: 'article', // single items
      pagination: '.load_more_posts', // page navigation
      next: '.load_more_posts a', // next page selector
      loader: '<img src="/atec/img/el/ajax-loader.gif"/>', // loading gif
      triggerPageThreshold: 1 // show load more if scroll more than this
    });
  });
</script>
</head>

<body>
<?php include '/info/www/atec/inc/header.html'; ?>

<!--start content-->
<div id="content-wrapper">
 <section class="pages clearfix"> 
  <!--start section title-->
  <div id="section-title">
   <h1>News</h1>
  </div>
  <!--start teaser-->
  <div id="teaser">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </div>
  <h2>Posts | Categories</h2>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum enim arcu, venenatis et ligula sed, mollis porttitor est. Nulla sit amet mauris a lectus semper dictum nec ac tellus. Aliquam tempor adipiscing massa id venenatis. Phasellus posuere, dui vitae semper faucibus, augue tortor porttitor justo, sit amet facilisis ligula justo vel neque. </p>
  <?php

$username = '';  // authentication in case we need it
$password = '';  // authentication
 
// jSON String for request
$json_string = '';
 
// Initializing curl
$ch = curl_init( $json_url );
 
// Configuring curl options
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_USERPWD => $username . ":" . $password,   // authentication
CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
CURLOPT_POSTFIELDS => $json_string
);
 
// Setting curl options
curl_setopt_array( $ch, $options );
 
// Getting results
$result =  curl_exec($ch); // Getting jSON result string
$json_array = json_decode($result, true);

// If we get a valid JSON File start listing things
if($json_array["status"]=="ok"){
	if($json_array['posts']){
		
		if(isset($categoryName))echo "<h2>Category: ".$categoryName."</h2>"; 
		if(isset($tagName))echo "<h2>Tagged with: ".$tagName."</h2>"; 
		if(isset($monthName))echo "<h2>".$monthName."</h2>"; 
		if(isset($yearName))echo "<h2>".$yearName."</h2>"; 
	echo "<section class=\"posts\">";
   foreach ($json_array['posts'] as $value) {
	echo "<article><h3><a href=\"/atec/news/".substr($value['date'], 0, 4)."/".substr($value['date'], 5, 2)."/".$value['slug']."\">".$value['title']."</a></h3>";
	preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $value['content'], $post_image);
 echo "<img style=\"float:left; width:10rem; height:auto;  padding-right:1em;\" src=\"".$post_image['src']."\" >";			
	echo "<p>".substr(strip_tags($value['content']), 0, 300)."&hellip;</p><p><a href=\"\">Read More</a></p></article>";
   } ?>
   </section>
			  <div class="load_more_posts">
    <a href='index.php?p=<?php echo $page ?>'>Load More</a>
  </div>
<?php	}
	if($json_array['post']){
	$post=$json_array['post'];
	echo "<h3>".$post['title']."</h3>";
 echo $post['content'];

	}
	
}

//if the json file we tried to use is naughty throw an exception
else{
echo	"ERROR POSTS NOT FOUND";
}



?>
 </section>
</div>
<?php include '/info/www/atec/inc/footer.html'; ?>
<?php include '/info/www/atec/inc/tail.html'; ?>
<?php include '/info/www/websvcs/shared/sdc.js' ?>
</body>
</html>
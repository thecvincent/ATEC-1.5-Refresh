<?php
			$uri = explode( "/", $_SERVER['REQUEST_URI']);
	$json_url = 'http://atec.io/journal/?json=1';
	$is_post=false;


if(isset($uri[3])&&$uri[3]!=""){
	$slug = $uri[3];
	if(isset($uri[4])&&$uri[4]!="")$slug = $uri[4];
	if(isset($uri[5])&&$uri[5]!="")$slug = $uri[5];
	if(isset($uri[6])&&$uri[6]!="")$slug = $uri[6];
	if(isset($uri[7])&&$uri[7]!="")$slug = $uri[7];
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
<?php include $_SERVER['DOCUMENT_ROOT'].'/atec/inc/head.html' ?>
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
<?php

 include $_SERVER['DOCUMENT_ROOT'].'/atec/inc/header.html'; ?>

<!--start content-->
 <h2 class="text-center">BLOG</h2>

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
	if(isset($json_array['posts'])){ ?>
   <div style="text-align:center">
    <section class="btn-group">
      <button type="button" class="btn btn-default">Posts</button>
      <button type="button" class="btn active btn-default">Collections</button>
    </section>
  </div>
  <section id="sidescroll">
    <menu type="list" class="collections">
      <li class="academics" style="background-image:url(/atec/img/collections/academics.jpg);"><a href="#"><h4>Academics</h4><div>38 posts</div></a></li>
      <li class="animation" style="background-image:url(/atec/img/collections/animation.jpg);"><a href="#"><h4>Animation</h4><div>38 posts</div></a></li>
      <li class="building current" style="background-image:url(/atec/img/collections/building.jpg);"><a href="#"><h4>Building</h4><div>38 posts</div></a></li>
      <li class="emac" style="background-image:url(/atec/img/collections/emac.jpg);"><a href="#"><h4>Emerging Media and Communication</h4><div>38 posts</div></a></li>
      <li class="gd"  style="background-image:url(/atec/img/collections/game.jpg);"><a href="#"><h4>Game Design</h4><div>38 posts</div></a></li>
      <li class="sound" style="background-image:url(/atec/img/collections/sound.jpg);"><a href="#"><h4>Sound Design</h4><div>38 posts</div></a></li>
      <li class="steam" style="background-image:url(/atec/img/collections/steam.jpg);"><a href="#"><h4>STEM to STEAM</h4><div>38 posts</div></a></li>
      <li class="research"><a href="#"><h4>Research</h4><div>38 posts</div></a></li>
    </menu>
  </section>

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
 
	<?php	
		if(isset($categoryName))echo "<h2>Category: ".$categoryName."</h2>"; 
		if(isset($tagName))echo "<h2>Tagged with: ".$tagName."</h2>"; 
		if(isset($monthName))echo "<h2>".$monthName."</h2>"; 
		if(isset($yearName))echo "<h2>".$yearName."</h2>"; 
	echo "<section class=\"posts\">";
   foreach ($json_array['posts'] as $value) {
	echo "<article><h3><a href=\"/atec/news/".substr($value['date'], 0, 4)."/".substr($value['date'], 5, 2)."/".$value['slug']."\">".$value['title']."</a></h3>";
	preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $value['content'], $post_image);
	if(isset($post_image['src']))echo "<img class=\"img-responsive\" src=\"".$post_image['src']."\" >";			
	echo "<p>".substr(strip_tags($value['content']), 0, 300)."&hellip;</p><p><a href=\"\">Read More</a></p></article>";
   } ?>
   </section>
			  <div class="load_more_posts">
    <a href='index.php?p=<?php echo $page ?>'>Load More</a>
  </div>
  </div>
  </div>
  <p>&nbsp;</p>
</article>
<?php	}


	if(isset($json_array['post'])){ ?>
 <article class="container blogpost">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
 <?php
	$post=$json_array['post'];
	echo "<h3>".$post['title']."</h3>";
 echo $post['content']; ?>
       <footer>
        <p><img src="/atec/img/portraits/ursula.jpg" class="img-circle pull-left" alt="" width="60" style="margin-right:12px;"><strong>Ursula Majoris</strong> (EMAC '13) is a graduate student focusing on the role of media and technology in the arts.</p>
      </footer>
</div></div>
	  <div id="auxiliary" class="row">
    <div class="col-xs-6 col-md-4 col-md-offset-2">
      <h4>Related</h4>
      <ul>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/08/comm-3342-changes/" title="Advanced Topics in Communication (COMM 3342) Topic Changes">Advanced Topics in Communication (COMM 3342) Topic Changes</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/08/a-tale-of-two-4372s-in-fall-2013/" title="A Tale of Two 4372s in Fall 2013">A Tale of Two 4372s in Fall 2013</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/08/atec-course-to-explore-crowd-funding/" title="ATEC Course to Explore Crowd Funding">ATEC Course to Explore Crowd Funding</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/07/new-atec-building-sight-of-next-nasher-xchange-work/" title="New ATEC Building Sight of Next Nasher XChange Work">New ATEC Building Sight of Next Nasher XChange Work</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/07/emac-course-explores-the-driving-force-behind-the-social-web/" title="EMAC Course Explores the Driving Force Behind the Social Web">EMAC Course Explores the Driving Force Behind the Social Web</a> </li>
      </ul>
    </div>
    <div class="col-xs-6 col-md-4">
      <h4>Recent</h4>
      <ul>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/09/education-tech-firm-draws-heavily-on-talents-of-ut-dallas-grads/" title="Education Tech Firm Draws Heavily on Talents of UT Dallas Grads">Education Tech Firm Draws Heavily on Talents of UT Dallas Grads</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/09/frightlite/" title="FrightLite: ATEC’s Latest Short from Animation Studio Course">FrightLite: ATEC’s Latest Short from Animation Studio Course</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/08/atec-professor-to-present-as-part-of-centraltrak-next-topic-series/" title="ATEC Professor to Present as Part of CentralTrak Next Topic Series">ATEC Professor to Present as Part of CentralTrak Next Topic Series</a> </li>
        <li> <a href="http://www.utdallas.edu/atec/blog/2013/08/atec-professor-receives-grant-for-social-media-mashup/" title="ATEC Professor Receives Grant for Social Media Mashup">ATEC Professor Receives Grant for Social Media Mashup</a> </li>
      </ul>
    </div>
  </div>
<?php
	}
	
}

//if the json file we tried to use is naughty throw an exception
else{
echo	"ERROR POSTS NOT FOUND";
}

?>


<?php include $_SERVER['DOCUMENT_ROOT'].'/atec/inc/footer.html'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/atec/inc/tail.html'; ?>
</body>
</html>
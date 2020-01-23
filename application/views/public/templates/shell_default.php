<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE HTML>
<!--[if IE 7]><html class="ie ie7 no-js"><![endif]-->
<!--[if IE 8]><html class="ie ie8 no-js"><![endif]-->
<!--[if IE 9]><html class="ie ie9 no-js"><![endif]-->
<!--[if IE 10]><html class="ie ie10 no-js"><![endif]-->
<!--[if IE 11]><html class="ie ie11 no-js"><![endif]-->
<!--[if !IE]><html class="no-js"><![endif]-->

<head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0,width=device-width,height=device-height">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!--
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    -->

    <title><?php
if (isset($_this->template->meta_title)) { echo $_this->template->meta_title; }

	if (isset($_this->pageContents->meta_title->content) && !empty($_this->pageContents->meta_title->content))
	{ 
		echo $_this->pageContents->meta_title->content .' | '; 
	}
	else
	{ 
		$title = (isset($_this->pageContents->title) 
                        && !empty($_this->pageContents->title)) 
                        ? $_this->pagecontent->title->content : (isset($_this->page)) ? $_this->page->label : '';
		echo $title.' | '; 
	}
	?> Double Tree Bristol</title>


<?php
	// set "expires" meta tag if page has an expiration date (set in controller)
	if(isset($page_expiration) && strtotime($page_expiration) > time() )
	{
?>
<meta name="expires" content="<?php echo date("D, d M, Y",strtotime($page_expiration)); ?>">
<?php
	}
	
$meta_name_tags = array("description"=>"meta_description",
						"keywords"=>"meta_keywords"
						);
foreach($meta_name_tags as $name => $block_name)
{
	if(!isset($_this->pageContents->$block_name->content) || empty($_this->pageContents->$block_name->content))
	{
		continue;
	}
?>
<meta name="<?php echo $name ?>" content="<?php echo $_this->pageContents->$block_name->content ?>">
<?php
}
$meta_properties_tags = array("og:title"=>"meta_title","og:image"=>"og_image");
foreach($meta_properties_tags as $name => $block_name)
{
	if(!isset($_this->pageContents->$block_name->content) || empty($_this->pageContents->$block_name->content))
	{
		continue;
	}
?>
<meta property="<?php echo $name ?>" content="<?php echo $_this->pageContents->$block_name->content ?>">
<?php
}
?>
<link href='https://fonts.googleapis.com/css?family=Lato:400,700|Oswald:400,300' rel='stylesheet' type='text/css'>
<?php
//loop and load each style
//TOP
foreach ($_this->template->tstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}
//MIDDLE
foreach ($_this->template->mstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}
//BOTTOM
foreach ($_this->template->bstyles as $style => $type) {
	echo HTML::style($style,array('media' => $type)), "\n";
}



//extra page-specific header code
echo (isset($_this->pageContents->extra_head_code->content) && !empty($_this->pageContents->extra_head_code->content)) ? $_this->pageContents->extra_head_code->content : '';
?>

<script src="/assets/js/modernizr.custom.2.8.3.min.js" type="text/javascript"></script>
 <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script>
            var sessiontoken = "<?= Session::instance()->get('ybr_token'); ?>";
            var BASE_PATH = "/";// root path to this site
            var XHR_PUBLIC_PATH = BASE_PATH + "request/";// path to the front-facing "ajax" controller
</script>


  <?php
    
    //echo phpinfo();
    $ga_id =  Kohana::$config->load('siteconfig.google.analytics.id');
    if(!is_null($ga_id)){?>
  
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?=$ga_id;?>', 'auto');
  
  ga('send', 'pageview');

</script>
   
 <?php } ?>

</head>

<body>

<main id="site">
	
    <header id="header">
       <div id="utility">
            <div class="container">
                <div class="row">
                    <div id="local" class="col-sm-4 col-xs-12 text-center">
                        <span>Bristol, Connecticut</span> &nbsp; &nbsp;<strong>860-589-7766</strong>
                    </div>
                    <div id="utility" class="hidden-xs pull-right">
                        <?php echo ybr::menuHandler("Utility"); ?>
                    </div>
                </div>
            </div>
       </div><!--end utility-->
       <div id="logo" class="text-center">
           <a href="/"><img src="/assets/images/full_logo_2018.jpg"></a>
        </div>
        
    </header><!-- /header -->
    <nav class="navbar navbar-default">
      <!--<div class="container">-->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <!--<span class="menulabel">MENU</span>-->
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse text-center">
           <?php echo ybr::menuHandler("Main Nav"); ?>
            <div class='navbar-utility hidden-lg hidden-md hidden-sm'>
            <?php echo ybr::menuHandler("Utility"); ?>
            </div>
        </div><!--/.nav-collapse -->
      <!--</div>-->
    </nav>

    
    <?= $innerView; // aka "Layout" template ?>
    
    
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 footer-left">
                    <a href="/"><img src="/assets/images/BristolConnecticut_white.png"></a>
                </div>
                <div class="col-sm-4 footer-middle">
                    
                    <p><strong>DoubleTree by Hilton Hotel Bristol</strong><br>
                        42 Century Drive, Bristol, Connecticut 06010<br>
                        Contact: 860-589-7766<br>
                        Email: <a href="mailto:FrontDesk.BDLCD@hilton.com">FrontDesk.BDLCD@hilton.com</a></p>
                    
                </div>
                <div class="col-sm-4 footer-right">
                    <?php echo ybr::menuHandler("Social Footer"); ?>
                </div>
            </div>
        </div>
        <div id="copyright" class="text-center">
            <span>&copy; 2017 DoubleTree Bristol. All Rights Reserved.</span> &nbsp;&nbsp;&nbsp; <a href="http://hiltonworldwide1.hilton.com/en_US/ww/customersupport/privacy-policy.do?_ga=1.107269506.1048339204.1456153958">Privacy Policy</a>
        </div>
    </footer><!-- /footer -->

</main><!-- /main -->

<?php
//loop and load each script
//TOP
foreach ($_this->template->tscripts as $file) {
	echo HTML::script($file), "\n";
}
//MIDDLE
foreach ($_this->template->mscripts as $file) {
	echo HTML::script($file), "\n";
}
//BOTTOM
foreach ($_this->template->bscripts as $file) {
	echo HTML::script($file), "\n";
}
?>

</body>
</html>
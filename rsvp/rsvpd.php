<?php

$missing_params = array();
$bad_param_values = array();
 
$email_to = "jonathanandliana.rsvp@gmail.com";
 
function required_field($field, $formal_name) {
	if(empty($_POST[$field])) {
		global $missing_params;
    	$missing_params[] = $formal_name;
		return FALSE;
    }
	return TRUE;
}

if(required_field('names', 'Names')) {
	$names = $_POST['names'];
}
if(required_field('rsvp', 'Can you join us?')) {
	$rsvp = $_POST['rsvp'];
}
if(required_field('no-adults', 'No. Adults')) {
	$no_adults = $_POST['no-adults'];
}
if(required_field('no-children', 'No. Children')) {
	$no_children = $_POST['no-children'];
}
if(required_field('email', 'Email Address')) {
	$email = $_POST['email'];
	$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
	if(!preg_match($email_exp,$email)) {
	    $bad_param_values[] = $email ." doesn't look like a valid email address!";
	}
}

if(count($missing_params) == 0 and count($bad_param_values) == 0) {

    $diet = "";
    $hotel = "";
    $comments = "";
	if (isset($_POST['diet'])) {
		$diet = $_POST['diet'];
	}
	if (isset($_POST['hotel'])) {
		$hotel = $_POST['hotel'];
	}
	if (isset($_POST['comments'])) {
		$comments = $_POST['comments'];
	}

	$email_message = "Form details below.\n\n";
	
	function clean_string($string) {
	    $bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}
	 
	$email_message .= "<div>Who: ".clean_string($names)."</div>";
	$email_message .= "<div>Email: ".clean_string($email)."</div>";
	$email_message .= "<div>RSVP: ".clean_string($rsvp)."</div>";
	$email_message .= "<div>Number of adults: ".clean_string($no_adults)."</div>";
	$email_message .= "<div>Number of children: ".clean_string($no_children)."</div>";
	$email_message .= "<div>Dietary restrictions: ".clean_string($diet)."</div>";
	$email_message .= "<div>Staying at blocked hotel: ".clean_string($hotel)."</div>";
	$email_message .= "<div>Comments:\n".clean_string($comments)."</div>";
	
	$email_subject = $names." RSVP'd!";
	
	// create email headers
	$headers = 'MIME-Version: 1.0'."\r\n".
	'Content-type: text/html; charset=iso-8859-1'."\r\n".
	'From: rsvp@jonathanandliana.com'."\r\n".
	'Reply-To: '.$email."\r\n" .
	'X-Mailer: PHP/' . phpversion();
	 
	@mail($email_to, $email_subject, $email_message, $headers);
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>Jo and Lili</title>
        <meta name="description" content="Welcome to the wedding website for Sarah and Brad's Big Day!">

        <meta name="viewport" content="width=device-width,initial-scale=1.0" />

        <!-- For iPhone 4 with high-resolution Retina display: -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png">
        <!-- For first-generation iPad: -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png">
        <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
        <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">

        <link rel="shortcut icon" href="favicon.ico?v=1">

        <link rel="stylesheet" href="/css/normalize.min.css">
        <link rel="stylesheet" href="/css/main-1.6.css">
        
        <link href='http://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
<!--**************************************************************************************************
Initial work by Bradley Greenwood (http://scal.io).
Tailored for Jonathan and Liana, by Jonathan
****************************************************************************************************-->
 
        <div id="bg-image">
            <img src="/img/liana-flower.png" alt="bg" />
        </div>

        <div id="bg-container">

            <div class="header-container sticky">
                <div id="heading" class="wrapper clearfix">
                    <nav id="nav">
                        <ul>
                            <li><a href="/thecouple">THE COUPLE</a></li>
                            <li><a href="/theweddingparty">THE WEDDING PARTY</a></li>
                            <li><a href="/thebigday">THE BIG DAY</a></li>
                            <li><a href="/accommodations">ACCOMMODATIONS</a></li>
                            <li><a href="/registry">REGISTRY</a></li>
                            <li><a href="/rsvp">RSVP</a></li>
                        </ul>

                    </nav>
                </div>
            </div>

            <div class="main-container">
                <div class="rsvp wrapper clearfix">
                    
                    <div id="rsvp-text">
                    	<?php
                    		if (count($missing_params) > 0) {
                    			echo "Looks like you didn't give us all your info! Here's what I'm missing:";
                    			echo "<ul>";
								foreach($missing_params as $param) {
									echo "<li>\"".$param."\"</li>";
								}
                    			echo "</ul>";
							}
							if (count($bad_param_values) > 0) {
								foreach($bad_param_values as $message) {
									echo $message."<br/><br/>";
								}
							}
							if(count($missing_params) > 0 or count($bad_param_values) > 0) {
        						echo "Please press the back button on your browser and try again!";
							} else {
                        		echo "Thanks so much for filling out all that information! We'll be in touch soon :)";
							}
						?>
                    </div>
                        
                    <div class="clearfix"></div>

                </div> <!-- #main -->
            </div> <!-- #main-container -->
        </div> <!-- #bg-container -->

        <script src="/js/main-1.6.js"></script>

        <script>
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-XXXXXXX-XX']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
    </body>
</html>
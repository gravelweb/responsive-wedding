<?php

$bad_param_values = array();
$missing_params_1 = array();
$missing_params_2 = array();
$submitted_rsvp = isset($_POST['submitted_rsvp']);
$submitted_food = isset($_POST['submitted_food']);

# fields required
$names = "";
$rsvp = "";
$no_adults = "";
$email = "";
# fields optional
$no_children = "";
$diet = "";
$comments = "";

function is_checked($field, $value) {
	if($field == $value) {
		echo 'CHECKED';
	}
}

function is_missing($field_name) {
	global $missing_params_1;
	global $missing_params_2;
	if (in_array($field_name, $missing_params_1)) {
		echo "rsvp-missing";
	} elseif (in_array($field_name, $missing_params_2)) {
		echo "rsvp-missing";
	}
}

if ($submitted_rsvp) {
	$email_to = "jonathanandliana.rsvp@gmail.com";
	 
	function required_field($field, &$array) {
		if(empty($_POST[$field])) {
	    	$array[] = $field;
			return FALSE;
	    }
		return TRUE;
	}
	
	function clean_string($string) {
	    $bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}
	
	if(required_field('names', $missing_params_1)) {
		$names = $_POST['names'];
	}
	
	$meals = array();
	$guests = array();
	
	if(required_field('no-adults', $missing_params_1)) {
		$no_adults = $_POST['no-adults'];
		$no_adults_exp = '/^[0-9]+$/';
		if(!preg_match($no_adults_exp,$no_adults)) {
		    $bad_param_values[] = 'Number of adults in your party needs to be a number!';
	    	$missing_params_1[] = 'no-adults';
		} else {
			for ($i = 1; $i <= $no_adults; $i++) {
				$guests[$i] = '';
				$meals[$i] = '';
			}
		}
	}
	
	if(required_field('email', $missing_params_1)) {
		$email = trim($_POST['email']);
		$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
		if(!preg_match($email_exp,$email)) {
		    $bad_param_values[] = 'Sorry! <span class="rsvp-email">'. $email ."</span> doesn't look like a valid email address!";
	    	$missing_params_1[] = 'email';
		}
	}

	if(required_field('rsvp', $missing_params_1)) {
		$rsvp = $_POST['rsvp'];
	}

	if (! $submitted_food) {
			
		if(isset($_POST['no-children'])) {
			$no_children = $_POST['no-children'];
		}
		if (isset($_POST['diet'])) {
			$diet = $_POST['diet'];
		}
		if (isset($_POST['comments'])) {
			$comments = $_POST['comments'];
		}
		
		if(count($missing_params_1) == 0) {
		
			$email_message = "RSVP details below.\n\n";
			 
			$email_message .= "<div>Who: ".clean_string($names)."</div>";
			$email_message .= "<div>Email: ".clean_string($email)."</div>";
			$email_message .= "<div>RSVP: ".clean_string($rsvp)."</div>";
			$email_message .= "<div>Number of adults: ".clean_string($no_adults)."</div>";
			$email_message .= "<div>Number of children: ".clean_string($no_children)."</div>";
			$email_message .= "<div>Dietary restrictions: ".clean_string($diet)."</div>";
			$email_message .= "<div>Comments:\n".clean_string($comments)."</div>";
		}
	} else {
                $food_details = '';
		for ($i = 1; $i <= $no_adults; $i++) {
			if(required_field('guest'.$i, $missing_params_2)) {
				$guests[$i] = $_POST['guest'.$i];
                                $food_details .= '<br />'.clean_string($guests[$i]).' wants: ';
			}
			if(required_field('meal'.$i, $missing_params_2)) {
				$meals[$i] = $_POST['meal'.$i];
                                $food_details .= clean_string($meals[$i]);
			}
		}

		if(count($missing_params_2) == 0) {
			$email_message = "Food details below.\n\n";
			
			$email_message .= "<div>Who: ".clean_string($names)."</div>";
			$email_message .= "<div>Email: ".clean_string($email)."</div>";
			$email_message .= "<div>Food options: ".$food_details."</div>";
		}
	}

	if(count($missing_params_1) == 0 and count($missing_params_2) == 0) {
		$email_subject = $names." RSVP'd!";
		
		// create email headers
		$headers = 'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'From: rsvp@jonathanandliana.com'."\r\n".
		'Reply-To: '.$email."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		 
		@mail($email_to, $email_subject, $email_message, $headers);
	}
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

        <link rel="shortcut icon" href="../favicon.ico?v=1">

        <link rel="stylesheet" href="../css/normalize.min.css">
        <link rel="stylesheet" href="../css/main-1.6.css">
        
        <link href='http://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>

        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
<!--**************************************************************************************************
Initial work by Bradley Greenwood (http://scal.io).
Tailored by Jonathan and Liana
****************************************************************************************************-->

        <!--div id="bg-image">
            <img src="../img/texpaper.jpg" alt="bg" />
        </div-->
        
        <div id="bg-container">
        	<div class="header-container sticky">
                <div id="heading" class="wrapper clearfix">
                    <nav id="nav">
                        <ul>
                            <li><a href="../thecouple">THE COUPLE</a></li>
                            <li><a href="../theweddingparty">THE WEDDING PARTY</a></li>
                            <li><a href="../thewhereandwhen">THE WHERE AND WHEN</a></li>
                            <li><a href="../accommodations">ACCOMMODATIONS</a></li>
                            <li><a href="../registry">REGISTRY</a></li>
                            <li><a class="selected" href="../rsvp">RSVP</a></li>
                        </ul>

                    </nav>
                </div>
            </div>
            
            <div class="rsvp-container">
                <div class="rsvp rsvp-wrapper clearfix">
                    
                    <div id="rsvp-text">
                    	<div id="rsvp-form-header"><img src="../img/rsvp-label.png" width="80%"/></div>
                    	<div id="rsvp-error">
                    	<?php
                    		if (count($missing_params_1) > 0 or count($missing_params_2) > 0) {
                    			echo "Looks like we're missing some info! See below.<br/>";
							}
							if (count($bad_param_values) > 0) {
								foreach($bad_param_values as $message) {
									echo '('.$message.')<br/>';
								}
							}
						?>
                    	</div>
                    	<?php
							if(! $submitted_rsvp or count($missing_params_1) > 0) {
						?>
                        <form id="rsvp-form" action="index.php" method="post">
                        	<input type="hidden" name="submitted_rsvp" value="true"/>
                            <div class="rsvp-label <?php is_missing('names') ?>">Name(s) *</div>
                            <div class="rsvp-field"><input type="text" name="names" value="<?php echo $names ?>"></div>
                            
                            <div class="rsvp-label <?php is_missing('rsvp') ?>">Will you be able to join us? *</div>
                            <div class="rsvp-field">
                            	<ul>
	                            	<li>
		                            	<input type="radio" name="rsvp" id="choice-rsvp-yes" value="yes" <?php is_checked($rsvp, 'yes') ?>>
		                            	<label for="choice-rsvp-yes">I'll be there!</label>
	                            	</li>
	                            	<li>
	                            		<input type="radio" name="rsvp" id="choice-rsvp-no" value="no" <?php is_checked($rsvp, 'no') ?>>
		                            	<label for="choice-rsvp-no">Regretfully, I cannot attend.</label>
	                            	</li>
	                            </ul>
                            </div>
                            
                            <div class="rsvp-label <?php is_missing('no-adults') ?>">No. of adults in your party *</div>
                            <div class="rsvp-field"><input type="number" name="no-adults" value="<?php echo $no_adults ?>"></div>
                            
                            <div class="rsvp-label">No. of children in your party</div>
                            <div class="rsvp-field"><input type="number" name="no-children" value="<?php echo $no_children ?>"></div>
                            
                            <div class="rsvp-label">Dietary restrictions</div>
                            <div class="rsvp-field"><input type="text" name="diet" value="<?php echo $diet ?>"></div>
                            <div class="rsvp-label">Comments! Song Requests! Inquiries!</div>
                            <div class="rsvp-field">
                            	<textarea name="comments" form="rsvp-form"><?php echo $comments ?></textarea>
                            </div>
                                                  
                            <div class="rsvp-label <?php is_missing('email') ?>">Email address *</div>
                            <div class="rsvp-field"><input type="text" name="email" value="<?php echo $email ?>"></div>

                            <div class="rsvp-field"><input type="submit" value="Submit"></div>
                        </form>
                        <?php
							} elseif($rsvp == "yes" and (! $submitted_food or count($missing_params_2) > 0)) {
						?>
                        <form id="food-form" action="index.php" method="post">
                        	<input type="hidden" name="submitted_rsvp" value="true"/>
                        	<input type="hidden" name="submitted_food" value="true"/>
                            <input type="hidden" name="names" value="<?php echo $names ?>">
                            <input type="hidden" name="email" value="<?php echo $email ?>">
                            <input type="hidden" name="no-adults" value="<?php echo $no_adults ?>">
                            <input type="hidden" name="rsvp" value="<?php echo $rsvp ?>">
                        	<?php
								for ($i = 1; $i <= $no_adults; $i++) {
							?>
							<div class="rsvp-label <?php is_missing('guest'.$i) ?>">Guest <?php echo $i ?></div>
                            <div class="rsvp-field"><input type="text" name="guest<?php echo $i ?>" value="<?php echo $guests[$i] ?>"></div>
                            
                            <div class="rsvp-label <?php is_missing('meal'.$i) ?>">Meal option *</div>
                            <div class="rsvp-field">
                            	<ul>
	                            	<li>
		                            	<input type="radio" name="meal<?php echo $i ?>" id="choice-food-ribs<?php echo $i ?>" value="ribs" <?php is_checked($meals[$i], 'ribs') ?>>
		                            	<label for="choice-food-ribs">Braised Short Ribs</label>
	                            	</li>
	                            	<li>
	                            		<input type="radio" name="meal<?php echo $i ?>" id="choice-food-chicken<?php echo $i ?>" value="chicken" <?php is_checked($meals[$i], 'chicken') ?>>
		                            	<label for="choice-food-chicken">Rustic Roasted Chicken</label>
	                            	</li>
	                            	<li>
	                            		<input type="radio" name="meal<?php echo $i ?>" id="choice-food-veggie<?php echo $i ?>" value="veggie" <?php is_checked($meals[$i], 'veggie') ?>>
		                            	<label for="choice-food-veggie">Butternut Squash Ravioli</label>
	                            	</li>
	                            </ul>
	                        </div>
							<?php
								}
							?>
                            <div class="rsvp-field"><input type="submit" value="Submit"></div>
                        </form>
                        <?php
							} else {
                        		echo "<div id=\"rsvp-error\">Thank you! :)</div>";
							}
						?>
                        <div id="rsvp-inquiry">Inquiry? Email us! <a href="mailto:jonathandliana.rsvp@gmail.com?subject=RSVP Inquiry">jonathandliana.rsvp@gmail.com</a></div>
                    </div>
                        
                    <div class="clearfix"></div>

                </div> <!-- #main -->
            </div> <!-- #main-container -->
            <div class="clearfix"></div>
        </div> <!-- #bg-container -->

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-60847704-1', 'auto');
          ga('send', 'pageview');
        </script>
    </body>
</html>

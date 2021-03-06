<?php
	session_start();
?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Kidz Camp</title>
		<link rel="icon" href="images/flavicon.png" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="mystyles.css">


	<style>
  .rating {
  	  width:140px;
      overflow: hidden;
      display: inline-block;
      padding-bottom: 10px;
  }
  .rating-input {
      position: absolute;
      left: 0;
      top: -50px;
  }
   .rating-star {
      float: right;
      display: block;
      width: 16px;
      height: 16px;
      background: url('images/stars.gif') 0 0;
  }


	.rating-star:hover,
	.rating-star:hover ~ .rating-star,
	.rating-input:checked ~ .rating-star{
		background-position: 0 -16px;
	}

	.rating-input:checked ~ .rating-star {
      	background-position: 0 -32px;
  	}

	</style>


	</head>

	<body>
 		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="javascript.js" ></script>

		<script type="text/javascript">
			$(document).ready(function() {
				$.post("getCartCount.php", {userId: <?php echo (isset($_SESSION['user']) ? $_SESSION['user']->id : -1); ?>})
				.done(function(data) {
					$("#cartnum").text(data); //Set number of items in cart
				})
				.fail(function() { alert("AJAX FAILED"); });
			});

			function handleLogin() {
				var usr = document.getElementsByName('username')[0].value;
				var pwd = document.getElementsByName('password')[0].value;
			    
				$.post( "check.php", { username:usr, password:pwd })
					.done(function( data ) {
			    		if( data == "T" ){
			    			//Refresh the page
			    			window.location = window.location.pathname;
			    		}else {
			    			alert( "Password and/or Username Incorrect" );
			    		}
			  		}).fail(function() {
			    		alert( "AJAX FAILED" );
			  		});
			}
			
			function logout() {
				window.location.href = "logout.php";
			}
			function textCounter(field,field2,maxlimit) {
 				var countfield = document.getElementById(field2);
 				countfield.value = maxlimit - field.value.length;
			}
			
			function submitComment(){
				
				var rating = $('input[name="userRating"]:checked').val(); 
				var comment = $('#userReview').val();
				if (comment.length == 0)
					alert("Please enter a comment");
				else {
					$.post( "submitForum.php", { numStars:rating, review:comment })
						.done(function( data ) {
							$('#forum').remove();
							$('.centerpage').html('<h3>Thank you for the feedback!</h3>');
				  		}).fail(function() {
				    		alert( "AJAX FAILED" );
				  		});
				}
			}

		</script>

		<header>
			<div class="logo">
				<a href='index.php'><img src="images/logo.png"/></a>
			</div>
			<div id='cssmenu'>
				<ul>
	  				<li><a href='index.php'><span style="color: yellow">Home</span></a></li>
					<li><a href='camp.php'><span style="color: red">The Camp</span></a></li>
					<li><a href='shop.php'><span style="color: green">Shop</span></a></li>
					<li><?php
					if(isset($_SESSION['user'])) {
						echo '<a href="account.php"><span style="color: blue">Your Account</span></a></li>';
					}
					else {
						echo '<a href="javascript:alertLogin()"><span style="color: blue">Your Account</span></a>';
					}
					?></li>
				</ul>
			</div>
			<div class="loginOrWelcome">
				<?php
					if(isset($_SESSION['user'])) {
						echo '<div>';
						echo '<p>Welcome, <strong>' . $_SESSION['user']->firstName . '</strong>!</p>';
						echo '<button type="button" onclick="showCart(' . $_SESSION['user']->id .', ' . $_SESSION['user']->didEnroll . ')">Cart: <span id="cartnum">0</span> item(s)</button>';
						echo '<button type="button" onclick="logout();">Logout</button>';
						echo '</div>';
					}
					else {
						echo '<form action="javascript:handleLogin();">';
						echo '<table><tr>';
						echo '<td colspan="2"><input type="text" name="username" width="16" placeholder="Username"></td>';
						echo '</tr><tr>';
						echo '<td colspan="2"><input type="password" name="password" width="16" maxlength="16" placeholder="Password"></td>';
						echo '</tr><tr>';
						echo '<td><input type="button" onclick="displaySignup()" value="Create account" /></td>';
						echo '<td><input type="submit" value="Login" /></td>';
						echo '</tr></table></form>';
					}
				?>
			</div>
		</header>
		<section id="boxholder">
			<div id="outer" style="margin-left: auto; margin-right: auto;">
				<div id="boxes">
					<div class="crop">
						<a href="testimonials.php"><img src="images/childrenplaying.jpg" /></a>
					</div>
				</div>
				<div id="boxes">
					<div class="crop">
						<a href="forum.php"><img src="images/kids.jpg" /></a>
					</div>
				</div>
				<div id="boxes">
					<div class="crop">
						<a href="activities.php"><img src="images/teencomputer.jpg" /></a>
					</div>
				</div>
				<div id="boxes">
					<div class="crop">
					<?php
					if(isset($_SESSION['user'])) {
						$a = $_SESSION['user']->firstName;
						$b = $_SESSION['user']->lastName;
						$c = $_SESSION['user']->numEnrolled;
						echo '<a href="javascript:displayRegistration(';
						echo "'" . $a . "'";
						echo ', ';
						echo "'" . $b . "'";
						echo ', ';
						echo "'" . $c . "'";
						echo ')"><img src="images/parentsregistration.jpg" /></a>';
					}
					else {
						echo "<a href='javascript:alertLogin()'><img src='images/parentsregistration.jpg' /></a>";
					}
					?>
					<a href="javascript:displayRegistration()"><img src="images/parentsregistration.jpg" /></a>
					</div>
				</div>
			</div>
		</section>
		<section class="centerpage" style="min-width: 950px; width: 950px;" >
		<div style="width: 500px; float: left;">
			<?php
				if(!isset($_SESSION['user'])) {
					echo '<p> Please log in to rate. </p>';
				} else {
					if( ! $_SESSION['user']->didEnroll ) {
						echo '<p> You must enroll in at least one session to rate. </p>';
					} else {
				?>
				<h1>Submit a Testimonial</h1>
				<p> You may submit a review, testimonial, or suggestion in the comment box below. We appreciate your feedback!</p>
					<form id="forum" action="javascript:submitComment();">
					<p>If you had a great time, please leave us a review. We would love to hear from you!</p>
         				<span class="rating">
         					Rating:
							<input type="radio" value="5" class="rating-input"
        						id="userRating-5" name="userRating">
        					<label for="userRating-5" class="rating-star"></label>
        					<input type="radio" value="4" class="rating-input"
        						id="userRating-4" name="userRating">
        					<label for="userRating-4" class="rating-star"></label>
        					<input type="radio" value="3" class="rating-input"
        						id="userRating-3" name="userRating">
        					<label for="userRating-3" class="rating-star"></label>
        					<input type="radio" value="2" class="rating-input"
        						id="userRating-2" name="userRating">
        					<label for="userRating-2" class="rating-star"></label>
            				<input type="radio" value="1" class="rating-input"
        						id="userRating-1" name="userRating">
        					<label for="userRating-1" class="rating-star"></label>
							
						</span>
         				<table>
        					<tr><td colspan="5">
        						<textarea id="userReview" maxlength="512" rows="4" cols="50" 
        						placeholder="Tell us about your experience with KidzCamp!"
        						onkeyup="textCounter(this,'counter',512);" ></textarea></td>
        					</tr>
        					<tr><td colspan="5">Remaining Characters:
        						<input disabled  maxlength="2" size="2" value="512" id="counter">
        						</td>
        					</tr>
       						<tr><td colspan="2"><input type="submit" name="submit" value="Comment"></td></tr>
       					 </table>
        			</form>
				<?php 
				} 
			} ?>
			</div>
			<img style="float: right;" src='images/testimonial.png' />
		</section>
		<br />
		<footer>
			<center> Web Master:<a href="mailto:jbuschman@scu.edu"> Jordan Buschman </a>  Web Master:<a href="mailto:achung@scu.edu"> Aaron Chung</a> Web Master:<a href="mailto:adeartola@scu.edu"> Andy de Artola</a></center>
			<center>Copyright 2013 KidzCamp Inc. </center>
		</footer>
	</body>
</html> 

<?php
	session_start();
?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Kidz Camp</title>
		<link rel="stylesheet" type="text/css" href="mystyles.css">


	<style>
  .rating {
      overflow: hidden;
      display: inline-block;
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
					<li><a href='#'><span style="color: blue">About</span></a></li>
				</ul>
			</div>
			<div class="loginOrWelcome">
				<?php
					if(isset($_SESSION['user'])) {
						echo '<div>';
						echo '<span>Welcome, <strong>' . $_SESSION['user']->firstName . '</strong>!</span><br />';
						echo '<button type="button" onclick="logout();" style="margin-top: 5px;">Logout</button>';
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
						<img src="images/childrenplaying.jpg" />
					</div>
				</div>
				<div id="boxes">
					<div class="crop">
						<img src="images/teencomputer.jpg" />
					</div>
				</div>
				<div id="boxes">
					<div class="crop">
						<a href="forum.php"><img src="images/kids.jpg" /></a>
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
		<section class="centerpage">
			<?php
					if(isset($_SESSION['user'])) {
						echo '<p> YOU LOGGED IN </p>';
						?>
							<form action="javascript:submitComment();">
         						<span class="rating">
									<input type="radio" class="rating-input"
        								id="rating-input-1-5" name="rating-input-1">
        							<label for="rating-input-1-5" class="rating-star"></label>
        							<input type="radio" class="rating-input"
        								id="rating-input-1-4" name="rating-input-1">
        							<label for="rating-input-1-4" class="rating-star"></label>
        							<input type="radio" class="rating-input"
        								id="rating-input-1-3" name="rating-input-1">
        							<label for="rating-input-1-3" class="rating-star"></label>
        							<input type="radio" class="rating-input"
        								id="rating-input-1-2" name="rating-input-1">
        							<label for="rating-input-1-2" class="rating-star"></label>
            						<input type="radio" class="rating-input"
        								id="rating-input-1-1" name="rating-input-1">
        							<label for="rating-input-1-1" class="rating-star"></label>
								</span>
         						<table>
        							<tr><td colspan="5">
        								<textarea maxlength="512" rows="4" cols="50" 
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
					else {
						echo '<p> Please Log In to submit </p>';
					}
				?>
		</section>
	</body>
</html> 

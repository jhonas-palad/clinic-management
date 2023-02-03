<?php 
	include './config/connection.php';

$message = '';

	if(isset($_POST['login'])) {
    $userName = $_POST['user_name'];
    $password = $_POST['password'];

    $encryptedPassword = md5($password);

    $query = "select `id`, `display_name`, `user_name`, 
`profile_picture` from `users` 
where `user_name` = '$userName' and 
`password` = '$encryptedPassword';";

try {
  $stmtLogin = $con->prepare($query);
  $stmtLogin->execute();

  $count = $stmtLogin->rowCount();
  if($count == 1) {
    $row = $stmtLogin->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['display_name'] = $row['display_name'];
    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['profile_picture'] = $row['profile_picture'];

    header("location:dashboard.php");
    exit;

  } else {
    $message = 'Incorrect username or password.';
  }
}  catch(PDOException $ex) {
      echo $ex->getTraceAsString();
      echo $ex->getMessage();
      exit;
    }
  

		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="plugins/logincss/loginform.css">
    <link rel="stylesheet" href="plugins/slideshow/css.css">
    <!---we had linked our css file----->
       <!---we had linked our css file----->
</head>

<style>
    .imageHolder img {
        object-fit: cover;
    }

    .navbar {
        background: #5C0F0B;
    }

    .footer {
        background: #5C0F0B;
    }
</style>
<body>
<nav class="sticky">
    <div class="full-page">
        <div class="navbar">
            <div>
            <div class = "logo-image">
              <a class="active" href="index.php"><img id="pic" src="images/ubicon.png" style="max-height: calc(51px - (0px * 2))";> University of Batangas</a></div>
        

            </div>
            <nav>
                <ul id='MenuItems'>
                    <!--<li><a href='contact.php'>Contact</a></li>-->
                    <li class="loginHolder">
        <a href="#myModal" rel="facebox" class="roundedBtn_processed" data-toggle="modal" style="background-color: #6B1500" onclick="return false;">
          Log in
        </a>
      </li>
                </ul>
            </nav>  
        </div>
        </nav>
<br><br><br>
        <div class="container site_full_width">
        <div id="wrap">
          

  <!-- Modal -->
  <div class="text-center">
	<!-- Button HTML (to Trigger Modal) -->
	<a href="#myModal" class="roundedBtn_processed" data-toggle="modal"></a>
</div>

<!-- Modal HTML -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="modal-header">
				<div class="avatar">
					<img src="images/ebrahman.png" alt="Avatar">
				</div>				
				<h4 class="modal-title">Member Login</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post">
					<div class="form-group">
						<input type="text" class="form-control" name="user_name" placeholder="Username" required="required">		
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password" required="required">	
					</div>        
					<div class="form-group">
						<button name="login" type="submit" class="btn btn-primary btn-lg btn-block login-btn">Login</button>
					</div>
          <div class="row">
          <div class="col-md-12">
            <p class="text-danger">
              <?php 
              if($message != '') {
                echo $message;
              }
              ?>
            </p>
          </div>
        </div>
				</form>
			</div>
			<!--<div class="modal-footer">
				<a href="#">Forgot Password?</a>
			</div>-->
		</div>
	</div>
</div>     
</div>

<div class="galleryContainer">
        <div class="slideShowContainer">
            <div id="playPause" onclick="playPauseSlides()"></div>
            <div onclick="plusSlides(-1)" class="nextPrevBtn leftArrow"><span class="arrow arrowLeft"></span></div>
            <div onclick="plusSlides(1)" class="nextPrevBtn rightArrow"><span class="arrow arrowRight"></span></div>
            <div class="captionTextHolder"><p class="captionText slideTextFromTop"></p></div>
            <div class="imageHolder">
                <img src="images/clinic_1.jpg">1366X768
                <p class="captionText">Kaneki Ken from Tokyo Ghoul</p>
            </div>
            <div class="imageHolder">
                <img src="images/clinic_2.jpg">
                <p class="captionText">The Electric Bed</p>
            </div>
            <div class="imageHolder">
                <img src="images/clinic_3.jpg">
                <p class="captionText">The Health Office</p>
            </div>
            <div class="imageHolder">
                <img src="images/clinic_4.jpg">
                <p class="captionText">The Doctor's Office</p>
            </div>
            <div class="imageHolder">
                <img src="images/clinic_5.jpg">
                <p class="captionText">Meliodas from The Seven Deadly Sins</p>
            </div>
        </div>
        <div id="dotsContainer"></div>
    </div>

 <!-- <div class="container-lb-1">
  <div class="row-1">
   <div class="block">
   <div class="imgCrop" style="background-image: url('images/clinic_1.jpg')"></div>
          </div>

            <p>
              <br>
              <strong>Vision</strong>
            </p>

          <p>We envision the University of Batangas to be a center of excellence committed to serve the broadecdr community through quality education.</p>

        </div>
        <div class="row-2">
   <div class="flexFixer">
   <div class="imgCrop1" style="background-image: url('images/clinic_2.jpg')"></div>
          </div>

            <p>
              <br>
              <strong>Vision</strong>
            </p>

          <p>We envision the University of Batangas to be a center of excellence committed to serve the broader community through quality education.</p>

        </div>
        <div class="flexFixer">
   <div class="imgCrop1" style="background-image: url('images/clinic_2.jpg')"></div>
          </div>

            <p>
              <br>
              <strong>Vision</strong>
            </p>

          <p>We envision the University of Batangas to be a center of excellence committed to serve the broader community through quality education.</p>

        </div>-->
        
            </div>

   





    <div class="footer">&copy;<span id="year"> </span><span> University of Batangas. All rights reserved.</span></div>
            </div>

<script src="plugins/slideshow/myScript.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>

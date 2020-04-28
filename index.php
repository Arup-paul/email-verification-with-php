
<?php

require_once "controllers/authController.php";

// verify the user using token
if(isset($_GET['token'])){
    $token = $_GET['token'];
    verifyUser($token);
}

// session not set
if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Homepage</title>
</head>
<body>

      <div class="container">
          <div class="row">
              <div class="col-4 offset-4 form-div login">
              <?php
                if ( isset( $_SESSION['message'] ) ): ?>
                <div class="alert  <?php echo $_SESSION['alert-class']; ?>">
                   <?php
                    echo $_SESSION['message'];
                    unset( $_SESSION['message'] );
                    unset( $_SESSION['alert-class'] );
                    ?>
                </div>
                  <?php endif;?>
                 <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>
                 <a href="index.php?logout=1" class="logout">logout</a>


                <?php
                     if ( !$_SESSION['verified'] ): ?>


                 <div class="alert alert-warning">
                     You Need to verify your account <div class="">Sign in to your email account and click on the verification link we just emailed you at
                         <strong> <?php echo $_SESSION['email']; ?></strong>
                     </div>
                 </div>
                  <?php endif;?>

                  <?php if ( $_SESSION['verified'] ): ?>

                     <button class="btn btn-block btn-lg btn-primary">
                         Verified
                     </button>
                     <?php endif;?>


              </div>
          </div>
      </div>



</body>
</html>
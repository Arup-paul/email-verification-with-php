
<?php

require_once "controllers/authController.php";

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
    <title>Register</title>
</head>
<body>

      <div class="container">
          <div class="row">
              <div class="col-4 offset-4 form-div">
                  <form action="signup.php" method="post">
                      <h3 class="text-center">Register</h3>

                        <?php if ( count( $errors ) > 0 ) {?>
                       <div class="alert alert-danger">
                            <?php foreach ( $errors as $error ) {?>
                           <li><?php echo $error; ?></li>
                            <?php }?>
                       </div>
                            <?php }?>

                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" value="<?php echo $username; ?>" name="username" class="form-control form-control-lg">
                      </div>


                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" value="<?php echo $email; ?>" name="email" class="form-control form-control-lg">
                      </div>

                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password"  name="password" class="form-control form-control-lg">
                      </div>


                      <div class="form-group">
                        <label for="passwordconfirm">Confirm Password</label>
                        <input type="password" name="password_confirm" class="form-control form-control-lg">
                      </div>

                      <div class="form-group">
                        <button type="submit" name="signup-btn" class="btn btn-primary btn-block btn-lg">Sign Up</button>
                      </div>

                      <p class="text-center">Already a Member? <a href="login.php">Sign In</a></p>

                  </form>
              </div>
          </div>
      </div>



</body>
</html>
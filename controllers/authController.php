<?php
session_start();
require "config/db.php";
require_once "emailController.php";
//if user clicks on the sign up button

$errors   = array();
$username = "";
$email    = "";

if ( isset( $_POST['signup-btn'] ) ) {
    $username         = $_POST['username'];
    $email            = $_POST['email'];
    $password         = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
}

//validation

if ( empty( $username ) ) {
    $errors['username'] = "Username required";
}
if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
    $errors['email'] = "Email address is invalid";
}
if ( empty( $email ) ) {
    $errors['email'] = "Email required";
}

if ( empty( $password ) ) {
    $errors['password'] = "Password required";
}

if ( !empty( $password !== $password_confirm ) ) {
    $errors['password'] = "Password do not match";
}

// email check
$emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
$stmt       = $conn->prepare( $emailQuery );
$stmt->bind_param( 's', $email );
$stmt->execute();
$result    = $stmt->get_result();
$userCount = $result->num_rows;
$stmt->close();

if ( $userCount > 0 ) {
    $errors['email'] = "Email already Exists";
}
if ( count( $errors ) === 0 ) {
    $password = password_hash( $password, PASSWORD_DEFAULT );
    $token    = bin2hex( random_bytes( 50 ) );
    $verified = false;

    $sql  = "INSERT INTO users (username,email,verified,token,password) VALUES(?,?,?,?,?)";
    $stmt = $conn->prepare( $sql );
    $stmt->bind_param( 'ssbss', $username, $email, $verified, $token, $password );
    // $stmt->bind_param( 's', $username );
    // $stmt->bind_param( 's', $email );
    // $stmt->bind_param( 'b', $verified );
    // $stmt->bind_param( 's', $token );
    // $stmt->bind_param( 's', $password );

    if ( $stmt->execute() ) {
        //login user
        $user_id              = $conn->insert_id;
        $_SESSION['id']       = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email']    = $email;
        $_SESSION['verified'] = $verified;

        sendVerificationEmail( $email, $token );

        //flash message
        $_SESSION['message']     = "You are logged in";
        $_SESSION['alert-class'] = "alert-success";
        header( "location:index.php" );
        exit();

    } else {
        $errors['db_error'] = "Database error:failed to register";
    }

}

//if user click login user

$error = array();

if ( isset( $_POST['login-btn'] ) ) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

//validation

if ( empty( $username ) ) {
    $error['username'] = "Username required";
}

if ( empty( $password ) ) {
    $error['password'] = "Password required";
}

// if ( count( $errors ) == 0 ) {

$sql  = "SELECT * FROM users WHERE email=? or username=? LIMIT 1";
$stmt = $conn->prepare( $sql );
$stmt->bind_param( 'ss', $username, $username );
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();

if ( password_verify( $password, $user['password'] ) ) {
    //login success

    $_SESSION['id']       = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email']    = $user['email'];
    $_SESSION['verified'] = $user['verified'];

    //flash message
    $_SESSION['message']     = "You are logged in";
    $_SESSION['alert-class'] = "alert-success";
    header( "location:index.php" );
    exit();

} else {
    $errors['login_fail'] = "Wrong creadentials";
}
// }

/// logout user

if ( isset( $_GET['logout'] ) ) {
    session_destroy();
    unset( $_SESSION['id'] );
    unset( $_SESSION['username'] );
    unset( $_SESSION['email'] );
    unset( $_SESSION['verified'] );
    header( "Location:login.php" );
    exit();
}

//verify user

function verifyUser( $token ) {
    global $conn;
    $sql    = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query( $conn, $sql );

    if ( mysqli_num_rows( $result ) > 0 ) {
        $user         = mysqli_fetch_assoc( $result );
        $update_query = "UPDATE users SET verified='1' WHERE token = '$token'";

        if ( mysqli_query( $conn, $update_query ) ) {
            //login user

            $_SESSION['id']       = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['verified'] = 1;

            //flash message
            $_SESSION['message']     = "You email address was succesfully verified";
            $_SESSION['alert-class'] = "alert-success";
            header( "location:index.php" );
            exit();

        } else {
            echo "User not found";
        }
    }
}



//recover account

$rerrors = array();

if(isset($_POST['forgot-password-btn'])){
    $email = $_POST['email'];

    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $rerrors['email'] = "Email address is invalid";
    }
    if ( empty( $email ) ) {
        $rerrors['email'] = "Email required";
    }

    if(count($rerrors) == 0){
       $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1"; 
        $result = mysqli_query($conn,$sql);
        $user = mysqli_fetch_assoc($result);
        $token  = $user['token'];
        sendPasswordResetLink($email,$token);
        header('location:password_message.php');
        exit(0);

    }
}



// if user reset password button

$rperror = array();
if(isset($_POST['reset-password-btn'])){
    $password = $_POST['password'];
    //$confirm_password = $_POST['confirm_password'];

    if(empty($password) || empty($confirm_password)){
        $rperror['password'] = "Password required";
    }

    // if($password !== $confirm_password){
    //     $rperror['password'] = "Password Do Not Match";
    // }

    $password = password_hash($password,PASSWORD_DEFAULT);
    $email = $_SESSION['email'];
    

    if(count($rperror) > 0){
        $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";

        $result = mysqli_query($conn,$sql);
        if($result){
            header('location:login.php');
            exit(0);
        }
    }
}

function resetPassword($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token ='$token' LIMIT 1";
    $result = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['email'] = $user['email'];
    header('location:reset_password.php');
    exit(0);

}


?>


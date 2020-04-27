<?php
session_start();
require "config/db.php";
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

if($password !== $password_confirm){
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
     $stmt->bind_param('ssbss',$username,$email,$verified,$token,$password);
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

        //flash message
        $_SESSION['message']     = "You are logged in";
        $_SESSION['alert-class'] = "alert-success";
        header( "location:index.php" );
        exit();

    } else {
        $errors['db_error'] = "Database error:failed to register";
    }

}

?>


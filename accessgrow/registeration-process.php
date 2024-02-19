<?php
require_once "config.php";

$first_name = $last_name = $phone_number = $country = $email = $username = $password = $confirm_password = "";
$first_name_err = $last_name_err = $phone_number_err = $country_err = $email_err = $username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate First Name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }
    
    // Validate Last Name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter your last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate Phone Number
    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Please enter your phone number.";
    } else{
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate Country
    if(empty(trim($_POST["country"]))){
        $country_err = "Please enter your country.";
    } else{
        $country = trim($_POST["country"]);
    }
    
    // Validate Email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validate Username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    // Validate Password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate Confirm Password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting into database
    if(empty($first_name_err) && empty($last_name_err) && empty($phone_number_err) && empty($country_err) && empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (first_name, last_name, phone_number, country, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sssssss", $param_first_name, $param_last_name, $param_phone_number, $param_country, $param_email, $param_username, $param_password);
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone_number = $phone_number;
            $param_country = $country;
            $param_email = $email;
            $param_username = $username;

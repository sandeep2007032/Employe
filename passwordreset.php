<?php
require_once('config.php');

if(isset($_POST['pwdrst'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpwd = mysqli_real_escape_string($conn, $_POST['cpwd']);

    if($password == $cpwd) {
        // Password strength validation can be added here

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $reset_password = mysqli_query($conn, "UPDATE `users` SET password='$hashedPassword' WHERE email='$email'");

        if($reset_password) {
            $msg = 'Your password has been updated successfully. <a href="login.php">Click here</a> to login.';
        } else {
            $msg = "Error while updating password.";
        }
    } else {
        $msg = "Password and Confirm Password do not match";
    }
}

if(isset($_GET['secret'])) {
    $username = base64_decode($_GET['secret']);
    $email = $username;

    $check_details = mysqli_query($conn, "SELECT email FROM `users` WHERE email='$email'");
    $res = mysqli_num_rows($check_details);
  
    if($res > 0) { 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            margin-top: 50px;
        }

        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-success {
            width: 100%;
            background-color: #28a745; /* Green */
            border-color: #28a745; /* Green */
        }

        .btn-success:hover {
            background-color: #218838; /* Darker green */
            border-color: #1e7e34; /* Darker green */
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">  
    <div class="table-responsive">  
    <h3 align="center">Reset Password</h3><br/>
    <div class="box">
     <form id="validate_form" method="post" >  
      <input type="hidden" name="email" value="<?php echo $username; ?>"/>
      <div class="form-group">
       <label for="password">Password</label>
       <input type="password" name="password" id="password" placeholder="Enter Password" required class="form-control"/>
      </div>
      <div class="form-group">
       <label for="cpwd">Confirm Password</label>
       <input type="password" name="cpwd" id="cpwd" placeholder="Enter Confirm Password" required class="form-control"/>
      </div>
      <div class="form-group">
       <input type="submit" id="login" name="pwdrst" value="Reset Password" class="btn btn-success" />
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
     </form>
     </div>
   </div>  
  </div>
</body>
</html>
<?php 
    }
}
?>

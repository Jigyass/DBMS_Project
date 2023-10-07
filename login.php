<?php
session_start();
include "database.php";
if(isset($_SESSION['restaurant_user_id']))
{
    header("location: index.php");
    return;
}
$msg = "";
$error = 0;
if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    $user = getSingleRow("SELECT * FROM `users` where email='$email' and password='$password'");
    if(isset($user['id']))
    {
        $_SESSION['restaurant_user_id'] = $user['id'];
        $_SESSION['restaurant_user_name'] = $user['name'];
        $_SESSION['restaurant_email'] = $user['email'];

        header("location:index.php");
        return;
    }
    else
    {
        $error = 1;
        $msg = "Email or Password in wrong";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=email], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

input[type=submit]:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Login Form</h2>

<form action="" method="post">

  <div class="container">
    <?php
    if($msg != "")
    {
        $gb_color = "green";
        if($error == 1)
        {
            $gb_color = "red";
        }
        echo "
        <div style='background: ".$gb_color."; color: white; padding: 12px; margin: 12px 0px;'>
            ".$msg."
        </div>";
    }
    ?>
    <label for="uname"><b>Email</b></label>
    <input type="email" placeholder="Enter Email" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
        
    <input type="submit" value="Login" name="login">
    
  </div>

  
</form>

</body>
</html>

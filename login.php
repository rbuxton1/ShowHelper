<?php
    include("config.php");
    session_start();   
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from form 
        $activeEmail = mysqli_real_escape_string($db,$_POST['email']);
        $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
        $hashedPass = hash('sha256', $mypassword);
        
        $activeEmail = str_replace(";", "", $activeEmail);
        $mypassword = str_replace(";", "", $mypassword);
      
        $sql = "SELECT id FROM user WHERE email = '$activeEmail' and password = '$hashedPass'";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        //$active = $row['active'];
      
        $count = mysqli_num_rows($result);
      
        // If result matched $myusername and $mypassword, table row must be 1 row
		
        if($count == 1) {
            //session_register("myusername");
            $_SESSION['login_user'] = $activeEmail;
            header("location: welcome.php");
        }else {
            $error = "Your Login Name or Password is invalid";
        }
    }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
        <div align = "center">
            <div style = "width:300px; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				<div style = "margin:30px">
                    <form action = "" method = "post">
                        <label>Email</label><br><input type = "text" name = "email" class = "box"/><br /><br />
                        <label>Password</label><br><input type = "password" name = "password" class = "box" /><br/><br />
                        <input type = "submit" value = " Submit "/><br />
                    </form>
                    <p>
                        <a href="register.php">Create an account</a> <br>
                        <a href="reset.php">Reset Password</a>
                    </p>
                    <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>	
                </div>	
            </div>	
        </div>
    </body>
</html>
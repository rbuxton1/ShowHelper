<?php
    include("config.php");
    session_start();

    $error = false;
    $errorMsg = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $name = strip_tags($name);
        $name = htmlspecialchars($name);
        
        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);
        
        $password = trim($_POST['password']);
        $password = strip_tags($password);
        $password = htmlspecialchars($password);
        
        $confirm = trim($_POST['retype']);
        $confirm = strip_tags($confirm);
        $confirm = htmlspecialchars($confirm);
        
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $error = true;   
            $errorMsg .= "Please enter a real email. ";
        } else {
            $query = "SELECT email FROM user WHERE email='$email'";
            $result = mysqli_query($db,$query);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);
            if($count != 0){
                $error = true;
                $errorMsg .= "Email is already in use. ";
            }
        }
        if(empty($name)){
            $error = true;
            $errorMsg .= "Please enter a username. ";
        }
        if(empty($password)){
            $error = true;
            $errorMsg .= "Please enter a password. ";
        }
        if(empty($confirm)){
            $error = true;
            $errorMsg .= "Please re-type password. ";
        }
        if($confirm != $password){
            $error = true;
            $errorMsg .= "Password does not match. ";
        }
        
        
        
        if(!$error){
            $hashedPass = hash('sha256', $password);
            
            $query = "INSERT INTO user (id, name, email, password) VALUES (NULL, '$name', '$email', '$hashedPass')";
            $result = mysqli_query($db,$query);
            
            if($result){
                $errorMsg = "Sucsess";
                unset($name);
                unset($password);
                unset($email);
                unset($confirm);
                unset($hashedPass);
            } else {
                $errorMsg = "Failure" . ((string)$result) . ": " . $query;
            }
        }
    }
?>
<html>
    <body>
        <center>
            <div style = "width:300px; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Register</b>
            </div>
            <div style = "width:300px; border: solid 1px #333333; " align = "center">
                <div style = "margin:30px">
                    <form action = "" method = "post">
                        <label>Full name</label><br><input type = "text" name = "name" class = "box"/>
                        <br><br>
                        <label>Email</label><br><input type = "text" name = "email" class = "box"/>
                        <br><br>
                        <label>Password</label><br><input type = "password" name = "password" class = "box" />
                        <br><br>
                        <label>Retype</label><br><input type = "password" name = "retype" class = "box"/>
                        <br><br>
                        <input type = "submit" value = " Submit "/><br>
                        <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $errorMsg; ?></div>
                        <br>
                        <p><a href="login.php">Login here</a></p>
                    </form>
                </div>
            </div>
        </center>
    </body>
</html>
<?php
    include('config.php');
?>
<html>
    <center> 
        <h1>Reset Password</h1>
        <p> To reset the password for a user enter the email the account was registered with. </p>
        <form action = "" method = "post">
            <label>Email: </label><input type = "text" name = "reset_email" class = "box"/><input type = "submit" value = "reset"/>
        </form>
        <?php
            if(!empty($_POST['reset_email'])){
                $email = $_POST['reset_email'];
                $email = str_replace(";", "", $email);
                
                $code = '';
                for($i = 0; $i < 10; $i++) {
                    $code .= mt_rand(0, 9);
                }
                
                $sql = "insert into reset (email,code) values ('$email','$code')";
                $result = mysqli_query($db,$sql);
                
                if(mail($email,"Password Reset","Hi there,\nThis email was sent in regards to a password change request. Please visit http://showhelper.net/dashboard/resetverif.php and provide this code '" . $code . "' to reset your password.\n-The Show Helper Team")){
                    echo "Reset code sent.";
                    header("Refresh:1");
                }

            }
        
            
        ?>
    </center>
</html>
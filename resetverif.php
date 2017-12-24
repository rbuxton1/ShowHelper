<?php
    include('config.php');
    
?>
<html>
    <center>
        <h1>Password reset</h1>
        <form action = "" method = "post">
            <label>Email: </label><input type = "text" name = "reset_email" class = "box"/> <br>
            <label>Code: </label><input type = "number" name = "reset_code" class = "box"/> <br>
            <label>New password: </label><input type = "text" name = "newPass" class = "box"/><label> Retype: </label><input type = "text" name = "newPassCom" class = "box"/> <br>
            <input type = "submit" value = "Reset"/>
        </form>
        <p>
        <?php
            if(!empty($_POST['reset_email']) and !empty($_POST['reset_code']) and !empty($_POST['newPass']) and !empty($_POST['newPassCom'])){
                $email = str_replace(";", "", (string)$_POST['reset_email']);
                $resetCode = str_replace(";", "", (string)$_POST['reset_code']);
                $newPass = str_replace(";", "", (string)$_POST['newPass']);
                $newPassCom = str_replace(";", "", (string)$_POST['newPassCom']);
                
                $sql = "select * from reset where email = '$email' and code = '$resetCode'";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                
                if($count == 1){
                    if($newPass == $newPassCom){
                        $hashPass = hash('sha256', $newPass);
                        $sql = "update user set password = '$hashPass' where email = '$email'";
                        $result = mysqli_query($db,$sql);
                        echo "Complete";
                        
                        $sql = "delete from reset where email = '$email'";
                        $result = mysqli_query($db,$sql);
                        header("Refresh:1");
                    } else {
                        echo "Passwords do not match.";
                    }
                } else {
                    echo "Code or email does not match.";
                }
            }
        ?>
        </p>
    </center>
</html>
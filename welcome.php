<?php
    include('session.php');
    include('config.php');
    session_start();


    //basic data about guest
    $email = $_SESSION['login_user'];
    $sql = mysqli_query($db,"select * from user where email = '$email' ");
    $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
    $name = $row['name'];
    $id = $row['id'];
    
    //Guests horse data
    $request = "select * from horse where currOwner = '$id'";
    $sql = mysqli_query($db, $request);
    
    $addMsg = "";
?>
<html>
    <head>
        <title>Welcome </title>
    </head>   
    <body>
        <center>
            <h1>Welcome, <?php echo $name; ?>!</h1>
            <p>
                <b>Version</b> <?php echo $ver ?> <br>
                Your owner ID is <?php echo $id ?>.<br>
                To sign out click <b><a href = "logout.php">here.</a></b><br>
                While a little rough this frontend provides all the necessary functions to allow for the insertion and control of horses. <br>
                To add new horses or to remove horses, go to the bottom of the table below. Your horses are as follows:
            </p>
            <table border="1">
                <tr><th>Code</th> <th>Horse Name</th> <th>Current Owner ID</th> <th>Original Owner Id</th></tr>
                <?php
                    while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
                        echo "<tr>";
                        echo "<td><center>" . $row['code'] . "</center></td>";
                        echo "<td><center>" . $row['name'] . "</center></td>";
                        echo "<td><center>" . $row['currOwner'] . "</center></td>";
                        echo "<td><center>" . $row['origOwner'] . "</center></td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            
            <hr>
            <h2>Add a Horse</h2>
            <p>
                To add a horse, enter its name <b><i>exactly</i></b> as you would like it to appear. Once done, the horse will be added to the table, and all other values will be added for you.
            </p>
            <form action = "" method = "POST">
                <label>Enter horse name: </label><input type = "text" name = "horseName" class = "box"/><input type = "submit" value = "Add"/>
                <br>
            </form>
            <p style="color:red">
                <?php 
                    if(!empty($_POST['horseName'])){
                        $h = $_POST['horseName'];
                        $h = str_replace(";", "", $h);
                        
                        bad:
                        $characters = '0123456789';
                        $charactersLength = strlen($characters);
                        $randomString = '';
                        for ($i = 0; $i < 8; $i++) {
                            $randomString .= $characters[rand(0, $charactersLength - 1)];
                        }
                        
                        $sql = "SELECT * FROM horse WHERE code = '$randomString'";
                        $test = mysqli_query($db, $sql);
                        $count = mysqli_num_rows($test);
                        if($count != 0){
                            #goto bad;
                        }
                        
                        
                        $sql = "insert into horse (id, name, currOwner, origOwner, code) values (NULL, '$h', '$id', '$id', '$randomString')" ;
                        $upload = mysqli_query($db,$sql);
                        header("Refresh:1");
                        
                        if(!$upload){
                            echo "Falure! Please try again.";
                        }
                    }
                ?>
            </p>
            
            <hr>
            <h2>Remove a Horse</h2>
            <p>
                To remove a horse enter the horses code into the text field. This will permenately remove the horse from the database, to re-add it you will have to enter it into the Add Horse section.
            </p>
            <form action = "" method = "post">
                  <label>Enter horse code: </label><input type = "text" name = "removeHorse" class = "box"/>
                  <input type = "submit" value = "Remove"/><br />
            </form>
            <p style="color:red">
                <?php 
                    if(!empty($_POST['removeHorse'])){
                        $h = $_POST['removeHorse'];
                        $h = str_replace(";", "", $h);
                        $sql = "select currOwner from horse where code = '$h'" ;
                        $res = mysqli_query($db,$sql);
                        $currOwner = mysqli_fetch_array($res,MYSQLI_ASSOC);
                        
                        if($currOwner['currOwner'] == $id){
                            $sql = "delete from horse where code = '$h'";
                            $res = mysqli_query($db,$sql);
                            
                            if($res){
                                echo "Done.";
                                header("Refresh:1");
                            } else {
                                echo "Unknown error. Try again." . $res . " ERRCODE:(CO" . $currOwner['currOwner'] . ",US" . $id . ",HID" . $h .")" ."<br>";
                            }
                        } else {
                            echo "HorseID '" . $h . "' belongs to UserID '" . $currOwner['currOwner'] . "', you are UserId '" . $id . "'. <br>";
                        }
                        //header("Refresh:1");
                    }
                ?>
            </p>
            
            <hr>
            <h2>Transfer a Horse</h2>
            <p>
                Transfer flavor text
            </p>
            <form action = "" method = "post">
                  <label>Enter horse code: </label><input type = "text" name = "transHorse" class = "box"/> <label>Enter user ID: </label> <input type = "number" name = "transUser" class = "box"/>
                  <input type = "submit" value = "Transmit"/><br />
            </form>
            <p style="color:red">
                <?php
                    if(!empty($_POST['transHorse'])){
                        $tId = $_POST['transUser'];
                        $tHorse = $_POST['transHorse'];
                        
                        $tId = str_replace(";", "", $tId);
                        $tHorse = str_replace(";", "", $tHorse);
                        
                        $sql = "select currOwner from horse where code = '$tHorse'";
                        $res = mysqli_query($db, $sql);
                        $currOwner = mysqli_fetch_array($res,MYSQLI_ASSOC);
                        
                        if($currOwner['currOwner'] == $id){
                            $sql = "UPDATE horse SET currOwner = '$tId' WHERE code = '$tHorse'";
                            $res = mysqli_query($db, $sql);
                            
                            if($res){
                                echo "Done.";
                                header("Refresh:1");
                            } else {
                                echo "Unknown error. Try again.<br>";
                            }
                        } else {
                            echo "HorseID '" . $tHorse . "' belongs to UserID '" . $currOwner['currOwner'] . "', you are UserId '" . $id . "'. <br>";
                        }
                        
                    }
                ?>
            </p>
            
        </center>
    </body>   
</html>
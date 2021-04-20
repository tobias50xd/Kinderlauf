<?php
    define("TOTAL_MONEY", 0);

    $servername =   "localhost";
    $username =     "root";
    $password =     "";
    $dbname =       "kinderlauf";

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if($conn->connect_error){
        die("Conection failed: ".$conn->connect_error);
    }

    if(isset($_GET["kinderlauf"]) && $_GET["kinderlauf"] == 1) {
        $result = "{\"total_money\": \"" . TOTAL_MONEY . "\", ";
        $stmt = "SELECT SUM(distance) AS \"total_distance\" FROM db_kinderlauf";
        $total_distance_list = $conn->query($stmt);
        $result = $result . "\"total_distance\": ";
        if($total_distance_list->num_rows > 0){
            $total_distance = $total_distance_list->fetch_assoc()["total_distance"];
            $result = $result . $total_distance . ", ";
        }else{
            $result = $result . "0, ";
        }
        $stmt = "SELECT COUNT(id) AS \"total_participants\" FROM db_kinderlauf";
        $total_participants_list = $conn->query($stmt);
        $result = $result . "\"total_participants\": ";
        if($total_participants_list->num_rows > 0){
            $total_participants = $total_participants_list->fetch_assoc()["total_participants"];
            $result = $result . $total_participants;
        }else{
            $result = $result . "0";
        }
        $result = $result . "}";
        echo $result;

        return;
    }

    if(isset($_POST["lname"]) && isset($_POST["fname"]) && isset($_POST["distance"]) && isset($_POST["discipline"])){
        if(preg_match('/^[^\s]/', $_POST ["lname"]) 
        && preg_match('/^[^\s]/', $_POST ["fname"])  
        && (preg_match('/^[0-9]+.?[0-9]{1,2}$/', $_POST ["distance"]) && $_POST["distance"] > 0)
        && in_array($_POST ["discipline"], array("Laufen", "Fahrrad", "Rollerblades", "Walken", "Sonstiges"))
        ){
            if(isset($_POST["orga"]) && preg_match('/^[^\s]/', $_POST ["orga"]) && isset($_POST["depart"]) && preg_match('/^[^\s]/', $_POST ["depart"])){
                $stmt = $conn->prepare("INSERT INTO db_kinderlauf (name, firstname, organisation, department, distance, discipline) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssds", $_POST ["lname"], $_POST ["fname"], $_POST ["orga"], $_POST ["depart"], $_POST ["distance"], $_POST ["discipline"]);
            }else if(isset($_POST["orga"]) && preg_match('/^[^\s]/', $_POST ["orga"])){
                $stmt = $conn->prepare("INSERT INTO db_kinderlauf (name, firstname, organisation, distance, discipline) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssds", $_POST ["lname"], $_POST ["fname"], $_POST ["orga"], $_POST ["distance"], $_POST ["discipline"]);
            }else if(isset($_POST["depart"]) && preg_match('/^[^\s]/', $_POST ["depart"])){
                $stmt = $conn->prepare("INSERT INTO db_kinderlauf (name, firstname, department, distance, discipline) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssds", $_POST ["lname"], $_POST ["fname"], $_POST ["depart"], $_POST ["distance"], $_POST ["discipline"]);
            }else{
                $stmt = $conn->prepare("INSERT INTO db_kinderlauf (name, firstname, distance, discipline) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $_POST ["lname"], $_POST ["fname"], $_POST ["distance"], $_POST ["discipline"]);
            }

            if($stmt->execute()){
                //redirecting
                header("Location: ./data_checkout.html?fname=".$_POST ["fname"]);
                die();
            }else{
                //back to origin
                header("Location: ./data_input.html?error_code=1&lname=".$_POST ["lname"]."&fname=".$_POST ["fname"]."&orga=".$_POST ["orga"]."&depart=".$_POST ["depart"]."&distance=".$_POST ["distance"]."&discipline=".$_POST ["discipline"]);
                die();
            }
        }else{
            //back to origin
            header("Location: ./data_input.html?error_code=1&lname=".$_POST ["lname"]."&fname=".$_POST ["fname"]."&orga=".$_POST ["orga"]."&depart=".$_POST ["depart"]."&distance=".$_POST ["distance"]."&discipline=".$_POST ["discipline"]);
            die();
        }   
    }else{
        //back to origin
        header("Location: ./data_input.html?error_code=1&lname=".$_POST ["lname"]."&fname=".$_POST ["fname"]."&orga=".$_POST ["orga"]."&depart=".$_POST ["depart"]."&distance=".$_POST ["distance"]."&discipline=".$_POST ["discipline"]);
        die();
    }

    $conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Checkout
        </title>
    </head>
    
</html>
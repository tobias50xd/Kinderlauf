<?php
    $servername =   "localhost";
    $username =     "root";
    $password =     "";
    $dbname =       "kinderlauf";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Conection failed: ".$conn->connect_error);
    }

    if(isset($_GET["organisations"]) && isset($_GET["teams"])){
        if($_GET["organisations"] == "all" && $_GET["teams"] =="all"){
            $stmt = $conn->prepare('SELECT organisation, department, 
                                    SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    GROUP BY organisation, department  
                                    ORDER BY organisation  DESC'
                                    );
        }elseif($_GET["organisations"] == "all"){
            if($_GET["teams"] != "NULL"){
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department = ?
                                    GROUP BY organisation'
                                    );
                $stmt->bind_param('s', $_GET["teams"]);
            }else{
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department IS NULL
                                    GROUP BY organisation'
                                    );
            }
        }elseif($_GET["teams"] == "all"){
            if($_GET["organisations"] != "NULL"){
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE organisation = ?
                                    GROUP BY department
                                    ORDER BY department ASC'
                                    );
                $stmt->bind_param('s', $_GET["organisations"]);
            }else{
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE organisation IS NULL
                                    GROUP BY department
                                    ORDER BY department ASC'
                                    );
            }
        }else{
            if($_GET["organisations"] != "NULL" && $_GET["teams"] != "NULL"){
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department = ? AND organisation = ?'
                                    );
                    $stmt->bind_param('ss', $_GET["teams"], $_GET["organisations"]);
            }else if($_GET["organisations"] == "NULL" && $_GET["teams"] != "NULL"){
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department = ? AND organisation IS NULL'
                                    );
                    $stmt->bind_param('s', $_GET["teams"]);
            }else if($_GET["organisations"] != "NULL" && $_GET["teams"] == "NULL"){
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department IS NULL AND organisation = ?'
                                    );
                    $stmt->bind_param('s', $_GET["organisations"]);
            }else{
                $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                    FROM db_kinderlauf
                                    WHERE department IS NULL AND organisation IS NULL'
                                    );
            }
            
        }

        $stmt->execute();
        $resultList = $stmt->get_result();
        if($resultList->num_rows > 0){
            $result = "<tr>";
            while($row = $resultList->fetch_assoc()){
                if($row["organisation"] == NULL){
                    $result = $result . "<td>Sonstige</td>";
                }else{
                    $result = $result . "<td>".$row["organisation"]."</td>";
                }
                if($row["department"] == NULL){
                    $result = $result . "<td>Keine</td>";
                }else{
                    $result = $result . "<td>".$row["department"]."</td>";
                }

                $result = $result . "<td>".$row["participants"]."</td>";
                $result = $result . "<td>".$row["distance"]." km</td>";
                $result = $result . "</tr>";

            }
        }else{
            $result = "<tr> <td>---</td> <td>---</td> <td>---</td> <td>---</td> </tr>";
        }
        
    }else if(isset($_GET["organisations"])){
        if($_GET["organisations"] == "all"){
            $stmt = $conn->prepare('SELECT department
                                    FROM db_kinderlauf
                                    GROUP BY department'
                                  );
        }else{
            if($_GET["organisations"] == "NULL"){
                $stmt = $conn->prepare('SELECT department
                                    FROM db_kinderlauf
                                    WHERE organisation IS NULL
                                    GROUP BY department'
                                  );
            }else{
                $stmt = $conn->prepare('SELECT department
                                    FROM db_kinderlauf
                                    WHERE organisation = ?
                                    GROUP BY department'
                                  );
                $stmt->bind_param('s', $_GET["organisations"]);
            }
        }

        $stmt->execute();
        $teamList = $stmt->get_result();
        $counter = 0;
        $result = "{\"team\": [";

        while($rowTeam = $teamList->fetch_assoc()){
            if($rowTeam["department"] == NULL){
                $result = $result . "\"Keine\",";
            }else{
                $result = $result . "\"" . $rowTeam["department"]."\",";
            }    
        }
        $result = rtrim($result, ',');
        $result = $result . "]}";

    }else if(!isset($_GET["teams"]) && !isset($_GET["organisations"])){
        $stmt = "SELECT organisation FROM db_kinderlauf GROUP BY organisation DESC";
        $organisationList = $conn->query($stmt);
        $result = "{\"organisation\": [";

        while($rowOrga = $organisationList->fetch_assoc()){
            if($rowOrga["organisation"] == NULL){
                $result = $result . "\"Sonstige\",";
            }else{
                $result = $result . "\"" . $rowOrga["organisation"]."\",";
            }    
        }
        $result = rtrim($result, ',');
        $result = $result . "]}";
    }

    $conn->close();

    echo $result;

?>
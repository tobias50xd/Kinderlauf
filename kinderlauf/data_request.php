<!DOCTYPE html>
<html>


<style>
    
    html, body{
        width: 100%;
        margin: 0px;
        text-align: center;
    }

    #top{
        display: flex;
        justify-content: space-between;
    }

    .logo_psv{
        max-width: 5%;
        height: auto;
        margin: 16px;
    }

    .logo_psv  img{
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

	#logo_klfk{
		margin-top: 16px;
		display: inline-block;
		max-width: 70%;
		min-width: 0;
		height: auto;	
		object-fit: contain;
	}

    #main{
        margin: 2vw;
        margin-top: 6vh;
        padding: 4vw;
        border: 1px solid;
        border-color: #ff1900;
        border-radius: 25px;
        text-align: center;
        display: inline-block;
    }

    #main h2{
        margin-left: 16px;
        margin-right: 16px;
        margin-top: 0px;
        padding: 0;
        color: #ff1900;
        margin-bottom: 24px;
        font-size: 3.5vh
    }

    #main select{
        font-size: 2vh;
        display: inline-block;
        margin-left: 16px;
        margin-right: 16px;
        width: 25vh;
        margin-top: 4px;
        margin-bottom: 4px;
        border: 1px solid;
        border-color: #ff1900;
        
    }

    #main input{
        background-color: #ff1900;
        border: 0px;
        width: 25vh;
        margin-top: 12px;
        padding: 4px;
        border-radius: 8px;
        color: white;
        font-size: 2vh;
    }

    #results{
        display: flex;
        justify-content: center;
        align-items: center;
        overflow-x: auto;
    }
    
    #results table{
        /*display: none;*/
        font-size: 3vmin;
        margin-top: 32px;
        margin-left: 0;
        margin-right: 0;
        padding: 0px;
        border: 2px solid;
        border-color: #ff1900;
        border-collapse:collapse;
        overflow-x: auto;
    }

    #results th{
        border: 2px solid;
        border-color: #ff1900;
        margin: 0px;
        padding: 1vmin;
    }

    #results td{
        text-align: right;
        border: 2px solid;
        border-color: #ff1900;
        margin: 0px;
        padding: 0.5vmin;
    }

    

</style>

    <?php
        $servername =   "localhost";
        $username =     "root";
        $password =     "";
        $dbname =       "kinderlauf";

        $isTableActive = true;

        $conn = new mysqli($servername,$username,$password,$dbname);

        if($conn->connect_error){
            die("Conection failed: ".$conn->connect_error);
        }
    ?>



    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../src/logo_postsv.ico">
        <title>Kinderlauf - Datenabfrage</title>
    </head>

    <body>
        <script>
            let dbArray = new Array(
                <?php
                    $stmt = "SELECT organisation, department
                            From db_kinderlauf
                            GROUP BY organisation, department DESC";
                    $departmentList = $conn->query($stmt);
                    while($rowDep = $departmentList->fetch_assoc()){
                        $orga = "\"".$rowDep["organisation"]."\"";
                        $dep = "\"".$rowDep["department"]."\"";
                        if($rowDep["organisation"] == NULL){
                            $orga = "\"NULL\"";
                        }
                        if($rowDep["department"] == NULL){
                            $dep = "\"NULL\"";
                        }
                        echo "{organisation: ".$orga.", department: ".$dep."}, ";    
                    }
                ?>
            );
            
            function changeDdTeams(pickedOption){
                    let selectView = document.getElementById('dd_teams');

                    for (let i = selectView.options.length ; i>=2; i--) {
                        selectView.remove(i);
                    }

                    for(let i=0;i<dbArray.length;i++){
                        if(pickedOption == "NULL"){
                            break; 
                        }
                        if(pickedOption == "all" || dbArray[i].organisation == pickedOption){
                            let opt = document.createElement('option');
                            opt.value = dbArray[i].department;
                            opt.innerHTML = dbArray[i].department;
                            if(opt.innerHTML == "NULL"){
                                opt.value = "NULL";
                                opt.innerHTML = "Keine";
                            }
                            selectView.appendChild(opt);
                        }
                    }
                }
        </script>

        <div id="top">
            <div class="logo_psv">
                <a href="https://www.postsportverein-landshut.de/spendenlauf">
                    <img src="https://www.postsportverein-landshut.de/images/site/PostSVLogo_gross.png" alt="Post SV Landshut">
                </a>
            </div>
            <img id="logo_klfk" src="../src/klfk.jpg" alt="Kinder laufen für Kinder">
            <div class="logo_psv" style="visibility: hidden;">
                <a href="https://www.postsportverein-landshut.de/spendenlauf">
                    <img src="https://www.postsportverein-landshut.de/images/site/PostSVLogo_gross.png" alt="Post SV Landshut">
                </a>
            </div>
        </div>
        <div id="main">
            <?php
                $stmt = "SELECT organisation FROM db_kinderlauf GROUP BY organisation DESC";
                $organisationList = $conn->query($stmt);
            ?>

            <h2>Detailierte Datenabfrage</h2>
            <form action="../kinderlauf/data_request.php" method="GET">
                <select id="dd_organisations" name="dd_organisations" onchange = "changeDdTeams(this.value);">
                    <option value="main">Schule/Organisation</option>
                    <option value="all">Alle</option>
                    <?php
                        while($rowOrg = $organisationList->fetch_assoc()){
                            if($rowOrg["organisation"] == NULL){
                                echo "<option value=\"NULL\">Sonstige</option>";
                            }else{
                                echo "<option value=\"".$rowOrg["organisation"]."\">".$rowOrg["organisation"]."</option>";
                            }
                        }
                    ?>

                </select><br>
                <select id="dd_teams" name="dd_teams">
                    <option value="main">Klasse/Abteilung</option>
                    <option value="all">Alle</option>
                    <?php
                        if(!empty($_GET["dd_organisations"])){
                            if($_GET["dd_organisations"] == "all"){
                                $stmt = $conn->prepare('SELECT department
                                FROM db_kinderlauf
                                GROUP BY department'
                                );
                            }else{
                                $stmt = $conn->prepare('SELECT department
                                FROM db_kinderlauf
                                WHERE organisation = ?
                                GROUP BY department'
                                );
                                $stmt->bind_param('s', $_GET["dd_organisations"]);
                            }
                            
                            $stmt->execute();
                            $departmentListAlt = $stmt->get_result();
                            while($rowDepAlt = $departmentListAlt->fetch_assoc()){
                                if($rowDepAlt["department"] == NULL){
                                    echo "<option value=\"NULL\">Keine</option>";
                                }else{
                                    echo "<option value=\"".$rowDepAlt["department"]."\">".$rowDepAlt["department"]."</option>";
                                }
                            }
                        }
                    ?>

                </select><br>

                <input type="submit" id="submit" value="Suchen">
            </form>
            <script>
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                if(urlParams.get('dd_organisations') != null ||urlParams.get('dd_organisations')!= undefined){
                    document.getElementById("dd_organisations").value = urlParams.get('dd_organisations');
                }
                if(urlParams.get('dd_teams') != null ||urlParams.get('dd_teams') != undefined){
                    document.getElementById("dd_teams").value = urlParams.get('dd_teams');
                }
                
            </script>

            <div id="results" <?php if(empty($_GET["dd_organisations"])||(empty($_GET["dd_teams"]))
                                ||($_GET["dd_organisations"]=="main")||($_GET["dd_teams"]=="main")){$isTableActive = false; echo "style=\"display: none;\"";}
                                ?>>
                <table >
                    <tr>
                        <th>Schule/Organisation</th>
                        <th>Klasse/Abteilung</th>
                        <th>Teilnehmer</th>
                        <th>Kilometer</th>
                    </tr>

                    <?php
                        if($isTableActive){
                            if($_GET["dd_organisations"] =="all" && $_GET["dd_teams"] =="all"){
                                $stmt = $conn->prepare('SELECT organisation, department, 
                                                        SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        GROUP BY organisation, department  
                                                        ORDER BY organisation  DESC'
                                                        );
                            }elseif($_GET["dd_organisations"] == "all"){
                                if($_GET["dd_teams"] != "NULL"){
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department = ?
                                                        GROUP BY organisation'
                                                        );
                                    $stmt->bind_param('s', $_GET["dd_teams"]);
                                }else{
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department IS NULL
                                                        GROUP BY organisation'
                                                        );
                                }
                            }elseif($_GET["dd_teams"] == "all"){
                                if($_GET["dd_organisations"] != "NULL"){
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE organisation = ?
                                                        GROUP BY department
                                                        ORDER BY department ASC'
                                                        );
                                    $stmt->bind_param('s', $_GET["dd_organisations"]);
                                }else{
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE organisation IS NULL
                                                        GROUP BY department
                                                        ORDER BY department ASC'
                                                        );
                                }
                            }else{
                                if($_GET["dd_organisations"] != "NULL" && $_GET["dd_teams"] != "NULL"){
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department = ? AND organisation = ?'
                                                        );
                                    	$stmt->bind_param('ss', $_GET["dd_teams"], $_GET["dd_organisations"]);
                                }else if($_GET["dd_organisations"] == "NULL" && $_GET["dd_teams"] != "NULL"){
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department = ? AND organisation IS NULL'
                                                        );
                                    	$stmt->bind_param('s', $_GET["dd_teams"]);
                                }else if($_GET["dd_organisations"] != "NULL" && $_GET["dd_teams"] == "NULL"){
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department IS NULL AND organisation = ?'
                                                        );
                                    	$stmt->bind_param('s', $_GET["dd_organisations"]);
                                }else{
                                    $stmt = $conn->prepare('SELECT organisation, department, SUM(distance) AS distance, IF(department != NULL, COUNT(department), COUNT(name)) AS participants
                                                        FROM db_kinderlauf
                                                        WHERE department IS NULL AND organisation IS NULL'
                                                        );
                                }
                                
                            }

                            $stmt->execute();

                            $result = $stmt->get_result();

                            if($result->num_rows > 0){
                                //output each row
                                while($row = $result->fetch_assoc()){
                                    echo "<tr>";
                                    if($row["organisation"] == NULL){
                                        echo    "<td>Sonstige</td>";
                                    }else{
                                        echo    "<td>".$row["organisation"]."</td>";
                                    }
                                    if($row["department"] == NULL){
                                        echo    "<td>Keine</td>";
                                    }else{
                                        echo    "<td>".$row["department"]."</td>";
                                    }
                                    echo    "<td>".$row["participants"]."</td>";
                                    echo    "<td>".$row["distance"]." km</td>";
                                    echo "</tr>";
                                }
                            }else{
                                echo "<tr>";
                                echo    "<td>---</td>";
                                echo    "<td>---</td>";
                                echo    "<td>---</td>";
                                echo    "<td>---</td>";
                                echo "</tr>";
                            }
                        }

                        $conn->close();
                    ?>
                    
                </table>
            </div>
        </div>
    </body>
</html>
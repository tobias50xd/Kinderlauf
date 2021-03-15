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
        display: flex;
        justify-content: space-evenly;
        flex-wrap: wrap;
        position: relative;
        border: 1px solid #ff1900;
        border-radius: 25px;
        margin: 2vw;
        margin-top: 6vh;
        padding: 4vw;
    }

    #left_side{
        text-align: center;
        border: 1px solid #ff1900;
        border-radius: 8px;
        padding-top: 8px;
        padding-right: 4vw;
        padding-left: 4vw;
        padding-bottom: 8px;
    }

    #left_side h2{
        color: #ff1900;
        margin: 0px;
        margin-bottom: 16px;
        margin-left: 8px;
        margin-right: 8px;
        padding: 0px;
    }

    .inputs{
        border: 1px solid;
        border-color: black;
        margin: 2px;
        margin-left: 8px;
        margin-right: 8px;
        width: 25vh;
    }

    .inputs:focus{
        border-color: #ff8800;
    }

    input, select{
        width:100%;
        box-sizing: border-box;
    }

    #chapta_start{
        margin: 8px;
        margin-top: 16px;
        width: 8vw;
        height: auto;
    }

    #chapta_code{
        margin-top: 8px;
        font-size: large;
    }

    #submit{
        position: absolute;
        bottom: 8px;
        right: 3vw;
        color: white;
        background-color: #ff1900;
        border: none;
        border-radius: 5px;
        padding-left: 16px;
        padding-right: 16px;
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 2.5vh;
        height: 4vh;
        width: auto;
    }



    #right_side{
        text-align: center;
        margin-right: 3vw;
        margin-left: 3vw;
        align-self:center;
    }

    #charity_details{
        border: none;
        background-color: #ff1900;
        border-radius: 8px;
        padding-left: 1vw;
        padding-right: 1vw;
        color: white;
        text-align: start;
    }

    #charity_details h3{
        padding-top: 8px;
        padding-bottom: 8px;
    }

    #submit_placeholder{
        height: 4vh;
    }



</style>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../src/logo_postsv.ico">
        <title>Kinderlauf - Datenerfassung</title>
    </head>

    <body>
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
            <div id="left_side">
                <h2>Lauf eintragen</h2>
                <form action="../kinderlauf/data_checkout.html" method="POST">
                    <input type="text" placeholder="Name" id="lname" name="lname" class="inputs"><br>
                    <input type="text" placeholder="Vorname" id="fname" name="fname" class="inputs"><br>
                    <input type="text" placeholder="Schule/Organisation" id="orga" name="orga" class="inputs"><br>
                    <input type="text" placeholder="Klasse/Abteilung" id="depart" name="depart" class="inputs"><br>
                    <input type="number" placeholder="Kilometer" id="distance" name="distance" class="inputs"><br>
                    <select id="discipline" name="discipline"class="inputs">
                        <option class="discipline_options" value="">Laufen</option>
                        <option class="discipline_options" value="">Fahrrad</option>
                        <option class="discipline_options" value="">Rollerblades</option>
                        <option class="discipline_options" value="">Sonstiges</option>
                    </select><br>
                    <img id="chapta_start" class="inputs" src="captcha_code_file.php?rand=<?php echo rand(); ?>" id="captchaimg" ><br>
                    <label style ="border: none;" class="inputs" for="message">Geben sie den obigen Code bitte ein:</label><br>
                    <input type="text" onfocus="this.value=''" value="Code" placeholder="Code" id="chapta_code" name="chapta_code" class="inputs">
                    <input id="submit" type="submit" value="Lauf abschließen">
                </form>
            </div>
            <div id="right_side">
                <div id="charity_details">
                    <h3>Spendenadresse:</h3>
                        Schulstiftung Seligenthal; Sonderkonto „Kinder laufen für Kinder“<br>
					    Sparkasse Landshut;	BIC: BYLADEM1LAH<br>
					    IBAN: DE09743500000004664205<br>
					    Verwendungszweck: Spende von ... für virtuellen Spendenlauf<br>
                    <h3>Danke für Ihre Unterstützung!</h3>
                </div>
                <div id="submit_placeholder">
                    
                </div>
            </div>
            
        </div>
        
    </body>
</html>
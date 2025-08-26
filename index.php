<?php

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }

   
    $board_array1 = [["m","","","","r"],
                    ["r","","r","",""],
                    ["r","","","",""],
                    ["","","","r","r"],
                    ["","r","","","g"],
                    ["r","","","r","r"],
                    ];

    $board_array2 = [["r","","","","r",""],
                    ["r","","r","","",""],
                    ["r","","m","","",""],
                    ["","","","r","r",""],
                    ["","r","","","","r"],
                    ["r","","g","r","","r"],
                    ["r","","","r","r",""],
                    ["r","","","r","r",""],
                    ];                

   // Only set the board if it doesn't exist yet
if (!isset($_SESSION["board_array"])) {
    $_SESSION["board_array"] = $board_array1;
    
} 

$_SESSION["display"] = "none";
$_SESSION["user_message"] = "";


    // Move RIGHT
    if (isset($_POST["btnRight"])){
        $player = getPlayer($_SESSION["board_array"]);

        if ($player[1]+1 !== count($_SESSION["board_array"][0])){
            $_SESSION["board_array"][$player[0]][$player[1]+1]= "m";
            $_SESSION["board_array"][$player[0]][$player[1]]= ""; 
        }
        else
        {
            $_SESSION["display"] = "block";
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
        
    }

    // Move LEFT
    if (isset($_POST["btnLeft"])){
        $player = getPlayer($_SESSION["board_array"]);

        if ($player[1] !== 0){
            $_SESSION["board_array"][$player[0]][$player[1]-1]= "m";
            $_SESSION["board_array"][$player[0]][$player[1]]= ""; 
        }
        else
        {
            $_SESSION["display"] = "block";
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
    }

    // Move UP
    if (isset($_POST["btnUp"])){
        $player = getPlayer($_SESSION["board_array"]);
        if ($player[0] !== 0){
             $_SESSION["board_array"][$player[0]-1][$player[1]]= "m";
             $_SESSION["board_array"][$player[0]][$player[1]]= ""; 
        }else
        {
             $_SESSION["display"] = "block";
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
    }

    // Move DOWN
    if (isset($_POST["btnDown"])){
        $player = getPlayer($_SESSION["board_array"]);
        if ($player[0]+1 !== count($_SESSION["board_array"])){
            $_SESSION["board_array"][$player[0]+1][$player[1]]= "m";
            $_SESSION["board_array"][$player[0]][$player[1]]= ""; 
        }else
        {
             $_SESSION["display"] = "block";
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
    }
    // RESET game
    if (isset($_POST["btnReset"])){
        session_destroy();
        header("Refresh:0");
    }

    function getPlayer($board_array) {
        for ($i=0; $i < count($board_array) ;$i++){
            for ($j=0; $j < count($board_array[$i]);$j++){
                if ($board_array[$i][$j]=== "m" ) return [$i,$j];
            }
        }
    }


    function drawBoard($board_array){

        for ($i=0; $i < count($board_array) ;$i++){
            echo '<div class="boardLine">';
            for ($j=0; $j < count($board_array[$i]);$j++){
                if ($board_array[$i][$j] === "m"){
                    echo '<div><img  class="boardSquare" src="./assets/images/miner.png"></div>';
                } else if($board_array[$i][$j] === "r"){
                    echo '<div><img  class="boardSquare" src="./assets/images/rock.png"></div>';
                } else if ($board_array[$i][$j] === "g"){
                    echo '<div><img  class="boardSquare" src="./assets/images/gold.png"></div>';
                }
                else 
                    echo '<div><img  class="boardSquare" src="./assets/images/empty.png"></div>';
                }
                echo '</div>';
        }

        
    }                
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Miner</title>
</head>
<body>
    <main>
        <div id="pageContainer">
            <div id="mainDisplay">
                <div id="board">
                    <div>
                        <?php drawBoard($_SESSION["board_array"])?>
                    </div>
                     <div id="userMessage" style="display: <?php echo $_SESSION["display"]?>">
                        <p id="messageText"><?php echo $_SESSION["user_message"] ?></p>
                     </div>    
                </div>
                <div id="navigation">
                    <form method="post">
                        <div id="formDiv">
                            <div><button name="btnUp" id="btnUp">Up</button></div>
                            <div id="btnLeftRight">
                                <div><button name="btnLeft" id="btnLeft">Left</button></div>
                                <div><button name="btnRight" id="btnRight">Right</button></div>
                            </div>
                            <div><button name="btnDown" id="btnDown">Down</button></div>
                            <div>
                                <button name="btnReset" id="btnReset">Reset</button>
                            </div>
                       
                            </div>
                        </div>
                    </form>

                </div>
                
            </div>
            
    </main>
    
</body>
</html>
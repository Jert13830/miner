<?php

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
    }

   
    $board_array1 = [["m","","","","r"],
                    ["r","","r","",""],
                    ["r","","","",""],
                    ["","","","r","r"],
                    ["","r","","","r"],
                    ["r","","g","r","r"],
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

if (!isset($_SESSION["goldFound"])) {
    $_SESSION["goldFound"] = false;
}

$_SESSION["display"] = "none";
$_SESSION["user_message"] = "";

$_SESSION["goldPos"] = getGold($_SESSION["board_array"]);
$_SESSION["playerPos"] = getPlayer($_SESSION["board_array"]);


getButtonClick();

if (isset($_POST["btnReset"])){
    session_destroy();
    header("Refresh:0");
}


function collision($row, $col): bool {
    switch ($_SESSION["board_array"][$row][$col])
    {
        case 'r' : 
            return false;
            break;
        case 'g' : 
            $_SESSION["goldFound"] = true;
            $_SESSION["board_array"][$row][$col] = "w";
            return true;
            break;
        default : 
            return true;
    }

}

function getButtonClick(){
    $is_direction = true;

    if (isset($_POST["buttons"]) && !$_SESSION["goldFound"]){
        
        switch (htmlspecialchars($_POST["buttons"]))
        {
            case 'right':
            

                if ($_SESSION["playerPos"][1]+1 !== count($_SESSION["board_array"][0]) && collision($_SESSION["playerPos"][0],$_SESSION["playerPos"][1]+1)){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]+1]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= ""; 
                }
                else
                {
                    showMessage("error");
                }
            break;

            case 'left':
                if ($_SESSION["playerPos"][1] !== 0 && collision($_SESSION["playerPos"][0],$_SESSION["playerPos"][1]-1)){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]-1]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= ""; 
                }
                else
                {
                    showMessage("error");
                }
            break;
                
            case 'up':
                if ($_SESSION["playerPos"][0] !== 0 && collision($_SESSION["playerPos"][0]-1,$_SESSION["playerPos"][1])){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]-1][$_SESSION["playerPos"][1]]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= ""; 
                }else
                {
                    showMessage("error");
                }
            break;

            case 'down':
                if ($_SESSION["playerPos"][0]+1 !== count($_SESSION["board_array"]) && collision($_SESSION["playerPos"][0]+1,$_SESSION["playerPos"][1])){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]+1][$_SESSION["playerPos"][1]]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= ""; 
                }else
                {
                    showMessage("error");
                }
            break;
        }

        if ($_SESSION["goldFound"]){
            showMessage("winner");     
        }

    }
}
    
    function showMessage($messageType){
        $_SESSION["display"] = "block";
        if ($messageType == "error"){
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
        else{
             $_SESSION["user_message"] = "Your rich. You hit the JACKPOT!!";
        }
        
    }

    function getPlayer($board_array) {
        for ($i=0; $i < count($board_array) ;$i++){
            for ($j=0; $j < count($board_array[$i]);$j++){
                if ($board_array[$i][$j]=== "m" ) return [$i,$j];
            }
        }
    }

    function getGold($board_array) {
        for ($i=0; $i < count($board_array) ;$i++){
            for ($j=0; $j < count($board_array[$i]);$j++){
                if ($board_array[$i][$j]=== "g" ) return [$i,$j];
            }
        }
    }

    function drawBlocks($board_array){
        for ($i=0; $i < count($board_array) ;$i++){
            for ($j=0; $j < count($board_array[$i]);$j++){
                if($i = $_SESSION["playerPos"][0] && $j=$_SESSION["playerPos"][1]){
                    echo '<div><img  class="boardSquare" src="./assets/images/empty.png"></div>';
                }
                else{
                    echo '<div><img  class="boardSquare" src="./assets/images/block.png"></div>';
                }
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
                    $showDiv = !$_SESSION["goldFound"];
                    echo '<div class="gold"><img  style="display: ' . (!$_SESSION["goldFound"] ? 'block' : 'none') . ';" class="boardSquare" src="./assets/images/gold.png"></div>';
                    echo '<div class="winner"><img  style="display: ' . (!$_SESSION["goldFound"] ? 'block' : 'none') . ';" class="boardSquare" src="./assets/images/winner.png"></div>';
                }else if ($board_array[$i][$j] === "w"){
                     echo '<div><img  class="boardSquare" src="./assets/images/winner.png"></div>';
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
                            <div><button  name="buttons" value="up" id="btnUp"><img class="btnDir"  src="./assets/images/AxeUp.png" alt="Axe up"></button></div>
                            <div id="btnLeftRight">
                                <div><button name="buttons" value="left" id="btnLeft"><img class="btnDir" src="./assets/images/bootLeft.png" alt="Left Boot"></button></div>
                                <div><button name="buttons" value="right" id="btnRight"><img class="btnDir"  src="./assets/images/bootRight.png" alt="Right Boot"></button></div>
                            </div>
                            <div><button name="buttons" value="down" id="btnDown"><img class="btnDir" src="./assets/images/axeDown.png" alt="Axe down"></button></div>
                            <div>
                                <button name="btnReset" value="reset" id="btnReset"><img id="btnResetImg" src="./assets/images/rest.png" alt="Metal plate"></button>
                            </div>
                       
                            </div>
                        </div>
                    </form>

                </div>
                
            </div>
            
    </main>
    
</body>
</html>
<?php

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
    }

   
    /*$board_array1 = [["m","","","","r"],
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
                    ];                */

    

   // Only set the board if it doesn't exist yet
if (!isset($_SESSION["board_array"])) {
    $_SESSION["board_array"] = create_array();
    
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

function create_array(): array {

    // Array
    $size = rand(5,9); //set the array to be a random size between 5 and 9 squares wide or high
    $grid = array_fill(0, $size,array_fill(0,$size,"."));

    // Place Player
    $playerX = rand(0,$size-1);
    $playerY = rand(0,$size - 1);
    $grid[$playerY][$playerX] = "m";

    // Place Gold
    do{
        $goldX = rand(0, $size-1);
        $goldY = rand(0, $size-1);
    } while ($goldX == $playerX && $goldY == $playerY);

    $grid[$goldY][$goldX] = "g";

    // Find Path from Player to Gold
    $pathX = $playerX;
    $pathY = $playerY;

    while ($pathX != $goldX || $pathY != $goldY){
       
        if($pathX < $goldX) $pathX++;
        elseif ($pathX > $goldX) $pathX--;
        elseif ($pathY < $goldY) $pathY++;
        elseif ($pathY > $goldY) $pathY--;

        if ($grid[$pathY][$pathX] == ".") $grid[$pathY][$pathX] = " ";
    }

    //Cover a 1/3 of the board with rocks
    for ($i = 0; $i < $size * $size / 3; $i++){

        $rockX = rand(0,$size-1);
        $rockY = rand(0, $size-1);

        if ($grid[$rockY][$rockX]=="."){
            $grid[$rockY][$rockX] = 'r';
        }
    }

    return $grid;
}

function collision($row, $col): bool {
    switch ($_SESSION["board_array"][$row][$col])
    {
        case 'r' : 
            return false;
            break;
        case 'g' : 
            $_SESSION["goldFound"] = true;
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
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= "."; 
                }
                else
                {
                    showMessage("error");
                }
            break;

            case 'left':
                if ($_SESSION["playerPos"][1] !== 0 && collision($_SESSION["playerPos"][0],$_SESSION["playerPos"][1]-1)){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]-1]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= "."; 
                }
                else
                {
                    showMessage("error");
                }
            break;
                
            case 'up':
                if ($_SESSION["playerPos"][0] !== 0 && collision($_SESSION["playerPos"][0]-1,$_SESSION["playerPos"][1])){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]-1][$_SESSION["playerPos"][1]]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= "."; 
                }else
                {
                    showMessage("error");
                }
            break;

            case 'down':
                if ($_SESSION["playerPos"][0]+1 !== count($_SESSION["board_array"]) && collision($_SESSION["playerPos"][0]+1,$_SESSION["playerPos"][1])){
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]+1][$_SESSION["playerPos"][1]]= "m";
                    $_SESSION["board_array"][$_SESSION["playerPos"][0]][$_SESSION["playerPos"][1]]= "."; 
                }else
                {
                    showMessage("error");
                }
            break;
        }

        if ($_SESSION["goldFound"]){
            showMessage("winner");     
        }

         // Update player position after move
        $_SESSION["playerPos"] = getPlayer($_SESSION["board_array"]);

    }
}
    
    function showMessage($messageType){
        $_SESSION["display"] = "block";
        if ($messageType == "error"){
            $_SESSION["user_message"] = "You can't go in that direction.";
        }
        else{
             $_SESSION["user_message"] = "You're rich. You hit the JACKPOT!!";
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
        echo '<div id="blocks">';
        for ($i=0; $i < count($board_array) ;$i++){
            echo '<div class="boardLine">';
            for ($j=0; $j < count($board_array[$i]);$j++){
                if($i == $_SESSION["playerPos"][0] && $j==$_SESSION["playerPos"][1]){
                   echo '<div><img class="boardBlock" src="./assets/images/empty.png"></div>';
                }
                else{
                    echo '<div><img class="boardBlock" src="./assets/images/block.png"></div>';
                   
                }
            }
            echo '</div>';
        }
        echo '</div>';
    }

    function playerClose($col,$row): bool{
        
        //above
        if ($col === $_SESSION["playerPos"][0]-1 && $row === $_SESSION["playerPos"][1] ){
            return true;
        }
        //below
        if ($col === $_SESSION["playerPos"][0]+1 && $row === $_SESSION["playerPos"][1] ){
            return true;
        }
        // left
        if ($col === $_SESSION["playerPos"][0] && $row === $_SESSION["playerPos"][1]-1 ){
            return true;
        }
        // right
        if ($col === $_SESSION["playerPos"][0] && $row === $_SESSION["playerPos"][1]+1 ){
            return true;
        }

        return false;
    }


    function drawBoard($board_array){

        for ($i=0; $i < count($board_array) ;$i++){
            echo '<div class="boardLine">';
            for ($j=0; $j < count($board_array[$i]);$j++){
                if ($board_array[$i][$j] === "m"){
                    echo '<div><img  class="boardSquare" src="./assets/images/miner.png"></div>';
                } else if($board_array[$i][$j] === "r" && playerClose($i,$j)){
                    echo '<div><img  class="boardSquare" src="./assets/images/rock.png"></div>';
                } else if ($board_array[$i][$j] === "g" && playerClose($i,$j)){
                        // Gold not yet found
                        echo '<div class="gold"><img style="display: block;" class="boardSquare" src="./assets/images/gold.png"></div>';
                } else if ($board_array[$i][$j] === "g" && ($_SESSION["playerPos"][0]==$i && $_SESSION["playerPos"][1]==$j)) {
                        // Winner: player is on gold
                        echo '<div class="winner"><img style="display: block;" class="boardSquare" src="./assets/images/winner.png"></div>';
                } else if ($board_array[$i][$j] === "." && playerClose($i,$j)){
                    echo '<div><img  class="boardSquare" src="./assets/images/empty.png"></div>';
                }else {
                    echo '<div><img  class="boardSquare block" src="./assets/images/block.png"></div>';
                }
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
                   
                            <?php drawBoard($_SESSION["board_array"])?>
                    
                  
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
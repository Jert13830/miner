<?php

$board_array1 = [["m","","","","r"],
                 ["r","","r","",""],
                 ["r","","","",""],
                 ["","","","r","r"],
                 ["","r","","","g"],
                 ["r","","","r","r"],
                ];

                

if (isset($_POST["btnRight"])){
    $player = getPlayer($board_array1);

    $board_array1[$player[0]][$player[1]+1]= "m";
    $board_array1[$player[0]][$player[1]]= ""; 
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
                    <?php drawBoard($board_array1)?>
                </div>
                <div id="navigation">
                    <form method="post">
                        <div id="formDiv">
                            <div><button id="btnUp">Up</button></div>
                            <div id="btnLeftRight">
                                <div><button id="btnLeft">Left</button></div>
                                <div><button name="btnRight" id="btnRight">Right</button></div>
                            </div>
                            <div><button id="btnDown">Down</button></div>
                        <div>
                            <button id="btnReset">Reset</button>
                        </div>
                        </div>
                    </form>

                </div>
            </div>
            <div id="userMessage">
                <p id="messageText">Messages to the user !!</p>
            </div>    
        </div>
    </main>
    
</body>
</html>




<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
canvas {
    border:1px solid #d3d3d3;
    background-color: #f1f1f1;
}
</style>
</head>


<body onload="startGame()" style="background-color:powderblue;">


<script>

var myGamePiece;
var myObstacles = [];
var myScore;

function startGame() {
    myGamePiece = new component(30, 30, "red", 10, 120);
    myGamePiece.gravity = 0.05;
    myScore = new component("30px", "Consolas", "black", 280, 40, "text");
    myGameArea.start();
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 480;
        this.canvas.height = 270;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.frameNo = 0;
        this.interval = setInterval(updateGameArea, 20);
        },
    clear : function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}

function component(width, height, color, x, y, type) {
    this.type = type;
    this.score = 0;
    this.width = width;
    this.height = height;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;
    this.gravity = 0;
    this.gravitySpeed = 0;
    this.update = function() {
        ctx = myGameArea.context;
        if (this.type == "text") {
            ctx.font = this.width + " " + this.height;
            ctx.fillStyle = color;
            ctx.fillText(this.text, this.x, this.y);
        } else {
            ctx.fillStyle = color;
            ctx.fillRect(this.x, this.y, this.width, this.height);
        }
    }
    this.newPos = function() {
        this.gravitySpeed += this.gravity;
        this.x += this.speedX;
        this.y += this.speedY + this.gravitySpeed;
        this.hitBottom();
    }
    this.hitBottom = function() {
        var rockbottom = myGameArea.canvas.height - this.height;
        if (this.y > rockbottom) {
            this.y = rockbottom;
            this.gravitySpeed = 0;
        }
    }
    this.crashWith = function(otherobj) {
        var myleft = this.x;
        var myright = this.x + (this.width);
        var mytop = this.y;
        var mybottom = this.y + (this.height);
        var otherleft = otherobj.x;
        var otherright = otherobj.x + (otherobj.width);
        var othertop = otherobj.y;
        var otherbottom = otherobj.y + (otherobj.height);
        var crash = true;
        if ((mybottom < othertop) || (mytop > otherbottom) || (myright < otherleft) || (myleft > otherright)) {
            crash = false;
        }
        return crash;
    }
}

function updateGameArea() {
    var x, height, gap, minHeight, maxHeight, minGap, maxGap;
    for (i = 0; i < myObstacles.length; i += 1) {
        if (myGamePiece.crashWith(myObstacles[i])) {
					var test = myGameArea.frameNo;
    		document.getElementById("myInput").value = test;
			return;
        } 
    }
    myGameArea.clear();
    myGameArea.frameNo += 1;
    if (myGameArea.frameNo == 1 || everyinterval(150)) {
        x = myGameArea.canvas.width;
        minHeight = 20;
        maxHeight = 200;
        height = Math.floor(Math.random()*(maxHeight-minHeight+1)+minHeight);
        minGap = 50;
        maxGap = 200;
        gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
        myObstacles.push(new component(10, height, "green", x, 0));
        myObstacles.push(new component(10, x - height - gap, "green", x, height + gap));
    }
    for (i = 0; i < myObstacles.length; i += 1) {
        myObstacles[i].x += -1;
        myObstacles[i].update();
    }
    myScore.text="SCORE: " + myGameArea.frameNo;
    myScore.update();
    myGamePiece.newPos();
    myGamePiece.update();
	
}

function everyinterval(n) {
    if ((myGameArea.frameNo / n) % 1 == 0) {return true;}
    return false;
}

function accelerate(n) {
    myGamePiece.gravity = n;
}

</script>
<br>
<button onmousedown="accelerate(-0.2)" onmouseup="accelerate(0.05)">ACCELERATE</button>
<p>Use the ACCELERATE button to stay in the air!!</p>
<p>How long can you stay alive?</p>

<form><input type=button value="RESTART" onClick="history.go()"></form> 
</body>

</html>







<?php

$id = 0;
$score = 0;



if(isset($_POST['ID']) && isset($_POST['SCR']) ){
	$id = $_POST['ID'];
	$score = $_POST['SCR'];

	
	//echo $id;
	//echo '<br>';
	//echo $score;
	
	

	}

	/*$serverName = 'LENOVO-PC\SQLEXPRESS'; //serverName\instanceName
	
	// Since UID and PWD are not specified in the $connectionInfo array,
	// The connection will be attempted using Windows Authentication.
	$connectionInfo = array( "Database"=>"test");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	
	if( $conn ) {
	     echo "Connection established.<br />";
	}else{
	     echo "Connection could not be established.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}


*/




$servername = "localhost";
$username = "root";  
$password = ""; 
$databasename = "scoreboard"; 
// Create connection
$conn = new mysqli($servername, $username, $password,$databasename);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//echo "<br>";
//echo "Connected successfully";
echo "<br>";


	

	if(!empty($id) && !empty($score) ){
		$sql2="UPDATE score S
		SET S.SCR = '$score'
		WHERE S.ID = '$id' AND S.SCR < '$score' ";
		$result= $conn->query($sql2);		
		}

if(!empty($id) && !empty($score) ){
$sql2="INSERT INTO score values('$id','$score')";
$result= $conn->query($sql2);



}


//$sql="SELECT ID, SCR FROM score";
$sql="SELECT * FROM score ORDER BY SCR DESC";
$result= $conn->query($sql);

echo 'LEADER BOARD:';
echo "<br>";
echo "<br>";

if ($result->num_rows > 0) {	
$i = 1;
while($row = $result->fetch_assoc()){
echo $i . ') '. $row["ID"]. ' - Score: ' . $row["SCR"]. ' Points'. "<br>";
$i++;
}
	} else {
echo "0 results"; 
	}

$conn->close();
/*mysql_connect('localhost', 'root','');

mysql_select_db('wp_music');

$sql="SELECT * FROM Consumption";
$records=mysql_query($sql);
*/

echo "<br>";
echo 'Enter your nickname:';
echo "<br>";

?> 






<form action="index.php" method="POST">
<input type="text" name="ID" />
<input id="myInput" type="text" value="Your Score" name='SCR' read only />
<input type="submit" value = "Submit Score" />
</form>



<br>



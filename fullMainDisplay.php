<?php $station = $_GET["station"];
if ($station == "") {
	$station = 217;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		body {
			background-color: black;
		}

		.departures {
			border: none;
			position: absolute;
			left: 0;
			top: 0;
			width: 50vw;
			height: 50vh;
		}

		.nextTo {
			border: none;
			position: absolute;
			left: 25vw;
			bottom: 0;
			width: 50vw;
			height: 50vh;
		}

		.arrivals {
			border: none;
			position: absolute;
			right: 0;
			top: 0;
			width: 50vw;
			height: 50vh;
		}
	</style>
</head>

<body>
	<iframe class="departures" src="https://trains.candycryst.com/monitor.php?type=departures&station=<?php echo $station ?>&hideCopyright=true"></iframe>
	<iframe class="nextTo" src="https://trains.candycryst.com/monitor.php?type=nextTo&station=<?php echo $station ?>&hideCopyright=true"></iframe>
	<iframe class="arrivals" src="https://trains.candycryst.com/monitor.php?type=arrivals&station=<?php echo $station ?>&hideCopyright=true"></iframe>
</body>

</html>
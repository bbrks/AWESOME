<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}

include "lib-admin.php";

$questionaireID = $_GET["questionaireID"];

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script src="../js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../js/Chart.min.js" type="text/javascript"></script>
	<style>
		.table .progress {
			margin-bottom: 0px;
		}
	</style>
	
</head>

<body>
	<?
	
	$results = getResults("cs21120", $questionaireID);
	//print_r($results);
	foreach($results as $key=>$module) {
		echo "<h3>{$module[0]["QuestionText"]}</h3>";
		
		if ($module[0]["Type"] == "text") {
			foreach ($module as $result) {
				echo ($result["NumValue"] . $result["TextValue"])."<br />";
			}
		}
		elseif ($module[0]["Type"] == "rate") {
			$data = array(
				array(
						"value"=>0,
						"color"=>"#F7464A",
						"label"=>"1"
					),
				array(
						"value"=>0,
						"color"=>"#F7464A",
						"label"=>2
					),
				array(
						"value"=>0,
						"color"=>"#FDB45C",
						"label"=>3,
					),
				array(
						"value"=>0,
						"color"=>"#5AD3D1",
						"label"=>4,
					),
				array(
						"value"=>0,
						"color"=>"#5AD3D1",
						"label"=>5
					)
			);
				
			foreach ($module as $result) {
				$data[$result["NumValue"]-1]["value"]++;
			}
			?>
			<canvas id="<?=$key?>" width="400" height="400"></canvas>
			<script>
				var ctx = document.getElementById("<?=$key?>").getContext("2d");
				var myNewChart = new Chart(ctx).Pie(<? echo json_encode($data); ?>);
				
				
			</script>
			<?
		}
	}
	
	
	?>
	
	
</body>
</html>

<?php
session_start();
if(isset($_POST['btnSubmit'])) {
	$vehicleNo = $_POST['vehicle_no'];
	$arrivalTime = $_POST['arrival_time'];
	$vehicleType = $_POST['vehicle_type'];
	$lane1Count = 0;$lane2Count = 0;$lane3Count = 0;$lane4Count = 0;
	if (isset($_SESSION['queue']['lane1']) && !empty($_SESSION['queue']['lane1'])) {
		$lane1Count = count($_SESSION['queue']['lane1']);
	}
	if (isset($_SESSION['queue']['lane2']) && !empty($_SESSION['queue']['lane2'])) {
		$lane2Count = count($_SESSION['queue']['lane2']);
	}
	if (isset($_SESSION['queue']['lane3']) && !empty($_SESSION['queue']['lane3'])) {
		$lane3Count = count($_SESSION['queue']['lane3']);
	}
	if (isset($_SESSION['queue']['lane4']) && !empty($_SESSION['queue']['lane4'])) {
		$lane4Count = count($_SESSION['queue']['lane4']);
	}
	if ($vehicleType == 'normal') {
		$vehiclesArray = array('lane1' => $lane1Count, 'lane2' => $lane2Count, 'lane3' => $lane3Count, 'lane4' => $lane4Count);
		asort($vehiclesArray);
		$lane = key($vehiclesArray);
	} else {
		$lane = 'vip';
	}
	if (empty($_SESSION['queue'][$lane])) {
		$depatureTime = strtotime("+1 minutes", strtotime($arrivalTime));
		$_SESSION['queue'][$lane][0] = array($vehicleNo, $arrivalTime, date('H:i', $depatureTime));
	} else {
		$queueCount = count($_SESSION['queue'][$lane])+1;
		$depatureTime = strtotime("+".$queueCount." minutes", strtotime($arrivalTime));
		array_push($_SESSION['queue'][$lane], array($vehicleNo, $arrivalTime,date('H:i', $depatureTime)));
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Toll Plaza</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<style>
		body{background-color: beige;}
		h5{text-transform: uppercase;}
		.card{border-radius: 35px;}
		.card-header{background-color: black;color: white;}
		.vip {background-color: #007bff !important}
	</style>
</head>
<body>
<div class="container">
	<h1 class="text-center mt-3">Toll Plaza</h1>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12 mx-auto">
				<div class="card mt-5">
					<div class="card-header text-center p-3">
						<h5>Vehicle Input</h5>
					</div>
					<div class="card-body">
						<form action="toll-plaza.php" method="POST">
							<div class="col-md-12-col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-4">
										<label for="">Vehicle No</label>
										<input required="" type="text" name="vehicle_no" class="form-control" placeholder="Vehicle No">
									</div>
									<div class="col-md-4">
										<label for="">Arrival Time</label>
										<input required="" type="time" name="arrival_time" class="form-control" placeholder="Arrival Time">
									</div>
									<div class="col-md-4">
										<label for="">Type of Vehicle</label>
										<select class="form-control" name="vehicle_type" required="">
											<option value="normal">Normal</option>
											<option value="vip">VIP</option>
										</select>
									</div>
								</div>
								<div class="row mt-5">
									<div class="col-md-4 mx-auto">
										<button name="btnSubmit" class="btn btn-dark btn-md">Enter in Queue</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="card mt-5">
					<div class="card-header text-center p-3">
						<h5>Vehicles in Queue</h5>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead align="center">
								<th>Vehicle No</th>
								<th>Arrival Time</th>
								<th>Departure Time</th>
								<th>Lane No</th>
							</thead>
							<tbody>
								<?php
									$queue = isset($_SESSION['queue'])?$_SESSION['queue']:null;
									if(isset($queue)) {
										foreach ($queue as $key => $value) {
											for ($i = 0; $i < count($queue[$key]); $i++) {
												echo '<tr align="center">
												<td>'.$queue[$key][$i][0].'</td>
												<td>'.$queue[$key][$i][1].'</td>
												<td>'.$queue[$key][$i][2].'</td>
												<td>'.$key.'</td>
												</tr>';
											} 
										}
									}
								?>
							</tbody>
						</table>
					</div>	
				</div>
			</div>
		</div>
	</div>
	<h1 class="text-center mt-2">Queue Simulation</h1>
	<div class="col-md-12 col-sm-12 col-xs-12 mt-3 mb-5">
		<div class="row">
			<div class="col-md-2 mx-auto mx-auto">
				<div class="card">
					<div class="card-header text-center p-3">
						<h5>Lane 1</h5>
					</div>
					<div class="card-body">
						<?php
							$laneOneCount = isset($queue['lane1'])?count($queue['lane1']):0;
							for ($i = 0; $i < $laneOneCount; $i++) {
								echo'<h4 class="text-center"><span class="badge badge-secondary">'.$queue['lane1'][$i][0].'</span></h4>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-2 mx-auto">
				<div class="card">
					<div class="card-header text-center p-3">
					<h5>Lane 2</h5>
					</div>
					<div class="card-body">
						<?php
							$laneTwoCount = isset($queue['lane2'])?count($queue['lane2']):0;
							for ($i = 0; $i < $laneTwoCount; $i++) {
								echo'<h4 class="text-center"><span class="badge badge-secondary">'.$queue['lane2'][$i][0].'</span></h4>';
							}
						?>
					</div>
				</div>
			</div>	
			<div class="col-md-2 mx-auto">
				<div class="card">
					<div class="card-header text-center p-3">
					<h5>Lane 3</h5>
					</div>
					<div class="card-body">
						<?php
							$laneThreeCount = isset($queue['lane3'])?count($queue['lane3']):0;
							for ($i = 0; $i < $laneThreeCount; $i++) {
								echo'<h4 class="text-center"><span class="badge badge-secondary">'.$queue['lane3'][$i][0].'</span></h4>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-2 mx-auto">
				<div class="card">
					<div class="card-header text-center p-3">
						<h5>Lane 4</h5>
					</div>	
					<div class="card-body">
						<?php
							$laneFourCount = isset($queue['lane4'])?count($queue['lane4']):0;
							for ($i = 0; $i < $laneFourCount; $i++) {
								echo'<h4 class="text-center"><span class="badge badge-secondary">'.$queue['lane4'][$i][0].'</span></h4>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-2 mx-auto">
				<div class="card">
					<div class="card-header text-center vip p-3">
						<h5>VIP Lane</h5>
					</div>	
					<div class="card-body">
						<?php
							$vipLaneCount = isset($queue['vip'])?count($queue['vip']):0;
							for ($i = 0; $i < $vipLaneCount; $i++) {
								echo'<h4 class="text-center"><span class="badge badge-primary">'.$queue['vip'][$i][0].'</span></h4>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
</body>
</html>
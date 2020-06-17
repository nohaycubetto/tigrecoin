<?php
require __DIR__ . '/vendor/autoload.php';

function insert_coin($coin_amount, $machine_id)
{
	$options = [
		'cluster' => 'mt1',
		'useTLS' => true
	];

	$pusher = new Pusher\Pusher(
		'd2b0d2035e8be20fcdf2',
		'75646fdc8772b387bd06',
		'1021042',
		$options
	);

	$timestamp = date('Y-m-d H:i:s').' UTC';
	$event_name = 'insert-coin';
	$message = [
		'tigrecoin'=> [
			'machine_id'=>$machine_id,
			'event_name'=>$event_name,
			'data'=>[
				'coin_amount'=>$coin_amount,
				'timestamp'=>$timestamp
			]
		]
	];
	$pusher->trigger('tigrecoin-'.$machine_id, $event_name, $message);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(!isset($_POST['machine_id'])) die('machine_id is required');
	if(!isset($_POST['coin_amount'])) die('coin_amount is required');
	$machine_id = $_POST['machine_id'];
	$coin_amount = $_POST['coin_amount'];
	insert_coin($coin_amount,$machine_id);
	$notification = "Sent $coin_amount coin(s) to $machine_id";
}
?>
<html>
<head>
	<title>TigreCoin Test 1</title>
	<style>
		body { font-family:Helvetica, Arial, sans-serif; }
	</style>
</head>
<body>
	<h1>TigreCoin Test 1</h1>
	<p><strong><?php echo $notification; ?></strong></p>
	<form method="POST" action="index.php">
		<input type="text" name="machine_id" value="machine-1">
		<input type="text" name="coin_amount" value="1">
		<input type="submit" value="INSERT TIGRE COIN">
	</form>
</body>
</html>
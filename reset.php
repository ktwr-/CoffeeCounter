<?php
  ini_set('display_errors',1);
	date_default_timezone_set('Asia/Tokyo');
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	require('sqlsetting.php');
	#$id= $_POST['visible'];
	$dbname = h(preg_replace('/(?:\n|\r|\r\n)/', '', file_get_contents('./dbname.txt')));
	
try{
	$pdo = new PDO(
		'mysql:dbname=coffee_counter;host=localhost;charset=utf8mb4',
		$user,
		$passwd, 
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		]
	);
	$date = date("ym");
	$newdbname = 'coffee'.$date;
	$newtable= $pdo->query('CREATE table coffee'.$date.' (id int, coffee int default 0, stick int default 0, extra int default 0, comment varchar(100), foreign key(id) references user(id));');
	file_put_contents("./dbname.txt", $newdbname, LOCK_EX);
	$userid = $pdo->query('SELECT id FROM user;');
	while($result = $userid->fetch(PDO::FETCH_ASSOC)) {
		$stmt = $pdo->prepare('INSERT INTO '.$newdbname.' (id) values (:id);');
		$stmt->bindValue(':id',$result['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
	$pdo->query("INSERT INTO backnumber VALUES (null, '".$dbname."');");
	#$search->execute();	
	#$$value = $search->fetch();
	
	header('Location: ./index.php');
	exit;
} catch (PDOException $e) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 exit($e->getMessage()); 
}
?>

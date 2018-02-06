<?php
// 見える人と見えない人を切り替えるphp
#ini_set('display_errors','stderr');
	date_default_timezone_set('Asia/Tokyo');
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	require('sqlsetting.php');
	$id= $_POST['visible'];
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
	$newtable= $pdo->query('CREATE coffee'.$date.' id int, coffee int default 0, stick int default 0, extra int default 0, comment varchar(100), foreign key(id references user(id);');
	file_put_contents("./dbname.txt", 'coffee'.$date)
	#$search->execute();	
	#$$value = $search->fetch();
	
	header('Location: ./index.php');
	exit;
} catch (PDOException $d) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 exit($e->getMessage()); 
}
?>

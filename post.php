<?php
ini_set('display_errors','stderr');
error_reporting(E_ALL);
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	require('sqlsetting.php');

	$username = h($_POST['user']);
	#echo $username;
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
	// userがかぶっていないか確認	
	$user = $pdo->prepare('SELECT * FROM user where username = ?');
	$user->bindValue(1,$username);
	$user->execute();
	if ($user->rowCount()){
		header('Content-Type: text/html; charset=utf-8');
		echo "同じユーザが存在します。別のユーザ名で作成してください<br>";
		echo "<a href='./index.php'>戻る</a>";
		exit;
	}


	$stmt = $pdo->prepare('INSERT INTO user values(null,?,true)');
	$stmt->bindValue(1,$username);
	$stmt->execute();
	
	$id = $pdo->prepare('SELECT * FROM user where username = ?');
	$id->bindValue(1,$username);
	$id->execute();
	// dbnameにユーザを追加する。これでindex.phpに表示されるようになる.
	while($row = $id->fetch()) {
		$adduser = $pdo->prepare('INSERT INTO '.$dbname.' values(?,0,0)');
		$adduser->bindValue(1,$row['id']);
		$adduser->execute();
	}

	header('Location: ./index.php');
	exit;
} catch (PDOException $d) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 echo "ユーザ追加に失敗しました";
 exit($e->getMessage()); 
}
?>

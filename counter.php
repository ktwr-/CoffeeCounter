<?php
ini_set('display_errors','stderr');
error_reporting(E_ALL);
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	$id = $_POST['id'];
	$coffee = (int)$_POST['coffee'];
	$stick = (int)$_POST['stick'];
	$extra = (int)$_POST['extra'];
	#echo $username;
	$dbname = h(preg_replace('/(?:\n|\r|\r\n)/', '', file_get_contents('./dbname.txt')));
	require('sqlsetting.php');
	
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
	// 現在の珈琲とスティックを確認	
	$search = $pdo->prepare('SELECT * FROM '.$dbname.' where id = ?');
	$search->bindValue(1,$id);
	$search->execute();	
	$value = $search->fetch();
	// 新しい値を計算するよ
	$newcoffee = (int)$value['coffee'] + $coffee;
	$newstick = (int)$value['stick'] + $stick;
	$newextra = (int)$value['extra'] + $extra;
	
	echo $newcoffee;
	echo $newstick;
	if($_POST['comment'] !== ''){	
		$update = $pdo->prepare('UPDATE '.$dbname.' SET coffee = ?, stick = ?, extra = ? WHERE id = ?');
		$update->execute([$newcoffee, $newstick, $extra ,$id]);
	}else{
		$comment= h($_POST['comment']);
		$newcomment = h($value['comment'])."\n".$comment;
		$update = $pdo->prepare('UPDATE '.$dbname.' SET coffee = ?, stick = ?, extra = ?, comment = ? WHERE id = ?');
		$update->execute([$newcoffee, $newstick, $extra, $comment, $id]);
	}

	#echo "<a href='./index.php'>戻る</a>";
	header('Location: ./index.php');
	exit;
} catch (PDOException $d) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 echo "ユーザ追加に失敗しました";
 exit($e->getMessage()); 
}
?>

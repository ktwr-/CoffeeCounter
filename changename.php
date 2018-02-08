<?php
// 見える人と見えない人を切り替えるphp
#ini_set('display_errors','stderr');
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	$id= $_POST['userselect'];
	$text = h($_POST['changename']);
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
	
	$search = $pdo->prepare('SELECT * FROM user where id = ?');
	$search->bindValue(1,$id);
	$search->execute();	
	$value = $search->fetch();
	
	$update = $pdo->prepare('UPDATE user SET username = ? WHERE id = ?');
	$update->execute([$text, $id]);

	echo "<a href='./index.php'>戻る</a>";
	header('Location: ./index.php');
	exit;
} catch (PDOException $d) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 exit($e->getMessage()); 
}
?>

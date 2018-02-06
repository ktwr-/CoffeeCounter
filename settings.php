<?php
#ini_set('display_errors','stderr');
#error_reporting(E_ALL);
	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}

require('sqlsetting.php');
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
	$visibleuser = $pdo->query('SELECT * FROM user WHERE visible = true');
	$unvisibleuser = $pdo->query('SELECT * FROM user WHERE visible = false');
	#$table = $pdo->query('SELECT * FROM '.$dbname);
} catch (PDOException $d) {
 header('Content-Type: text/plain; charset=UTF-8', true, 500);
 exit($e->getMessage()); 
}
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">

    <title>設定を探す日常</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="index.php">こーひーかうんたー</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="./index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./account.php">金額計算 <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="./settings.php">設定</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./help.html">Help</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Home画面から見えなくするよ</h1>
						現在見えている人は見えなく、見えていない人は見えるように変わります．
						<form action="./visible.php" method="post">
						<div class="form-group">
						<select name="visible">ユーザを選択してね
						<option disabled="disabled">見える人</option>
						<?php
							while($row = $visibleuser->fetch()){
								printf("<option value='%d'>%s</option>",$row['id'], $row['username']);
							}
						?>
						<option disabled="disabled">見えない人</optin>
						<?php
							while($row = $unvisibleuser->fetch()){
								printf("<option value='%d'>%s</option>",$row['id'], $row['username']);
							}
						?>
						</select>
						<input type="submit" value="ドロン！">
						</div>
						</form>
					<h1>データベースリセット</h1>
						金額計算してデータベースリセットしたくなったらどうぞ
						リセットボタンを押すと前のデータに戻れなくなります。修正するならサーバから直接データベースとファイルを修正して!
						<form action="./reset.php" method="post">
						<input type="submit" value="リセット">
						</form>
					<h1>名前変更</h1>
						Homeで表示される名前を変えたかったら変更可能だよ(節度は持ってね)
						<form action="./changename.php" method="post">
						<select name="userselect">
						<?php
							$visibleuser = $pdo->query('SELECT * FROM user WHERE visible = true');
							while($row = $visibleuser->fetch()){
								printf("<option value='%d'>%s</option>",$row['id'], $row['username']);
							}
						?>
						<input type="text" name="changename">
						<input type="submit" value="名前変更">
						</form>
					
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="./bootstrap/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="./bootstrap/assets/js/vendor/popper.min.js"></script>
    <script src="./bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>

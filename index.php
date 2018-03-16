<?php
ini_set('display_errors','stderr');
error_reporting(E_ALL);
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
	$user = $pdo->query('SELECT * FROM user');
	$table = $pdo->query('SELECT * FROM '.$dbname);
} catch (PDOException $e) {
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

    <title>ご注文は珈琲カウンターですか？</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">こーひーかうんたー</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./account.php">金額計算</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./settings.php">設定</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./help.html">Help</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">今月<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./backnumber.php">過去の記録</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">何かメニューいる？</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Coffe Counter</h1>

          <h2>I'd like some more coffee!!</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>名前</th>
                  <th>珈琲</th>
                  <th>ネスレスティック</th>
                  <th>紙コップとか</th>
                  <th>珈琲増やすよ</th>
                  <th>スティック増やすよ</th>
									<th>その他</th>
									<th>コメント</th>
									<th></th>
                </tr>
              </thead>
              <tbody>
									<?php
										$rows = $user->fetchAll();
										$i=0;
										while($row = $table->fetch()){
											while(1){
												if($rows[$i]['id'] == $row['id']) break;	
												$i++;
											}
											if( $rows[$i]["visible"] == true){
												echo "<tr><form action='./counter.php' method='post'>";
												printf("<td>%s</td>\n",$rows[$i]["username"]);
												printf("<td>%d</td><td>%d</td>", $row['coffee'],$row['stick']);
												printf("<td>%d円</td>", $row['extra']);
												printf("<td><input type='number' name='coffee' value='1' step='1' required></td>\n<td><input type='number' name='stick' value='0' step='1' required></td>");
												printf("<td><input type='number' name='extra' value='0' required></td>\n<td><input type='text' name='comment'></td>");
												echo "<input type='hidden' name='id' value='".$rows[$i]['id']."'>";
												echo "<td><input type='submit' value='送信'></form></td></tr>";
											}
										}
									?>
              </tbody>
            </table>
          </div>
				<h2>ユーザ追加</h2>	
				<form action="./post.php" method='post'>
					<div class="form-group">
						<label>ユーザ名</label>
						<input type="text" name="user" class="form-control">
					</div>
					<button type="submit">追加</button>
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

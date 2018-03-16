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
	$backnumber = null;
	if(isset($_POST['month'])){
		$tablename = h($_POST['month']);
		$backnumber = $pdo->query('SELECT * FROM '.$tablename);
	}
	$user = $pdo->query('SELECT * FROM user');
	$formlist = $pdo->query('SELECT * FROM backnumber');
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

    <title>ご注文は金額計算ですか？</title>

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
            <li class="nav-item active">
              <a class="nav-link" href="./account.php">金額計算 <span class="sr-only">(current)</span></a>
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

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Coffe Counter</h1>

					<form action="./backnumber.php" method="post">
						<div class="form-group">
							<select name="month">
							<?php
								while($result = $formlist->fetch(PDO::FETCH_ASSOC)){
									printf("<option value='%s'>%s</option>", $result['tabelname'], $result['tabelname']);
								}
							?>
							<input type="submit" value="過去の記録をみるぞい">
						</div>
					</form>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>名前</th>
                  <th>珈琲</th>
                  <th>ネスレのスティック</th>
                  <th>その他</th>
                  <th>コメント</th>
                </tr>
              </thead>
              <tbody>
									<?php
										$rows = $user->fetchAll();
										$i=0;
										while($row = $backnumber->fetch()){
											while(1){
												if($rows[$i]['id'] == $row['id']) break;	
												$i++;
											}
											printf("<tr><td>%s</td>\n",$rows[$i]["username"]);
											printf("<td>%d</td><td>%d</td>", $row['coffee'],$row['stick']);
											printf("<td>%d円</td><td>%s</td>", $row['extra'],$row['comment']);
											echo "<tr>";
										}
									?>
              </tbody>
            </table>
          </div>
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

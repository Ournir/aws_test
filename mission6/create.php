<?php
  // DB接続設定
	$dsn = 'mysql:dbname=tb220940db;host=localhost';
	$user = 'tb-220940';
	$password = 'mzCPxmXbCE';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

  session_start();
  
  if(empty($_SESSION['NAME'])){
    die("不正なログインです!");
  }
  
  $name = $_SESSION['NAME'];

    // テーブルの作成
	$sql = 'CREATE TABLE IF NOT EXISTS old_archery'
	        ."("
	        ."id INT AUTO_INCREMENT PRIMARY KEY,"
	        ."preOwner char(32),"
          ."status TEXT,"
          ."bikou TEXT,"
          ."date datetime DEFAULT CURRENT_TIMESTAMP"
	        .");";
  $stmt = $pdo -> query($sql);

  // 編集情報初期化
  $editNumber = "";
  $editPreOwner = "";
  $editBikou = "";

if(!empty($_POST["preOwner"]) && !empty($_POST["status"]) && !empty($_POST["bikou"]) && !empty($_POST["submit"])){
  // 編集対象番号があるか
  if(!empty($_POST["edit_flag"])){
    // 編集（更新）処理
    $edit_flag = $_POST["edit_flag"];
    $preOwner = $_POST["preOwner"];
    $status = $_POST["status"];
    $bikou = $_POST["bikou"];

    $sql = 'UPDATE old_archery SET preOwner = :preOwner,status =:status,bikou =:bikou WHERE id = :id;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':preOwner',$preOwner,PDO::PARAM_STR);
    $stmt->bindParam(':status',$status,PDO::PARAM_STR);
    $stmt->bindParam(':bikou',$bikou,PDO::PARAM_STR);
    $stmt->bindParam(':id',$edit_flag,PDO::PARAM_INT);
    $stmt->execute();
    echo "中古弓情報を編集しました！<br>";
  // 新規投稿モード
  }else{
    // 挿入処理
    $preOwner = $_POST["preOwner"];
    $status = $_POST["status"];
    $bikou = $_POST["bikou"];

    $sql = $pdo -> prepare("INSERT INTO old_archery (preOwner, status,bikou) 
            VALUES (:preOwner, :status,:bikou)");
    $sql -> bindParam(':preOwner', $preOwner, PDO::PARAM_STR);
    $sql -> bindParam(':status', $status, PDO::PARAM_STR);
    $sql -> bindParam(':bikou', $bikou, PDO::PARAM_STR);
    $sql -> execute();
    echo "中古弓情報を登録しました！<br>";
  }
// 削除モード
}elseif(!empty($_POST["deleteNum"]) && !empty($_POST["delete"])){
  $id = $_POST["deleteNum"];
  $sql = 'DELETE FROM old_archery WHERE id = :id;';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
  echo "中古弓情報を削除しました！";
// 編集モード
}elseif(!empty($_POST["editNum"]) && !empty($_POST["edit"]) ){
  $id = $_POST["editNum"];
  $sql = 'SELECT * FROM old_archery';
  $stmt = $pdo->query($sql);
  $editData = $stmt->fetchAll();
  // formのvalueに値をセット
  foreach($editData as $Data){
    if($Data['id'] == $id){
      $editNumber = $Data['id'];
      $editPreOwner = $Data['preOwner'];
      $editBikou = $Data['bikou'];
      echo "下のフォームから編集情報を書き込んでください！<br>";
      break;
      // ラジオには値をセットできないため、ここではセットしない
    }
  }
}elseif(!empty($_POST["submit"]) || !empty($_POST["delete"]) || !empty($_POST["edit"])){
  echo "入力されていない内容があります。";
}

// ログアウト処理
if(!empty($_POST["rogout"])){
  //セッション変数のクリア
  $_SESSION = array();

  //セッションクリア
  @session_destroy();

  // ステータスコードを出力
  http_response_code( 301 ) ;
  // リダイレクト
  header( "Location: ./index.php" ) ;
  exit;
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <link rel="stylesheet" href="style.css">
  <title>中古弓管理システム</title>
</head>
<body>
  <div id = "header2">
    <h1><?php echo "ようこそ！　".$name."さん"; ?></h1>
    <p></p>
    <form action="" method="post" class="msr_btn13">
      <input type="submit" name="rogout" value="ログアウト">
    </form>
  </div>
  <div class="container">
    <main class="main">
      <!-- メインコンテンツ -->
        
        <div id="wrapper">
          <div class="msr_text_03 msr_radio_03">
            <!-- 弓の登録フォーム -->
            <h3>入力フォーム</h3>
            <p class="bold">弓の登録</p>
            <form action="" method="post">
              <input type="text" name="edit_flag" value="<?php echo $editNumber; ?>">
              <label>前所有者</label><input type="text" name="preOwner" value="<?php echo $editPreOwner; ?>"><br>
              <p>状態</p>
                <input type="radio" name="status" value="新品同然" id="msr_05_radio01"><label for="msr_05_radio01">新品同然</label>
                <input type="radio" name="status" value="普通" id="msr_05_radio02"><label for="msr_05_radio02">普通</label>
                <input type="radio" name="status" value="摩耗・欠損あり" id="msr_05_radio03"><label for="msr_05_radio03">摩耗・欠損あり</label><br>
              <label>備考</label><input type="text" name="bikou" value="<?php echo $editBikou; ?>">
              <input type="submit" name="submit" value="送信">
            </form><br>
            <!-- 弓の編集フォーム -->
            <p class="bold">弓の編集</p>
            <form action="" method="post">
              <label>編集対象番号</label><input type="number" name="editNum">
              <input type="submit" name="edit" value="編集">
            </form><br>
        
            <!-- 弓の削除フォーム -->
            <p class="bold">弓の削除</p>
            <form action="" method="post">
              <label>削除対象番号</label><input type="number" name="deleteNum">
              <input type="submit" name="delete" value="削除">
            </form><br><hr>
          </div>
        </div>
        
        <div id="footer">
          <div class="table">
            <h3>中古弓リスト</h3>
            <?php
              // 弓データの取得
              $sql = 'SELECT * FROM old_archery';
              $stmt = $pdo->query($sql);
              $lists = $stmt->fetchAll();
            ?>
            <!-- 弓データをテーブル表示 -->
            <table border="1">
              <tr>
                <th>番号</th>
                <th>前所有者</th>
                <th>状態</th>
                <th>備考</th>
                <th>登録日</th>
              </tr>
              <!-- テーブルデータをPHPを使って繰り返し処理 -->
              <?php foreach($lists as $list){ 
                echo "<tr>";
                echo "<td>". $list['id'] . "</td>";
                echo "<td>". $list['preOwner'] . "</td>";
                echo "<td>". $list['status'] . "</td>";
                echo "<td>". $list['bikou'] . "</td>";
                echo "<td>". $list['date'] . "</td>";
                echo "</tr>";
              } 
              ?>
            </table><hr>
          </div>
        </div>
  </main>
  <div class="sidebar">
    <div class="sidebar__item">
      <!-- 中身 -->
      <div class="right-column">
          <img src="archery.jpg" alt="アーチェリー" height="auto" width="auto">
        </div>
    </div>
    <div class="sidebar__item sidebar__item--fixed">
      <!-- 固定・追従させたいエリア -->
    </div>
  </div>
</div>
</body>
</html>
<?php
  $pdo = null;
  $sql = null;
?>

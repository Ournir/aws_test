<?php 
  session_start();

  // DB接続設定
	$dsn = 'mysql:dbname=tb220940db;host=localhost';
	$user = 'tb-220940';
	$password = 'mzCPxmXbCE';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  // テーブルの作成
	$sql = 'CREATE TABLE IF NOT EXISTS user_archery'
  ."("
  ."id INT AUTO_INCREMENT PRIMARY KEY,"
  ."name char(32),"
  ."mailaddress varchar(256),"
  ."password char(32),"
  ."year INT"
  .");";
  $stmt = $pdo -> query($sql);

  // 新規登録処理
  if(!empty($_POST['name']) && !empty($_POST['mailaddress']) && $_POST['password'] && !empty($_POST["submit"])){
    $name = $_POST['name'];
    $mailaddress = $_POST['mailaddress'];
    $password  = $_POST['password'];
    $year = $_POST['year'];

    // メールアドレス検索
    $sql = 'SELECT * FROM user_archery';
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll();
    // データの配列からメールだけ取り出し、$mailArrayという配列に入れる
    $mailArray = array_column($result,'mailaddress');
    // すでにメールが登録されているか検索
    if(in_array($mailaddress,$mailArray)){
      echo "「そのメールアドレスはすでに登録されています。」";
    }else{
      // 挿入処理
      $sql2 = $pdo -> prepare("INSERT INTO user_archery (name, mailaddress,password,year) 
              VALUES (:name, :mailaddress,:password,:year)");
      $sql2->bindParam(':name',$name,PDO::PARAM_STR);
      $sql2->bindParam(':mailaddress',$mailaddress,PDO::PARAM_STR);
      $sql2->bindParam(':password',$password,PDO::PARAM_STR);
      $sql2->bindParam(':year',$year,PDO::PARAM_INT);
      $sql2->execute();

      // メールを送る処理
      require 'src/Exception.php';
      require 'src/PHPMailer.php';
      require 'src/SMTP.php';
      require 'setting.php';

      // PHPMailerのインスタンス生成
      $mail = new PHPMailer\PHPMailer\PHPMailer();

      $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
      $mail->SMTPAuth = true;
      $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
      $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
      $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
      $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
      $mail->Port = SMTP_PORT; // 接続するTCPポート

      // メール内容設定
      $mail->CharSet = "UTF-8";
      $mail->Encoding = "base64";
      $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
      $mail->addAddress($mailaddress, $name." 様"); //受信者（送信先）を追加する
      //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
      //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
      //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
      $mail->Subject = MAIL_SUBJECT; // メールタイトル
      $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
      $body = '登録ありがとうございます！';

      $mail->Body  = $body; // メール本文
      // メール送信の実行
      if(!$mail->send()) {
        echo 'メッセージは送られませんでした！';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo '送信完了！';
      }
      // メール送信処理終了
  
      session_regenerate_id(true); //session_idを新しく生成し、置き換える
      $_SESSION['NAME'] = $name;

      // ステータスコードを出力
      http_response_code( 301 ) ;
      // リダイレクト
      header( "Location: ./create.php" ) ;
      exit;
    }
    // ログイン処理
  }elseif(!empty($_POST['mailaddress']) && !empty($_POST['password']) && !empty($_POST["rogin"])){
    // POST通信で受け取った情報を変数に代入する
    $mailaddress = $_POST['mailaddress'];
    $password = $_POST['password'];
    // パスワードチェックのためにデータを取得
    $sql = 'SELECT * FROM user_archery';
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll();
    // データの配列からメールだけ取り出し、$mailArrayという配列に入れる
    $mailArray = array_column($result,'mailaddress');
    // 登録されていないメールアドレスを入力した場合 = $mailArrayに$mailaddressがない場合
    if(in_array($mailaddress,$mailArray,true) == false){
      echo "「メールアドレスが違います。」";
    }else{
      foreach($result as $row){
        // メールとパスワードが同じ場合
        if($row['mailaddress'] == $mailaddress && $row['password'] == $password){
          // クリエイトページに遷移

          session_regenerate_id(true); //session_idを新しく生成し、置き換える
          $_SESSION['NAME'] = $row['name'];

          // ステータスコードを出力
          http_response_code( 301 ) ;
          // リダイレクト
          header( "Location: ./create.php" ) ;
          exit ;
        // 対象のメールアドレスだがパスワードが違う場合
        }elseif($row['mailaddress'] == $mailaddress && $row['password'] != $password){
          echo "パスワードが違います。";
          break;
        }
      }
    }
  }elseif(!empty($_POST["submit"]) || !empty($_POST["rogin"])){
    echo "入力されていない情報があります。";
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>中古弓管理システム</title>
</head>
<body>
  <div id="header1">
    <h1>中古弓管理システムへようこそ！</h1>
  </div>
  
  <div class="container">
  <main class="main">
    <!-- メインコンテンツ -->
    <!-- 新規登録 -->
    <div id="wrapper">
      <div class="left-column">
    
      </div>
      <div class="msr_text_03">
        <h2>新規登録の方はこちら</h2>
    
        <form action="" method="POST">
          <p>新規登録</p><br>
          <label>名前</label><input type="text" name="name"><br>
          <label>メールアドレス</label><input type="text" name="mailaddress"><br>
          <label>パスワード</label><input type="text" name="password"><br>
          <label>あなたは何期ですか？</label><input type="number" name="year" value="47"><br>
          <input type="submit" name="submit" value="登録">
        </form><br>
        <!-- ログイン -->
        <h2>ログインする方はこちら</h2>
    
        <form action="" method="POST">
          <p>ログイン</p>
          <label>メールアドレス</label><input type="text" name="mailaddress"><br>
          <label>パスワード</label><input type="text" name="password"><br>
          <input type="submit" name="rogin" value="ログイン">
        </form>
    
      </div>
    </div>
  </main>
  <div class="sidebar">
    <div class="sidebar__item">
      <!-- 中身 -->
      <img src="archery.jpg" alt="アーチェリー" height="auto" width="auto">  
    </div>
    <div class="sidebar__item sidebar__item--fixed">
      <!-- 固定・追従させたいエリア -->
    </div>
  </div>
</div>

</body>
</html>

<?php 
  // $sql = 'SELECT * FROM user_archery';
  // $stmt = $pdo->query($sql);
  // $lines = $stmt->fetchAll();
  // foreach($lines as $row){
  //   echo $row['id'].' : ';
  //   echo $row['name'].' , ';
  //   echo $row['mailaddress'].' , ';
  //   echo $row['password'].' , ';
  //   echo $row['year']."<br>";
  // }

  $pdo = null;
  $sql = null;
  $sql2 = null;
?>

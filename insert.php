<?php
// 1. POSTデータ取得
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$facility_name = $_POST['facility_name'];

// 2. DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
}

// 3. データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_an_table3(latitude, longitude, facility_name) VALUES(:latitude, :longitude, :facility_name)");
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
$stmt->bindValue(':facility_name', $facility_name, PDO::PARAM_STR);
$status = $stmt->execute();

// 4. データ登録処理後
if ($status == false) {
    // SQL実行時エラーの場合
    $error = $stmt->errorInfo();
    exit("SQL Error: ".$error[2]);
} else {
    // 5. 成功時メッセージ
    echo "<p>データが登録されました。</p>";
    echo '<button onclick="window.location.href=\'index.php\'">入力フォームに戻る</button>';
    echo '<button onclick="window.location.href=\'select.php\'">過去取得現在地一覧を見る</button>';
}
?>

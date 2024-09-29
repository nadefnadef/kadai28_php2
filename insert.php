<?php
// 1. POSTデータ取得
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$facility_name = $_POST['facility_name'];
$destination_name = $_POST['destination_name'];
$route_screenshot = $_POST['route_screenshot'];
$duration = $_POST['duration'];

// POSTデータが存在するかチェック
if (empty($latitude) || empty($longitude) || empty($duration)) {
    exit('緯度、経度、または所要時間が入力されていません');
}

// 2. DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
}

// 3. データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_an_table3(latitude, longitude, facility_name, destination_name, route_screenshot, duration) VALUES(:latitude, :longitude, :facility_name, :destination_name, :route_screenshot, :duration)");
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
$stmt->bindValue(':facility_name', $facility_name, PDO::PARAM_STR);
$stmt->bindValue(':destination_name', $destination_name, PDO::PARAM_STR);
$stmt->bindValue(':route_screenshot', $route_screenshot, PDO::PARAM_STR); // Base64データとして保存
$stmt->bindValue(':duration', $duration, PDO::PARAM_STR);
$status = $stmt->execute();

// 4. データ登録処理後
if ($status == false) {
    $error = $stmt->errorInfo();
    exit("SQL Error: ".$error[2]);
} else {
    echo "<p>データが登録されました。</p>";
    echo '<button onclick="window.location.href=\'index.php\'">入力フォームに戻る</button>';
    echo '<button onclick="window.location.href=\'select.php\'">過去取得現在地一覧を見る</button>';
}
?>


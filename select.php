<?php
// 1. DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
}

// 2. データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_an_table3 ORDER BY id DESC");
$status = $stmt->execute();

// 3. データ表示
if ($status == false) {
    $error = $stmt->errorInfo();
    exit("SQL Error: ".$error[2]);
} else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>過去取得現在地一覧</title>
</head>
<body>
    <h1>過去取得現在地一覧</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>投稿日時</th>
            <th>緯度</th>
            <th>経度</th>
            <th>施設名</th>
        </tr>
        <?php foreach ($result as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['timestamp'] ?></td>
            <td><?= $row['latitude'] ?></td>
            <td><?= $row['longitude'] ?></td>
            <td><?= $row['facility_name'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index.php'">入力フォームに戻る</button>
</body>
</html>

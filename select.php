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
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* スクリーンショットをクリックしたら全画面表示 */
        #screenshot-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        #screenshot-modal img {
            max-width: 90%;
            max-height: 90%;
        }

        #close-modal {
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }

        /* テーブルスタイル */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        td img {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>過去取得現在地一覧</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>投稿日時</th>
            <th>緯度</th>
            <th>経度</th>
            <th>施設名</th>
            <th>目的地名</th>
            <th>目的地までの所要時間</th>
            <th>ルートスクリーンショット</th>
        </tr>
        <?php foreach ($result as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['timestamp'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['latitude'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['longitude'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['facility_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['destination_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['duration'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
            <img src="<?= htmlspecialchars($row['route_screenshot'], ENT_QUOTES, 'UTF-8') ?>" alt="Route Screenshot" style="width:100px;height:100px;" onclick="showScreenshot('<?= htmlspecialchars($row['route_screenshot'], ENT_QUOTES, 'UTF-8') ?>')">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- モーダルウィンドウでスクリーンショットを全画面表示 -->
    <div id="screenshot-modal">
        <span id="close-modal" onclick="closeModal()">×</span>
        <img id="modal-image" src="" alt="Screenshot">
    </div>

    <!-- 戻るボタン -->
    <button onclick="window.location.href='index.php'">入力フォームに戻る</button>

    <script>
        // クリックされたスクリーンショットをモーダルで全画面表示
        function showScreenshot(src) {
            document.getElementById('modal-image').src = src;
            document.getElementById('screenshot-modal').style.display = 'flex';
        }

        // モーダルを閉じる
        function closeModal() {
            document.getElementById('screenshot-modal').style.display = 'none';
        }
    </script>
</body>
</html>

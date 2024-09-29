<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>現在地情報記録アプリ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>現在地情報記録アプリ</h1>

    <button type="button" id="get-location">現在地を取得する</button>
    
    <!-- Display latitude and longitude -->
    <span id="latitude-span">緯度: <input type="text" id="latitude" name="latitude" readonly></span>
    <span id="longitude-span">経度: <input type="text" id="longitude" name="longitude" readonly></span><br><br>

    <!-- Form -->
    <form action="insert.php" method="POST">
        <label for="facility_name">施設名（自由入力）:</label>
        <input type="text" id="facility_name" name="facility_name"><br><br>

        <!-- Submit button -->
        <input type="submit" value="送信">
    </form>

    <!-- Result message area -->
    <div id="result"></div>

    <!-- Buttons to navigate -->
    <button onclick="window.location.href='index.php'">入力フォームに戻る</button>
    <button onclick="window.location.href='select.php'">過去取得現在地一覧を見る</button>

    <!-- JavaScript to get location -->
    <script>
        document.getElementById('get-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    </script>
</body>
</html>

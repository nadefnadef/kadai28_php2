<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>現在地情報記録アプリ</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOURKEY&libraries=places"></script>
</head>
<body>
    <h1>現在地情報記録アプリ</h1>

    <button type="button" id="get-location">現在地を取得する</button>
    
    <span id="latitude-span">緯度: <input type="text" id="latitude" name="latitude" readonly></span>
    <span id="longitude-span">経度: <input type="text" id="longitude" name="longitude" readonly></span><br><br>

    <!-- Form -->
    <form id="location-form" action="insert.php" method="POST" onsubmit="return saveScreenshot();">
        <input type="hidden" id="latitude-hidden" name="latitude">
        <input type="hidden" id="longitude-hidden" name="longitude">
        <input type="hidden" id="duration-hidden" name="duration">
        <input type="hidden" id="route_screenshot" name="route_screenshot">
        <label for="facility_name">施設名（自由入力）:</label>
        <input type="text" id="facility_name" name="facility_name"><br><br>

        <!-- Destination Input -->
        <label for="destination_name">目的地名（自由入力）:</label>
        <input type="text" id="destination_name" name="destination_name"><br><br>

        <button type="button" id="search-route">目的地までのルート・所要時間を検索</button><br><br>
        
        <!-- Display route and time -->
        <div id="route-info" style="display:none;">
            <p>目的地までの所要時間: <span id="duration-display"></span></p>
            <button type="button" id="save-screenshot">ルートスクリーンショットを保存する</button>
            <div id="map" style="width:100%;height:400px;"></div>
            <input type="hidden" id="route_screenshot" name="route_screenshot"> <!-- スクリーンショット用の隠しフィールド -->
        </div>

        <input type="submit" value="送信">
    </form>

    <div id="result"></div>

    <!-- Navigation buttons -->
    <button onclick="window.location.href='index.php'">入力フォームに戻る</button>
    <button onclick="window.location.href='select.php'">過去取得現在地一覧を見る</button>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>
        // 現在地の取得
        document.getElementById('get-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('latitude-hidden').value = position.coords.latitude;
                    document.getElementById('longitude-hidden').value = position.coords.longitude;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        // 目的地のルート検索
        document.getElementById('search-route').addEventListener('click', function() {
            const latitude = document.getElementById('latitude-hidden').value;
            const longitude = document.getElementById('longitude-hidden').value;
            const destination = document.getElementById('destination_name').value;
            
            if (latitude && longitude && destination) {
                const origin = new google.maps.LatLng(latitude, longitude);
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer();
                const map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    center: origin
                });
                directionsRenderer.setMap(map);

                const request = {
                    origin: origin,
                    destination: destination,
                    travelMode: 'WALKING'
                };
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsRenderer.setDirections(result);
                        const duration = result.routes[0].legs[0].duration.text;
                        document.getElementById('duration-display').innerText = duration; // 所要時間表示
                        document.getElementById('duration-hidden').value = duration; // 隠しフィールドに値を設定
                        document.getElementById('route-info').style.display = 'block';

                        // スクリーンショットの取得
                        setTimeout(function() { // 地図の読み込み完了を待つため少し遅らせる
                            html2canvas(document.getElementById('map')).then(function(canvas) {
                                const screenshotDataUrl = canvas.toDataURL();
                                document.getElementById('route_screenshot').value = screenshotDataUrl; // スクリーンショットをフォームにセット
                            });
                        }, 1000);  // 1秒遅らせる
                    } else {
                        alert("ルートが見つかりませんでした。");
                    }
                });
            } else {
                alert("現在地と目的地を入力してください。");
            }
        });

        document.getElementById('save-screenshot').addEventListener('click', function() {
            html2canvas(document.getElementById('map')).then(function(canvas) {
                canvas.toBlob(function(blob) {
                    const formData = new FormData();
                    formData.append('screenshot', blob, 'route_screenshot.png'); // 画像ファイルを追加

                    // 画像をサーバーに送信
                    fetch('save_screenshot.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            alert("スクリーンショットが保存されました。");
                        } else {
                            alert("保存に失敗しました。");
                        }
                    });
                });
            });
        });

        function saveScreenshot() {
            document.getElementById('location-form').submit(); // フォームを送信
            return false; // フォームのデフォルト送信を防ぐ
        }
    </script>
</body>
</html>


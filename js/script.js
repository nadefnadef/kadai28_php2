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

    // Google Maps APIを使ってルートと所要時間を計算し、表示する
    document.getElementById('search-route').addEventListener('click', function() {
        var destinationName = document.getElementById('destination_name').value;
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;
        
        if (!destinationName || !latitude || !longitude) {
            alert("現在地と目的地名を入力してください。");
            return;
        }
        
        var origin = new google.maps.LatLng(latitude, longitude);
        var destination = destinationName;
        
        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer();
        
        var map = new google.maps.Map(document.createElement('div'));  // 仮のdivでMapを初期化
        directionsRenderer.setMap(map);
        
        var request = {
            origin: origin,
            destination: destination,
            travelMode: google.maps.TravelMode.WALKING
        };
        
        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                directionsRenderer.setDirections(result);
                const duration = result.routes[0].legs[0].duration.text;
                document.getElementById('duration').value = duration;  // フォームにdurationをセット
                document.getElementById('route-info').style.display = 'block';
        
                // スクリーンショットの取得
                map.addListener('tilesloaded', function() {
                    html2canvas(document.getElementById('map')).then(canvas => {
                        document.getElementById('route_screenshot').value = canvas.toDataURL();
                    });
                });
            } else {
                alert("ルートが見つかりませんでした。");
            }
        });        
    
        setTimeout(function() { // 地図の読み込み完了を待つため少し遅らせる
            html2canvas(document.getElementById('map')).then(function(canvas) {
                const screenshotDataUrl = canvas.toDataURL();
                document.getElementById('route_screenshot').value = screenshotDataUrl; // スクリーンショットをフォームにセット
            });
        }, 1000);
        
    });

</script>

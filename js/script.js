function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      document.getElementById("location").value = "Geolocation is not supported by this browser.";
    }
  }
  
  function showPosition(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var location = lat + "," + lng;
  
    document.getElementById("location").value = location;
  }
  
  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        alert("ユーザーが現在地情報の取得を拒否しました。");
        break;
      case error.POSITION_UNAVAILABLE:
        alert("現在地情報が利用できません。");
        break;
      case error.TIMEOUT:
        alert("現在地情報の取得がタイムアウトしました。");
        break;
      case error.UNKNOWN_ERROR:
        alert("不明なエラーが発生しました。");
        break;
    }
  }
  
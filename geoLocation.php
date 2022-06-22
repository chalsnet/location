<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type='text/javascript' src='https://emp.iigwireless.com/wp-content/themes/iig-employee-portal/js/jquery-3.6.0.min.js?ver=6.0' id='jquery-script-js'></script>
	<title>Geolocation example - watchPosition()</title>
</head>
<body>
	<p id="demo"></p>

	<script>

	let x = document.getElementById("demo");

	function distance(lat1,lat2, lon1, lon2) {

		// The math module contains a function
		// named toRadians which converts from
		// degrees to radians.
		lon1 = lon1 * Math.PI / 180;
		lon2 = lon2 * Math.PI / 180;
		lat1 = lat1 * Math.PI / 180;
		lat2 = lat2 * Math.PI / 180;

		// Haversine formula
		let dlon = lon2 - lon1;
		let dlat = lat2 - lat1;
		let a = Math.pow(Math.sin(dlat / 2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(dlon / 2),2);

		let c = 2 * Math.asin(Math.sqrt(a));

		// Radius of earth in kilometers. Use 3958
		// for miles
		let r = 6371;

		// console.log("distance");
		// calculate the result
		return(c * r);

	}

	let totalDistance = [];
	let closestStore = "";

	function success({ coords, timestamp }) {
		totalDistance = [];
			const latitude = coords.latitude;   // 위도
			const longitude = coords.longitude; // 경도
			// alert(`위도: ${latitude}, 경도: ${longitude}, 위치 반환 시간: ${timestamp}`);
			// 출처: https://7942yongdae.tistory.com/150 [프로그래머 YD:티스토리]

			x.innerHTML = "";
			x.innerHTML += "My Location: " + latitude + ", " + timestamp + "<p>";

			$.getJSON("./iig_store_information.json", function(data){
				let store_data = data.iig_store_info;
				store_data.forEach(function(item, index){

					let getDistance = distance(latitude, item.long, longitude, item.lat);
					let distanceMeter = (getDistance.toFixed(4)) * 1000;
					totalDistance.push(distanceMeter);

					if (distanceMeter < 100) {
						closestStore = item.store_code;
						console.log(closestStore + ": " + distanceMeter);
						document.cookie = "storeID=" + closestStore;
					}

				});
			});
	}

	function getUserLocation() {
		if (!navigator.geolocation) {
			throw "location information is unavailable.";
		}
			navigator.geolocation.watchPosition(success);
	}

	getUserLocation();


	</script>

</body>
</html>
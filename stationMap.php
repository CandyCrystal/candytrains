<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";
include "./config/candyDirectory.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Norwegian Train Station Map</title>
    <meta name="description" content="Station Map - CandyTrains">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />
    <link rel="stylesheet" href="./assets/css/stationMap.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
</head>

<body>

    <div class="map" id="mapid"></div>
</body>

</html>
<script>
    var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY2FuZHljcnlzdGFsIiwiYSI6ImNrZWxhNjRuYzBrbW8ycm53d3JtbnZ1eDgifQ.1i5_ODzpFUdx2v8h3aNZ7Q';


    var openStations = L.layerGroup(),
        closedStations = L.layerGroup(),
        lines = L.layerGroup(),
        closedTracks = L.layerGroup();

    var dark = L.tileLayer(mbUrl, {
            id: 'mapbox/dark-v10',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 18,
            attribution: mbAttr
        }),
        streets = L.tileLayer(mbUrl, {
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 18,
            attribution: mbAttr
        });

    var baseLayers = {
        "Dark": dark,
        "Light": streets
    };

    var overlays = {
        "Open Stations": openStations,
        "Closed Stations": closedStations,
        "Lines": lines
    };

    var mymap = L.map('mapid', {
        layers: [dark, openStations, lines]
    }).setView([60.3824654, 8.1913225, 7], 7);

    L.control.layers(baseLayers, overlays).addTo(mymap);

    dot = L.divIcon({
        className: 'dot',
        html: "<div class='stationMarker open'>",
        iconSize: [5, 5],
        iconAnchor: [2.5, 0]
    });
    dotOld = L.divIcon({
        className: 'dotOld',
        html: "<div class='stationMarker closed'>",
        iconSize: [5, 5],
        iconAnchor: [2.5, 0]
    });
    <?php
    $all = "SELECT * FROM candyTrainsNorway.stations WHERE stationIsClosed = 1;";
    $result = $databaseConnection->query($all);
    while ($row = mysqli_fetch_array($result)) { ?>
        var name = "<?php echo $row["stationName"]; ?>"
        var lat = "<?php echo $row["stationLat"]; ?>"
        var long = "<?php echo $row["stationLong"]; ?>"
        if (lat != 0 || long != 0) {
            var sm_<?php echo $row["stationID"]; ?> = L.marker([lat, long], {
                    icon: dotOld
                }).addTo(closedStations)
                .bindPopup('<h1>' + name + '</h1>');
        }
    <?php } ?>
    <?php $all = "SELECT * FROM candyTrainsNorway.stations WHERE stationIsClosed = 0;";
    $result = $databaseConnection->query($all);
    while ($row = mysqli_fetch_array($result)) { ?>
        var code = "<?php echo $row["stationRef"]; ?>"
        var name = "<?php echo $row["stationName"]; ?>"
        var lat = "<?php echo $row["stationLat"]; ?>"
        var long = "<?php echo $row["stationLong"]; ?>"
        if (lat != 0 || long != 0) {
            var sm_<?php echo $row["stationID"]; ?> = L.marker([lat, long], {
                    icon: dot
                }).addTo(openStations)
                .bindPopup('<h1>' + name + '</h1><p><a href="./station.php?station=' + code + '">Station Page</a></p> <?php echo $row["stationID"]; ?>');
        }
    <?php } ?>
    // mymap.on('click', function(e) {
    //     alert(", lat: " + e.latlng.lat + ", long:" + e.latlng.lng)
    // });
</script>
<script src="./mapLines.js"></script>
<script>
    var polyline = L.polyline(L1, {
        color: '#ef59a1'
    }).addTo(lines);
    var polyline = L.polyline(R10, {
        color: '#9e1a2a'
    }).addTo(lines);
    var polyline = L.polyline(R11, {
        color: '#9e1a2a'
    }).addTo(lines);
    var polyline = L.polyline(L12, {
        color: '#e41e24'
    }).addTo(lines);
    var polyline = L.polyline(L13, {
        color: '#f4892c'
    }).addTo(lines);
    var polyline = L.polyline(L14, {
        color: '#fcc22a'
    }).addTo(lines);
    var polyline = L.polyline(L2, {
        color: '#55c2ef'
    }).addTo(lines);
    var polyline = L.polyline(R20, {
        color: '#843b93'
    }).addTo(lines);
    var polyline = L.polyline(L21, {
        color: '#846aae'
    }).addTo(lines);
    var polyline = L.polyline(L22, {
        color: '#1a63b0'
    }).addTo(lines);
    var polyline = L.polyline(L3, {
        color: '#84c442'
    }).addTo(lines);
    var polyline = L.polyline(R30, {
        color: '#0e7d3e',
    }).addTo(lines);
    var polyline = L.polyline(tonsbergN, {
        color: 'red'
    }).addTo(closedTracks);
    var polyline = L.polyline(holmestrand, {
        color: 'red'
    }).addTo(closedTracks);
</script>
<?php
include "./config/connect.php";
include "./config/session.php";

$train = $_GET["train"];

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "stationMap");
$navbar = $navbarClass->getNavbar();

include "./database/getStationData.php";
$stationQuery = new getStationData($databaseConnection);
$stationList = $stationQuery->getStations("");



$mapPos = $_GET["map_pos"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Map</title>
    <meta name="description" content="Station Map - CandyTrains">
    <link rel="stylesheet" href="./assets/css/stationMap.css">
    <link rel="stylesheet" href="./assets/css/trainList.css">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <link rel="stylesheet" href="./assets/css/leaflet.groupedlayercontrol.css" />
    <script src="./assets/js/leaflet.groupedlayercontrol.js"></script>
    <script type="text/javascript" src="./assets/js/Map.UrlView.js"></script>

</head>

<body id="body">
    <?php echo $navbar; ?>
    <div class="preventSpam" id="spam"></div>
    <div class="map" id="mapid"></div>
    <div class="trainInput"><input type="number" id="trainNum" placeholder="Train Number"><input type="submit" onclick="getRoute()"></div>
</body>

</html>
<script>
    function viewRoute(train) {
        $.ajax({
            url: "./database/getTrainRoute.php?type=map&train=" + train,
            type: "POST",
            success: function(msg) {
                if (msg == "this failed") {
                    alert("Invalid train number")
                } else {
                    if (document.getElementById("popup") == undefined) {
                        var thisTime = msg;
                        var json = JSON.parse(msg)
                        var line = json.line
                        // document.getElementById("routeView").innerHTML = msg;
                        var table = "<table class='routeTable'><tr><th>Arrival</th><th>Station</th><th>Departure</th></tr>"
                        var thisPopup = document.createElement("div");
                        var thisLine = [];
                        var theseStations = L.layerGroup();
                        for (var i = 0; i < json.stations.length; i++) {
                            var varname = "station_marker_" + json.stations[i].station_ref
                            console.log(varname)
                            window[varname].bringToFront()
                            thisLine.push(window[varname]._latlng)
                            L.circleMarker(window[varname]._latlng, stationDotView).bindTooltip(json.stations[i].station_name, {
                                permanent: false,
                                className: "my-label",
                                offset: [0, 0]
                            }).addTo(theseStations)
                            if (json.stations[i].arrival_time == null) {
                                var arr = ""
                            } else {
                                var arr = json.stations[i].arrival_time
                            }
                            if (json.stations[i].departure_time == null) {
                                var dep = ""
                            } else {
                                var dep = json.stations[i].departure_time
                            }
                            table += "<tr><td class='right'>" + arr + "</td><td class='center'>" + json.stations[i].station_name + "</td><td class='left'>" + dep + "</td></tr>"
                        }
                        L.polyline([thisLine]).addTo(viewLine)
                        theseStations.addTo(viewLine)
                        thisPopup.classList.add("popup")
                        thisPopup.id = "popup"
                        thisPopup.style.left = "20px"
                        thisPopup.style.top = "20px"
                        thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(' + train + ')"><div class="cross">&times;</div></div><div id="popupheader_' + train + ' line" class="popupheader ' + line + '">Train ' + train + '</div><p>' + table + '</p>'
                        document.getElementById("body").appendChild(thisPopup)
                        dragElement(document.getElementById("popup"));
                    }
                }
            }
        })
    }

    function createLineBase(stopA, stopB) {
        return L.polyline([
            stopA._latlng,
            stopB._latlng
        ], {
            color: '#aa4444',
            weight: 10,
            opacity: 1,
        })
    }

    var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY2FuZHljcnlzdGFsIiwiYSI6ImNrZWxhNjRuYzBrbW8ycm53d3JtbnZ1eDgifQ.1i5_ODzpFUdx2v8h3aNZ7Q';


    var station_markers = L.featureGroup()

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
        }),
        satelite = L.tileLayer(mbUrl, {
            id: 'mapbox/satellite-v9',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 18,
            attribution: mbAttr
        });

    var baseLayers = {
        "Dark": dark,
        "Light": streets,
        "Satelite": satelite
    };
    var active = L.layerGroup();
    var future = L.layerGroup();
    var proposed = L.layerGroup();

    var viewLine = L.layerGroup()
    var other = L.layerGroup();
    var passenger_station = L.layerGroup();
    var cargo_station = L.layerGroup();
    var block_post = L.layerGroup();
    var railyard = L.layerGroup();
    var side_track = L.layerGroup();
    var closed_station = L.layerGroup();
    var passenger_halt = L.layerGroup();
    var passing_track = L.layerGroup();

    var overlays = {
        "Stations": {
            "Passenger Station": passenger_station,
            "Passenger halt": passenger_halt,
            "Cargo Station": cargo_station,
            "Passing track": passing_track,
            "Block post": block_post,
            "Railyard": railyard,
            "Side track": side_track,
            "Closed Station": closed_station,
            "Other": other
        },
        "Lines": {
            "Active lines": active,
            "Future Lines": future,
            "Proposed Lines": proposed
        }
    };

    var mymap = L.map('mapid', {
        layers: [dark, active, passenger_station, passenger_halt, cargo_station, block_post, railyard, side_track, other, closed_station, viewLine, passing_track],
        urlView: true,
        zoomControl: false
    });

    L.control.zoom({
        position: 'bottomleft'
    }).addTo(mymap);

    if (!mymap.options.urlView || !mymap.urlView.viewLoaded()) {
        //Default view
        mymap.setView([60.3824654, 8.1913225, 7], 7);
    }

    L.control.groupedLayers(baseLayers, overlays, {
        collapsed: false
    }).addTo(mymap);

    var stationDotView = {
        color: '#000',
        fillColor: '#fff',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }

    var stationDot = {
        color: '#000',
        fillColor: '#ccc',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_0 = {
        color: '#000',
        fillColor: '#009',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_1 = {
        color: '#000',
        fillColor: '#090',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_2 = {
        color: '#000',
        fillColor: '#099',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_3 = {
        color: '#000',
        fillColor: '#900',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_4 = {
        color: '#000',
        fillColor: '#909',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_5 = {
        color: '#000',
        fillColor: '#990',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_6 = {
        color: '#000',
        fillColor: '#999',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_7 = {
        color: '#000',
        fillColor: '#040',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }
    var stationDot_8 = {
        color: '#000',
        fillColor: '#400',
        fillOpacity: 1,
        radius: 10,
        weight: 4,
        opacity: 3
    }


    <?php while ($row = mysqli_fetch_array($stationList)) { ?>
        var name = "<?php echo $row["station_name"]; ?>"
        var ref = "<?php echo $row["station_ref"]; ?>"
        var type = "<?php echo $row["station_type_id"]; ?>"
        var lat = "<?php echo $row["station_lat"]; ?>"
        var lng = "<?php echo $row["station_lng"]; ?>"
        if (lat != 0 || lng != 0) {
            var station_marker_<?php echo $row["station_ref"]; ?> = L.circleMarker([lat, lng], stationDot).bindTooltip(name, {
                permanent: false,
                className: "my-label",
                offset: [0, 0]
            });
            // type = ""+type
            switch (type) {
                case "0":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_0)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(other)
                    break;
                case "1":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_1)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(passenger_station)
                    break;
                case "2":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_2)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(cargo_station)
                    break;
                case "3":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_3)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(passing_track)
                    break;
                case "4":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_4)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(railyard)
                    break;
                case "5":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_5)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(side_track)
                    break;
                case "6":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_6)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(closed_station)
                    break;
                case "7":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_7)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(passenger_halt)
                    break;
                case "8":
                    station_marker_<?php echo $row["station_ref"]; ?>.setStyle(stationDot_8)
                    station_marker_<?php echo $row["station_ref"]; ?>.addTo(block_post)
                    break;
            }
            // station_marker_<?php echo $row["station_ref"]; ?>.addTo(passenger)

        }
    <?php } ?>
    var stretchPrepare = [];

    function loaded(geoTest) {
        var i = 0;
        geoTest.features.forEach(element => {
            if (element != null && element.properties.type != null) {
                if (element.properties.active == 2 || element.properties.active == 3) {
                    var mark = L.geoJSON(element, {
                        color: '#222',
                        weight: 3,
                        opacity: 1
                    }).addTo(future)
                } else {
                    var mark = L.geoJSON(element, {
                        color: '#000',
                        weight: 2,
                        opacity: 1
                    }).addTo(active)
                }
            }
        });
    }
</script>
<script src="./assets/js/geoData.js" onload="loaded(geoData)"></script>
<script>
    mymap.on('moveend', function() {
        console.log(mymap.getBounds());
    });

    function closePopup() {
        $('#popup').remove();
        viewLine.clearLayers()
    }

    function dragElement(elmnt) {
        var pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
            // if present, the header is where you move the DIV from:
            document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
            // otherwise, move the DIV from anywhere inside the DIV:
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    function getRoute() {
        closePopup()
        viewRoute(document.getElementById("trainNum").value)
    }
    <?php if ($train != "") {
        echo "viewRoute(" . $train . ")";
    } ?>
</script>
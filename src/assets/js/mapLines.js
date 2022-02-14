function getGeoJSONFromMarker($input) {
    var latLng = $input._latlng;
    var lat = latLng.lat;
    var lng = latLng.lng
    return "[" + lng + "," + lat + "]"
}

function createLines(colors, geoJSON, layer, lineWeight, dotSize, dotBorder) {
    var lineColors = colors;

    var outlines = L.layerGroup();
    var lineBg = L.layerGroup();
    var busLines = L.layerGroup();
    var busStops = L.layerGroup();

    var ends = [];

    function addStop(ll) {
        for (var i = 0, found = false; i < ends.length && !found; i++) {
            found = (ends[i].lat == ll.lat && ends[i].lng == ll.lng);
        }
        if (!found) {
            ends.push(ll);
        }
    }

    var lineSegment, linesOnSegment, segmentCoords, segmentWidth;
    geoJSON.features.forEach(function(lineSegment) {
        segmentCoords = L.GeoJSON.coordsToLatLngs(lineSegment.geometry.coordinates, 0);

        linesOnSegment = lineSegment.properties.lines;
        segmentWidth = linesOnSegment.length * (lineWeight + 1);

        L.polyline(segmentCoords, {
            color: '#000',
            weight: segmentWidth + 5,
            opacity: 1
        }).addTo(outlines);

        L.polyline(segmentCoords, {
            color: 'transparent',
            weight: segmentWidth + 3,
            opacity: 1
        }).addTo(lineBg);

        for (var j = 0; j < linesOnSegment.length; j++) {
            L.polyline(segmentCoords, {
                color: lineColors[linesOnSegment[j]],
                weight: lineWeight,
                opacity: 1,
                offset: j * (lineWeight + 1) - (segmentWidth / 2) + ((lineWeight + 1) / 2)
            }).addTo(busLines);
        }

        addStop(segmentCoords[0]);
        addStop(segmentCoords[segmentCoords.length - 1]);
    });

    ends.forEach(function(endCoords) {
        L.circleMarker(endCoords, {
            color: '#000',
            fillColor: '#ccc',
            fillOpacity: 1,
            radius: dotSize,
            weight: 4,
            opacity: dotBorder
        }).addTo(busStops);
    });

    outlines.addTo(layer);
    lineBg.addTo(layer);
    busLines.addTo(layer);
    busStops.addTo(layer);

}

var templateGeoJsonString = `{
    "type": "FeatureCollection",
    "features": [{
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
				${getGeoJSONFromMarker(sm_1160)},
				${getGeoJSONFromMarker(sm_1159)}
			]
		}
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [4, 5, 3, 0, 1, 2]
		},
		"geometry": {
			"type": "LineString",
			"coordinates": [
                ${getGeoJSONFromMarker(sm_1116)},
				${getGeoJSONFromMarker(sm_1159)}
            ]
        }
    }]
}`;
var templateGeoJson = JSON.parse(templateGeoJsonString)

var norwegianTramLinesGeoJsonString = `{
    "type": "FeatureCollection",
    "features": [{
            "type": "Feature",
            "properties": {
                "lines": [0],
                "name": "Bybanen i bergen"
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1335)},
                    ${getGeoJSONFromMarker(sm_1309)},
                    ${getGeoJSONFromMarker(sm_1310)},
                    ${getGeoJSONFromMarker(sm_1311)},
                    ${getGeoJSONFromMarker(sm_1312)},
                    ${getGeoJSONFromMarker(sm_1313)},
                    ${getGeoJSONFromMarker(sm_1314)},
                    ${getGeoJSONFromMarker(sm_1315)},
                    ${getGeoJSONFromMarker(sm_1316)},
                    ${getGeoJSONFromMarker(sm_1317)},
                    ${getGeoJSONFromMarker(sm_1318)},
                    ${getGeoJSONFromMarker(sm_1319)},
                    ${getGeoJSONFromMarker(sm_1320)},
                    ${getGeoJSONFromMarker(sm_1321)},
                    ${getGeoJSONFromMarker(sm_1322)},
                    ${getGeoJSONFromMarker(sm_1323)},
                    ${getGeoJSONFromMarker(sm_1324)},
                    ${getGeoJSONFromMarker(sm_1325)},
                    ${getGeoJSONFromMarker(sm_1326)},
                    ${getGeoJSONFromMarker(sm_1327)},
                    ${getGeoJSONFromMarker(sm_1328)},
                    ${getGeoJSONFromMarker(sm_1329)},
                    ${getGeoJSONFromMarker(sm_1330)},
                    ${getGeoJSONFromMarker(sm_1331)},
                    ${getGeoJSONFromMarker(sm_1332)},
                    ${getGeoJSONFromMarker(sm_1333)},
                    ${getGeoJSONFromMarker(sm_1334)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1335)},
                    ${getGeoJSONFromMarker(sm_1309)},
                    ${getGeoJSONFromMarker(sm_1310)},
                    ${getGeoJSONFromMarker(sm_1311)},
                    ${getGeoJSONFromMarker(sm_1312)},
                    ${getGeoJSONFromMarker(sm_1313)},
                    ${getGeoJSONFromMarker(sm_1314)},
                    ${getGeoJSONFromMarker(sm_1315)},
                    ${getGeoJSONFromMarker(sm_1316)},
                    ${getGeoJSONFromMarker(sm_1317)},
                    ${getGeoJSONFromMarker(sm_1318)},
                    ${getGeoJSONFromMarker(sm_1319)},
                    ${getGeoJSONFromMarker(sm_1320)},
                    ${getGeoJSONFromMarker(sm_1321)},
                    ${getGeoJSONFromMarker(sm_1322)},
                    ${getGeoJSONFromMarker(sm_1323)},
                    ${getGeoJSONFromMarker(sm_1324)},
                    ${getGeoJSONFromMarker(sm_1325)},
                    ${getGeoJSONFromMarker(sm_1326)},
                    ${getGeoJSONFromMarker(sm_1327)},
                    ${getGeoJSONFromMarker(sm_1328)},
                    ${getGeoJSONFromMarker(sm_1329)},
                    ${getGeoJSONFromMarker(sm_1330)},
                    ${getGeoJSONFromMarker(sm_1331)},
                    ${getGeoJSONFromMarker(sm_1332)},
                    ${getGeoJSONFromMarker(sm_1333)},
                    ${getGeoJSONFromMarker(sm_1334)}
                ]
            }
        }
    ]
}`
var norwegianTramLinesGeoJson = JSON.parse(norwegianTramLinesGeoJsonString)

var norwegianMetroLinesGeoJsonString = `{
    "type": "FeatureCollection",
    "features": [{
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1160)},
                    ${getGeoJSONFromMarker(sm_1159)},
                    ${getGeoJSONFromMarker(sm_1158)},
                    ${getGeoJSONFromMarker(sm_1157)},
                    ${getGeoJSONFromMarker(sm_1156)},
                    ${getGeoJSONFromMarker(sm_1155)},
                    ${getGeoJSONFromMarker(sm_1154)},
                    ${getGeoJSONFromMarker(sm_1153)},
                    ${getGeoJSONFromMarker(sm_1152)},
                    ${getGeoJSONFromMarker(sm_1151)},
                    ${getGeoJSONFromMarker(sm_1150)},
                    ${getGeoJSONFromMarker(sm_1149)},
                    ${getGeoJSONFromMarker(sm_1148)},
                    ${getGeoJSONFromMarker(sm_1147)},
                    ${getGeoJSONFromMarker(sm_1146)},
                    ${getGeoJSONFromMarker(sm_1145)},
                    ${getGeoJSONFromMarker(sm_1144)},
                    ${getGeoJSONFromMarker(sm_1143)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1116)},
                    ${getGeoJSONFromMarker(sm_1117)},
                    ${getGeoJSONFromMarker(sm_1082)},
                    ${getGeoJSONFromMarker(sm_1083)},
                    ${getGeoJSONFromMarker(sm_1084)},
                    ${getGeoJSONFromMarker(sm_1085)},
                    ${getGeoJSONFromMarker(sm_1086)},
                    ${getGeoJSONFromMarker(sm_1087)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1087)},
                    ${getGeoJSONFromMarker(sm_1088)},
                    ${getGeoJSONFromMarker(sm_1089)},
                    ${getGeoJSONFromMarker(sm_1090)},
                    ${getGeoJSONFromMarker(sm_1091)},
                    ${getGeoJSONFromMarker(sm_1118)},
                    ${getGeoJSONFromMarker(sm_1119)},
                    ${getGeoJSONFromMarker(sm_1120)},
                    ${getGeoJSONFromMarker(sm_1121)},
                    ${getGeoJSONFromMarker(sm_1122)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1174)},
                    ${getGeoJSONFromMarker(sm_1173)},
                    ${getGeoJSONFromMarker(sm_1172)},
                    ${getGeoJSONFromMarker(sm_1171)},
                    ${getGeoJSONFromMarker(sm_1170)},
                    ${getGeoJSONFromMarker(sm_1169)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1169)},
                    ${getGeoJSONFromMarker(sm_1168)},
                    ${getGeoJSONFromMarker(sm_1167)},
                    ${getGeoJSONFromMarker(sm_1114)},
                    ${getGeoJSONFromMarker(sm_1115)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1088)},
                    ${getGeoJSONFromMarker(sm_1092)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1092)},
                    ${getGeoJSONFromMarker(sm_1166)},
                    ${getGeoJSONFromMarker(sm_1165)},
                    ${getGeoJSONFromMarker(sm_1164)},
                    ${getGeoJSONFromMarker(sm_1163)},
                    ${getGeoJSONFromMarker(sm_1162)},
                    ${getGeoJSONFromMarker(sm_1161)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1101)},
                    ${getGeoJSONFromMarker(sm_1102)},
                    ${getGeoJSONFromMarker(sm_1103)},
                    ${getGeoJSONFromMarker(sm_1104)},
                    ${getGeoJSONFromMarker(sm_1105)},
                    ${getGeoJSONFromMarker(sm_1106)},
                    ${getGeoJSONFromMarker(sm_1107)},
                    ${getGeoJSONFromMarker(sm_1108)},
                    ${getGeoJSONFromMarker(sm_1109)},
                    ${getGeoJSONFromMarker(sm_1110)},
                    ${getGeoJSONFromMarker(sm_1111)},
                    ${getGeoJSONFromMarker(sm_1112)},
                    ${getGeoJSONFromMarker(sm_1113)},
                    ${getGeoJSONFromMarker(sm_1167)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1092)},
                    ${getGeoJSONFromMarker(sm_1093)},
                    ${getGeoJSONFromMarker(sm_1094)},
                    ${getGeoJSONFromMarker(sm_1095)},
                    ${getGeoJSONFromMarker(sm_1096)},
                    ${getGeoJSONFromMarker(sm_1097)},
                    ${getGeoJSONFromMarker(sm_1098)},
                    ${getGeoJSONFromMarker(sm_1099)},
                    ${getGeoJSONFromMarker(sm_1100)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1129)},
                    ${getGeoJSONFromMarker(sm_1130)},
                    ${getGeoJSONFromMarker(sm_1131)},
                    ${getGeoJSONFromMarker(sm_1132)},
                    ${getGeoJSONFromMarker(sm_1133)},
                    ${getGeoJSONFromMarker(sm_1134)},
                    ${getGeoJSONFromMarker(sm_1135)},
                    ${getGeoJSONFromMarker(sm_1136)},
                    ${getGeoJSONFromMarker(sm_1137)},
                    ${getGeoJSONFromMarker(sm_1138)},
                    ${getGeoJSONFromMarker(sm_1139)},
                    ${getGeoJSONFromMarker(sm_1140)},
                    ${getGeoJSONFromMarker(sm_1141)},
                    ${getGeoJSONFromMarker(sm_1142)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1142)},
                    ${getGeoJSONFromMarker(sm_1123)},
                    ${getGeoJSONFromMarker(sm_1124)},
                    ${getGeoJSONFromMarker(sm_1125)},
                    ${getGeoJSONFromMarker(sm_1126)},
                    ${getGeoJSONFromMarker(sm_1127)},
                    ${getGeoJSONFromMarker(sm_1128)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1176)},
                    ${getGeoJSONFromMarker(sm_1177)},
                    ${getGeoJSONFromMarker(sm_1178)},
                    ${getGeoJSONFromMarker(sm_1179)},
                    ${getGeoJSONFromMarker(sm_1180)},
                    ${getGeoJSONFromMarker(sm_1181)},
                    ${getGeoJSONFromMarker(sm_1126)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1123)},
                    ${getGeoJSONFromMarker(sm_1175)},
                    ${getGeoJSONFromMarker(sm_1182)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1182)},
                    ${getGeoJSONFromMarker(sm_1141)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1175)},
                    ${getGeoJSONFromMarker(sm_1085)}
                ]
            }
        }
    ]
}`
var norwegianMetroLinesGeoJson = JSON.parse(norwegianMetroLinesGeoJsonString)

var norwegianRailwayLinesGeoJsonString = `{
	"type": "FeatureCollection",
    "features": [{
		"type": "Feature",
        "properties": {
			"lines": [1],
			"name":"Spikkestad-Lillestrøm-Eidsvoll: Hovedbanen & Gamle Drammenbanen"
        },
        "geometry": {
			"type": "LineString",
            "coordinates": [
				// ${getGeoJSONFromMarker(sm_278)},
				// ${getGeoJSONFromMarker(sm_246)},
				// ${getGeoJSONFromMarker(sm_88)},
				// ${getGeoJSONFromMarker(sm_100)},
				// ${getGeoJSONFromMarker(sm_82)},
				// ${getGeoJSONFromMarker(sm_28)},
				// ${getGeoJSONFromMarker(sm_5)},
				// ${getGeoJSONFromMarker(sm_115)},
				// ${getGeoJSONFromMarker(sm_317)},
				// ${getGeoJSONFromMarker(sm_113)},
				// ${getGeoJSONFromMarker(sm_16)},
				// ${getGeoJSONFromMarker(sm_270)},
				// ${getGeoJSONFromMarker(sm_252)},
				// ${getGeoJSONFromMarker(sm_24)},
				// ${getGeoJSONFromMarker(sm_117)},
				// ${getGeoJSONFromMarker(sm_280)},
				// ${getGeoJSONFromMarker(sm_170)},
				// ${getGeoJSONFromMarker(sm_269)},
				// ${getGeoJSONFromMarker(sm_195)},
				// ${getGeoJSONFromMarker(sm_217)},
				// ${getGeoJSONFromMarker(sm_33)},
				// ${getGeoJSONFromMarker(sm_1)},
				// ${getGeoJSONFromMarker(sm_208)},
				// ${getGeoJSONFromMarker(sm_79)},
				// ${getGeoJSONFromMarker(sm_98)},
				// ${getGeoJSONFromMarker(sm_118)},
				// ${getGeoJSONFromMarker(sm_172)},
				// ${getGeoJSONFromMarker(sm_92)},
				// ${getGeoJSONFromMarker(sm_60)},
				// ${getGeoJSONFromMarker(sm_293)},
				// ${getGeoJSONFromMarker(sm_247)},
				// ${getGeoJSONFromMarker(sm_162)},
				// ${getGeoJSONFromMarker(sm_216)},
				// ${getGeoJSONFromMarker(sm_51)},
				// ${getGeoJSONFromMarker(sm_50)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Skien-Nordagutu-Kongeberg-drammen-asker-lysaker"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_263)},
				${getGeoJSONFromMarker(sm_200)},
				${getGeoJSONFromMarker(sm_203)},
				${getGeoJSONFromMarker(sm_135)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Skien-Nordagutu-Kongeberg-drammen-asker-lysaker"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_135)},
				${getGeoJSONFromMarker(sm_41)},
				${getGeoJSONFromMarker(sm_326)},
				${getGeoJSONFromMarker(sm_107)},
				${getGeoJSONFromMarker(sm_285)},
				${getGeoJSONFromMarker(sm_181)},
				${getGeoJSONFromMarker(sm_83)},
				${getGeoJSONFromMarker(sm_44)},
				${getGeoJSONFromMarker(sm_44)},
				${getGeoJSONFromMarker(sm_29)},
				${getGeoJSONFromMarker(sm_159)},
				${getGeoJSONFromMarker(sm_5)},
				${getGeoJSONFromMarker(sm_5)},
				${getGeoJSONFromMarker(sm_252)},
				${getGeoJSONFromMarker(sm_170)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Lillestrøm - Dal"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_162)},
				${getGeoJSONFromMarker(sm_153)},
				${getGeoJSONFromMarker(sm_65)},
				${getGeoJSONFromMarker(sm_163)},
				${getGeoJSONFromMarker(sm_131)},
				${getGeoJSONFromMarker(sm_121)},
				${getGeoJSONFromMarker(sm_204)},
				${getGeoJSONFromMarker(sm_96)},
				${getGeoJSONFromMarker(sm_39)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Skien-Drammen: Hovedbanen & Gamle Drammenbanen"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_263)},
				${getGeoJSONFromMarker(sm_221)},
				${getGeoJSONFromMarker(sm_150)},
				${getGeoJSONFromMarker(sm_249)},
				${getGeoJSONFromMarker(sm_302)},
				${getGeoJSONFromMarker(sm_290)},
				${getGeoJSONFromMarker(sm_311)},
				${getGeoJSONFromMarker(sm_267)},
				${getGeoJSONFromMarker(sm_108)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Skien-Drammen: Hovedbanen & Gamle Drammenbanen"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_108)},
				${getGeoJSONFromMarker(sm_248)},
				${getGeoJSONFromMarker(sm_44)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Oslo S-Lillestrøm"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_217)},
				${getGeoJSONFromMarker(sm_162)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Lillestrøm-Kongsvinger"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_162)},
				${getGeoJSONFromMarker(sm_308)},
				${getGeoJSONFromMarker(sm_197)},
				${getGeoJSONFromMarker(sm_58)},
				${getGeoJSONFromMarker(sm_296)},
				${getGeoJSONFromMarker(sm_297)},
				${getGeoJSONFromMarker(sm_20)},
				${getGeoJSONFromMarker(sm_241)},
				${getGeoJSONFromMarker(sm_9)},
				${getGeoJSONFromMarker(sm_85)},
				${getGeoJSONFromMarker(sm_25)},
				${getGeoJSONFromMarker(sm_339)},
				${getGeoJSONFromMarker(sm_260)},
				${getGeoJSONFromMarker(sm_136)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Gjøvikbanen"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_217)},
				${getGeoJSONFromMarker(sm_312)},
				${getGeoJSONFromMarker(sm_76)},
				${getGeoJSONFromMarker(sm_207)},
				${getGeoJSONFromMarker(sm_127)},
				${getGeoJSONFromMarker(sm_273)},
				${getGeoJSONFromMarker(sm_187)},
				${getGeoJSONFromMarker(sm_201)},
				${getGeoJSONFromMarker(sm_338)},
				${getGeoJSONFromMarker(sm_320)},
				${getGeoJSONFromMarker(sm_86)},
				${getGeoJSONFromMarker(sm_292)},
				${getGeoJSONFromMarker(sm_94)},
				${getGeoJSONFromMarker(sm_80)},
				${getGeoJSONFromMarker(sm_231)},
				${getGeoJSONFromMarker(sm_169)},
				${getGeoJSONFromMarker(sm_75)},
				${getGeoJSONFromMarker(sm_120)},
				${getGeoJSONFromMarker(sm_22)},
				${getGeoJSONFromMarker(sm_53)},
				${getGeoJSONFromMarker(sm_225)},
				${getGeoJSONFromMarker(sm_223)},
				${getGeoJSONFromMarker(sm_72)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Oslo S-Ski"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_217)},
				${getGeoJSONFromMarker(sm_205)},
				${getGeoJSONFromMarker(sm_164)},
				${getGeoJSONFromMarker(sm_99)},
				${getGeoJSONFromMarker(sm_109)},
				${getGeoJSONFromMarker(sm_236)},
				${getGeoJSONFromMarker(sm_134)},
				${getGeoJSONFromMarker(sm_275)},
				${getGeoJSONFromMarker(sm_189)},
				${getGeoJSONFromMarker(sm_77)},
				${getGeoJSONFromMarker(sm_213)},
				${getGeoJSONFromMarker(sm_327)},
				${getGeoJSONFromMarker(sm_148)},
				${getGeoJSONFromMarker(sm_262)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Ski-Rakkestad"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_262)},
				${getGeoJSONFromMarker(sm_143)},
				${getGeoJSONFromMarker(sm_268)},
				${getGeoJSONFromMarker(sm_301)},
				${getGeoJSONFromMarker(sm_133)},
				${getGeoJSONFromMarker(sm_279)},
				${getGeoJSONFromMarker(sm_6)},
				${getGeoJSONFromMarker(sm_271)},
				${getGeoJSONFromMarker(sm_190)},
				${getGeoJSONFromMarker(sm_49)},
				${getGeoJSONFromMarker(sm_102)},
				${getGeoJSONFromMarker(sm_222)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Ski-Halden"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_262)},
				${getGeoJSONFromMarker(sm_340)},
				${getGeoJSONFromMarker(sm_325)},
				${getGeoJSONFromMarker(sm_276)},
				${getGeoJSONFromMarker(sm_125)},
				${getGeoJSONFromMarker(sm_186)},
				${getGeoJSONFromMarker(sm_239)},
				${getGeoJSONFromMarker(sm_240)},
				${getGeoJSONFromMarker(sm_64)},
				${getGeoJSONFromMarker(sm_253)},
				${getGeoJSONFromMarker(sm_87)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Eidsvoll-Hamar-Dombås"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_50)},
				${getGeoJSONFromMarker(sm_299)},
				${getGeoJSONFromMarker(sm_282)},
				${getGeoJSONFromMarker(sm_91)},
				${getGeoJSONFromMarker(sm_31)},
				${getGeoJSONFromMarker(sm_183)},
				${getGeoJSONFromMarker(sm_161)},
				${getGeoJSONFromMarker(sm_112)},
				${getGeoJSONFromMarker(sm_229)},
				${getGeoJSONFromMarker(sm_332)},
				${getGeoJSONFromMarker(sm_219)},
				${getGeoJSONFromMarker(sm_43)},
				${getGeoJSONFromMarker(sm_42)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Dombås-Åndalsnes"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_42)},
				${getGeoJSONFromMarker(sm_156)},
				${getGeoJSONFromMarker(sm_157)},
				${getGeoJSONFromMarker(sm_18)},
				${getGeoJSONFromMarker(sm_337)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [1],
			"name":"Dombås-Trondheim"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_42)},
				${getGeoJSONFromMarker(sm_106)},
				${getGeoJSONFromMarker(sm_137)},
				${getGeoJSONFromMarker(sm_212)},
				${getGeoJSONFromMarker(sm_15)},
				${getGeoJSONFromMarker(sm_294)},
				${getGeoJSONFromMarker(sm_111)},
				${getGeoJSONFromMarker(sm_166)},
				${getGeoJSONFromMarker(sm_154)},
				${getGeoJSONFromMarker(sm_146)},
				${getGeoJSONFromMarker(sm_178)},
				${getGeoJSONFromMarker(sm_103)},
				${getGeoJSONFromMarker(sm_255)},
				${getGeoJSONFromMarker(sm_175)},
				${getGeoJSONFromMarker(sm_259)},
				${getGeoJSONFromMarker(sm_305)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Trondheim-Kopperå"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_305)},
				${getGeoJSONFromMarker(sm_147)},
				${getGeoJSONFromMarker(sm_160)},
				${getGeoJSONFromMarker(sm_152)},
				${getGeoJSONFromMarker(sm_155)},
				${getGeoJSONFromMarker(sm_175)},
				${getGeoJSONFromMarker(sm_155)},
				${getGeoJSONFromMarker(sm_152)},
				${getGeoJSONFromMarker(sm_237)},
				${getGeoJSONFromMarker(sm_331)},
				${getGeoJSONFromMarker(sm_110)},
				${getGeoJSONFromMarker(sm_104)},
				${getGeoJSONFromMarker(sm_101)},
				${getGeoJSONFromMarker(sm_81)},
				${getGeoJSONFromMarker(sm_179)},
				${getGeoJSONFromMarker(sm_139)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Hamar-Røros-Støren"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_91)},
				${getGeoJSONFromMarker(sm_119)},
				${getGeoJSONFromMarker(sm_173)},
				${getGeoJSONFromMarker(sm_54)},
				${getGeoJSONFromMarker(sm_228)},
				${getGeoJSONFromMarker(sm_287)},
				${getGeoJSONFromMarker(sm_214)},
				${getGeoJSONFromMarker(sm_56)},
				${getGeoJSONFromMarker(sm_281)},
				${getGeoJSONFromMarker(sm_138)},
				${getGeoJSONFromMarker(sm_7)},
				${getGeoJSONFromMarker(sm_93)},
				${getGeoJSONFromMarker(sm_11)},
				${getGeoJSONFromMarker(sm_2)},
				${getGeoJSONFromMarker(sm_10)},
				${getGeoJSONFromMarker(sm_310)},
				${getGeoJSONFromMarker(sm_300)},
				${getGeoJSONFromMarker(sm_215)},
				${getGeoJSONFromMarker(sm_244)},
				${getGeoJSONFromMarker(sm_73)},
				${getGeoJSONFromMarker(sm_227)},
				${getGeoJSONFromMarker(sm_336)},
				${getGeoJSONFromMarker(sm_90)},
				${getGeoJSONFromMarker(sm_149)},
				${getGeoJSONFromMarker(sm_256)},
				${getGeoJSONFromMarker(sm_140)},
				${getGeoJSONFromMarker(sm_233)},
				${getGeoJSONFromMarker(sm_294)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Stavanger-Nelaug-Nordagutu-Notodden"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_284)},
				${getGeoJSONFromMarker(sm_220)},
				${getGeoJSONFromMarker(sm_176)},
				${getGeoJSONFromMarker(sm_122)},
				${getGeoJSONFromMarker(sm_68)},
				${getGeoJSONFromMarker(sm_251)},
				${getGeoJSONFromMarker(sm_67)},
				${getGeoJSONFromMarker(sm_342)},
				${getGeoJSONFromMarker(sm_130)},
				${getGeoJSONFromMarker(sm_34)},
				${getGeoJSONFromMarker(sm_209)},
				${getGeoJSONFromMarker(sm_319)},
				${getGeoJSONFromMarker(sm_329)},
				${getGeoJSONFromMarker(sm_32)},
				${getGeoJSONFromMarker(sm_210)},
				${getGeoJSONFromMarker(sm_105)},
				${getGeoJSONFromMarker(sm_48)},
				${getGeoJSONFromMarker(sm_184)},
				${getGeoJSONFromMarker(sm_257)},
				${getGeoJSONFromMarker(sm_84)},
				${getGeoJSONFromMarker(sm_291)},
				${getGeoJSONFromMarker(sm_272)},
				${getGeoJSONFromMarker(sm_8)},
				${getGeoJSONFromMarker(sm_177)},
				${getGeoJSONFromMarker(sm_30)},
				${getGeoJSONFromMarker(sm_202)},
				${getGeoJSONFromMarker(sm_141)},
				${getGeoJSONFromMarker(sm_323)},
				${getGeoJSONFromMarker(sm_196)},
				${getGeoJSONFromMarker(sm_322)},
				${getGeoJSONFromMarker(sm_71)},
				${getGeoJSONFromMarker(sm_199)},
				${getGeoJSONFromMarker(sm_45)},
				${getGeoJSONFromMarker(sm_167)},
				${getGeoJSONFromMarker(sm_37)},
				${getGeoJSONFromMarker(sm_203)},
				${getGeoJSONFromMarker(sm_307)},
				${getGeoJSONFromMarker(sm_206)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Hokksund-Roa-Myrdal-Bergen"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_107)},
				${getGeoJSONFromMarker(sm_330)},
				${getGeoJSONFromMarker(sm_116)},
				${getGeoJSONFromMarker(sm_231)},
				${getGeoJSONFromMarker(sm_116)},
				${getGeoJSONFromMarker(sm_62)},
				${getGeoJSONFromMarker(sm_198)},
				${getGeoJSONFromMarker(sm_74)},
				${getGeoJSONFromMarker(sm_335)},
				${getGeoJSONFromMarker(sm_69)},
				${getGeoJSONFromMarker(sm_315)},
				${getGeoJSONFromMarker(sm_97)},
				${getGeoJSONFromMarker(sm_59)},
				${getGeoJSONFromMarker(sm_89)},
				${getGeoJSONFromMarker(sm_188)},
				${getGeoJSONFromMarker(sm_313)},
				${getGeoJSONFromMarker(sm_328)},
				${getGeoJSONFromMarker(sm_343)},
				${getGeoJSONFromMarker(sm_165)},
				${getGeoJSONFromMarker(sm_180)},
				${getGeoJSONFromMarker(sm_224)},
				${getGeoJSONFromMarker(sm_264)},
				${getGeoJSONFromMarker(sm_344)},
				${getGeoJSONFromMarker(sm_314)},
				${getGeoJSONFromMarker(sm_132)},
				${getGeoJSONFromMarker(sm_334)},
				${getGeoJSONFromMarker(sm_70)},
				${getGeoJSONFromMarker(sm_333)},
				${getGeoJSONFromMarker(sm_36)},
				${getGeoJSONFromMarker(sm_254)},
				${getGeoJSONFromMarker(sm_55)},
				${getGeoJSONFromMarker(sm_27)},
				${getGeoJSONFromMarker(sm_40)},
				${getGeoJSONFromMarker(sm_283)},
				${getGeoJSONFromMarker(sm_316)},
				${getGeoJSONFromMarker(sm_303)},
				${getGeoJSONFromMarker(sm_4)},
				${getGeoJSONFromMarker(sm_13)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Hell-Bodø"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_104)},
				${getGeoJSONFromMarker(sm_306)},
				${getGeoJSONFromMarker(sm_288)},
				${getGeoJSONFromMarker(sm_261)},
				${getGeoJSONFromMarker(sm_341)},
				${getGeoJSONFromMarker(sm_235)},
				${getGeoJSONFromMarker(sm_265)},
				${getGeoJSONFromMarker(sm_158)},
				${getGeoJSONFromMarker(sm_245)},
				${getGeoJSONFromMarker(sm_14)},
				${getGeoJSONFromMarker(sm_324)},
				${getGeoJSONFromMarker(sm_243)},
				${getGeoJSONFromMarker(sm_277)},
				${getGeoJSONFromMarker(sm_286)},
				${getGeoJSONFromMarker(sm_124)},
				${getGeoJSONFromMarker(sm_274)},
				${getGeoJSONFromMarker(sm_78)},
				${getGeoJSONFromMarker(sm_95)},
				${getGeoJSONFromMarker(sm_151)},
				${getGeoJSONFromMarker(sm_193)},
				${getGeoJSONFromMarker(sm_174)},
				${getGeoJSONFromMarker(sm_304)},
				${getGeoJSONFromMarker(sm_185)},
				${getGeoJSONFromMarker(sm_46)},
				${getGeoJSONFromMarker(sm_17)},
				${getGeoJSONFromMarker(sm_182)},
				${getGeoJSONFromMarker(sm_266)},
				${getGeoJSONFromMarker(sm_47)},
				${getGeoJSONFromMarker(sm_171)},
				${getGeoJSONFromMarker(sm_242)},
				${getGeoJSONFromMarker(sm_232)},
				${getGeoJSONFromMarker(sm_57)},
				${getGeoJSONFromMarker(sm_318)},
				${getGeoJSONFromMarker(sm_218)},
				${getGeoJSONFromMarker(sm_309)},
				${getGeoJSONFromMarker(sm_191)},
				${getGeoJSONFromMarker(sm_26)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Myrdal-Flåm"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_188)},
				${getGeoJSONFromMarker(sm_321)},
				${getGeoJSONFromMarker(sm_226)},
				${getGeoJSONFromMarker(sm_129)},
				${getGeoJSONFromMarker(sm_23)},
				${getGeoJSONFromMarker(sm_12)},
				${getGeoJSONFromMarker(sm_114)},
				${getGeoJSONFromMarker(sm_63)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Nelaug-Arendal"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_196)},
				${getGeoJSONFromMarker(sm_61)},
				${getGeoJSONFromMarker(sm_38)},
				${getGeoJSONFromMarker(sm_66)},
				${getGeoJSONFromMarker(sm_21)},
				${getGeoJSONFromMarker(sm_230)},
				${getGeoJSONFromMarker(sm_35)},
				${getGeoJSONFromMarker(sm_289)},
				${getGeoJSONFromMarker(sm_3)}
            ]
        }
	},
	{
		"type": "Feature",
		"properties": {
			"lines": [0],
			"name":"Narvik"
		},
        "geometry": {
			"type": "LineString",
            "coordinates": [
				${getGeoJSONFromMarker(sm_194)},
				${getGeoJSONFromMarker(sm_234)},
				${getGeoJSONFromMarker(sm_126)},
				${getGeoJSONFromMarker(sm_298)},
				${getGeoJSONFromMarker(sm_19)}
            ]
        }
	}
]
}`
var norwegianRailwayLinesGeoJson = JSON.parse(norwegianRailwayLinesGeoJsonString)

var OsloTbaneGeoJsonString = `{
    "type": "FeatureCollection",
    "features": [{
            "type": "Feature",
            "properties": {
                "lines": [0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1160)},
                    ${getGeoJSONFromMarker(sm_1159)},
                    ${getGeoJSONFromMarker(sm_1158)},
                    ${getGeoJSONFromMarker(sm_1157)},
                    ${getGeoJSONFromMarker(sm_1156)},
                    ${getGeoJSONFromMarker(sm_1155)},
                    ${getGeoJSONFromMarker(sm_1154)},
                    ${getGeoJSONFromMarker(sm_1153)},
                    ${getGeoJSONFromMarker(sm_1152)},
                    ${getGeoJSONFromMarker(sm_1151)},
                    ${getGeoJSONFromMarker(sm_1150)},
                    ${getGeoJSONFromMarker(sm_1149)},
                    ${getGeoJSONFromMarker(sm_1148)},
                    ${getGeoJSONFromMarker(sm_1147)},
                    ${getGeoJSONFromMarker(sm_1146)},
                    ${getGeoJSONFromMarker(sm_1145)},
                    ${getGeoJSONFromMarker(sm_1144)},
                    ${getGeoJSONFromMarker(sm_1143)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4, 5, 3, 0, 1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1116)},
                    ${getGeoJSONFromMarker(sm_1117)},
                    ${getGeoJSONFromMarker(sm_1083)},
                    ${getGeoJSONFromMarker(sm_1084)},
                    ${getGeoJSONFromMarker(sm_1085)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4, 5, 3, 0, 1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1116)},
                    ${getGeoJSONFromMarker(sm_1117)},
                    ${getGeoJSONFromMarker(sm_1083)},
                    ${getGeoJSONFromMarker(sm_1084)},
                    ${getGeoJSONFromMarker(sm_1085)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [3, 0, 1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1085)},
                    ${getGeoJSONFromMarker(sm_1086)},
                    ${getGeoJSONFromMarker(sm_1087)},
                    ${getGeoJSONFromMarker(sm_1088)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [3, 0]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1088)},
                    ${getGeoJSONFromMarker(sm_1089)},
                    ${getGeoJSONFromMarker(sm_1090)},
                    ${getGeoJSONFromMarker(sm_1091)},
                    ${getGeoJSONFromMarker(sm_1118)},
                    ${getGeoJSONFromMarker(sm_1119)},
                    ${getGeoJSONFromMarker(sm_1120)},
                    ${getGeoJSONFromMarker(sm_1121)},
                    ${getGeoJSONFromMarker(sm_1122)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1174)},
                    ${getGeoJSONFromMarker(sm_1173)},
                    ${getGeoJSONFromMarker(sm_1172)},
                    ${getGeoJSONFromMarker(sm_1171)},
                    ${getGeoJSONFromMarker(sm_1170)},
                    ${getGeoJSONFromMarker(sm_1169)},
                    ${getGeoJSONFromMarker(sm_1168)},
                    ${getGeoJSONFromMarker(sm_1167)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1167)},
                    ${getGeoJSONFromMarker(sm_1114)},
                    ${getGeoJSONFromMarker(sm_1115)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1167)},
                    ${getGeoJSONFromMarker(sm_1114)},
                    ${getGeoJSONFromMarker(sm_1115)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1, 2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1088)},
                    ${getGeoJSONFromMarker(sm_1092)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [1]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1092)},
                    ${getGeoJSONFromMarker(sm_1166)},
                    ${getGeoJSONFromMarker(sm_1165)},
                    ${getGeoJSONFromMarker(sm_1164)},
                    ${getGeoJSONFromMarker(sm_1163)},
                    ${getGeoJSONFromMarker(sm_1162)},
                    ${getGeoJSONFromMarker(sm_1161)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1101)},
                    ${getGeoJSONFromMarker(sm_1102)},
                    ${getGeoJSONFromMarker(sm_1103)},
                    ${getGeoJSONFromMarker(sm_1104)},
                    ${getGeoJSONFromMarker(sm_1105)},
                    ${getGeoJSONFromMarker(sm_1106)},
                    ${getGeoJSONFromMarker(sm_1107)},
                    ${getGeoJSONFromMarker(sm_1108)},
                    ${getGeoJSONFromMarker(sm_1109)},
                    ${getGeoJSONFromMarker(sm_1110)},
                    ${getGeoJSONFromMarker(sm_1111)},
                    ${getGeoJSONFromMarker(sm_1112)},
                    ${getGeoJSONFromMarker(sm_1113)},
                    ${getGeoJSONFromMarker(sm_1167)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [2]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1092)},
                    ${getGeoJSONFromMarker(sm_1093)},
                    ${getGeoJSONFromMarker(sm_1094)},
                    ${getGeoJSONFromMarker(sm_1095)},
                    ${getGeoJSONFromMarker(sm_1096)},
                    ${getGeoJSONFromMarker(sm_1097)},
                    ${getGeoJSONFromMarker(sm_1098)},
                    ${getGeoJSONFromMarker(sm_1099)},
                    ${getGeoJSONFromMarker(sm_1100)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4, 3]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1129)},
                    ${getGeoJSONFromMarker(sm_1130)},
                    ${getGeoJSONFromMarker(sm_1131)},
                    ${getGeoJSONFromMarker(sm_1132)},
                    ${getGeoJSONFromMarker(sm_1133)},
                    ${getGeoJSONFromMarker(sm_1134)},
                    ${getGeoJSONFromMarker(sm_1135)},
                    ${getGeoJSONFromMarker(sm_1136)},
                    ${getGeoJSONFromMarker(sm_1137)},
                    ${getGeoJSONFromMarker(sm_1138)},
                    ${getGeoJSONFromMarker(sm_1139)},
                    ${getGeoJSONFromMarker(sm_1140)},
                    ${getGeoJSONFromMarker(sm_1141)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [3]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1141)},
                    ${getGeoJSONFromMarker(sm_1142)},
                    ${getGeoJSONFromMarker(sm_1123)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4, 3]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1123)},
                    ${getGeoJSONFromMarker(sm_1124)},
                    ${getGeoJSONFromMarker(sm_1125)},
                    ${getGeoJSONFromMarker(sm_1126)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [5, 4, 3]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1126)},
                    ${getGeoJSONFromMarker(sm_1127)},
                    ${getGeoJSONFromMarker(sm_1128)},
                    ${getGeoJSONFromMarker(sm_1116)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1176)},
                    ${getGeoJSONFromMarker(sm_1177)},
                    ${getGeoJSONFromMarker(sm_1178)},
                    ${getGeoJSONFromMarker(sm_1179)},
                    ${getGeoJSONFromMarker(sm_1180)},
                    ${getGeoJSONFromMarker(sm_1181)},
                    ${getGeoJSONFromMarker(sm_1126)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1123)},
                    ${getGeoJSONFromMarker(sm_1175)},
                    ${getGeoJSONFromMarker(sm_1182)},
                    ${getGeoJSONFromMarker(sm_1141)}
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "lines": [4, 5]
            },
            "geometry": {
                "type": "LineString",
                "coordinates": [
                    ${getGeoJSONFromMarker(sm_1175)},
                    ${getGeoJSONFromMarker(sm_1085)}
                ]
            }
        }
    ]
}`;
var OsloTbaneGeoJson = JSON.parse(OsloTbaneGeoJsonString)
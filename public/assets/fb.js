var map;

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -3.121195, lng: 112.954302},
        zoom: 5
    });



var markers = [];
var getMarkerUniqueId= function(lat, lng) {
    return lat + '_' + lng;
}
function addMarker(desciprtion, location, lokasi, pelapor, warna) { // Adds a marker to the map and push to the array.

    var myvar =
        '		<div class=\'list-group\'>'+
        '			<div href=\'#\' class=\'list-group-item\'><img src=\'bom.jpg\' style=\'width: 100%\'></div>'+
        '			<div href=\'#\' class=\'list-group-item\'>'+ desciprtion +
        '				<p></p><p>'+
        '					<b>Lokasi</b> : ' + lokasi + '</p>'+
        '			</div>'+
        '			<div href=\'#\' class=\'list-group-item\'>'+
        '				<i class=\'glyphicon glyphicon-user\'></i> : '+ pelapor +
        '				<span style=\'float: right\'><i class=\'glyphicon glyphicon-calendar\'></i> : 18 Juli 2017 07:00 PM</span>'+
        '			</div>'+
        '</div>';



    var infowindow = new google.maps.InfoWindow({
        content: myvar
    });

    var markerId = getMarkerUniqueId(location.lat, location.lng); // that will be used to cache this marker in markers object.
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        animation: google.maps.Animation.DROP,
        id: markerId,
        title: desciprtion
    });
    marker.setIcon('http://maps.google.com/mapfiles/ms/icons/'+ warna +'-dot.png')
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
    markers[markerId] = marker;
}
var removeMarker = function(marker, markerId) {
    marker.setMap(null); // set markers setMap to null to remove it from map
    delete markers[markerId]; // delete marker instance from markers object
};


const config = {
    apiKey: "AIzaSyDPEz5Wm00mESD9B2IU8qZNHay73GBZ7eo",
    authDomain: "id-aman-95163.firebaseapp.com",
    databaseURL: "https://id-aman-95163.firebaseio.com",
    projectId: "id-aman-95163",
    storageBucket: "id-aman-95163.appspot.com",
    messagingSenderId: "851913006784"
};
firebase.initializeApp(config);

console.log('fireabase init');

const dbRefLaporan = firebase.database().ref().child('laporan');
const dbRefLaporanPerPolda = dbRefLaporan.child('19');

const dbRefDarurat = firebase.database().ref().child('darurat');
const dbRefDaruratPerPolda = dbRefDarurat.child('19');


dbRefLaporanPerPolda.on('child_added', snap => {
    var loc = {lat: snap.val().lat, lng:snap.val().lon};
addMarker(snap.val().description, loc, snap.val().location, 'Rifqi', 'green');
$('#events').click();
var audio = new Audio('alarm2.mp3');
audio.play();
});

dbRefDaruratPerPolda.on('child_added', snap => {
    var loc = {lat: snap.val().lat, lng:snap.val().lon};
addMarker('Darurat!', loc, snap.val().location, 'Rifqi', 'red');
var audio = new Audio('alarm.mp3');
 audio.play();
});

// dbRefLaporanPerPolda.on('child_removed', snap => {
// 	var loc = {lat: snap.val().lat, lng:snap.val().lon};
// 	addMarker(snap.val().description, loc, snap.val().location, 'Rifqi', 'red')
// 	var audio = new Audio('alarm.mp3');
// 	audio.play();
// });

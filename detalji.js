var req;
var mymap = null;
var marker = new Array();
var nazmjesto = "";
var najblizemjesto = "";
var ko1;
var ko2;
var polyline;

function loadXMLDoc(buttonid) {
	 if (window.XMLHttpRequest) { 
		req = new XMLHttpRequest(); 
	 } else if (window.ActiveXObject) { 
		req = new ActiveXObject("Microsoft.XMLHTTP");
	 }
	 if (req) { 
		 req.open("GET", "detalji.php?id=" + buttonid, true);
		 req.send(null); 
		 if (req.readyState != 4){
			document.getElementById("detaljnije").innerHTML = '<img src="wheel.gif"/>'; // readyState will be 3
		 }
		 req.onreadystatechange = refresh;
		}
}


function refresh() {
 if (req.readyState == 4) { 
	 if (req.status == 200) { // kôd statusa odgovora = 200 OK
		document.getElementById("detaljnije").innerHTML = this.responseText;
	 } else { 
		alert("Nije primljen 200 OK, nego:\n" + req.statusText);
	 }
 }
}

function karta(lat,lon){
	if (mymap == null){
		mymap = L.map('map').setView([51.505, -0.09], 13);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
		}).addTo(mymap);
	} else{
		mymap.removeLayer(marker[0]);
		mymap.removeLayer(marker[1]);
		mymap.removeLayer(polyline);
		marker.pop();
		marker.pop();
		
	}
	var markeric = new L.Marker([lat, lon], {draggable:true});
	marker.push(markeric);
    marker[0].addTo(mymap);
    marker[0].bindPopup("<b>".concat(nazmjesto.replace('_',' '),"</b><br />","Širina: ",lat,"<br />Dužina: ",lon,"<br />Izvor: ",'<br /> <a href="https://en.wikipedia.org/api/rest_v1/" target="_blank">WikiMedia REST API</a>')).openPopup();
	var koor = "[".concat(lat,",",lon,"]");
	ko1 = koor;
	mymap.setView(new L.LatLng(lat, lon), 11);
}

function karta2(lat,lon){
	var marker1 = new L.Marker([lat, lon], {draggable:true});
	marker.push(marker1);
    marker[1].addTo(mymap);
    marker[1].bindPopup("<b>".concat(nazmjesto.replace('_',' '),"</b><br />","Širina: ",lat,"<br />Dužina: ",lon,"<br />Izvor: ",'<br /> <a href="https://www.mediawiki.org/wiki/MediaWiki" target="_blank">MediaWiki Action API</a>')).openPopup();
	var koor = "[".concat(lat,",",lon,"]");
	ko2 = koor;
	mymap.setView(new L.LatLng(lat, lon), 11);
}

function povezi(){
	var koord = Array();
	koord.push(marker[0].getLatLng());
	koord.push(marker[1].getLatLng());
	polyline = L.polyline(koord, {color: 'red'}).addTo(mymap);

}

function promijenimjesto(mjesto){
	nazmjesto = String(mjesto);
}

function promijenimjesto2(mjesto){
	najblizemjesto = String(mjesto);
}


function promijeniBoju(red_tablice){
	red_tablice.style.backgroundColor = "#0c070720";
}

function vrati(red_tablice){
	red_tablice.style.backgroundColor = "#ffffff";
}

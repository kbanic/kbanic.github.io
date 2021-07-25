<?php
function build_query($r){
	$u = 'ABCDEFGHIJKLMNOPQRSTUVWXYZČĆĐŠŽ';
	$l = mb_strtolower($u, 'UTF-8');
	$conditions = array();

	// all input to lowercase
	array_walk_recursive($r, function(&$item, $key) {
		    $item = mb_strtolower($item, 'UTF-8');
	});

	if (!empty($r['spol'])){
		$conditions[] = "@spol='{$r['spol']}'";
	}

	if (!empty($r['ime'])) {
		//$conditions[] = "ime[contains(text(), '{$r['ime']}')]";
		$conditions[] = "ime[contains(translate(text(), '{$u}', '{$l}'), '{$r['ime']}')]";
	}

	if (!empty($r['prezime'])) {
		$conditions[] = "prezime[contains(translate(text(), '{$u}', '{$l}'), '{$r['prezime']}')]";
	}

	if (!empty($r['pbrmjestorod']) || !empty($r['mjestorod'])) {
		$sub_cond = array();

		if (!empty($r['pbrmjestorod'])){
			$sub_cond[] = "contains(translate(@pbr, '{$u}', '{$l}'), '{$r['pbrmjestorod']}')";
		} 
		if (!empty($r['mjestorod'])){
			$sub_cond[] = "contains(translate(text(), '{$u}', '{$l}'), '{$r['mjestorod']}')";
		} 

		$merge = implode(' and ', $sub_cond);
		$conditions[] = "mjestoRod[{$merge}]";
	}

	if (!empty($r['zanimanja'])){
		$sub_cond = array();
		foreach($r['zanimanja'] as $zanimanje){
			$sub_cond[] = "contains(translate(text(), '{$u}', '{$l}'), '{$zanimanje}')";
		}

		$merge = implode(' or ', $sub_cond);
		$conditions[] = "ostalaZanimanja[zanimanje[{$merge}]]";
	}

	if (!empty($r['boraviste'])){
		$sub_cond = array();	
		foreach($r['boraviste'] as $boraviste) {
			$sub_cond[] = "contains(translate(text(), '{$u}', '{$l}'), '{$boraviste}')";
		}
		$merge = implode(' or ', $sub_cond);
		$conditions[] = "zemljeBoravista[boraviste[{$merge}]]";
	}

	if (!empty($r['mjestoobraz']) || !empty($r['obrazovanje'])){
		$sub_cond = array();
			
		if (!empty($r['mjestoobraz'])){
			$sub_cond[] = "contains(translate(@mjesto, '{$u}', '{$l}'), '{$r['mjestoobraz']}')";
		}
		if (!empty($r['obrazovanje'])){
			$sub_cond[] = "contains(translate(text(), '{$u}', '{$l}'), '{$r['obrazovanje']}')";
		}

		$merge = implode(' or ', $sub_cond);
		$conditions[] = "obrazovanje[institucija[{$merge}]]";
	}

	if (!empty($r['godnagrada']) || !empty($r['nagrada'])){	
		$sub_cond = array();
		
		if (!empty($r['godnagrada'])){
			$sub_cond[] = "contains(@godina, '{$r['godnagrada']}')";
		}
		if (!empty($r['nagrada'])){
			$sub_cond[] = "contains(translate(text(), '{$u}', '{$l}'), '{$r['nagrada']}')";
		}

		$merge = implode(' or ', $sub_cond);
		$conditions[] = "nagrade[nagrada[{$merge}]]";
	}

	if (!empty($r['imeskulptura'])){
		$conditions[] = "stvaralastvo[kip[nazivKip[contains(translate(text(), '{$u}', '{$l}'), '{$r['imeskulptura']}')]]]";
	}

	return '//kipar[' . implode(' and ', $conditions) . ']';
}

function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function wikiphoto($wikiTitle){
	$url = "http://hr.wikipedia.org/api/rest_v1/page/summary/" . $wikiTitle;
	$json = file_get_contents($url);
	$data = json_decode($json,true);
	$slika = array();
	if(isset($data['originalimage']['source'])){
		$slika[0] = $data['originalimage']['source'];
	}
	else{
		$slika[0] = null;
	}
	if(isset($data['extract'])){
		$slika[1] = substr($data['extract'],0,167);
	}
	else{
		$slika[1] = null;
	}
	return $slika;
}

function wikikoor($mjesto){
	$url = "http://en.wikipedia.org/api/rest_v1/page/summary/" . $mjesto;
	$json = file_get_contents($url);
	$data = json_decode($json,true);
	if( (isset($data['coordinates']['lat'])) && isset($data['coordinates']['lon'])){
		$lat = $data['coordinates']['lat'];
		$lon = $data['coordinates']['lon'];
	}
	else{
		$lat = null;
		$lon = null;
	}
	return $lat . "," . $lon;
}


function nominatim($mjesto){
	$url = myUrlEncode("http://nominatim.openstreetmap.org/search?q=" . $mjesto . "&format=xml&limit=1");
	
    $USERAGENT = $_SERVER['HTTP_USER_AGENT'];

    $opts = array('http'=>array('header'=>"User-Agent: $USERAGENT\r\n"));
    $context = stream_context_create($opts);
    $jsonfile = file_get_contents($url, false, $context);
	$xml = simplexml_load_string($jsonfile);
	$sirina = $xml->place['lat'];
	$duzina = $xml->place['lon'];
	$value = $sirina . "," . $duzina;
	return $value;
}

function wikimedia($wikiTitle){
	$url = myUrlEncode("https://hr.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=" . $wikiTitle . "&format=xml");
	$xml = simplexml_load_file($url);
	$podacireturn = [];
	$pageid = $xml->query[0]->pages[0]->page[0]['pageid'];
	$url = myUrlEncode("https://hr.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=" . $wikiTitle . "&format=json");
	$json = file_get_contents($url);
	$data = json_decode($json,true);
	if(isset($data['query']['pages'][(String)$pageid]['revisions']['0']['*'])){
		$podaci = substr($data['query']['pages'][(String)$pageid]['revisions']['0']['*'],0,350);
		$pattern = "/rođenje(\s)*= .*\\n\|(\s)*smrt/";
		$matches = preg_match_all($pattern, $podaci, $regexArray);
		for($i = 0; $i <$matches; $i++){
		$newPattern = "/\[\[(.)*?\]\]/";
		$newMatches = preg_match_all($newPattern, $regexArray[0][$i], $newRegexArray);
		for($j = 0; $j <$newMatches; $j++){
			$newRegexArray[0][$j] = preg_replace("/\[\[/", "", $newRegexArray[0][$j]);
			$newRegexArray[0][$j] = preg_replace("/\]\]/", "", $newRegexArray[0][$j]);
			if(preg_match("/\./", $newRegexArray[0][$j]) == 0){
				$result = $newRegexArray[0][$j];
				break;
			}
		}
		array_push($podacireturn, $result);
		array_push($podacireturn, nominatim($result));
	}
	}
	else{
		array_push($podacireturn,'Ne postoji zapis.');
		array_push($podacireturn,'Ne postoje koordinate.');
	}
	
	return $podacireturn;
}



function corona(){

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.covid19api.com/summary",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$data = json_decode($response,true);
	$podaci = array();
	if(isset($data)){
		array_push($podaci, $data['Countries']['41']['NewConfirmed']);
		array_push($podaci, $data['Countries']['41']['TotalConfirmed']);
		array_push($podaci, $data['Countries']['41']['NewDeaths']);
		array_push($podaci, $data['Countries']['41']['TotalDeaths']);
		array_push($podaci, $data['Countries']['41']['NewRecovered']);
		array_push($podaci, $data['Countries']['41']['TotalRecovered']);
		array_push($podaci, $data['Countries']['41']['Date']); 
	}
	return $podaci;
}
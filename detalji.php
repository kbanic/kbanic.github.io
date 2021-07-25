<?php
include('funkcije.php');

$dom = new DOMDocument();
$dom->load("podaci.xml");

$xpath = new DOMXpath($dom);

$id = ($_REQUEST["id"]);

$node = $xpath->query("//*[@id='$id'][1]")->item(0);

sleep(1);

$doc = new DOMDocument('1.0');

$table = $doc->createElement('table');
$doc->appendChild($table);
$tbody = $doc->createElement('tbody');
$table->appendChild($tbody);


$ime = $node->getElementsByTagName('wikiTitle')->item(0)->nodeValue;
$ime = str_replace("_", " ", $ime);

$tr = $doc->createElement('h4', $ime);
$tbody->appendChild($tr); 


$tr = $doc->createElement('p');
$tbody->appendChild($tr);

// ZANIMANJA

$djeca = $node->getElementsByTagName('zanimanje');
 
$tr = $doc->createElement('tr',"• ostala zanimanja:");
$tbody->appendChild($tr);

$nb = $djeca->length;
$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);

if ($nb == 0){
	$td = $doc->createElement('li', "nema");
	$ul->appendChild($td);
}else{
	$arr = array();
	$str = "";
	foreach($djeca as $zanimanje){
		array_push($arr, $zanimanje->nodeValue);
	}
	foreach($arr as $item){
		$str = $str . ", " .  $item;
	}
	$li = $doc->createElement('li', substr($str,1));
	$ul->appendChild($li);
}

// NAGRADE 

$djeca = $node->getElementsByTagName('nagrada');
 
$tr = $doc->createElement('tr',"• nagrade:");
$tbody->appendChild($tr);

$nb = $djeca->length;
$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);

if ($nb == 0){
	$td = $doc->createElement('li', 'nema');
	$tbody->appendChild($td);
}else{

	foreach($djeca as $nagrada){
	$li = $doc->createElement('li', $nagrada->nodeValue . ", " . $nagrada->getAttribute('godina') . ".");
	$ul->appendChild($li);
	}
}

// KIPOVI

$djeca = $node->getElementsByTagName('kip')->item(0);
 
$tr = $doc->createElement('tr',"• najpoznatiji kip:");
$tbody->appendChild($tr);

$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);


$arr = array();
$str = "";
$djeca = $node->getElementsByTagName('nazivKip')->item(0);
$str = $str . $djeca->nodeValue;
$djeca = $node->getElementsByTagName('mjestoKip')->item(0);
$str = $str . " - " . $djeca->nodeValue;
$djeca = $node->getElementsByTagName('drzavaKip')->item(0);
$str = $str . ", " . $djeca->nodeValue;

$li = $doc->createElement('li', $str);
$ul->appendChild($li);


// KOORDINATE

/* $djeca = $node->getElementsByTagName('koordinate')->item(0);
 
$tr = $doc->createElement('tr',"najpoznatiji kip:");
$tbody->appendChild($tr);

$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);


$arr = array();
$str = "";
$djeca = $node->getElementsByTagName('nazivKip')->item(0);
$str = $str . $djeca->nodeValue;
$djeca = $node->getElementsByTagName('mjestoKip')->item(0);
$str = $str . " - " . $djeca->nodeValue;
$djeca = $node->getElementsByTagName('drzavaKip')->item(0);
$str = $str . ", " . $djeca->nodeValue;

$li = $doc->createElement('li', $str);
$ul->appendChild($li); */



$djeca = $node->getElementsByTagName('institucija');
 
$tr = $doc->createElement('tr',"• obrazovanje:");
$tbody->appendChild($tr);

$nb = $djeca->length;
$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);

if ($nb == 0){
	$td = $doc->createElement('li', 'nema');
	$tbody->appendChild($td);
}else{
	foreach($djeca as $obrazovanje){
	$li = $doc->createElement('li', $obrazovanje->nodeValue . ", " . $obrazovanje->getAttribute('mjesto') );
	$ul->appendChild($li);
	}
}

 $djeca = $node->getElementsByTagName('boraviste');
 
$tr = $doc->createElement('tr',"• zemlje prebivanja:");
$tbody->appendChild($tr);

$nb = $djeca->length;
$td = $doc->createElement('tr');
$tbody->appendChild($td);
$ul = $doc->createElement('ul');
$td->appendChild($ul);

if ($nb == 0){
	$td = $doc->createElement('li', 'nema');
	$tbody->appendChild($td);
}else{
	$arr = array();
	$str = "";
	foreach($djeca as $boraviste){
		array_push($arr, $boraviste->nodeValue);
	}
	foreach($arr as $item){
		$str = $str . ", " .  $item;
	}
	$li = $doc->createElement('li', substr($str,1));
	$ul->appendChild($li);
} 


echo "<h3>DETALJNIJI PODACI</h3>" . "<p/>" . ($doc->saveHTML());


?>
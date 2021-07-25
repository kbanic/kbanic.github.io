<?php
include('funkcije.php');

$dom = new DOMDocument();
$dom->load("podaci.xml");

$xpath = new DOMXpath($dom);

$q = build_query($_REQUEST);

if($q != '//kipar[]'){ 
	$nodes = $xpath->query($q);
} else{
	$nodes = $xpath->query('//kipar'); //if query empty list all
}
$s = "wiki";

if ($nodes->length > 0){
	foreach($nodes as $node){
		$data = wikiphoto($node->getElementsByTagName('wikiTitle')->item(0)->nodeValue);
		$node->getElementsByTagName('slika')->item(0)->nodeValue = $data[0];
		$node->getElementsByTagName('sazetak')->item(0)->nodeValue = $data[1];
		$wiki = wikimedia($node->getElementsByTagName('wikiTitle')->item(0)->nodeValue);
		$node->getElementsByTagName('mjesto')->item(0)->nodeValue = $wiki[0];
		$node->getElementsByTagName('koordinate')->item(0)->nodeValue = $wiki[1];
		$najblizekoor = wikikoor($node->getElementsByTagName('najblizeMjesto')->item(0)->nodeValue);
		$node->getElementsByTagName('najblizeKoor')->item(0)->nodeValue = $najblizekoor;
	}	
} 


?>


<html>
            <head>
                <title>Podaci</title>
                <link rel="stylesheet" type="text/css" href="dizajn.css"/>
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />
                <meta name="author" content="Klara Banić"/>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
                <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
				<script src="detalji.js"></script>
            </head>
            
            <body>
		    <div class="wrapper">
				<header>
				  <a href="index.html">
				  <div>
				  <h1 class="centered_header_text">NAJPOZNATIJI HRVATSKI KIPARI</h1>
				  </div>
				  <div class="naslovna">
				  <img src="header.jpg" alt="djelo kipara" />
				  </div>
				  </a>
				</header>

				<div class="content">
					<nav>
					<h3>NAVIGACIJA</h3>
					<p></p>
					<ul>
					  <li><a href="index.html">Početna stranica</a></li>
					  <li><a href="obrazac.html">Pretraga</a></li>
					  <li><a href="http://www.fer.unizg.hr/predmet/or">Otvoreno računarstvo</a></li>
					  <li><a href="http://www.fer.unizg.hr" target="_blank">FER</a></li>
					  <li><a href="podaci.xml">Podaci</a></li>
					  <li><a href="mailto:klara.banic@fer.hr">E-mail</a></li>
					</ul>
					<p/>
					<p/>
					<p/>
					<div id="detaljnije"></div>
					</nav>
				<main>
				<info>
				<div class="corona">
					<h4> Koronavirus u Hrvatskoj </h4>
					<table>
					<tr>
					<td>Novi slučajevi: 
					<?php 
					$data = corona();
					if (isset($data[0])){
						echo $data[0];
					}else{
						echo "-";
					}?>			
					</td>
					&#160; &#160; &#160; &#160; &#160; &#160; &#160;
					<td>&#160;&#160;&#160;&#160;Novi preminuli:
					<?php 
					if (isset($data[2])){
						echo $data[2];
					}else{
						echo "-";
					}	?>
					</td>
					&#160; &#160; &#160; &#160; &#160; &#160; &#160;
					<td>&#160;&#160;&#160;&#160; Novi oporavljeni:
					<?php 
					if (isset($data[4])){
						echo $data[4];
					}else{
						echo "-";
					}	?>
					</td>
					</tr>
					<tr>
					 <td> Ukupno slučajeva: 
					<?php 
					if (isset($data[1])){
						echo $data[1];
					}else{
						echo "-";
					}	?>
					 </td>
					&#160; &#160; &#160;&#160;
					<td> &#160;&#160;&#160;&#160;Ukupno preminulih: 
					<?php 
					if (isset($data[3])){
						echo $data[3];
					}else{
						echo "-";
					}	?>
					</td>
					&#160; &#160; &#160;&#160;
					<td>&#160;&#160;&#160;&#160; Ukupno oporavljenih: 
					<?php 
					if (isset($data[5])){
						echo $data[5];
					}else{
						echo "-";
					}	?>
					</td>
					</tr>
					</table>
					<p/>
					<h6> IZVOR: <a style="color:#505050;" href="https://covid19api.com">COVID19 API</a> </h6>
					<h6> (AŽURIRANO: 
					<?php 
					if (isset($data[6])){
						echo date("d/m/Y H:i",strtotime($data[6])); 
					}else{
						echo "neuspjelo ažuriranje";
					}?>	
					) </h6>
				</div>
				</info>
				<p/>
				<div class = "tablica">
				<table class="minimalistBlack" style="float:left;margin-bottom:30px;">
				  <thead>
					<tr>
					  <th scope="col">IME I PREZIME</th>
					  <th scope="col">&#160;&#160;&#160;SLIKA&#160;&#160;&#160;</th>
					  <th scope="col">DATUM ROĐENJA</th>
					  <th scope="col">MJESTO ROĐENJA</th>
					  <th scope="col">‎‎‏‏‎&#160;&#160;&#160;&#160;&#160;AKCIJA‎‎‏‏‎&#160;&#160;&#160;&#160;&#160;</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if ($nodes->length > 0){
							foreach($nodes as $node){
					?>
					<tr onmouseover="promijeniBoju(this)" onmouseout="vrati(this)">
					  <td>
					  <?php 
					echo $node->getElementsByTagName("ime")->item(0)->nodeValue . " ";
					echo $node->getElementsByTagName("prezime")->item(0)->nodeValue;
					  ?>
					</td>
					  <td><img src="<?php echo $node->getElementsByTagName('slika')->item(0)->nodeValue; ?>" style="max-width:60%;"></img></td>
					  <td>
					  <?php 
					echo $node->getElementsByTagName("datRod")->item(0)->nodeValue;
  					?>
					</td>
					  <td>
					  <?php
					  echo $node->getElementsByTagName("mjestoRod")->item(0)->nodeValue;
					  ?>
					  </td>
					  <td><button id="<?php
					  echo $node->getAttribute("id");?> "
					  type="button" onclick="loadXMLDoc(this.id);
					  promijenimjesto('<?php echo $node->getElementsByTagName('najblizeMjesto')->item(0)->nodeValue?>');
					  karta(<?php echo $node->getElementsByTagName('najblizeKoor')->item(0)->nodeValue?>);
					  promijenimjesto('<?php echo $node->getElementsByTagName('mjestoRod')->item(0)->nodeValue?>');
					  karta2(<?php echo $node->getElementsByTagName('koordinate')->item(0)->nodeValue?>);
					  povezi();
					  "> Više o ... </button></td>
					</tr>
					<?php }} ?>
					
				  </tbody>
				  <p></p>
				</table>
				<div id="map" style="height:320px; width:28%;"></div>
				</div>
				</main>
				</div>
				
			</div>
			<footer>
				<p>Izradila: Klara Banić • Kontakt:<a href="mailto:klara.banic@fer.hr">
				klara.banic@fer.hr</a></p>
			</footer>

    </body>
</html>

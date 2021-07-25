<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"> 
<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>Podaci</title>
				<link rel="stylesheet" type="text/css" href="dizajn.css"/>
				<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />
				<meta name="author" content="Klara Banić"/>
				<script language="javascript" src="detalji.js"/>
				<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
				<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
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
					<td>Novi slučajevi: <xsl:value-of select="container/info/novislucajevi"/>   </td>
					&#160; &#160; &#160; &#160; &#160; &#160; 
					<td>Novi preminuli: <xsl:value-of select="container/info/novipreminuli"/>  </td>
					&#160; &#160; &#160; &#160; &#160; &#160; 
					<td> Novi oporavljeni: <xsl:value-of select="container/info/novioporavljeni"/> </td>
					</tr>
					<tr>
					 <td> Ukupno slučajeva: <xsl:value-of select="container/info/ukupnoslucajevi"/> </td>
					&#160; &#160; &#160;
					<td> Ukupno preminulih: <xsl:value-of select="container/info/ukupnopreminuli"/> </td>
					&#160; &#160; &#160;
					<td> Ukupno oporavljenih: <xsl:value-of select="container/info/ukupnooporavljeni"/> </td>
					</tr>
					</table>
					<p/>
					<h6> IZVOR: <a style="color:#505050;" href="https://covid19api.com">COVID19 API</a> </h6>
					<h6> (AŽURIRANO: <xsl:value-of select="container/info/datum"/> ) </h6>
				</div>
				</info>
				<p/>
				<div class = "tablica">
				<table class="minimalistBlack">
				  <thead>
					<tr>
					  <th scope="col">IME I PREZIME</th>
					  <th scope="col">SLIKA</th>
					  <th scope="col">DATUM ROĐENJA</th>
					  <th scope="col">MJESTO ROĐENJA</th>
					  <th scope="col">‎‎‏‏‎&#160;&#160;&#160;&#160;&#160;AKCIJA‎‎‏‏‎&#160;&#160;&#160;&#160;&#160;</th>
					</tr>
				  </thead>
				  <tbody>
				  <xsl:for-each select="container/podaci/kipar">
					<tr onmouseover="promijeniBoju(this)" onmouseout="vrati(this)">
					  <td><xsl:value-of select="ime"/><xsl:value-of select="concat(' ',prezime)"/></td>
					  <td><img src="{slika}" style="max-width: 40%;"></img></td>
					  <td><xsl:value-of select="datRod"/></td>
					  <td><xsl:value-of select="mjestoRod"/></td>
					  <xsl:variable name="koord" select="koordinate"/>
					  <td><button id="{@id}" type="button" onclick="loadXMLDoc(this.id); karta('{$koord}');"> Više o ... </button></td>
					</tr>
				   </xsl:for-each>
				  </tbody>
				</table>
				<div id="map"></div>
				</div>
				<p/>
				</main>
				</div>
			</div>

			<footer>
				<p>Izradila: Klara Banić • Kontakt:<a href="mailto:klara.banic@fer.hr">
				klara.banic@fer.hr</a></p>
			</footer>

			</body>

		</html>

	</xsl:template>
</xsl:stylesheet>
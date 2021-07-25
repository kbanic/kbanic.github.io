<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"> 

<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>Podaci</title>
				<link rel="stylesheet" type="text/css" href="dizajn.css"/>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="author" content="Klara Banić"/>
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
					</nav>


				<main>
				<table class="minimalistBlack">
				  <thead>
					<tr>
					  <th scope="col">IME</th>
					  <th scope="col">PREZIME</th>
					  <th scope="col">DATUM ROĐENJA</th>
					  <th scope="col">MJESTO ROĐENJA</th>
					  <th scope="col">POŠTANSKI BROJ MJESTA ROĐENJA</th>
					  <th scope="col">OBRAZOVANJE</th>
					  <th scope="col">ZEMLJE BORAVIŠTA</th>
					  <th scope="col">NAJZNAČAJNIJA NAGRADA</th>
					  <th scope="col">NAJPOZNATIJI KIP</th>
					</tr>
				  </thead>
				  <tbody>
				  <xsl:for-each select="podaci/kipar">
					<tr>
					  <td><xsl:value-of select="ime"/></td>
					  <td><xsl:value-of select="prezime"/></td>
					  <td><xsl:value-of select="datRod"/></td>
					  <td><xsl:value-of select="mjestoRod"/></td>
					  <td><xsl:value-of select="mjestoRod/@pbr"/></td>
					  <td>
					  <xsl:for-each select="obrazovanje/institucija">					  
						• <xsl:value-of select="."/>, <xsl:value-of select="@mjesto"/>
						<xsl:if test="not(position() = last())">
							<p/>
						</xsl:if>
					  </xsl:for-each>
					  </td>
					  <td>
					  <xsl:for-each select="zemljeBoravista/boraviste">
						<xsl:value-of select="."/>
						<xsl:if test="not(position() = last())">, 
						</xsl:if>
					  </xsl:for-each>
					  </td>
					  <td><xsl:value-of select="nagrade/nagrada[1]"/>, <xsl:value-of select="nagrade/nagrada/@godina"/>.</td>
					  <td><xsl:value-of select="stvaralastvo/kip/nazivKip[1]"/>, <xsl:value-of select="stvaralastvo/kip/mjestoKip[1]"/>, <xsl:value-of select="stvaralastvo/kip/drzavaKip[1]"/></td>
					</tr>
				   </xsl:for-each>
				  </tbody>
				</table>
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
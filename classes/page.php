<?php

/**
 * Met deze class kan een pagina volledig gebouwt worden.
 * De namen van de functies spreken voor zichzelf.
 * En hun doel ook.
 *
 * @author Hans-Jurgen Bakkenes
 */
class pagina
{

	/**
	 * Hieronder staan de properties van de class gedefinieërd.
	 * Properties zijn variabelen die in de class/via de class gebruikt kunnen worden.
	 * 
	 * Voor de properties staat een beveiligingsniveau.
	 * Deze kan bestaan uit: private, protected en public.
	 * ALLE properties met bovenstaande beveiligingsniveau's, kunnen binnen dezelfde class gebruikt worden.
	 * 
	 * Public: Properties die beveiligingsniveau public hebben kunnen worden aangeroepen buiten de class
	 * door bijvoorbeeld te typen: $pagina = new pagina(); $pagina->titel;
	 * Hier werd de propertie titel aangeroepen nadat de class geinstantieërd werd.
	 * 
	 * Protected: Properties die beveiligingsniveau protected hebben kunnen alleen door andere classes gebruikt worden.
	 * Hier is in deze applicatie geen sprake van.
	 * 
	 * Private: Properties die beveiligingsniveau private hebben kunnen alleen binnen dezelfde class gebruikt worden.
	 */
	private static $instantie;
	private $csspad;
	public $titel;
	public $meta_beschrijving;
	public $meta_sleutelwoorden;
	public $javascript;
	public $css;

	/**
	 * Deze functie word aangeroepen als de class word geinstantieerd.
	 */
	public function __construct()
	{
		
	}

	/**
	 * Geeft singleton terug van de class. 
	 * Met de singleton word gecontroleerd of een class reeds geinstantieerd is.
	 * 
	 * Het instantièren van een class is het plaatsen van een class in een variabele.
	 * Deze variabele word dan een object van class ... genoemd.
	 * 
	 * Met behulp van -> kunnen functies uit de class worden aangeroepen.
	 * @return pagina
	 */
	public static function getInstantie()
	{
		if (!self::$instantie) {
			self::$instantie = new self();
		}
		return self::$instantie;
	}

	/**
	 * Deze functie maakt voor jullie automatisch de javascript include op de pagina.
	 * De minimaal vereiste variabele is de source.
	 * Plaats javascript bestanden altijd in de daarvoor bestemde map.
	 * @param string $src
	 * @param string $type
	 */
	public function setJavascript($src, $type = "text/javascript")
	{
		$this->javascript .= "<script type=\"" . $type . "\" src=\"" . config::$jspad . $src . "\"></script>";
	}

	/**
	 * Deze functie maakt het mogelijk dat een stuk eigen geschreven javascript code op de pagina kan worden geplaatst.
	 * Evenals de voorgaande javascript functie word type automatisch ingevuld als je deze niet aangeeft.
	 * Zorg er voor dat dubbele quotes ge-escaped worden of dat er alleen enkele quotes worden gebruikt.
	 * @param string $code
	 * @param string $type 
	 */
	public function setJavascriptCode($code, $type = "text/javascript")
	{
		$this->javascript .= "<script type=\"" . $type . "\">" . $code . "</script>";
	}

	/**
	 * Deze functie maakt voor jullie automatisch de css include op de pagina.
	 * De minimaal vereiste variabele is de source.
	 * Plaats css bestanden altijd in de daarvoor bestemde map.
	 * 
	 * Hoe werkt het:
	 * 
	 * 
	 * @param string $src
	 * @param string $type
	 * @param string $rel
	 */
	public function setCss($src, $type = "text/css", $rel = "stylesheet")
	{
		if(isset($_GET['css']) == 2){
			$src = "style2.css";
		}
		$this->css .= "<link rel=\"" . $rel . "\" href=\"" . config::$csspad . $src . "\" type=\"" . $type . "\" />";
		$this->css .= "<!--[if IE]><link rel=\"stylesheet\" href=\"" . config::$csspad . "ifie9.css\" type=\"text/css\" /><![endif]-->";
	}

	public function setTitel($titel)
	{
		$this->titel = $titel;
	}

	public function getTitel()
	{
		return $this->titel;
	}

	public function setMetaKeyword($keyword)
	{
		$this->meta_sleutelwoorden .= $keyword;
	}

	public function setMetaDescription($description)
	{
		$this->meta_beschrijving .= $description;
	}

	public function getVereisteHTML()
	{
		return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
				<html lang=\"en-US\" xml:lang=\"en-US\" xmlns=\"http://www.w3.org/1999/xhtml\"> 
				<head>
				<title>" . $this->titel . "</title>
				<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\" />
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
				<meta name=\"description\" content=\"" . $this->meta_beschrijving . "\" />
				<meta name=\"keywords\" content=\"" . $this->meta_sleutelwoorden . "\" />
				" . $this->css . "
				" . $this->javascript . "
				</head>
				<body>";
	}

	public function getNoscript()
	{
		echo '<noscript><center><p class="noscript">Jouw browser ondersteund geen javascript!/Heeft javascript uitgeschakeld</p></center></noscript>';
	}

	public function getHeader()
	{
		return "<div id=\"header\">
			<![if !IE]><h1><a href=\"index.php\">EventPlaza</a></h1><![endif]>
			<!--[if IE]><a href=\"index.php\"><img src=\"css/image/header.jpg\" alt=\"Eventplaza\" /></a><![endif]-->
	</div>";
	}

	public function getMenu()
	{
		?>
		<ul id="nav">
			<!--[if IE]><div class="iewankers"></div><![endif]-->
			<li class="single">
				<a href="index.php" class="button">Home</a>
			</li>
			<li>
				<a href="studentenlijst.php" class="drop button">Studenten</a>
				<div class="dropdown">
					<div><a href="studentenlijst.php" class="button">Studentenlijst<br /><font>Ingeschreven studenten</font></a></div>
				</div>
			</li>
			<li>
				<a href="verenigingenlijst.php" class="drop button">Verenigingen</a>
				<div class="dropdown">
					<div><a href="verenigingenlijst.php" class="button">Overzicht<br /><font>Een lijst van alle verenigingen</font></a></div>
					<?php if(!isMember()){ ?>
						<div><a href="registrerenvereniging.php" class="button">Registreer<br /><font>Voeg een vereniging toe</font></a></div>
					<?php } ?>
				</div>
			</li>
			<li>
				<a href="evenementenlijst.php" class="drop button">Evenementen</a>
				<div class="dropdown">
					<div><a href="evenementenlijst.php" class="button">Overzicht<br /><font>Een lijst van alle evenementen</font></a></div>
					<?php if(isVereniging()){ ?>
						<div><a href="evenementtoevoegen.php" class="button">Toevoegen<br /><font>Een evenement toevoegen</font></a></div>
					<?php } ?>
				</div>
			</li>
			<?php if (isAdmin()) { ?>
				<li>
					<a href="beheer.php" class="drop button">Beheer</a>
					<div class="dropdown">
						<div><a href="raadpleegevenementcategorieen.php" class="button">Categoriën<br /><font>Overzicht van categoriën</font></a></div>
						<div><a href="rapport.php" class="button">Rapport<br /><font>Rapporten opvragen</font></a></div>
					</div>
				</li>
			<?php } ?>
			<![if !IE]><li class="align_right"><![endif]>
			<!--[if IE]><li><![endif]-->
				<?php
				if (isMember()) {
					?>
					<a href="#" class="drop">Ingelogd</a>
					<div id="login" class="dropdown">
						<?php if(isVereniging()){ ?>
							<div><a href="raadplegenvereniging.php?id=<?php print($_SESSION['verenigingid']); ?>" class="button">Vereniging<br /><font>Je eigen vereniging</font></a></div>
						<?php }
							if(isStudent()){
						?>
							<div><a href="raadplegenprofiel.php?id=<?php print($_SESSION['studentid']); ?>" class="button">Profiel<br /><font>Je eigen profiel</font></a></div>
							<!--<div><a href="inbox.php?id=<?php print($_SESSION['studentid']); ?>" class="button">Inbox<br /><font>Je berichten</font></a></div>-->
						<?php } ?>
						<div><a href="login.php?logout=true" class="button">Uitloggen<br /><font>De verbinding verbreken</font></a></div>
					</div>
					<?php
				} else {
					?>
					<a href="login.php" class="drop">Inloggen</a>
					<div id="login" class="dropdown">
						Username + Password:
						<div class="form">
							<form action="login.php" method="post">
							<input type="submit" name="login" value="Ga!" class="submit" />
							<input type="text" name="username" class="text" /><br />
							<input type="password" name="password" class="pass" />
							<input type="text" name="url" hidden="hidden" value="<?php print($_SERVER['REQUEST_URI']); ?>" />
							
						</form>
						</div>
						<div class="clear"><a href="registreer.php" class="button">Registreer<br /><font>Nog geen lid?</font></a></div>
					</div>
				<?php } ?>
			</li>
			<!--[if IE]><div class="iewankers_eind"></div><![endif]-->
		</ul>
		<?php
	}

	public function getFooter()
	{
		return "<div id=\"footer\">
				<p>eventplaza is ontworpen door is1e gfy, in opdracht van zoen</p>
			</div>";
	}

	public function getVereisteHTMLafsluiting()
	{
		return "</body></html>";
	}

}
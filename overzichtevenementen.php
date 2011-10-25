<?php
/**
 * @author: Hans-Jurgen Bakkenes
 * @description: 
 */

if (!isAdmin()) {
	header("location:index.php");
}

$pagina = pagina::getInstantie();

$pagina->setTitel("Overzicht aantal evenementen per vereniging");
$pagina->setCss("style.css");

echo $pagina->getVereisteHTML();
?>
<div id="container">
	<?php echo $pagina->getHeader(); ?>
	<div id="page">
		<?php echo $pagina->getMenu(); ?>
		<div id="content">
			<h1><?php echo $pagina->getTitel(); ?></h1>
			<div class="zoeken">
				<form action="" method="post">
					<table>
						<tr>
							<th>
								<label for="beginperiode">Van</label>
							</th>
							<td>
								<input type="text" name="beginperiode" id="beginperiode" maxlength="10" />
							</td>
							<th>
								<label for="eindperiode">Tot</label>
							</th>
							<td>
								<input type="text" name="eindperiode" id="eindperiode" maxlength="10" />
							</td>
							<td>
								<input type="submit" name="periode" value="Zoek" />
							</td>
						</tr>
					</table>
				</form>
			</div>
			<br />
			<div class="gegevens">
				<?php
				
				database::getInstantie();
				if ((isset($_POST["periode"]) && (!empty($_POST["beginperiode"])) || (isset($_POST["periode"]) && !empty($_POST["eindperiode"]))) && preg_match("/^[0-9]{1,2}[-]{1}[0-9]{1,2}[-]{1}[0-9]{2,4}$/", $_POST["beginperiode"]) && preg_match("/^[0-9]{1,2}[-]{1}[0-9]{1,2}[-]{1}[0-9]{2,4}$/", $_POST["eindperiode"])) {
					$where = "";
					if (tijd::checkCorrectieDatum($_POST["beginperiode"]) && tijd::checkCorrectieDatum($_POST["eindperiode"])) {
						if ($_POST["beginperiode"] < $_POST["eindperiode"]) {
							$where = "WHERE begindatum >= '".tijd::formatteerTijd($_POST["beginperiode"], "Y-m-d")."' AND einddatum <= '".tijd::formatteerTijd($_POST["eindperiode"], "Y-m-d")."'";
						}
					} else if (tijd::checkCorrectieDatum($_POST["beginperiode"])) {
						$where = "WHERE begindatum >= '".tijd::formatteerTijd($_POST["beginperiode"], "Y-m-d")."'";
					} else if (tijd::checkCorrectieDatum($_POST["eindperiode"])) {
						$where = "WHERE einddatum <= ".tijd::formatteerTijd($_POST["eindperiode"], "Y-m-d");
					} 
					$sql = "SELECT `vereniging`.`naam`, COUNT(evenement.evenementid) AS totaal FROM `vereniging` LEFT OUTER JOIN evenement ON vereniging.verenigingid = evenement.organiserendeverenigingid ".$where." GROUP BY vereniging.naam;";
				} else {
					$sql = "SELECT `vereniging`.`naam`, COUNT(evenement.evenementid) AS totaal FROM `vereniging` LEFT OUTER JOIN evenement ON vereniging.verenigingid = evenement.organiserendeverenigingid GROUP BY vereniging.naam;";
				}
				
				$resultaat_van_server = mysql_query($sql);
				
				if (mysql_num_rows($resultaat_van_server) > 0) {
					?>
					<table border="1">
						<th>Naam vereniging</th><th>Evenementen</th>
						<?php
						while($array = mysql_fetch_array($resultaat_van_server)) {
							echo "<tr><td>".$array["naam"]."</td><td>".$array["totaal"]."</td></tr>";
						}
						?>
					</table>
					<?php
				}
				
				?>
			</div>
		</div>
	</div>
	<?php echo $pagina->getFooter(); ?>
</div>

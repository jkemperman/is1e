<?php
/**
 * @author: Hans-Jurgen Bakkenes
 * @description: 
 */
$pagina = pagina::getInstantie();
database::getInstantie();
if (isset($_GET["id"]) && intval($_GET["id"])) {
	$id = mysql_real_escape_string($_GET["id"]);
} else {
	$id = "";
}

$query = "(SELECT 
			CONCAT('bericht_',berichtid) as id, onderwerp, bericht, CONCAT(voornaam, ' ', achternaam) AS naam, datum 
		FROM 
			bericht
		INNER JOIN 
			student
		ON
			van = student.studentid
		WHERE 
			naar = ".$id."
		OR 
			groepid
		IN (
			SELECT 
				groepid
			FROM
				groep
			WHERE
				eigenaar = ".$id."
		))
		UNION
		(
		 SELECT DISTINCT
			CONCAT('reactie_',reactieid) as id, naam as onderwerp, inhoud as bericht, CONCAT(voornaam, ' ', achternaam) AS naam, tijdstip as datum
		FROM 
			`reactie` 
		INNER JOIN 
			student
		ON 
			afzender_id = studentid 
		INNER JOIN 
			evenement
		ON
			reactie.evenementid = evenement.evenementid
		WHERE 
			afzender_id 
		IN (
			SELECT 
				studentid 
			FROM 
				groeplid 
			WHERE 
				groepid 
			IN 
				(
				SELECT 
					groepid 
				FROM 
					groep 
				WHERE
					eigenaar = ".$id."
				)
			OR
				groepid
			IN
				(
					SELECT 
						groepid
					FROM 
						groeplid
					WHERE 
						studentid = ".$id."
				)
			AND 
				studentid != ".$id."
			)
		)
		UNION
		(
		 SELECT 
			CONCAT('profielbericht_',profielberichtid) as id, onderwerp, inhoud as bericht, CONCAT(voornaam, ' ', achternaam) AS naam, datum 
		FROM 
			`profielbericht` 
		INNER JOIN 
			student 
		ON 
			afzender = student.studentid 
		WHERE 
			afzender = ".$id.")
		ORDER BY datum DESC;";
$resultaat_van_server = mysql_query($query);

$pagina->setTitel("Inbox");
$pagina->setCss("style.css");
$pagina->setJavascript("jquery.js");
$pagina->setJavascriptCode("
	$(document).ready(function() {
		$(\"#emails td\").click(function(){
			var info = $(this).children(\":first-child\").attr('class');
			$.post('bericht.php', { informatie: info }, function(data) {
				$('#emailcontent').html(data);
			});
		});
		
		$(\"#emailoptions span\").click(function(){
			var info = $(this).html();
			$.post('bericht.php', { optie: info }, function(data) {
				$('#emailcontent').html(data);
			}).complete(function() {
				if ($(\"#emailoptions\").children(\"span\").html() == \"Nieuw\") {
					$(this).remove();
					$(\"#emailoptions\").append(\"<span>Opslaan</span><span>Annuleren</span>\");
				}
			});
		});
	});
");

echo $pagina->getVereisteHTML();
?>
<div id="container">
	<?php echo $pagina->getHeader(); ?>
	<div id="page">
		<?php echo $pagina->getMenu(); ?>
		<div id="content">
			<h1><?php echo $pagina->getTitel(); ?></h1>
			<?php if (mysql_num_rows($resultaat_van_server) > 0) { ?>
			<div id="emailoptions">
				<span>Nieuw</span>
			</div>
			<div id="inbox">
				<div id="emails">
					<table style="width:100%;" cellpadding="0" cellspacing="0">
					<?php
						while ($array = mysql_fetch_assoc($resultaat_van_server)) {
							?><tr>
								<td><?php echo "<div class=\"".$array["id"]."\" style=\"width:100%;float:left;\"><div style=\"float:right;\">".tijd::formatteerTijd($array["datum"], "d-m-Y")."</div>".$array["naam"]."</div><div style=\"width:100%;float:left;\">".$array["onderwerp"]."</div>"; ?></td>
							</tr><?php
						}
					?>
					</table>
				</div>
				<div id="emailcontent">
					
				</div>
			</div>
			<?php } else { ?>
				Geen berichten in uw inbox.
			<?php } ?>
			<div style="clear:both;"></div>
		</div>
	</div>
	<?php echo $pagina->getFooter(); ?>
</div>
<?php
echo $pagina->getVereisteHTMLafsluiting();
?>
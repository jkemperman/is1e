<?php
/**
 * @author: Hans-Jurgen Bakkenes
 * @description: 
 */
$pagina = pagina::getInstantie();

$pagina->setTitel("Smileys bij textarea");
$pagina->setCss("style.css");
$pagina->setJavascript("jquery.js");
$pagina->setJavascriptCode("
	$(document).ready(function() {
		$(\"#smiley\").click(function(){
			if ($(\"#list\").css('display') == 'none') {
				var position = $(this).position();
				$(\"#list\").css({'display':'block', 'left':position.left + 32, 'top':position.top});
			} else {
				$(\"#list\").css('display','none');
			}
		});
		$(\".bold, .italic, .underline\").click(function(){
			var html = $.trim($(this).html());
			$(\"textarea\").val( $(\"textarea\").val() + '[' + html.toLowerCase() + ']' + '[/' + html.toLowerCase() + ']');
		});
		$(\"#list span\").click(function(){
			$(\"textarea\").val( $(\"textarea\").val() + $(this).attr(\"title\"));
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
			<div>
				<textarea style="float:left;height:200px;width:200px;"></textarea>
				<div id="tekstopties">
					<div id="smiley">
						<img src="/project/images/smile.gif" style="float:left;margin-left:8px;margin-top:3px;margin-right:8px;width:15px;height:15px;" />
					</div>
					<div class="bold">
						B
					</div>
					<div class="italic">
						I
					</div>
					<div class="underline">
						U
					</div>
				</div>
				<div id="list">
					<?php
					foreach (specialetekens::getSmileys() as $array) {
						$smiley = explode("_", $array, 2);
						list($width, $height, $type, $attr) = getimagesize($_SERVER["DOCUMENT_ROOT"]."/project/images/".$smiley[0].".gif");
						$rwidth = 32 - $width;
						$marginleft = round(($rwidth / 2),0, PHP_ROUND_HALF_DOWN);
						$rheight = 22 - $height;
						$margintop = round(($rheight / 2),0, PHP_ROUND_HALF_DOWN);
						echo "<span title=\"".$smiley[1]."\"><img src=\"/project/images/".$smiley[0].".gif\" style=\"margin-left:".$marginleft."px;margin-top:".$margintop."px;width:".$width."px;height:".$height."px;\" /></span>";
					}
					?>
				</div>
				<?php
				echo specialetekens::vervangTekensInTekst("Lache toch :). [b]Maarja[/b] [i]je[/i] [u]moet[/u] wat. :o:(:O|:(");
				?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	<?php echo $pagina->getFooter(); ?>
</div>



<?php
echo $pagina->getVereisteHTMLafsluiting();

?>
<?php
if (isset($_SESSION["studentid"]) && isset($_SESSION['user_id'])) {
	database::getInstantie();
	$query = "SELECT * FROM groeplid INNER JOIN student ON student.studentid = groeplid.studentid WHERE groeplid.studentid = ".$_SESSION["studentid"].";";
	$resultaat = mysql_query($query);
	if ($resultaat && mysql_num_rows($resultaat) > 0) {
		echo "
<div id='inline_content' style='padding:10px; background:#fff;'>";
		while ($array = mysql_fetch_assoc($resultaat)) {
			echo "<table class=\"".$array["groepid"]."\"><tr><td>".$array["voornaam"]." ".$array["achternaam"]."</td><td><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">Accepteren</span></td><td><font style=\"color:blue;text-decoration:underline;cursor:pointer;\">Negeren</font></td></tr></table>";
		}
		echo "</div>";
	}
}
?>

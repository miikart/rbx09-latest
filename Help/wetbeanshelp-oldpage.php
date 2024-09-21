<?php include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/api/web/nav.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
?>
<div id="Body">
			
	<div id="ctl00_cphGoldblox_KBPanel">
  
		<h1>
			Wetbeans's help</h1>
<h2>You can go to the forums for help, if you want. But not much will happen on there, so you might as well join the discord server if you have any questions..<h2>
	<table width="100%">
		<tbody><tr align="center">
			<td width="20%">
				<a id="ctl00_cphGoldblox_HyperLink1" href="/Forum/ShowForum.aspx?ForumID=6"><font size="5">Building Forum</font></a>
			</td>
			<td>
				<a id="ctl00_cphGoldblox_HyperLink2" href="/Forum/ShowForum.aspx?ForumID=7"><font size="5">Scripting Forum</font></a>
			</td>
			<td>
				<a id="ctl00_cphGoldblox_HyperLink4" href="/Forum/ShowForum.aspx?ForumID=8"><font size="5">Technical Forum</font></a>
			</td>
			<td>
				<a id="ctl00_cphGoldblox_HyperLink5" href="#" ><font size="5">User Help Wiki</font></a>
			</td>
			<td>
				<a id="ctl00_cphGoldblox_HyperLink3" href="/Forum/ShowForum.aspx?ForumID=3"><font size="5">Suggestions</font></a>
			</td>
<td>
				<a id="ctl00_cphGoldblox_HyperLink3" href="https://discord.gg/nzx7CCtdTb"><font size="5">The discord server</font></a>
			</td>
		</tr>
	</tbody></table>
	<h2>
		Still Have Questions?</h2>
	<p style="font-size: large">
		
		
		
		<span id="ctl00_cphGoldblox_InfoOnlyLabel"> Email our </span>
		<a href="mailto:<?=$site_email;?>">Customer Service Team</a> (<?=$site_email;?>)
	</p>
	<br>
	<br>
<h1 style="color: orange;"> Notice: If the email doesn't match up with the website url, it means that this website is temporary so you should just ask for help in the discord server.</h1>
		</div>
<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'
				 ?>
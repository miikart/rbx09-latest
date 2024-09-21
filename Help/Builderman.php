<?php include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/api/web/nav.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
?>
<div id="Body">
			
	<div id="ctl00_cphGoldblox_KBPanel">
  
		<h1>
			Builderman's Help Page</h1>
		<h2>
			Builderman's Knowledge Base</h2>
		<p style="font-size: large">
			The fastest way to get help is to search Builderman's Knowledge Base.<br>
			Builderman has already helped thousands of <?=$sitename2;?>ians. Let him help you!
			<iframe title="Content" src="http://web.archive.org/web/20090120092412if_/http://na2.salesforce.com/sol/public/search.jsp?orgId=00D400000009DDW" width="100%" height="412"></iframe>
		</p>
	
</div>
	<h2>
		<?=$sitename;?> Help Forums &amp; Wiki</h2>
	<p style="font-size: large">
		<?=$sitename2;?>ians love helping each other, and odds are someone has already had the same
		question as you.<br>
		Check out the <?=$sitename;?> Help Forums for answers:<br>
	</p>
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
				<a id="ctl00_cphGoldblox_HyperLink5" href="#" onclick="alert('Soon')"><font size="5">User Help Wiki</font></a>
			</td>
			<td>
				<a id="ctl00_cphGoldblox_HyperLink3" href="/Forum/ShowForum.aspx?ForumID=4"><font size="5">Suggestions</font></a>
			</td>
		</tr>
	</tbody></table>
	<h2>
		Still Have Questions</h2>
	<p style="font-size: large">
		
		
		
		<span id="ctl00_cphGoldblox_InfoOnlyLabel"> Email our </span>
		<a href="mailto:<?=$site_email;?>">Customer Service Team</a> (<?=$site_email;?>)
	</p>
	<br>
	<br>
		</div>
<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'
				 ?>
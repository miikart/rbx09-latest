<?php include 'api/web/config.php'; ?>
<?php include 'api/web/header.php'; ?>
<?php include 'api/web/nav.php'; ?>
<?php
if(!$auth)
    exit(header('location: /Login/Default.aspx'));

if((int)$_USER["discord_verified"] === 1)
    exit(header('location: /Default.aspx'));
?>
<div id="Body">
<h1>Discord Verification</h1>
<h2>Your account (<?php echo htmlspecialchars($_USER["username"]); ?>) needs to be verified using Discord to play GOLDBLOX (the codename for this site).</h2>
<a href="https://discord.com/oauth2/authorize?client_id=<?php echo (int)$discord["clientid"]; ?>&response_type=code&redirect_uri=http%3A%2F%2Fwww.rccs.lol%2Fapi%2FDiscordVerification.ashx&scope=guilds+identify"><h3>Click here to verify using your Discord account</h3></a>
</div>
<?php include 'api/web/footer.php'; ?>
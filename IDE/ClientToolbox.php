<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/db.php";
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
if(isset($_GET['q'])) {
    $search = mysqli_real_escape_string($conn, $_GET['q']);
} else {
    $search = "";
}

$category = (int)$_GET['c'];

if($category == 1){
	$categoryName = "bricks";
}elseif($category == 2){
	$categoryName = "robots";
}elseif($category == 3){
	$categoryName = "chassis";
}elseif($category == 4){
	$categoryName = "tools";
}elseif($category == 5){
	$categoryName = "sound";
}elseif($category == 6){
	$categoryName = "furniture";
}elseif($category == 7){
	$categoryName = "roads";
}elseif($category == 8){
	$categoryName = "skyboxes";
}elseif($category == 9){
	$categoryName = "billboards";
}elseif($category == 10){
	$categoryName = "objects";
}elseif($category == 11){
	$categoryName = "mine";
}elseif($category == 12){
	$categoryName = "all";
}elseif($category == 13){
	$categoryName = "decals";
}elseif($category == 14){
	$categoryName = "mydecals";
}else{
	$categoryName = "bricks";
}
			
$resultsperpage = 16;


if(!isset($_GET['p'])) {
    $page = 1;
}else{
    $page = (int)$_GET['p'];
}
$thispagefirstresult = ($page-1)*$resultsperpage;
if ($categoryName != "decals") {
    if ($categoryName != "all" && $categoryName != "mine" && $categoryName != "mydecals") {
        $models2 = "SELECT id, name, creatorid 
                    FROM catalog 
                    WHERE LOWER(name) LIKE LOWER('%$search%') 
                    AND type = 'model' 
                   
                    ";
    } elseif ($categoryName == "mydecals") {
        $models2 = "SELECT * FROM owned_items WHERE ownerid = {$_USER['id']} AND type = 'decal'";
    } elseif ($categoryName == "mine") {
        $models2 = "SELECT * FROM owned_items WHERE ownerid = {$_USER['id']} AND type = 'model'";
                    
    } else {
        $models2 = "SELECT id, name, creatorid 
                    FROM catalog 
                    WHERE LOWER(name) LIKE LOWER('%$search%') 
                    AND type = 'model' 
               ";
    }
} else {
    $models2 = "SELECT id, name, creatorid 
                FROM catalog 
                WHERE LOWER(name) LIKE LOWER('%$search%') 
                AND type = 'decal' 
              ";
}

$result2 = $conn->query($models2);
if (!$result2) {
    die("Error executing query: " . $conn->error);
}

$numberofpages = ceil(mysqli_num_rows($result2) / $resultsperpage);
if ($categoryName != "decals") {
    if ($categoryName != "all" && $categoryName != "mine" && $categoryName != "mydecals") {
        $models = "SELECT id, name, creatorid 
                   FROM catalog 
                   WHERE LOWER(name) LIKE LOWER('%$search%') 
                   AND type = 'model' 
             
            
                   LIMIT 16 OFFSET $thispagefirstresult";
    } elseif ($categoryName == "mine") {
        $models = "SELECT * FROM owned_items WHERE ownerid = {$_USER['id']} AND type = 'model' LIMIT 16 OFFSET $thispagefirstresult";
    } elseif ($categoryName == "mydecals") {
        $models = "SELECT * FROM owned_items WHERE ownerid = {$_USER['id']} AND type = 'decal' LIMIT 16 OFFSET $thispagefirstresult";
    } else {
        $models = "SELECT id, name, creatorid 
                   FROM catalog 
                   WHERE LOWER(name) LIKE LOWER('%$search%') 
                   AND type = 'model' 
                
                   LIMIT 16 OFFSET $thispagefirstresult";
    }
} else {
    $models = "SELECT id, name, creatorid 
               FROM catalog 
               WHERE LOWER(name) LIKE LOWER('%$search%') 
               AND type = 'decal' 
            
               LIMIT 16 OFFSET $thispagefirstresult";
}

$result = $conn->query($models);

$oneofthis = mysqli_num_rows($result);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
	Toolbox
</title><link href="./Toolbox.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script><script id="Functions" type="text/jscript">
			function insertContent(id)
			{
			
                   try
                   {
                 window.external.Insert("http://rccs.lol/asset/?id=" + id);
                   }
                   catch(x)
                   {
                       alert("registered improperly. ask sword for help.");
                   }          
			}
			function dragRBX(id)
			{
				event.dataTransfer.setData("Text", "http://www.rccs.lol/asset/?id=" + id);			}
			function clickButton(e, buttonid)
			{
				var bt = document.getElementById(buttonid);
				if (typeof bt == 'object')
				{
					if(navigator.appName.indexOf("Netscape")>(-1))
					{
						if (e.keyCode == 13)
						{
							bt.click();
							return false;
						}
					}
					if (navigator.appName.indexOf("Microsoft Internet Explorer")>(-1))
					{
						if (event.keyCode == 13)
						{
							bt.click();
							return false;
						}
					}
				}
			}
    </script></head><body class="Page" bottommargin="0" leftmargin="0" rightmargin="0">

<div></div>
        <div id="ToolboxContainer">
            <div id="ToolboxControls">
                <div id="ToolboxSelector">
                    <select name="ddlToolboxes" onchange="updateURL(this)" id="ddlToolboxes" class="Toolboxes">
    <option <?php if($category == 1) {echo 'selected=""';} ?>value="1">Bricks</option>
    <option <?php if($category == 2) {echo 'selected=""';} ?>value="2">Robots</option>
    <option <?php if($category == 3) {echo 'selected=""';} ?>value="3">Chassis</option>
    <option <?php if($category == 4) {echo 'selected=""';} ?>value="4">Tools</option>
    <option <?php if($category == 5) {echo 'selected=""';} ?>value="5">Sound</option>
    <option <?php if($category == 6) {echo 'selected=""';} ?>value="6">Furniture</option>
    <option <?php if($category == 7) {echo 'selected=""';} ?>value="7">Roads</option>
    <option <?php if($category == 8) {echo 'selected=""';} ?>value="8">Skyboxes</option>
    <option <?php if($category == 9) {echo 'selected=""';} ?>value="9">Billboards</option>
    <option <?php if($category == 10) {echo 'selected=""';} ?>value="10">Game Objects</option>
    <option <?php if($category == 14) {echo 'selected=""';} ?>value="14">My Decals</option>
    <option <?php if($category == 13) {echo 'selected=""';} ?>value="13">Free Decals</option>
    <option <?php if($category == 11) {echo 'selected=""';} ?>value="11">My Models</option>
    <option <?php if($category == 12) {echo 'selected=""';} ?>value="12">Free Models</option>
</select>

<script>
function updateURL(selectElement) {
    var selectedValue = selectElement.value;
    var currentURL = window.location.href;
    
    var urlParts = currentURL.split('?');
    var baseURL = urlParts[0];

    var newURL = baseURL + '?c=' + selectedValue;
    window.location.href = newURL;
}

</script>

                </div>
<?php if($category > 10) { ?><div id="pSearch">
    <div id="ToolboxSearch">
        <input name="tbSearch" type="text" value="" id="tbSearch" class="Search" onkeyup="handleKeyPress(event)" />
        <a id="lbSearch" class="ButtonText" href="javascript:void(0)" onclick="searchWithParameter(); return false;">
            <div id="Button">Search</div>
        </a>
    </div>
</div> <?php } ?>

<script>
    function searchWithParameter() {
        var searchTerm = document.getElementById("tbSearch").value;
        var baseUrl = window.location.href.split('?')[0];
        var searchUrl = baseUrl + "?q=" + encodeURIComponent(searchTerm) + "&c=" + document.getElementById("ddlToolboxes").value;
        window.location.href = searchUrl;
    }
    
    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            searchWithParameter();
        }
    }
</script>
            </div>
            <div id="ToolboxItems">
                <span id="dlToolboxItems" style="display:inline-block;width:100%;">

                <?php
                
                    if (mysqli_num_rows($result) > 0) {
                      while($row = mysqli_fetch_assoc($result)) {
                          if(isset($row['itemid'])) {
                              $findthestupidthingie = mysqli_query($conn, "SELECT * FROM catalog WHERE id = {$row['itemid']} ");
                              
                              $row = mysqli_fetch_assoc($findthestupidthingie);
                          }
                       echo '
                            <span>
                                <span class="ToolboxItem" 
                                      ondragstart="dragRBX('. $row['id'] .')"
                                      onmouseover="this.style.borderStyle=\'outset\'" 
                                      onmouseout="this.style.borderStyle=\'none\'">
                                    <a id="dlToolboxItems_ctl04_ciToolboxItem" 
                                       title="item" 
                                       href="javascript:insertContent('. $row['id'] .')" 
                                       style="display:inline-block;height:60px;width:60px;cursor:pointer;">
                                        <img src="https://rccs.lol/Thumbs/Asset.ashx?assetId='. $row['id'] .'&isSmall=1" 
                                             border="0" 
                                             id="img" 
                                             alt="'. $row['name'] .'" 
                                             width="60" 
                                             height="60" />
                                    </a>
                                </span>
                            </span>'; 
                      }
                    } else {
                      echo "I AM MEWING";
                    }
                ?>
</span>


                        
            </div>
            <div id="pNavigation">
	
                <div class="Navigation">
                    <div id="Previous">
                        <?php if($page-1 > 0) { echo '<a href="ClientToolbox.aspx?p='. ($page - 1) .'&c='.$category.'" id="PreviousPage"><span class="NavigationIndicators">&lt;&lt;</span>
                            Prev</a>';}?>
                    </div>
                    <div id="Next">
                        <?php if($page + 1 < $numberofpages + 1) { echo '<a href="ClientToolbox.aspx?p='. ($page + 1) .'&c='.$category.'" id="NextPage">Next <span class="NavigationIndicators">&gt;&gt;</span></a>';}?>
                    </div>
                    <div id="Location">
                        <span id="PagerLocation"><?=$thispagefirstresult?>-<?=$oneofthis?> of <?php echo mysqli_num_rows($result2);?></span>
                    </div>
                </div>
            
</div>
        </div>
    
<div>
</div>
</body>
</html>

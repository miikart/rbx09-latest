<?php


include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";



if($auth == false)
{
	header("Location: /Login/Default.aspx");
	exit();
}

if($_USER['USER_PERMISSIONS'] !== "Administrator"){
	header("Location: /Error/Default.aspx");
	exit();
}
?>

<style>
    #EditProfileContainer {
        background-color: #eeeeee;
        border: 1px solid #000;
        color: #555;
        margin: 0 auto;
        width: 620px;
    }
    #EditProfileContainer #AgeGroup, #EditProfileContainer #ChatMode, #EditProfileContainer #PrivacyMode, #EditProfileContainer #EnterEmail, #EditProfileContainer #ResetPassword, #EditProfileContainer #Blurb {
        margin: 0 auto;
        width: 60%;
    }
    #assetid {
        text-align: center;
        margin-top: 5px;
    }
    #renderImage {
        width:450px;
        border: 1px solid black;
        margin-top: 10px;
    }
    #render {
        width:500px;
        height: 40px;
    }
    .renderContainer {
        text-align: center;
    }
</style>

<div id="Body">
    
    <script>
      function render() {
    var id = document.getElementById("aid").value;
    var img = document.getElementById("renderImage");
    var type = document.querySelector('input[name="type"]:checked').value;
    var url;
    var imgurl;
    img.src = "/images/unavail.png";
    switch(type) {
        case "asset":
            url = `/api/renderitem.php`;
            imgurl = `/Thumbs/Asset.ashx?assetId=${id}&t=`
            break;
          
        case "place":
            url = `/api/renderplacetest.php`;
            imgurl = `/Thumbs/Asset.ashx?assetId=${id}&t=`
            break;
        case "head":
            url = `/api/renderhead.php`
            imgurl = `/Thumbs/Asset.ashx?assetId=${id}&t=`
        default:
            break;
    }
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${id}`
    })
    .then(response => {
        if (!response.ok) {
            img.src = "/images/unavail.png";
            throw new Error('gra');
        }
        console.log('rendered');
        var timestamp = new Date().getTime();
        img.src = imgurl + timestamp;
    })
    .catch(error => {
        img.src = "/images/unavail.png";
        console.error('grrr:', error);
    });
}

    </script>
    
    <div id="EditProfileContainer">
		<h2>Rerender Assets</h2>
		<div class="renderContainer" style="width:100%;">
    		<fieldset style="border: dashed 0px Gray; margin: 0 auto;">
            <span style="font-weight: bold;">Type:</span><br>
            <label>
                <input type="radio" name="type" value="asset" checked="">
                Asset
            </label>
            
            <label>
                <input type="radio" name="type" value="place">
                Place
            </label>
            <label>
                <input type="radio" name="type" value="head">
                Head
            </label>
        </fieldset>
        </div>
    	<div id="assetid">
			<span style="font-weight: bold;">Asset ID:</span><br>
			<input name="aid" id="aid" type="text" value="" maxlength="64" class="TextBox" style="text-align: center;">
		</div>
		<div class="renderContainer">
		    <img id="renderImage" src="/images/unavail.png">
		</div>
		<div class="Buttons">
			<button name="render" id="render" tabindex="4" onclick="render()" class="Button" type="render" value="Render">Render</button>
		</div>
	</div>
</div>
				
<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php";
?>
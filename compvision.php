<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="/jquery.min.js"></script>
</head>
<body>
    
<script type="text/javascript">
    function processImage() {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "5414297775b24ead914f1d1857a59a18 ";
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color,Faces,Tags,ImageType,Adult",
            "details": "Celebrities,Landmarks",
            "language": "en",
        };
        // Display the image.
        var sourceImageUrl = " <?php echo $_GET['link'] ?>";
        document.querySelector("#sourceImage").src = sourceImageUrl;
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
            type: "POST",
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
            .done(function(data) {
                // Show formatted JSON on webpage.
				<!-- var json = JSON.stringify(data, null, 2); -->
				//var desc = json.description;
                var desc = data.description.captions[0].text;
				console.log('value json:', data);
				$("#responseTextArea").val(JSON.stringify(desc, null, 2));
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                    errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                    jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
    };

    window.onload=function(){
        processImage();
    }
</script>

<h1>Analyze image:</h1>
<br><br>
Image to analyze: <?php echo $_GET['link'] ?>
<br><br> 
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>

</body>
</html>

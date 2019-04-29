<html>
 <head>
 <Title>Input Scan</Title>
 <style type="text/css">
 	body { background-color: #fff; border-top: solid 10px #000;
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>Scan here!</h1>
 <p>Upload your image, then click <strong>Analyze</strong> to analyze the image.</p>
 <form method="post" action="scanner.php" enctype="multipart/form-data" >
       Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload"> </br></br>
       <input type="submit" name="submit" value="Analyze" />
       <input type="submit" name="load_data" value="Load Data" />
 </form>

 
 <?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=stevenfolder;AccountKey=XF3yd6/TwQk68xkSA5/xpMHg9QH9ZHNp/MCwOV7y/1bPYhg04cemi65PcwKB4RlMU+NNx6y9tSQD8jfs2hZglA==;EndpointSuffix=core.windows.net";
$blobClient = BlobRestProxy::createBlobService($connectionString);
$createContainerOptions = new CreateContainerOptions();
$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "blockblobssteven";

    if(isset($_POST["submit"])) {
	try {
        $fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
        // Getting local file so that we can upload it to Azure
        $myFile = fopen($_FILES["fileToUpload"]["tmp_name"], "r");

        # Upload file as a block blob
        echo "Uploaded BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";   

        //Upload blob
        $blobClient->createBlockBlob($containerName, $fileToUpload, $myFile);
        header("Location: analyze.php");
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}
 
 ?>
 
  </body>
 </html>

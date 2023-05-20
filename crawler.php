<?php
include("classes/DomDocumentParser.php");
    
function findLinks($url) {
    echo $url;

    $parser = new DomDocumentParser($url);
}

$startURL = "https://www.theguardian.com";
findLinks($startURL);

?>
<?php
include("classes/DomDocumentParser.php");
    
function findLinks($url) {
    echo $url;

    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();
    
    foreach($linkList as $link) {
        $href = $link->getAttribute('href');
        echo $href . '<br>';
    }
}

$firstURL = "https://www.theguardian.com";
findLinks($firstURL);

$secondURL = "https://www.bbc.com";
findLinks($secondURL);

?>
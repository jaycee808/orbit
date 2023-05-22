<?php
include("classes/DomDocumentParser.php");

// function to retrieve url's from searched websites
function findLinks($url) {
    $parser = new DomDocumentParser($url);
    $linkList = $parser->getLinks();
    
    foreach($linkList as $link) {
        $href = $link->getAttribute('href');

        if(strpos($href, '#') !== false) {
            continue;
        } else if (substr($href, 0, 11) == 'javascript:') {
            continue;
        }

        $href = createLink($href, $url);

        echo $href . '<br>';
    }
}

// function to covert found links to full url's
function createLink($src, $url) {
    echo "SRC: $src<br>";
    echo "URL: $url<br>";

    $scheme = parse_url($url)["scheme"]; // https
    $host = parse_url($url)["host"]; // main website url
    echo "HOST: $host<br>";
    
    // switch statement to amend data to full url's
    switch (true) {
        case (substr($src, 0, 2) == "//"):
            $src = $scheme . ":" . $src;
            break;
        case (substr($src, 0, 1) == "/"):
            $src = $scheme . "://" . $host . $src;
            break;
        case (substr($src, 0, 2) == "./"):
            $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
            break;
        case (substr($src, 0, 3) == "../"):
            $src = $scheme . ":/" . $host . "/" . $src;
            break;
        case (substr($src, 0, 5) !== "https" && substr($src, 0, 4) !== "http"):
            $src = $scheme . ":/" . $host . "/" . $src;
            break;
    }
    
    return $src;
}


// url's crawled to get links
$firstURL = "https://www.theguardian.com";
findLinks($firstURL);

$secondURL = "https://www.bbc.com";
findLinks($secondURL);

$thirdURL = "https://inews.co.uk/";
findLinks($thirdURL);

$fourthURL = "https://www.independent.co.uk/";
findLinks($fourthURL);

?>
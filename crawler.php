<?php
include("classes/DomDocumentParser.php");

$crawled = array(); // array to store links that have been retrieved
$newCrawl = array(); // array to store new links

// function to retrieve url's from searched websites
function findLinks($url) {
    global $crawled;
    global $newCrawl;
    
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

        //if statement to recursively crawl links
        if(!in_array($href, $crawled)) {
            $crawled[] = $href;
            $newCrawl[] = $href;

            getLinkInfo($href);
        } 
        
        // else return;
    }

    array_shift($newCrawl);

    foreach($newCrawl as $page) {
        findLinks($page);
    }
}

// function to covert found links to full url's
function createLink($src, $url) {
    // echo "SRC: $src<br>";
    // echo "URL: $url<br>";

    $scheme = parse_url($url)["scheme"]; // https
    $host = parse_url($url)["host"]; // main website url
    // echo "HOST: $host<br>";

    // switch statement to amend data to full url's
    switch(true) {
        case(substr($src, 0, 2) == "//"):
            $src = $scheme . ":" . $src;
            break;
        case(substr($src, 0, 1) == "/"):
            $src = $scheme . "://" . $host . $src;
            break;
        case(substr($src, 0, 2) == "./"):
            $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
            break;
        case(substr($src, 0, 3) == "../"):
            $src = $scheme . ":/" . $host . "/" . $src;
            break;
        case(substr($src, 0, 5) !== "https" && substr($src, 0, 4) !== "http"):
            $src = $scheme . ":/" . $host . "/" . $src;
            break;
    }
    
    return $src;
}

// function to get titles of pages
function getLinkInfo($url) {
    $parser = new DomDocumentParser($url);
    $titleList = $parser->retrieveTitles();

    if(sizeof($titleList) == 0 || $titleList->item(0) == NULL) {
        return;
    }

    $title = $titleList->item(0)->nodeValue;
    $title = str_replace("\n", "", $title);

    if($title == "") {
        return;
    }

    $description = "";
    $keywords = "";

    $metaTagList = $parser->retrieveMetaTags();

    foreach($metaTagList as $metaTag) {
        $metaTagName = $metaTag->getAttribute("name");
        $metaTagContent = $metaTag->getAttribute("content");
        
        if($metaTagName == "description") {
            $description = $metaTagContent;
        }
    
        if($metaTagName == "keywords") {
            $keywords = $metaTagContent;
        }
    }

    echo "URL: $url, Title: $title<br>";
    echo "URL: $url, Description: $description<br>";
    echo "URL: $url, Keywords: $keywords<br>";

    if(duplicateLink($url, $title, $description, $keywords)) {
		echo "$url already exists<br>";
	} else if(duplicateLink($url, $title, $description, $keywords)) {
		echo "SUCCESS: $url<br>";
	} else {
		echo "ERROR: Failed to insert $url<br>";
	}

    addLinkToDatabase($url, $title, $description, $keywords);
}

// function to add found links to database
function addLinkToDatabase($url, $title, $description, $keywords) {
    global $conn;
    
    $dbname = "orbit";

    try {
        $conn = new PDO("mysql:dbname=$dbname;host=localhost", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $conn->prepare("INSERT INTO pages (url, title, description, keywords)
                                VALUES (:url, :title, :description, :keywords)");

        $query->bindParam(":url", $url);
        $query->bindParam(":title", $title);
        $query->bindParam(":description", $description);
        $query->bindParam(":keywords", $keywords);

        return $query->execute();
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return false;
    }
}

// function to remove duplicate links
function duplicateLink($url, $title, $description, $keywords) {
    global $conn;
    
    $dbname = "orbit";

    try {
        $conn = new PDO("mysql:dbname=$dbname;host=localhost", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $conn->prepare("SELECT * FROM pages WHERE url = :url");

        $query->bindParam(":url", $url);

        return $query->rowCount() != 0;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return false;
    }
}

// url's crawled to get links
// $firstURL = "https://www.theguardian.com";
// findLinks($firstURL);

$secondURL = "https://www.bbc.com";
findLinks($secondURL);

$thirdURL = "https://inews.co.uk/";
findLinks($thirdURL);

$fourthURL = "https://www.independent.co.uk/";
findLinks($fourthURL);

?>
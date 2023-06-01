<?php
include("../public/classes/DomDocumentParser.php");
include("config.php");

$crawled = array(); // array to store links that have been retrieved
$newCrawl = array(); // array to store new links

// function to retrieve URLs from searched websites
function findLinks($url) {
    global $crawled;
    global $newCrawl;

    $parser = new DomDocumentParser($url);
    $linkList = $parser->getLinks();

    foreach ($linkList as $link) {
        $href = $link->getAttribute('href');

        if (strpos($href, '#') !== false) {
            continue;
        } else if (substr($href, 0, 11) == 'javascript:') {
            continue;
        }

        $href = createLink($href, $url);

        //if statement to recursively crawl links
        if (!in_array($href, $crawled)) {
            $crawled[] = $href;
            $newCrawl[] = $href;
            getLinkInfo($href);
        }
    }

    array_shift($newCrawl);

    foreach ($newCrawl as $page) {
        findLinks($page);
    }
}

// function to convert found links to full URLs
function createLink($src, $url) {
    $scheme = parse_url($url)["scheme"];
    $host = parse_url($url)["host"];

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
            $src = $scheme . "://" . $host . "/" . $src;
            break;
        case (substr($src, 0, 5) !== "https" && substr($src, 0, 4) !== "http"):
            $src = $scheme . "://" . $host . "/" . $src;
            break;
    }

    return $src;
}

// function to get titles of pages
function getLinkInfo($url) {
    global $connection;

    $parser = new DomDocumentParser($url);
    $titleList = $parser->retrieveTitles();

    if (sizeof($titleList) == 0 || $titleList->item(0) == NULL) {
        return;
    }

    $title = $titleList->item(0)->nodeValue;
    $title = str_replace("\n", "", $title);

    if ($title == "") {
        return;
    }

    $description = "";
    $keywords = "";

    $metaTagList = $parser->retrieveMetaTags();

    foreach ($metaTagList as $metaTag) {
        $metaTagName = $metaTag->getAttribute("name");
        $metaTagContent = $metaTag->getAttribute("content");

        if ($metaTagName == "description") {
            $description = $metaTagContent;
        }

        if ($metaTagName == "keywords") {
            $keywords = $metaTagContent;
        }
    }

    echo "URL: $url, Title: $title<br>";
    echo "URL: $url, Description: $description<br>";
    echo "URL: $url, Keywords: $keywords<br>";

    if (duplicateLink($url)) {
        echo "$url already exists<br>";
    } else if (addLinkToDatabase($url, $title, $description, $keywords)) {
        echo "SUCCESS: $url<br>";
    } else {
        echo "ERROR: Failed to insert $url<br>";
    }
}

// function to add found links to database
function addLinkToDatabase($url, $title, $description, $keywords) {
    global $connection;

    try {
        $query = $connection->prepare("INSERT INTO pages (url, title, description, keywords)
                                VALUES (:url, :title, :description, :keywords)");

        $query->bindParam(":url", $url);
        $query->bindParam(":title", $title);
        $query->bindParam(":description", $description);
        $query->bindParam(":keywords", $keywords);

        return $query->execute();
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return false;
    }
}

// function to check for duplicate links
function duplicateLink($url) {
    global $connection;

    try {
        $query = $connection->prepare("SELECT * FROM pages WHERE url = :url");
        $query->bindParam(":url", $url);
        $query->execute();

        return $query->rowCount() != 0;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return false;
    }
}

// function to remove titles containing 'Sign In' from the database
function removeLinks() {
    global $connection;

    try {
        $query = $connection->prepare("DELETE FROM pages WHERE title LIKE '%Sign In%'");
        $query->execute();

        echo "Removed titles containing 'Sign In' from the database";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// $firstURL = "https://www.theguardian.com";
// findLinks($firstURL);

// $secondURL = "https://www.bbc.com";
// findLinks($secondURL);

// $thirdURL = "https://inews.co.uk/";
// findLinks($thirdURL);

// $fourthURL = "https://www.independent.co.uk/";
// findLinks($fourthURL);

// $fifthURL = "https://www.rollingstone.com/";
// findLinks($fifthURL);

// $sevenURL = "https://www.imdb.com";
// findLinks($sevenURL);

// $nineURL = "https://pitchfork.com/";
// findLinks($nineURL);

// $tenURL = "https://variety.com/";
// findLinks($tenURL);

// $thirteenURL = "https://www.residentadvisor.net/";
// findLinks($thirteenURL);

$fourteenURL = "https://www.tripadvisor.co.uk/";
findLinks($fourteenURL);
?>

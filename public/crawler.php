<?php
include("../public/classes/DomDocumentParser.php");
include("config.php");

$crawled = array(); // array to store links that have been retrieved
$newCrawl = array(); // array to store new links
$storedImages = array(); // array to store images that have already been retrieved

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

        $href = createFullUrlLink($href, $url);

        // if statement to recursively crawl links
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
function createFullUrlLink($src, $url) {
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
    global $connection, $storedImages;

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

    $imageList = $parser->findImages();
    foreach($imageList as $image) {
        $src = $image->getAttribute('src');
        $alt = $image->getAttribute('alt');
        $title = $image->getAttribute('title');

        if(!$title && !$alt) {
            continue;
    }

    $src = createFullUrlLink($src, $url);

    if(!in_array($src, $storedImages)) {
        $storedImages[] = $src;

        addImageToDatabase($url, $src, $title, $alt);
    }
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

    removeLinks($url);
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

// function to add images to database
function addImageToDatabase($url, $src, $alt, $title) {
    global $connection;

    try {
        $query = $connection->prepare("INSERT INTO images (pageUrl, title, imageUrl, alt)
                                VALUES (:pageUrl, :title,:imageUrl, :alt )");

        $query->bindParam(":pageUrl", $url);
        $query->bindParam(":title", $title);
        $query->bindParam(":imageUrl", $src);
        $query->bindParam(":alt", $alt);

        return $query->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        return false;
    }
}

// $fourteenURL = "https://www.imdb.com/";
// findLinks($fourteenURL);

// $secondURL = "https://www.bbc.co.uk/";
// findLinks($secondURL);

$thirdURL = "https://www.bbc.co.uk/news/topics/c40rjmqdwr7t";
findLinks($thirdURL);

// $fourthURL = "https://www.sportengland.org/";
// findLinks($fourthURL);

// $fifthURL = "https://www.billboard.com/";
// findLinks($fifthURL);

// $sevenURL = "https://www.menshealth.com/";
// findLinks($sevenURL);

// $nineURL = "https://pitchfork.com/";
// findLinks($nineURL);

// $tenURL = "https://variety.com/";
// findLinks($tenURL);

// $sixteenURL = "https://www.nme.com/";
// findLinks($sixteenURL);

$seventeenURL = "https://www.manchestereveningnews.co.uk/";
findLinks($seventeenURL);

// $seventeenURL = "https://www.standard.co.uk/";
// findLinks($seventeenURL);

// $eighteenURL = "https://www.huffingtonpost.co.uk/";
// findLinks($eighteenURL);

?>
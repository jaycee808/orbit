<?php
include("config.php");
include("./classes/searchResults.php");
include("./classes/imageResults.php");

if (isset($_GET["term"])) {
    $term = $_GET["term"];
} else {
    exit("Please enter a search term");
}

$page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <title>orbit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
</head>
<body>
    <!-- header with logo and search bar -->
    <div id="search-page">
        <div class="search-page-header">
            <div class="search-page-logo">
                <a href="./index.php">
                    orbit
                </a>
            </div>
                <form class="search-page-search-bar" action="search.php" method="GET">
                    <input class="search-page-search-box" type="text" name="term" placeholder="<?php echo $term ?>">
                    <button class="search-page-search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
        </div>

        <!-- image search results -->
        <div id="imageResultsDisplay" class="results-section">
            <div class="images-title">Images</div>
                <div class="imageResults horizontal-scroll">
                    <?php 
                        $imageSearch = new ImageResults($connection);
                        echo $imageSearch->resultsImages($page, 24, $term, 1);
                    ?>
                </div>
        </div>

        <!-- pages search results -->
        <div id="pageResultsDisplay" class="results-section">
            <div class="pages-title">Pages</div>
                <div class="pageResults">
                    <?php
                    $numOfResultsPerPage = 30;
                    $maxPages = 8;
                    $pageIndex = isset($_GET['page']) ? intval($_GET['page']) : 1;

                    $pageSearch = new SearchResults($connection);
                    echo $pageSearch->resultsPages($pageIndex, $numOfResultsPerPage, $term, $maxPages);
                    ?>
                </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-title"></div>&copy; 2024 ORBIT 
    </footer>
</body>
</html>
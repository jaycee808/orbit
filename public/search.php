<?php
include("config.php");
include("./classes/searchResults.php");
include("./classes/imageResults.php");

if (isset($_GET["term"])) {
    $term = $_GET["term"];
} else {
    exit("Please enter a search term");
}

$type = isset($_GET["type"]) ? $_GET["type"] : "pages";
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
                <form class="search-bar" action="search.php" method="GET">
                    <input class="search-box" type="text" name="term" placeholder="<?php echo $term ?>">
                    <button class="search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
        </div>
        <!-- Tabs to filter search results -->
        <div class="search-tabs">
            <ul class="tab-list">
                <li class="<?php echo $type == 'pages' ? 'active' : '' ?>">
                    <a href='<?php echo "search.php?term=$term&type=Pages"; ?>'>
                        Pages
                    </a>
                </li>

                <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                    <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                        Images
                    </a>
                </li>
            </ul>
        </div>
        

        <!-- section to display search results -->
        <div id="resultsDisplay">
            <?php
            $numOfResultsPerPage = 30;
            $maxPages = 10;
            $pageIndex = isset($_GET['page']) ? intval($_GET['page']) : 1;

            // Display page results
            if ($type == "pages") {
                $search = new SearchResults($connection);
                echo $search->resultsPages($pageIndex, $numOfResultsPerPage, $term, $maxPages);
            }

            // Display image results
            else if ($type == "images") {
                $search = new ImageResults($connection);
                echo $search->resultsImages($pageIndex, $numOfResultsPerPage, $term, $maxPages);
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-title"></div>&copy; 2024 ORBIT 
            <div class="footer-links">
                <a href="#">Privacy Policy</a> 
                <a href="#">Terms of Service</a>
            </div>
    </footer>
</body>
</html>
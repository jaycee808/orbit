<?php
include("./classes/searchResults.php");
include("config.php");

$type = isset($_GET["type"]) ? $_GET["type"] : "pages";
$pageIndex = isset($_GET["pageIndex"]) ? $_GET["pageIndex"] : 1;

if(isset($_GET["term"])) {
    $term = $_GET["term"];
} else {
    exit("Please enter a search value");
}

// echo $_GET["term"];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <title>orbit</title>
</head>
<body>
    <!-- header with logo and search bar -->
    <div id="search-page">
        <div class="search-page-header">
            <div id="logo">
                <a href="./index.php">
                    <h1>orbit</h1>
                </a>
            </div>
            <div id="search-bar">
                <form action="search.php" method="GET">
                    <input class="search-box" type="text" name="term" placeholder="<?php echo $term ?>">
                    <button class="search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>

        <!-- tabs to filter search results -->
        <div class="search-tabs">
            <ul class="tab-list">
                <li class="<?php echo $type == 'pages' ? 'active' : '' ?>">
                    <a href='<?php echo "search.php?term=$term&type=pages"; ?>'>
                        Pages
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- section to display search results -->
    <div id="resultsDisplay">
        <?php
        $search = new searchResults($connection);
        $pageIndex = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $numOfResultsPerPage = 20;
        $maxPages = 6;

        echo $search->resultsPages($pageIndex, $numOfResultsPerPage, $term, $maxPages);
        ?>
    </div>
</body>
</html>
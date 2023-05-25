<?php
include "classes/searchResults.php";
include("config.php");
// php code for highlighting active search tab and missing search term
	if(isset($_GET["term"])) {
		$term = $_GET["term"];
	} else {
	exit("Please enter a search value");
	}

	$type = isset($_GET["type"]) ? $_GET["type"] : "pages";

    if(isset($_GET["term"])) {
        $term = $_GET["term"];
    } else {
    exit("Please enter a search value");
    }

    echo $_GET["term"];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
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
            <input class="search-box" type="text" name="term" placeholder="Let's explore">
            <input class="search-button" type="submit" value="Search">
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
            <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                Images
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- section to display search results -->
<div class="search-results-pages">

<?php
	$resultsProvider = new searchResults($conn);

	$numResults = $resultsProvider->getNumResults($term);

	echo "<p class='resultsCount'>$numResults results found</p>";

	echo $resultsProvider->resultsPages(1, 20, $term);
?>

</div>
</body>
</html>
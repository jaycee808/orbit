<!-- php code for highlighting active search tab and missing search term -->
<?php
	if(isset($_GET["term"])) {
		$term = $_GET["term"];
	} else {
	    exit("Please enter a search value");
	}

	$type = isset($_GET["type"]) ? $_GET["type"] : "pages";
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <title>orbit</title>
</head>
<body>
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

        <div class="search-results">
        <!-- php code for prompting user to enter value -->
        <?php
            if(isset($_GET["term"])) {
                $term = $_GET["term"];
            } else {
                exit("Please enter a search value");
            }

            echo $_GET["term"];
        ?>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <title>orbit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
</head>
<body>
    <section id="home-page">
        <div class="logo">orbit</div>
            <div class="tagline">
                search a universe of information
            </div>
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input class="search-box" type="text" name="term" placeholder="Let's explore">
                    <button class="search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="quick-searches">
                <ul class="search-list">
                    <li><a href="search.php?term=politics">Politics</a></li>
                    <li><a href="search.php?term=entertainment">Entertainment</a></li>
                    <li><a href="search.php?term=sport">Sport</a></li>
                    <li><a href="search.php?term=travel">Travel</a></li>
                </ul>
            </div>
    </section>
</body>
</html>

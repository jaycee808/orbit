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
    <section id="home-page">
        <div class="home-page-header">
            <div class="home-page-logo">orbit</div>
                <form class="home-page-search-bar" action="search.php" method="GET">
                    <input class="home-page-search-box" type="text" name="term" placeholder="Let's explore">
                    <button class="home-page-search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
        </div>
            
        <div class="quick-search">
            <div class="quick-search-title">Quick Search</div>
                <ul class="search-list">
                    <li><a href="search.php?term=entertainment">Entertainment</a></li>
                    <li><a href="search.php?term=sport">Sport</a></li>
                    <li><a href="search.php?term=travel">Travel</a></li>
                    <li><a href="search.php?term=politics">Politics</a></li>
                </ul>
        </div>

    </section>
    
    <footer class="footer">
        <div class="footer-title"></div>&copy; 2024 ORBIT 
    </footer>
</body>
</html>
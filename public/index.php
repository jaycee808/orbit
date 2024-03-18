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
        <div class="logo">orbit</div>
            <form class="search-bar" action="search.php" method="GET">
                <input class="search-box" type="text" name="term" placeholder="Let's explore">
                <button class="search-icon" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            
        <div class="quick-search">
            <div class="quick-search-title">Quick Search</div>
            <ul class="search-list">
                <div class="column">
                    <li><a href="search.php?term=entertainment">Entertainment</a></li>
                    <li><a href="search.php?term=sport">Sport</a></li>
                </div>
                <div class="column">
                    <li><a href="search.php?term=travel">Travel</a></li>
                    <li><a href="search.php?term=politics">Politics</a></li>
                </div>
            </ul>
        </div>

    </section>
    
    <footer class="footer">
        <div class="footer-title"></div>&copy; 2024 ORBIT 
            <div class="footer-links">
                <a href="#">Privacy Policy</a> 
                <a href="#">Terms of Service</a>
            </div>
    </footer>
</body>
</html>
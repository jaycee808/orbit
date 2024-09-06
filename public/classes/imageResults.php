<?php
class ImageResults {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getNumResults($term) {
        $query = $this->connection->prepare("SELECT COUNT(*) as total 
                                    FROM images
                                    WHERE title LIKE :term 
                                    OR alt LIKE :term
                                    ORDER BY id DESC");

        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row["total"];
    }

    public function resultsImages($pageIndex, $numOfResultsPerPage, $term, $maxPages) {
        $totalResults = $this->getNumResults($term);
        $totalPages = ceil($totalResults / $numOfResultsPerPage);
    
        $pageIndex = max(min($pageIndex, $totalPages), 1);
        $startPage = max(min($pageIndex - floor($maxPages / 2), $totalPages - $maxPages + 1), 1);
        $endPage = min($startPage + $maxPages - 1, $totalPages);
    
        $displayResults = ($pageIndex - 1) * $numOfResultsPerPage;
    
        $query = $this->connection->prepare("SELECT *
                                    FROM images 
                                    WHERE title LIKE :term 
                                    OR alt LIKE :term
                                    ORDER BY id DESC");
    
        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->execute();
    
        $resultsHtml = "<div class='totalResults'>Total results: $totalResults</div>";
        $resultsHtml .= "<div class='imageResults'>";
    
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $imageUrl = $row["imageUrl"];
            $pageUrl = $row["pageUrl"];
            $title = $row["title"];
            $alt = $row["alt"];
    
            if($title) {
                $displayText = $title;
            } else if($alt) {
                $displayText = $alt;
            } else {
                $displayText = $imageUrl;
            }

            // Limit the display text to 25 characters
            if (mb_strlen($displayText) > 25) {
                $displayText = mb_substr($displayText, 0, 25) . '...';
            }
    
            $resultsHtml .= "<div class='imagesDisplay'>
                                    <a href='$imageUrl'>
                                    <img src='$imageUrl'>
                                    <span class='imageDetails'>$displayText</span>
                                </a>
                            </div>";
        }
    
        $resultsHtml .= "</div>";
    
        return $resultsHtml;
    } 
}
?>
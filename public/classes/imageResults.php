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
        
        $query = $this->connection->prepare("SELECT *
                                    FROM images 
                                    WHERE title LIKE :term 
                                    OR alt LIKE :term
                                    ORDER BY id DESC
                                    LIMIT 48");
    
        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->execute();
    
        $resultsHtml = "<div class='totalResults'>Total results: $totalResults</div>";
        $resultsHtml .= "<div class='imageResults'>";
    
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $imageUrl = $row["imageUrl"];
            $title = $row["title"];
            $alt = $row["alt"];
    
            if(strlen($title) > 25) {
                $displayText = substr($title, 0, 25) . '...';
            } else if(strlen($alt) > 25) {
                $displayText = substr($alt, 0, 25) . '...';
            } else {
                $displayText = substr($imageUrl, 0, 25) . '...';
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
<?php
class SearchResults {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getNumResults($term) {
        $query = $this->connection->prepare("SELECT COUNT(*) as total 
                                    FROM pages WHERE title LIKE :term 
                                    OR url LIKE :term 
                                    OR keywords LIKE :term 
                                    OR description LIKE :term");

        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row["total"];
    }

    public function resultsPages($pageIndex, $numOfResultsPerPage, $term, $maxPages) {
        $totalResults = $this->getNumResults($term);
        $totalPages = ceil($totalResults / $numOfResultsPerPage);

        $pageIndex = max(min($pageIndex, $totalPages), 1);
        $startPage = max(min($pageIndex - floor($maxPages / 2), $totalPages - $maxPages + 1), 1);
        $endPage = min($startPage + $maxPages - 1, $totalPages);

        $displayResults = ($pageIndex - 1) * $numOfResultsPerPage;

        $query = $this->connection->prepare("SELECT * 
                                    FROM pages WHERE title LIKE :term 
                                    OR url LIKE :term 
                                    OR keywords LIKE :term 
                                    OR description LIKE :term
                                    ORDER BY id DESC
                                    LIMIT :displayResults, :numOfResults");

        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->bindParam(":displayResults", $displayResults, PDO::PARAM_INT);
        $query->bindParam(":numOfResults", $numOfResultsPerPage, PDO::PARAM_INT);
        $query->execute();

        $resultsHtml = "<div class='totalResults'>Total results: $totalResults</div>";
        $resultsHtml .= "<div class='searchResults'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $url = $row["url"];
            $title = $row["title"];
            $description = $row["description"];

            $url = $this->trimField($url, 30);
            $description = $this->trimField($description, 200);

            $resultsHtml .=
                "<div class='resultContainer'>
                    <div class='resultDetail'>
                        <div class='title'>
                            <a class='result' href='$url'>
                                $title
                            </a>
                        </div>
                        <span class='url'>$url</span><br />
                        <span class='description'>$description</span>
                    </div>
                </div>";
        }

        $resultsHtml .= "</div>";

        $resultsHtml .= "<div class='pagination'>";
        for ($page = $startPage; $page <= $endPage; $page++) {
            $resultsHtml .= "<a href='search.php?term=$term&page=$page'>$page</a>";
        }
        $resultsHtml .= "</div>";

        return $resultsHtml;
    }

    private function trimField($string, $characterLimit) {
        $dots = strlen($string) > $characterLimit ? "..." : "";
        return substr($string, 0, $characterLimit) . $dots;
    }
}
?>
<?php
class searchResults {

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

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];
	}

	public function resultsPages($pageIndex, $numOfResults, $term) {

		$displayResults = ($pageIndex - 1) * $numOfResults;

		$query = $this->connection->prepare("SELECT * 
									FROM pages WHERE title LIKE :term 
									OR url LIKE :term 
									OR keywords LIKE :term 
									OR description LIKE :term
									ORDER BY clicks DESC
									LIMIT :displayResults, :numOfResults");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":displayResults", $displayResults, PDO::PARAM_INT);
		$query->bindParam(":numOfResults", $numOfResults, PDO::PARAM_INT);
		$query->execute();

		$resultsHtml = "<div class='searchResults'>";

		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
			$url = $row["url"];
			$title = $row["title"];
			$description = $row["description"];
			
			$resultsHtml .= 
			"<div class='resultContainer'>
				<h3 class='title'>
					<a class='result' href='$url'>
					$title
					</a>
				</h3>
					<span class='url'>$url</span>
					<span class='description'>$description</span>
			</div>";
		}

		$resultsHtml .= "</div>";

		return $resultsHtml;
	}
}
?>
<?php
class DomDocumentParser {
    private $doc;

    public function __construct($url) {
        echo "URL: $url";

        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "User-Agent: MyWebCrawler/1.0\r\n"
            )
        );

        $context = stream_context_create($options);
        $html = file_get_contents($url, false, $context);

        if ($html === false) {
            throw new Exception("Failed to retrieve HTML from the URL.");
        }

        $this->doc = new DomDocument();
        libxml_use_internal_errors(true); // Suppress HTML parsing errors
        $this->doc->loadHTML($html);
        libxml_clear_errors(); // Clear any HTML parsing errors
    }

    // function to get links to pages by anchor tags
    public function getLinks() {
        return $this->doc->getElementsByTagName('a');
    }

    // function to get title tags
    public function retrieveTitles() {
        return $this->doc->getElementsByTagName('title');
    }

    // function to get meta tags
    public function retrieveMetaTags() {
        return $this->doc->getElementsByTagName('meta');
    }
}

?>
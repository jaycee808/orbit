<?php
    class DomDocumentParser {
        private $doc;

        public function __construct($url) {
            echo "URL: $url";

            $foundPages = array(
                'http'=>array('method'=>'GET', 'header'=>"User-Agent: orbit/1.2\n")
            );

            $context = stream_context_create($foundPages);

            $this->doc = new DomDocument();
            @$this->doc->loadHTML(file_get_contents($url, false, $context));
        }

        // function to get links to pages by anchor tags
        public function getLinks() {
            return $this->doc->getElementsByTagName('a');
        }

        // function to get title tags
        public function retrieveTitles() {
            return $this->doc->getElementsByTagName('title');
        }
    }
?>
<?php
class NewsCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        @list(, $url, $limit) = $args;

        //Validate args
        if (empty($limit)) {
            $limit = 20;
        }


        // Use args
        $feed = simplexml_load_file($url);
        if (!$feed instanceOf SimpleXMLElement) {
            throw new Exception("URL did not give me valid XML - check this is the ATOM feed you want\n" . $url);
        }

        $feed->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');

        $entries = $feed->xpath('//atom:entry');

        $conn->message($data['from'], "Hello! We've got new Atom entries (" . count($entries) . "), showing up to " . $limit);


        // Whee
        $entries = $feed->xpath('//atom:entry');
        foreach ($entries as $n => $entry) {
             $conn->message($data['from'], (string)$entry->title . " by " . (string)$entry->author->name);
             $conn->message($data['from'], "More: " . (string)$entry->link);
        }

        return true;
    }
}
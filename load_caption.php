<?php
require_once('../../config.php');

function encodeUrl($url) {
    if (urldecode($url) !== $url) {
        return $url; // Return as-is if already encoded
    }

    return preg_replace_callback(
        '/%2F|%3A/',  // Match encoded `/` (%2F) and `:` (%3A)
        function ($matches) {
            return urldecode($matches[0]); // Decode them back
        },
        rawurlencode($url)
    );
}

$file = $_GET['file'];
$content = file_get_contents(encodeUrl($file));
echo $content;
<?php
$url = "https://rtd.kv.banenor.no/web_client/std?station=LIE&header=yes&content=track&track=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);

// echo strval($data);

if (strpos($platform1, 'Passerende') !== false || strpos($platform2, 'Passerende') !== false || strpos($platform3, 'Passerende') !== false || strpos($platform4, 'Passerende') !== false || strpos($platform5, 'Passerende') !== false) {
    echo 'Passing';
} else {
    echo "not passing";
}

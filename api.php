<?php
require_once 'vendor/autoload.php';
$client = new GuzzleHttp\Client();

$translate = function (string $text, string $to = 'tr', string $from = 'auto') use ($client): string {
    $text = urlencode($text);
    $from = strtolower($from);
    $to = strtolower($to);

    $response = $client->request('GET', 'https://translate.googleapis.com/translate_a/single?', [
        'query' => 'client=dict-chrome-ex&sl=' . $from . '&tl=' . $to . '&dt=t&q=' . $text,
        'headers' => [
            'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
        ]
    ]);

    if ($response->getStatusCode() === 200 && $response->getReasonPhrase() === 'OK') {
        $result = json_decode($response->getBody(), true);
        $res = array();
        if (isset($result) && !empty($result)) {
            for ($i = 0; $i < count($result[0]); $i++) {
                array_push($res, $result[0][$i][0]);
            }
            return htmlspecialchars(implode('', $res));
        }
    }
};

echo $translate(htmlspecialchars($_POST['text']), $_POST['language']);

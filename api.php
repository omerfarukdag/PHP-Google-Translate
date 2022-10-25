<?php
$translate = function (string $text, string $to = 'tr', string $from = 'auto'): string {
    $text = urlencode($text);
    $from = strtolower($from);
    $to = strtolower($to);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://translate.googleapis.com/translate_a/single?client=dict-chrome-ex&sl=' . $from . '&tl=' . $to . '&dt=t&q=' . $text);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result) && !empty($result)) {
        for ($i = 0; $i < count($result[0]); $i++) {
            $data[] = $result[0][$i][0];
        }
        return htmlspecialchars(implode('', $data));
    }
};

echo json_encode(['text' => $translate(htmlspecialchars($_POST['text']), $_POST['language'])]);
?>
<?php
// send.php - simple example to forward booking info to Telegram
// IMPORTANT: Replace the placeholders below with real values and move this file
// outside the public webroot or protect it with server-side secrets in production.

// --- CONFIGURE THESE ---
$BOT_TOKEN = '7640165388:AAG2syUWjj_gwTWi6PaytGnzZucPrXO7-to'; // bot token from BotFather
$SECRET_KEY = '123'; // must match the secret sent from client
$CHAT_IDS = [
    // configured chat IDs — replace or add more as needed
    '7724955797',
    '6236306144',
];
// -----------------------

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if($method !== 'POST'){
    echo json_encode(['ok'=>false,'error'=>'Only POST allowed']);
    exit;
}

$secret = $_POST['secret'] ?? '';
if(!$secret || $secret !== $SECRET_KEY){
    echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
    exit;
}

$name = strip_tags(trim($_POST['name'] ?? ''));
$phone = strip_tags(trim($_POST['phone'] ?? ''));
$members = intval($_POST['members'] ?? 0);
$date = strip_tags(trim($_POST['date'] ?? ''));
$message = strip_tags(trim($_POST['message'] ?? ''));
$chat_ids_input = trim($_POST['chat_ids'] ?? '');

// Merge configured chat ids with any additional provided (admins only)
if($chat_ids_input){
    $extra = array_map('trim', explode(',', $chat_ids_input));
    foreach($extra as $c){ if($c) $CHAT_IDS[] = $c; }
}

if(empty($CHAT_IDS)){
    echo json_encode(['ok'=>false,'error'=>'No chat IDs configured on server']);
    exit;
}

$text = "New booking from Hum Aura Decor Studio:\n" .
        "Name: $name\nPhone: $phone\nMembers: $members\nDate: $date\nMessage: $message";

foreach(array_unique($CHAT_IDS) as $chat_id){
    $payload = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    $url = "https://api.telegram.org/bot" . $BOT_TOKEN . "/sendMessage";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    // Do not reveal token/errors to client in production. Logging recommended.
}

echo json_encode(['ok'=>true]);

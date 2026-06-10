<?php

$db = new PDO('mysql:host=localhost;dbname=evently', 'root', '');
$stmt = $db->query("SELECT uuid FROM invitations LIMIT 1");
$uuid = $stmt->fetchColumn();

$ch = curl_init('http://evently.test/i/' . $uuid . '/rsvp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['is_attending' => true]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

file_put_contents('curl_test.txt', "HTTP: $httpcode\nResponse: $response\n");
echo "Done";

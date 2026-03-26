<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Требуется авторизация']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Не указан ID']);
    exit;
}

$id = (int)$data['id'];

$flats = [];
if (file_exists('../data/flats.json')) {
    $flats = json_decode(file_get_contents('../data/flats.json'), true);
}

$found = false;
$new_flats = [];

foreach ($flats as $flat) {
    if ($flat['id'] === $id) {
        if ($flat['user_id'] !== $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Нет прав на удаление']);
            exit;
        }
        $found = true;
        continue;
    }
    $new_flats[] = $flat;
}

if (!$found) {
    http_response_code(404);
    echo json_encode(['error' => 'Квартира не найдена']);
    exit;
}

file_put_contents('../data/flats.json', json_encode($new_flats, JSON_PRETTY_PRINT));

echo json_encode(['success' => true]);
?>
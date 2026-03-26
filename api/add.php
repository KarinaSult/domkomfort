<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Требуется авторизация']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Неверные данные']);
    exit;
}

$title = trim($data['title'] ?? '');
$address = trim($data['address'] ?? '');
$price = (int)($data['price'] ?? 0);
$rent_price = (int)($data['rent_price'] ?? 0);
$rooms = (int)($data['rooms'] ?? 0);
$area = (float)($data['area'] ?? 0);
$type = $data['type'] ?? 'sale';
$phone = trim($data['phone'] ?? '');
$description = trim($data['description'] ?? '');

if (empty($title) || empty($address) || $price <= 0 || $rooms <= 0 || $area <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Заполните все поля']);
    exit;
}

$flats = [];
if (file_exists('../data/flats.json')) {
    $flats = json_decode(file_get_contents('../data/flats.json'), true);
}

$new_flat = [
    'id' => count($flats) + 1,
    'title' => $title,
    'address' => $address,
    'price' => $price,
    'rent_price' => $rent_price,
    'rooms' => $rooms,
    'area' => $area,
    'type' => $type,
    'phone' => $phone,
    'description' => $description,
    'image' => '',
    'user_id' => $_SESSION['user_id']
];

$flats[] = $new_flat;
file_put_contents('../data/flats.json', json_encode($flats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true, 'flat' => $new_flat]);
?>
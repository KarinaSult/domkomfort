<?php
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo '<div style="text-align: center; padding: 50px;">';
    echo '<h2>Доступ запрещен</h2>';
    echo '<p>Эта страница только для администраторов</p>';
    echo '<a href="index.php" class="btn">На главную</a>';
    echo '</div>';
    include 'footer.php';
    exit;
}

function uploadImage($file) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;
    if ($file["size"] > 5000000) return false;
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") return false;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "uploads/" . $new_filename;
    }
    return false;
}

$flats = [];
if (file_exists('data/flats.json')) {
    $flats = json_decode(file_get_contents('data/flats.json'), true);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $rent_price = $_POST['rent_price'] ?? 0;
    $rooms = $_POST['rooms'];
    $area = $_POST['area'];
    $type = $_POST['type'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadImage($_FILES['image']);
        if ($image === false) {
            $message = '<div style="background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0;">Ошибка загрузки изображения</div>';
        }
    }
    
    if (empty($message)) {
        $new_flat = [
            'id' => count($flats) + 1,
            'title' => $title,
            'address' => $address,
            'price' => (int)$price,
            'rent_price' => (int)$rent_price,
            'rooms' => (int)$rooms,
            'area' => (float)$area,
            'type' => $type,
            'phone' => $phone,
            'description' => $description,
            'image' => $image,
            'user_id' => $_SESSION['user_id'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $flats[] = $new_flat;
        file_put_contents('data/flats.json', json_encode($flats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $message = '<div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px 0;">Квартира добавлена!</div>';
        $flats = json_decode(file_get_contents('data/flats.json'), true);
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $new_flats = [];
    foreach ($flats as $flat) {
        if ($flat['id'] != $id) {
            $new_flats[] = $flat;
        }
    }
    file_put_contents('data/flats.json', json_encode($new_flats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: admin.php');
    exit;
}
?>

<style>
    h1, h2, label {
        color: white;
    }
    .form-control {
        background: white;
        color: #2d3436;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        width: 100%;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .admin-card {
        background: white;
        padding: 15px;
        margin: 10px 0;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .add-form {
        background: rgba(255,255,255,0.1);
        padding: 25px;
        border-radius: 15px;
        margin-top: 20px;
    }
</style>

<h1 style="color: white;">Админ-панель</h1>

<?php echo $message; ?>

<div style="margin: 20px 0;">
    <a href="admin.php" class="btn">📋 Список квартир</a>
    <a href="admin.php?add=1" class="btn">➕ Добавить квартиру</a>
</div>

<?php if (isset($_GET['add'])): ?>

<h2 style="color: white;">➕ Добавить квартиру</h2>
<div class="add-form">
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label style="color: white;">Название квартиры *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: white;">Адрес *</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: white;">Тип *</label>
                <select name="type" class="form-control">
                    <option value="sale">🏠 Продажа</option>
                    <option value="rent">🔑 Аренда</option>
                </select>
            </div>
            <div class="form-group">
                <label style="color: white;">Цена продажи (₽)</label>
                <input type="number" name="price" class="form-control" placeholder="Если аренда, оставьте 0">
            </div>
            <div class="form-group">
                <label style="color: white;">Цена аренды (₽/мес)</label>
                <input type="number" name="rent_price" class="form-control" placeholder="Если продажа, оставьте 0">
            </div>
            <div class="form-group">
                <label style="color: white;">Количество комнат *</label>
                <input type="number" name="rooms" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: white;">Площадь (м²) *</label>
                <input type="number" step="0.1" name="area" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="color: white;">Контактный телефон *</label>
                <input type="text" name="phone" class="form-control" value="+7 (999) 123-45-67" required>
            </div>
        </div>
        
        <div class="form-group">
            <label style="color: white;">📷 Фото квартиры</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small style="color: #ccc;">Форматы: JPG, PNG. Макс. размер: 5MB</small>
        </div>
        
        <div class="form-group">
            <label style="color: white;">📝 Описание</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Подробное описание квартиры..."></textarea>
        </div>
        
        <button type="submit" name="add" class="btn" style="margin-top: 15px;">💾 Сохранить квартиру</button>
        <a href="admin.php" class="btn btn-outline" style="margin-top: 15px;">❌ Отмена</a>
    </form>
</div>

<?php else: ?>

<h2 style="color: white;">📋 Все квартиры (<?php echo count($flats); ?>)</h2>

<?php foreach ($flats as $flat): ?>
    <div class="admin-card">
        <div style="display: flex; gap: 15px; align-items: center;">
            <?php if (!empty($flat['image']) && file_exists($flat['image'])): ?>
                <img src="<?php echo $flat['image']; ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
            <?php else: ?>
                <div style="width: 80px; height: 60px; background: #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center;">📷</div>
            <?php endif; ?>
            <div>
                <strong style="color: #2d3436;"><?php echo htmlspecialchars($flat['title']); ?></strong><br>
                <span style="color: #636e72;"><?php echo htmlspecialchars($flat['address']); ?></span><br>
                <?php if ($flat['type'] == 'sale'): ?>
                    <strong style="color: #0984e3;"><?php echo number_format($flat['price'], 0, '.', ' '); ?> ₽</strong>
                <?php else: ?>
                    <strong style="color: #00b894;"><?php echo number_format($flat['rent_price'], 0, '.', ' '); ?> ₽/мес</strong>
                <?php endif; ?>
                | 🛏️ <?php echo $flat['rooms']; ?> комн. | 📐 <?php echo $flat['area']; ?> м²
            </div>
        </div>
        <div>
            <a href="admin.php?delete=<?php echo $flat['id']; ?>" class="btn btn-danger" onclick="return confirm('Удалить квартиру?')">🗑 Удалить</a>
        </div>
    </div>
<?php endforeach; ?>

<?php endif; ?>

<?php include 'footer.php'; ?>
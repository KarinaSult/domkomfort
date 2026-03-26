<?php
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function uploadImage($file) {
    $target_dir = "../uploads/";
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

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_flat'])) {
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
    }
    
    $flats = [];
    if (file_exists('../data/flats.json')) {
        $flats = json_decode(file_get_contents('../data/flats.json'), true);
    }
    
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
        'user_name' => $_SESSION['user_login'],
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $flats[] = $new_flat;
    file_put_contents('../data/flats.json', json_encode($flats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = '<div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">✅ Квартира добавлена!</div>';
}

$flats = [];
if (file_exists('../data/flats.json')) {
    $all_flats = json_decode(file_get_contents('../data/flats.json'), true);
    foreach ($all_flats as $flat) {
        if ($flat['user_id'] == $_SESSION['user_id']) {
            $flats[] = $flat;
        }
    }
}
?>

<style>
    .cabinet-container {
        color: white;
    }
    
    .cabinet-container h1,
    .cabinet-container h2,
    .cabinet-container h3,
    .cabinet-container p,
    .cabinet-container label,
    .cabinet-container strong {
        color: white;
    }
    
    .cabinet-container .card {
        background: white;
    }
    
    .cabinet-container .card h3,
    .cabinet-container .card p,
    .cabinet-container .card .card-price {
        color: #2d3436;
    }
    
    .cabinet-container .form-control,
    .cabinet-container input,
    .cabinet-container textarea,
    .cabinet-container select {
        background: white;
        color: #2d3436;
    }
    
    .cabinet-container .btn-add {
        background: linear-gradient(135deg, #0984e3, #6c5ce7);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 30px;
        cursor: pointer;
        font-size: 16px;
        margin: 20px 0;
    }
    
    .cabinet-container .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(9,132,227,0.3);
    }
</style>

<div class="cabinet-container">

<h1>Личный кабинет</h1>
<p>Добро пожаловать, <strong><?php echo htmlspecialchars($_SESSION['user_login']); ?></strong>!</p>

<?php echo $message; ?>

<button onclick="document.getElementById('addForm').style.display='block'" class="btn-add">
    + Добавить квартиру
</button>

<div id="addForm" style="display: none; background: white; padding: 30px; border-radius: 20px; margin: 20px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <h3 style="color: #2d3436; margin-bottom: 20px;">Добавить новую квартиру</h3>
    
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="color: #2d3436;">Название *</label><br>
                <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Адрес *</label><br>
                <input type="text" name="address" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Тип *</label><br>
                <select name="type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="sale">Продажа</option>
                    <option value="rent">Аренда</option>
                </select>
            </div>
            
            <div>
                <label style="color: #2d3436;">Цена продажи (₽)</label><br>
                <input type="number" name="price" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Цена аренды (₽/мес)</label><br>
                <input type="number" name="rent_price" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Комнат *</label><br>
                <input type="number" name="rooms" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Площадь (м²) *</label><br>
                <input type="number" step="0.1" name="area" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div>
                <label style="color: #2d3436;">Телефон *</label><br>
                <input type="text" name="phone" value="+7 (999) 123-45-67" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
        </div>
        
        <div style="margin: 20px 0;">
            <label style="color: #2d3436;">Описание</label><br>
            <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"></textarea>
        </div>
        
        <div style="margin: 20px 0;">
            <label style="color: #2d3436;">Фото квартиры</label><br>
            <input type="file" name="image" accept="image/*">
        </div>
        
        <button type="submit" name="add_flat" class="btn" style="background: linear-gradient(135deg, #0984e3, #6c5ce7); color: white;">Сохранить</button>
        <button type="button" onclick="document.getElementById('addForm').style.display='none'" class="btn btn-outline" style="background: transparent; border: 2px solid #0984e3; color: #0984e3;">Отмена</button>
    </form>
</div>

<h2>Мои квартиры (<?php echo count($flats); ?>)</h2>

<?php if (empty($flats)): ?>
    <p>У вас пока нет добавленных квартир.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($flats as $flat): ?>
            <div class="card">
                <?php if (!empty($flat['image'])): ?>
                    <img src="/Курсовая/<?php echo $flat['image']; ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 20px 20px 0 0;">
                <?php else: ?>
                    <div style="height: 200px; background: #ddd; display: flex; align-items: center; justify-content: center;">Нет фото</div>
                <?php endif; ?>
                
                <div style="padding: 20px;">
                    <h3><?php echo htmlspecialchars($flat['title']); ?></h3>
                    <p><?php echo htmlspecialchars($flat['address']); ?></p>
                    
                    <?php if ($flat['type'] == 'sale'): ?>
                        <div class="card-price"><?php echo number_format($flat['price'], 0, '.', ' '); ?> ₽</div>
                    <?php else: ?>
                        <div class="card-price" style="color: #00b894;"><?php echo number_format($flat['rent_price'], 0, '.', ' '); ?> ₽/мес</div>
                    <?php endif; ?>
                    
                    <p>📞 <?php echo htmlspecialchars($flat['phone']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div>

<script>
function showForm() {
    document.getElementById('addForm').style.display = 'block';
}
</script>

<?php include '../footer.php'; ?>
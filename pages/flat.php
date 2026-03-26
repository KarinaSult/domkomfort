<?php
include '../header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$flats = [];
if (file_exists('../data/flats.json')) {
    $flats = json_decode(file_get_contents('../data/flats.json'), true);
}

$flat = null;
foreach ($flats as $item) {
    if ($item['id'] == $id) {
        $flat = $item;
        break;
    }
}

if (!$flat) {
    echo "<h1 style='text-align: center;'>Квартира не найдена</h1>";
    echo "<div style='text-align: center;'><a href='catalog.php' class='btn'>Вернуться</a></div>";
    include '../footer.php';
    exit;
}
?>

<div style="background: white; border-radius: 24px; padding: 40px; margin: 40px 0; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        
        <div>
            <?php if (!empty($flat['image'])): ?>
                <img src="/Курсовая/<?php echo $flat['image']; ?>" style="width: 100%; border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <?php else: ?>
                <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #74b9ff, #a29bfe); display: flex; align-items: center; justify-content: center; color: white; font-size: 64px; border-radius: 16px;">
                    🏢
                </div>
            <?php endif; ?>
        </div>
        
        <div>
            <?php if ($flat['type'] == 'sale'): ?>
                <span style="background: #0984e3; color: white; padding: 5px 15px; border-radius: 20px; display: inline-block;">Продажа</span>
            <?php else: ?>
                <span style="background: #00b894; color: white; padding: 5px 15px; border-radius: 20px; display: inline-block;">Аренда</span>
            <?php endif; ?>
            
            <h1 style="font-size: 32px; margin: 20px 0; color: #2d3436;"><?php echo htmlspecialchars($flat['title']); ?></h1>
            <p style="font-size: 18px; color: #636e72;">📍 <?php echo htmlspecialchars($flat['address']); ?></p>
            
            <div style="display: flex; gap: 20px; margin: 30px 0;">
                <div style="background: #f0f0f0; padding: 15px 25px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 24px;">🛏️</div>
                    <div style="font-weight: bold;"><?php echo $flat['rooms']; ?> комн.</div>
                </div>
                <div style="background: #f0f0f0; padding: 15px 25px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 24px;">📐</div>
                    <div style="font-weight: bold;"><?php echo $flat['area']; ?> м²</div>
                </div>
            </div>
            
            <?php if ($flat['type'] == 'sale'): ?>
                <div style="font-size: 36px; font-weight: bold; color: #0984e3; margin: 20px 0;">
                    <?php echo number_format($flat['price'], 0, '.', ' '); ?> ₽
                </div>
            <?php else: ?>
                <div style="font-size: 36px; font-weight: bold; color: #00b894; margin: 20px 0;">
                    <?php echo number_format($flat['rent_price'], 0, '.', ' '); ?> ₽/мес
                </div>
            <?php endif; ?>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin: 20px 0;">
                <p style="font-size: 20px; font-weight: bold;">📞 <?php echo htmlspecialchars($flat['phone'] ?? '+7 (999) 123-45-67'); ?></p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3 style="margin-bottom: 10px; color: #2d3436;">Описание</h3>
                <p style="line-height: 1.8; color: #636e72;"><?php echo nl2br(htmlspecialchars($flat['description'] ?? 'Описание отсутствует')); ?></p>
            </div>
            
            <div style="display: flex; gap: 20px; margin-top: 30px;">
                <a href="#" class="btn" style="flex: 1; text-align: center;" onclick="alert('Заявка отправлена! Скоро перезвоним.'); return false;">Записаться на просмотр</a>
                <a href="catalog.php" class="btn btn-outline" style="flex: 1; text-align: center;">← Назад</a>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
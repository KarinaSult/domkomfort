<?php
include '../header.php';

$flats = [];
if (file_exists('../data/flats.json')) {
    $flats = json_decode(file_get_contents('../data/flats.json'), true);
}
?>

<h1 style="font-size: 48px; margin: 60px 0 30px;">Каталог квартир</h1>

<div class="grid">
    <?php if (empty($flats)): ?>
        <p style="grid-column: span 3; text-align: center;">Пока нет квартир</p>
    <?php else: ?>
        <?php foreach ($flats as $flat): ?>
            <div class="card">
                <?php if (!empty($flat['image'])): ?>
                    <img src="/Курсовая/<?php echo $flat['image']; ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                <?php else: ?>
                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #74b9ff, #a29bfe); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                        🏢
                    </div>
                <?php endif; ?>
                
                <div style="padding: 20px;">
                    <h3 style="font-size: 20px; margin-bottom: 5px;"><?php echo htmlspecialchars($flat['title']); ?></h3>
                    <p style="color: #666; margin-bottom: 10px;">📍 <?php echo htmlspecialchars($flat['address']); ?></p>
                    
                    <div style="display: flex; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                        <?php if ($flat['type'] == 'sale'): ?>
                            <span style="background: #0984e3; color: white; padding: 3px 10px; border-radius: 15px; font-size: 14px;">Продажа</span>
                        <?php else: ?>
                            <span style="background: #00b894; color: white; padding: 3px 10px; border-radius: 15px; font-size: 14px;">Аренда</span>
                        <?php endif; ?>
                        <span style="background: #6c5ce7; color: white; padding: 3px 10px; border-radius: 15px; font-size: 14px;">🛏️ <?php echo $flat['rooms']; ?> комн.</span>
                        <span style="background: #e17055; color: white; padding: 3px 10px; border-radius: 15px; font-size: 14px;">📐 <?php echo $flat['area']; ?> м²</span>
                    </div>
                    
                    <?php if ($flat['type'] == 'sale'): ?>
                        <p style="font-size: 24px; font-weight: bold; color: #0984e3; margin: 15px 0;">
                            <?php echo number_format($flat['price'], 0, '.', ' '); ?> ₽
                        </p>
                    <?php else: ?>
                        <p style="font-size: 24px; font-weight: bold; color: #00b894; margin: 15px 0;">
                            <?php echo number_format($flat['rent_price'], 0, '.', ' '); ?> ₽/мес
                        </p>
                    <?php endif; ?>
                    
                    <p style="margin: 10px 0; font-size: 16px;">
                        <strong>📞 <?php echo htmlspecialchars($flat['phone'] ?? '+7 (999) 123-45-67'); ?></strong>
                    </p>
                    
                    <p style="color: #666; margin: 15px 0; font-size: 14px; line-height: 1.5;">
                        <?php echo htmlspecialchars(mb_substr($flat['description'] ?? 'Описание отсутствует', 0, 100)); ?>...
                    </p>
                    
                    <a href="flat.php?id=<?php echo $flat['id']; ?>" class="btn" style="width: 100%; text-align: center;">Подробнее</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../footer.php'; ?>
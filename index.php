<?php
include 'header.php';

$flats = [];
if (file_exists('data/flats.json')) {
    $flats = json_decode(file_get_contents('data/flats.json'), true);
}
$latest_flats = array_slice($flats, -3);
?>

<div style="background: linear-gradient(135deg, rgba(9,132,227,0.85), rgba(108,92,231,0.85)), url('/Курсовая/uploads/hero-bg.jpg'); background-size: cover; background-position: center; border-radius: 24px; margin: 30px 0 40px; padding: 60px 40px; text-align: center; color: white;">
    <h1 style="font-size: 48px; margin-bottom: 20px;">ДомКомфорт - найди свою идеальную квартиру</h1>
    <p style="font-size: 20px; margin-bottom: 30px;">Более 1000 объектов в базе. Поможем с выбором.</p>
    <a href="pages/catalog.php" class="btn" style="background: white; color: #0984e3;">Смотреть каталог</a>
    
    <div style="display: flex; justify-content: center; gap: 60px; margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 40px;">
        <div><div style="font-size: 36px; font-weight: 700;">1000+</div><div>Квартир</div></div>
        <div><div style="font-size: 36px; font-weight: 700;">500+</div><div>Довольных клиентов</div></div>
        <div><div style="font-size: 36px; font-weight: 700;">10+</div><div>Банков-партнеров</div></div>
    </div>
</div>

<div style="background: white; border-radius: 24px; padding: 40px; margin: 40px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 10px; color: #2d3436;">🏦 Ипотечный калькулятор 2026</h2>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Актуальная ставка 20.5% годовых</p>
    
    <div style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label style="font-weight: 600;">Стоимость квартиры</label>
            <span id="priceValue" style="color: #0984e3; font-weight: 600;">5 000 000 ₽</span>
        </div>
        <input type="range" id="priceRange" min="1000000" max="30000000" step="500000" value="5000000" style="width: 100%;" oninput="updateMortgage()">
    </div>
    
    <div style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label style="font-weight: 600;">Первоначальный взнос</label>
            <span id="initialValue" style="color: #0984e3; font-weight: 600;">20% (1 000 000 ₽)</span>
        </div>
        <input type="range" id="initialRange" min="10" max="50" step="1" value="20" style="width: 100%;" oninput="updateMortgage()">
    </div>
    
    <div style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label style="font-weight: 600;">Срок кредита</label>
            <span id="yearsValue" style="color: #0984e3; font-weight: 600;">20 лет</span>
        </div>
        <input type="range" id="yearsRange" min="5" max="30" step="1" value="20" style="width: 100%;" oninput="updateMortgage()">
    </div>
    
    <div style="margin-top: 30px; background: #f8f9fa; border-radius: 20px; padding: 25px;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <div style="color: #666;">Ежемесячный платёж</div>
                <div id="paymentResult" style="font-size: 28px; font-weight: 700; color: #0984e3;">0 ₽</div>
            </div>
            <div>
                <div style="color: #666;">Сумма кредита</div>
                <div id="creditResult" style="font-size: 20px; font-weight: 600;">0 ₽</div>
            </div>
            <div>
                <div style="color: #666;">Первоначальный взнос</div>
                <div id="initialResult">0 ₽ (0%)</div>
            </div>
            <div>
                <div style="color: #666;">Ставка 2026</div>
                <div style="color: #e74c3c;">20.5%</div>
            </div>
        </div>
    </div>
</div>

<h2 style="font-size: 32px; margin: 40px 0 20px; color: white;">Последние предложения</h2>

<div class="grid">
    <?php if (empty($latest_flats)): ?>
        <p style="grid-column: span 3; text-align: center; color: white;">Пока нет квартир</p>
    <?php else: ?>
        <?php foreach ($latest_flats as $flat): ?>
            <div class="card">
                <?php if (!empty($flat['image'])): ?>
                    <img src="/Курсовая/<?php echo $flat['image']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div style="height: 200px; background: linear-gradient(135deg, #74b9ff, #a29bfe); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">🏢</div>
                <?php endif; ?>
                <div style="padding: 20px; background: white;">
                    <h3><?php echo htmlspecialchars($flat['title']); ?></h3>
                    <p style="color: #666;"><?php echo htmlspecialchars($flat['address']); ?></p>
                    <?php if ($flat['type'] == 'sale'): ?>
                        <div class="card-price"><?php echo number_format($flat['price'], 0, '.', ' '); ?> ₽</div>
                    <?php else: ?>
                        <div class="card-price" style="color: #00b894;"><?php echo number_format($flat['rent_price'], 0, '.', ' '); ?> ₽/мес</div>
                    <?php endif; ?>
                    <a href="pages/flat.php?id=<?php echo $flat['id']; ?>" class="btn" style="width: 100%;">Подробнее</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function updateMortgage() {
    let price = parseFloat(document.getElementById('priceRange').value);
    let initialPercent = parseFloat(document.getElementById('initialRange').value);
    let years = parseFloat(document.getElementById('yearsRange').value);
    let rate = 20.5;
    
    document.getElementById('priceValue').innerText = price.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('initialValue').innerText = initialPercent + '% (' + Math.round(price * initialPercent / 100).toLocaleString('ru-RU') + ' ₽)';
    document.getElementById('yearsValue').innerText = years + ' лет';
    
    let initialAmount = price * (initialPercent / 100);
    let creditAmount = price - initialAmount;
    let months = years * 12;
    let monthRate = rate / 100 / 12;
    
    let payment = 0;
    if (monthRate > 0) {
        payment = creditAmount * (monthRate * Math.pow(1 + monthRate, months)) / (Math.pow(1 + monthRate, months) - 1);
    } else {
        payment = creditAmount / months;
    }
    
    document.getElementById('paymentResult').innerText = Math.round(payment).toLocaleString('ru-RU') + ' ₽';
    document.getElementById('creditResult').innerText = Math.round(creditAmount).toLocaleString('ru-RU') + ' ₽';
    document.getElementById('initialResult').innerText = Math.round(initialAmount).toLocaleString('ru-RU') + ' ₽ (' + initialPercent + '%)';
}

updateMortgage();
</script>

<style>
input[type="range"] {
    -webkit-appearance: none;
    height: 6px;
    background: #e0e0e0;
    border-radius: 5px;
    outline: none;
}
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    background: #0984e3;
    border-radius: 50%;
    cursor: pointer;
}
input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}
</style>

<?php include 'footer.php'; ?>
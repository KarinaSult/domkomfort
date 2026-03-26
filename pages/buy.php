<?php
include '../header.php';

$price = isset($_GET['price']) ? (int)$_GET['price'] : 5000000;
$initial = isset($_GET['initial']) ? (int)$_GET['initial'] : 20;
$years = isset($_GET['years']) ? (int)$_GET['years'] : 20;
$rate = 20.5;

$initial_amount = $price * ($initial / 100);
$credit_amount = $price - $initial_amount;
$months = $years * 12;
$month_rate = $rate / 100 / 12;

if ($month_rate > 0) {
    $payment = $credit_amount * ($month_rate * pow(1 + $month_rate, $months)) / (pow(1 + $month_rate, $months) - 1);
} else {
    $payment = $credit_amount / $months;
}

$total_payment = $payment * $months;
$overpayment = $total_payment - $credit_amount;
?>

<div style="max-width: 800px; margin: 50px auto;">
    <h1 style="text-align: center; margin-bottom: 10px;">Ипотечный калькулятор</h1>
    <p style="text-align: center; color: #666; margin-bottom: 40px;">Рассчитайте ежемесячный платёж за 1 минуту</p>
    
    <div style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05);">
        
        <form method="GET" id="mortgageForm">
            
            <div style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <label style="font-weight: 600;">Стоимость квартиры</label>
                    <span id="priceValue" style="color: #0984e3; font-weight: 600;"><?php echo number_format($price, 0, '.', ' '); ?> ₽</span>
                </div>
                <input type="range" name="price" min="1000000" max="30000000" step="500000" value="<?php echo $price; ?>" 
                       style="width: 100%;" oninput="updateValue('price', this.value)">
            </div>
            
            <div style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <label style="font-weight: 600;">Первоначальный взнос</label>
                    <span id="initialValue" style="color: #0984e3; font-weight: 600;"><?php echo $initial; ?>% (<?php echo number_format($initial_amount, 0, '.', ' '); ?> ₽)</span>
                </div>
                <input type="range" name="initial" min="10" max="50" step="1" value="<?php echo $initial; ?>" 
                       style="width: 100%;" oninput="updateValue('initial', this.value)">
            </div>
            
            <div style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <label style="font-weight: 600;">Срок кредита</label>
                    <span id="yearsValue" style="color: #0984e3; font-weight: 600;"><?php echo $years; ?> лет</span>
                </div>
                <input type="range" name="years" min="5" max="30" step="1" value="<?php echo $years; ?>" 
                       style="width: 100%;" oninput="updateValue('years', this.value)">
            </div>
            
            <input type="hidden" name="price" id="priceHidden" value="<?php echo $price; ?>">
            <input type="hidden" name="initial" id="initialHidden" value="<?php echo $initial; ?>">
            <input type="hidden" name="years" id="yearsHidden" value="<?php echo $years; ?>">
            
            <button type="submit" class="btn" style="width: 100%;">Рассчитать</button>
        </form>
        
        <div style="margin-top: 40px; background: #f8f9fa; border-radius: 20px; padding: 30px;">
            <h3 style="margin-bottom: 20px;">Результаты расчёта</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <div style="color: #666; font-size: 14px;">Ежемесячный платёж</div>
                    <div style="font-size: 32px; font-weight: 700; color: #0984e3;"><?php echo number_format($payment, 0, '.', ' '); ?> ₽</div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">Сумма кредита</div>
                    <div style="font-size: 24px; font-weight: 600;"><?php echo number_format($credit_amount, 0, '.', ' '); ?> ₽</div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">Первоначальный взнос</div>
                    <div style="font-size: 18px;"><?php echo number_format($initial_amount, 0, '.', ' '); ?> ₽ (<?php echo $initial; ?>%)</div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">Процентная ставка</div>
                    <div style="font-size: 18px; color: #e74c3c;"><?php echo $rate; ?>%</div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">Общая выплата</div>
                    <div style="font-size: 18px;"><?php echo number_format($total_payment, 0, '.', ' '); ?> ₽</div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">Переплата</div>
                    <div style="font-size: 18px; color: #e74c3c;"><?php echo number_format($overpayment, 0, '.', ' '); ?> ₽</div>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 12px;">
            <p style="font-size: 14px; color: #0984e3;">📌 *Расчёт произведён по актуальной ставке <?php echo $rate; ?>% годовых. Итоговая сумма может отличаться в зависимости от программы ипотеки.</p>
        </div>
    </div>
</div>

<script>
function updateValue(field, value) {
    if (field === 'price') {
        document.getElementById('priceValue').innerText = Number(value).toLocaleString('ru-RU') + ' ₽';
        document.getElementById('priceHidden').value = value;
    } else if (field === 'initial') {
        let price = document.getElementById('priceHidden').value;
        let initialAmount = price * (value / 100);
        document.getElementById('initialValue').innerText = value + '% (' + Math.round(initialAmount).toLocaleString('ru-RU') + ' ₽)';
        document.getElementById('initialHidden').value = value;
    } else if (field === 'years') {
        document.getElementById('yearsValue').innerText = value + ' лет';
        document.getElementById('yearsHidden').value = value;
    }
    document.getElementById('mortgageForm').submit();
}
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
        box-shadow: 0 2px 8px rgba(9,132,227,0.3);
    }
    input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }
</style>

<?php include '../footer.php'; ?>
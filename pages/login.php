<?php
include '../header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        if (file_exists('../data/users.json')) {
            $users = json_decode(file_get_contents('../data/users.json'), true);
            
            foreach ($users as $user) {
                if ($user['login'] === $login && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_login'] = $user['login'];
                    $_SESSION['user_role'] = $user['role'] ?? 'user';
                    header('Location: cabinet.php');
                    exit;
                }
            }
        }
        $error = 'Неверный логин или пароль';
    }
}
?>

<div style="max-width: 500px; margin: 50px auto;">
    <div style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 10px; color: #2d3436;">Вход</h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Добро пожаловать в ДомКомфорт</p>
        
        <?php if ($error): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Логин</label>
                <input type="text" name="login" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="Введите логин" required>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Пароль</label>
                <input type="password" name="password" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="Введите пароль" required>
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #0984e3, #6c5ce7); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">
                Войти
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 25px; color: #666;">
            Нет аккаунта? <a href="register.php" style="color: #0984e3;">Зарегистрироваться</a>
        </p>
        
        <p style="text-align: center; margin-top: 15px; font-size: 14px; color: #888;">
            Тестовый вход: admin / password
        </p>
    </div>
</div>

<style>
    input:focus {
        border-color: #0984e3 !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(9,132,227,0.1);
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(9,132,227,0.2);
    }
</style>

<?php include '../footer.php'; ?>
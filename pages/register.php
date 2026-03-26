<?php
include '../header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $agree = isset($_POST['agree']) ? true : false;
    
    if (empty($login) || empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email';
    } elseif ($password !== $confirm) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } elseif (!$agree) {
        $error = 'Необходимо согласие на обработку персональных данных';
    } else {
        $users = [];
        if (file_exists('../data/users.json')) {
            $users = json_decode(file_get_contents('../data/users.json'), true);
        }
        
        $login_exists = false;
        foreach ($users as $user) {
            if ($user['login'] === $login) {
                $login_exists = true;
                break;
            }
        }
        
        $email_exists = false;
        foreach ($users as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                $email_exists = true;
                break;
            }
        }
        
        if ($login_exists) {
            $error = 'Пользователь с таким логином уже существует';
        } elseif ($email_exists) {
            $error = 'Пользователь с таким email уже зарегистрирован';
        } else {
            $new_user = [
                'id' => count($users) + 1,
                'login' => $login,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user',
                'registered_at' => date('Y-m-d H:i:s')
            ];
            
            $users[] = $new_user;
            file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $success = 'Регистрация прошла успешно! <a href="login.php">Войти</a>';
        }
    }
}
?>

<div style="max-width: 500px; margin: 50px auto;">
    <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 10px;">Создать аккаунт</h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Добро пожаловать в ДомКомфорт</p>
        
        <?php if ($error): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div style="background: #dcfce7; color: #16a34a; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Логин</label>
                <input type="text" name="login" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="Введите логин" required>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email</label>
                <input type="email" name="email" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="example@mail.ru" required>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Пароль</label>
                <input type="password" name="password" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="Минимум 6 символов" required>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Подтверждение пароля</label>
                <input type="password" name="confirm" style="width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px;" placeholder="Повторите пароль" required>
            </div>
            
            <!-- Чекбокс согласия -->
            <div style="margin-bottom: 30px;">
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="agree" style="width: 18px; height: 18px; cursor: pointer;" required>
                    <span style="color: #555; font-size: 14px;">
                        Я согласен на обработку персональных данных
                    </span>
                </label>
                <p style="font-size: 12px; color: #888; margin-top: 8px; margin-left: 30px;">
                    Нажимая кнопку «Зарегистрироваться», вы даёте согласие на обработку своих персональных данных
                </p>
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #0984e3, #6c5ce7); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">
                Зарегистрироваться
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 25px; color: #666;">
            Уже есть аккаунт? <a href="login.php" style="color: #0984e3;">Войти</a>
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
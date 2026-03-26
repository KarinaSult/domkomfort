<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ДомКомфорт - агентство недвижимости</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: url('/Курсовая/uploads/hero-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #2d3436;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #0984e3, #6c5ce7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        
        .nav {
            display: flex;
            gap: 40px;
        }
        
        .nav a {
            color: #2d3436;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav a:hover {
            color: #0984e3;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #0984e3, #6c5ce7);
            color: white;
            text-decoration: none;
            border-radius: 100px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 10px 20px rgba(9, 132, 227, 0.15);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(9, 132, 227, 0.25);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #0984e3;
            color: #0984e3;
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: #0984e3;
            color: white;
        }
        
        .hero {
            background: linear-gradient(135deg, rgba(9,132,227,0.85), rgba(108,92,231,0.85));
            backdrop-filter: blur(8px);
            border-radius: 24px;
            margin: 40px 0;
            padding: 60px 40px;
            text-align: center;
            color: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 50px;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 40px;
            color: white;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: white;
        }
        
        .stat-label {
            opacity: 0.9;
            color: white;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }
        
        .card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }
        
        .card-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .card-content {
            padding: 25px;
        }
        
        .card-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .card-address {
            color: #636e72;
            font-size: 15px;
            margin-bottom: 20px;
        }
        
        .card-price {
            font-size: 28px;
            font-weight: 700;
            color: #0984e3;
            margin: 15px 0;
        }
        
        .card-details {
            display: flex;
            gap: 20px;
            color: #636e72;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            margin: 15px 0;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 0;
            margin-top: 80px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #636e72;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                height: auto;
                padding: 20px 0;
            }
            
            .nav {
                margin-top: 20px;
                gap: 20px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .stats {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="/Курсовая/index.php" class="logo">ДомКомфорт</a>
                <nav class="nav">
                    <a href="/Курсовая/index.php">Главная</a>
                    <a href="/Курсовая/pages/catalog.php">Каталог</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/Курсовая/pages/cabinet.php">Личный кабинет</a>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="/Курсовая/admin.php">👑 Админ</a>
                        <?php endif; ?>
                        <a href="/Курсовая/logout.php">Выйти</a>
                    <?php else: ?>
                        <a href="/Курсовая/pages/login.php">Вход</a>
                        <a href="/Курсовая/pages/register.php">Регистрация</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
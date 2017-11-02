<?php
session_start();
include 'functions.php';
$pdo = connectDB();
if (isset($_POST['login'])) {
    /* функция authAdmin() возвращает значение 'admin' если введённые логин и пароль правильные. 
      В этом случае админа можно авторизовать (записать в сессию)
      Если логин и пароль введены неправильно, функция возвращает false */
    $_SESSION['user'] = authAdmin($pdo, $_POST['login'], $_POST['password']);
}

if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    /* Если отправлено action со значением logout, разлогинить админа (очистить переменную в сессии) */
    unset($_SESSION['user']);
}

if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
    /* Если админ авторизован, показать кнопку выхода */
    echo "Вы авторизованы как " . $_SESSION['user'];
    echo exitForm();
} else {
    /* Если админ не авторизован, вывести форму авторизации */
    echo loginForm();
}
// ДАННЫЕ АДМИНА: логин: admin пароль: admin
?>
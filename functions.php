<?php

function connectDB() {
    $host = '127.0.0.1';
    $db = 'hiteck_quest';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $opt[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
    $opt[PDO::ATTR_EMULATE_PREPARES] = false;

    $pdo = new PDO($dsn, $user, $pass, $opt);
    return $pdo;
}

function checkAuth($pdo, $login, $password) {
    $query = $pdo->prepare("SELECT * FROM `user` WHERE `login`=?");
    $query->execute(array($login));

    if (empty($login)) {
        echo "Поле 'логин' не может быть пустым<br>";
    }
    if (empty($password)) {
        echo "Поле 'пароль' не может быть пустым<br>";
    }
    while ($row = $query->fetch()) {
        if ($row['password'] == $password) {
            return true;
        }
    }
}

function authAdmin($pdo, $login, $password) {
    if (checkAuth($pdo, $login, $password) == true) {
        return 'admin';
    }
    return false;
}

function loginForm() {
    $form = '<form method="POST" action="admin.php">
            логин:<input name="login"><br>
            пароль:<input name="password"><br>
            <button>Войти</button>
            </form>';
    return $form;
}

function exitForm() {
    $form = "<form method='POST' action='admin.php'>
                <input type='hidden' name='action' value='logout'>
                <button>Выход</button>
                </form>";
    return $form;
}

function addArticleForm() {
    $form = "<form method='POST' action='blog.php'>
        Добавить статью в блог<br>
        Заголовок статьи: <input name='title'><br>
        Текст статьи: <textarea name='textArticle'></textarea><br>
        <button>Отправить статью</button>
        </form>";
    return $form;
}

function addArticle($title, $text, $pdo){
    $query = $pdo->prepare("INSERT INTO `article` (`title`, `text`, `date`) VALUES (?, ?, ?)");
    $date = date('Y-m-d');
    $query->execute(array($title, $text, $date));
}

function echoAllArticles($pdo){
    $query = $pdo->prepare("SELECT * FROM `article`");
    $query->execute();
    
    while ($row = $query->fetch()) {
        echo "<br>Название статьи: " . $row['title'] . "<br>";
        echo "Текст статьи: " . $row['text'] . "<br>";
        echo "Дата: " . $row['date'] . "<br>";
        echoAllComments($row['id'], $pdo);
        echo addCommentForm($row['id']);
    }
}

function addCommentForm($article_id){
    $form = "<form method='POST' action='blog.php'>
        Добавить комментарий<br>
        Ваше имя:<input name='name'><br>
        Комментарий:<textarea name='textComment'></textarea><br>
        <input type='hidden' name='article_id' value='" . $article_id . "'>
        <button>Отправить комментарий</button>
        </form><br>";
    return $form;
}

function addComment($name, $text, $article_id, $pdo){
    $query = $pdo->prepare("INSERT INTO `comment` (`commentator_name`, `text`, `article_id`) VALUES (?, ?, ?)");
    $query->execute(array($name, $text, $article_id));
}

function echoAllComments($article_id, $pdo){
    $query = $pdo->prepare("SELECT * FROM `comment` WHERE `article_id`=?");
    $query->execute(array($article_id));
    echo "Комментарии:<br>";
    while ($row = $query->fetch()) {
        echo "Имя: " . $row['commentator_name'] . "<br>";
        echo "Комментарий:" . $row['text'] . "<br>";
    }
}
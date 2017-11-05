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
    $form = '<div class="bcontainer"><div class="post"><form method="POST">
            <span class="bctitle">Страница авторизации</span><br><br>
            <input class="cname" name="login" placeholder="Логин"><br><br>
            <input class="ctextp" name="password" placeholder="Пароль"><br><br>
            <button class="csend">Войти</button>
            </form></div></div>';
    return $form;
}

function exitForm() {
    $form = "<form method='POST'>
                <input type='hidden' name='action' value='logout'>
                <button>Выход</button>
                </form>";
    return $form;
}

function addArticleForm() {
    $form = "<div class='bcontainer'><div class='post'><form method='POST'>
        <span class='bctitle'>Добавить статью в блог</span><br><br>
        <input class='cname' name='title' placeholder='Заголовок статьи'><br><br>
        <textarea class='ctext' name='textArticle' placeholder='Текст статьи'></textarea><br><br>
        <button class='csend'>Отправить статью</button>
        </form></div></div>";
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
    
    echo "<div class='bcontainer'>";
    while ($row = $query->fetch()) {
        echo "<div class='post'>";
        echo "<div class='article'>";
        echo "<span class='btitle'>" . $row['title'] . "</span><br>";
        echo "<hr>";
        echo "<span class='btext'>" . $row['text'] . "</span><br><br>";
        echo "<span class='bdate'>Дата: " . $row['date'] . "</span><br>";
        echo "</div>";
        echo "<div class='bcomments'>";
        echoAllComments($row['id'], $pdo);
        echo "</div>";
        echo "<div class='newcomment'>";
        echo addCommentForm($row['id']);
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}

function addCommentForm($article_id){
    $form = "<form method='POST'>
        <span class='bctitle'>Добавить комментарий</span><br><br>
        <input class='cname' name='name' placeholder='Ваше имя'><br><br>
        <textarea class='ctext' name='textComment' placeholder='Комментарий'></textarea><br>
        <input type='hidden' name='article_id' value='" . $article_id . "'><br>
        <button class='csend'>Отправить комментарий</button>
        </form>";
    return $form;
}

function addComment($name, $text, $article_id, $pdo){
    $query = $pdo->prepare("INSERT INTO `comment` (`commentator_name`, `text`, `article_id`) VALUES (?, ?, ?)");
    $query->execute(array($name, $text, $article_id));
}

function echoAllComments($article_id, $pdo){
    $query = $pdo->prepare("SELECT * FROM `comment` WHERE `article_id`=?");
    $query->execute(array($article_id));
    echo "<span class='bctitle'>Комментарии:</span><br>";
    while ($row = $query->fetch()) {
        echo "<hr><span class='bcname'>" . $row['commentator_name'] . ":</span><br>";
        echo "<span class='bctext'>" . $row['text'] . "</span><br>";
    }
    echo "<hr>";
}

/*
function feedbackForm(){
    // форма обратной связи
        $form = "<div class='fb'><form method='POST'>
        <div class='fbleft'>
        <input class='fbfields' name='name' placeholder='Ваше имя'><br>
        <input class='fbfields' name='email' placeholder='Ваш E-mail'><br>
        </div>
        <div class='fbright'>
        <textarea class='fbmessage' name='text' placeholder='Сообщение'></textarea><br>
        </div>
        <button class='fbsend'>ОТПРАВИТЬ</button>
        </form></div><br>";
        return $form;
}
*/

function sendMail($name, $email, $text){
    $to = 'hiteck_quest@mail.ru';
    $subject = 'Обратная связь';
    $message = $name . "\r\n" . $text . "\r\n" . $email;
    $headers = 'From: '.$email; 
    $send = mail($to, $subject, $message, $headers);
}

function orderForm($quest_id){
        $form = "<div class='orderbox'><form method='POST'>
        Заказать квест:<br>
        <input class='oinput' name='name' placeholder='Ваше имя'><br>
        <input class='oinput' name='phone' placeholder='Ваш телефон'><br>
        <input type='hidden' name='order' value='" . $quest_id . "'>
        <button class='osend'>Заказать</button>
        </form><br></div>";
    return $form;
}

function addOrder($name, $phone, $quest_id){
    $pdo = connectDB();
    $query = $pdo->prepare("INSERT INTO `order` (`clientName`, `phone`, `quest_id`) VALUES (?, ?, ?)");
    $query->execute(array($name, $phone, $quest_id));
    
    echo orderInfo($phone, $pdo);
}

function orderInfo($phone, $pdo){
    $query = $pdo->prepare("SELECT order.id, order.clientName, order.phone, quest.title FROM `order` INNER JOIN `quest` ON order.quest_id = quest.id WHERE `phone`=? ORDER BY `id` DESC LIMIT 1");
    $query->execute(array($phone));
    $row = $query->fetch();
    $str = "Уважаемый(-ая) " . $row['clientName'] . "!<br>
        Вы оставили заявку на квест \"" . $row['title'] . "\".<br>
        Мы перезвоним вам по телефону " . $row['phone'] . " в ближайшее время.";
    return $str;
}
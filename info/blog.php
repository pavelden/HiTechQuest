<div class="welcome">
    <p>Блог</p>
</div>

<?php
session_start();
include_once './php/functions.php';
$pdo = connectDB();

if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {/* Если админ залогинен */
    /* Показать форму добавления новости */
    echo addArticleForm();
    
    if(isset($_POST['textArticle'])){/* Если из массива POST пришла переменная title (админ хочет добавить статью) */
        // функция addArticle() добавляет статью в БД
        addArticle($_POST['title'], $_POST['textArticle'], $pdo);
    }
}

if(isset($_POST['textComment'])){
    addComment($_POST['name'], $_POST['textComment'], $_POST['article_id'], $pdo);
}

// функция echoAllArticles() выводит все статьи из БД
echoAllArticles($pdo);
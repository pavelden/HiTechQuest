<div class="welcome">
    <p>Обратная связь</p>
</div>
<div class='fb'>
    <form method='POST'>
        <div class='fbleft'>
            <input class='fbfields' name='name' placeholder='Ваше имя'><br>
            <input class='fbfields' name='email' placeholder='Ваш E-mail'><br>
        </div>
        <div class='fbright'>
            <textarea class='fbmessage' name='text' placeholder='Сообщение'></textarea><br>
        </div>
        <button class='fbsend'>ОТПРАВИТЬ</button>
    </form>
</div><br>
<?php
include_once './php/functions.php';
if (isset($_POST['email'])) {
    sendMail($_POST['name'], $_POST['email'], $_POST['text']);
}
?>
<?php
include 'functions.php';
echo feedbackForm();
if(isset($_POST['email'])){
    sendMail($_POST['$name'], $_POST['email'], $_POST['text']);
}

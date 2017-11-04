<?php
include_once './php/functions.php';
if(isset($_POST['order'])){
    addOrder($_POST['name'], $_POST['phone'], $_POST['order']);
    // функция addOrder() добавляет заказ в БД и выводит информацию о принятом заказе.
}else{
    echo orderForm($_GET['page']);
    // функция orderForm() выводит форму заказа
    // переданное значение - id квеста
    // то есть на странице с первым квестом (Мягкий лабиринт) функция будет вызываться как orderForm(1), со вторым orderForm(2)
    // квесты нумеруются от 1 до 3 (лабиринт, роботы, хакеры)
}
?>
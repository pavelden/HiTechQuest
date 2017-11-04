<?php
function changeInfo() {
    switch (@$_GET['page']) {
        case 1:
            $info = "quest1";
            break;
        case 2:
            $info = "quest2";
            break;
        case 3:
            $info = "quest3";
            break;
        case "choose":
            $info = "chooseQuest";
            break;
        case "blog":
            $info = "blog";
            break;
        case "feedback":
            $info = "feedback";
            break;
        case "admin":
            $info = "admin";
            break;
        default:
            $info = "index";
            break;
    }
    return $info;
}
?>
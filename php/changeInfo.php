<?php
function changeInfo() {
    switch (@$_GET['page']) {
        case "quest1":
            $info = "quest1";
            break;
        case "quest2":
            $info = "quest2";
            break;
        case "quest3":
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
        default:
            $info = "index";
            break;
    }
    return $info;
}
?>
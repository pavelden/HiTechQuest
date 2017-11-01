<?php 
include ("php/changeInfo.php"); 
$infoPage = changeInfo();
?>

<div class="info">
    <?php include ($infoPage.".php"); ?>
</div>
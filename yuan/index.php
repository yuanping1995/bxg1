<?php
ob_start();
echo "Hello Worl0000d!";
$content = ob_get_contents();//取得php页面输出的全部内容
$fp = fopen("./public/tmp/new/0001.html", "w");
fwrite($fp, $content);
fclose($fp);

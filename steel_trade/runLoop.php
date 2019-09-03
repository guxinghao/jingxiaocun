<?php
$rs = system("ps aux | grep /website/steel_trade/steel_trade/protected/runPush.php > 1.txt");
$arr = file("1.txt");
$total = count($arr);
$count = array();
$str='php /website/steel_trade/steel_trade/protected/runPush.php';
for ($i = 0; $i < $total; $i ++) {
    
    preg_match('|(\d+)|',$arr[$i],$r);
    if(count($count)>0)
    {
        system("kill -9 ".$r[1]);
    }
    if (stristr($arr[$i], $str) !== false && stristr($arr[$i], 'sh -c '.$str)===false) {
        $count[] = 'no';
    }
}
if (count($count) >= 1) {
    echo "A same running";
    exit();
} else {
    system($str);
}
?>
<?php
function dataen($txt)
{
    srand((double)microtime() * 1000000);
    $encrypt_key = md5(rand(0, 32000));
    $ctr = 0;
    $tmp = '';
    $txt = strval($txt);
    for ($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    return base64_encode(passport_key($tmp));
}

function datade($txt)
{
    $txt = passport_key(base64_decode($txt));
    $tmp = '';
    for ($i = 0; $i < strlen($txt); $i++) {
        $tmp .= $txt[$i] ^ $txt[++$i];
    }
    return $tmp;
}

function passport_key($txt)
{
    $encrypt_key = 'alizhizhuchi';
    $encrypt_key = md5($encrypt_key);
    $ctr = 0;
    $tmp = '';
    for ($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}
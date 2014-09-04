<?php

//DBに接続する。
function connect(){
    $db = mysql_connect('localhost:8889', 'root', 'root');
    if (!$db) {
        exit('データベースに接続できませんでした。');
    }
    
    $result = mysql_select_db('pink_search', $db);
    mysql_set_charset('utf8');

    if (!$result) {
        exit('データベースを選択できませんでした。');
    }
    
    return $db;
}

function checkupdate(){
    $check_url = 'SELECT url FROM thread';
    $result_url = mysql_query($check_url , $db);
	if(!$result_url){
	    die('失敗しました。'.mysql_error());
    }
    
}

//$url_update_check = checkupdate();
//if($url_update_check){
//    print_r("成功してます");
//}elseif (!$url_update_check) {
//    print_r("失敗してます。");
//}
//DBを閉じる。
function close($db) {
    
    mysql_close($db);
}
?>
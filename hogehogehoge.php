<base href="http://pele.bbspink.com/test/read.cgi/hnews/" target="body">
<?php
//１回だけ'datebase_connect.php'を処理する。
require_once('datebase_connect.php');

$base_url = 'http://pele.bbspink.com/hnews/';
$url = $base_url.'subback.html';

// 新しい cURL リソースを作成する。
$ch = curl_init();

// オプションにURLを設定
curl_setopt($ch, CURLOPT_URL, $url);
// 文字列で結果を返させる
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 結果を返す
$result = curl_exec($ch);
$result = mb_convert_encoding($result, "UTF-8", "SJIS");

// cURL リソースを閉じ、システムリソースを開放する。
curl_close($ch);

//正規表現
$thread = '@<a[^>]*?(?<!\.)href="([^"]*+)"[^>]*+>(.*?)</a>@';

preg_match_all($thread, $result, $matches);
//print_r($matches[1]);



//接続(datebase_connect.php)
$db = connect();

$list = array();

foreach ($matches[1] as $thread_url){
    //print_r($base_url.$thread_url.'<br />');
    $list[] = array('url' => $thread_url);
}

$cnt = 0;
foreach ($matches[2] as $thread_title){
    //print_r($thread_title.'<br />');
    //$list[$cnt] = array('title' => $thread_title);
    $map = $list[$cnt];
    $map += array('title' => $thread_title);
    $list[$cnt] = $map;
    $cnt++;
}
//foreach ($list as $result_url){
//    echo $result_url['url'].'<br>';
//    echo $result_url['title'].'<br>';
//    $sql = "INSERT INTO thread (url, thread_name, date) VALUES ('".$base_url.$result_url['url']."','".$result_url['title']."', now())";
//    $result_flag = mysql_query($sql);
//}

//スレッドチェック。
foreach ($list as $result_url){
    $_query = 'SELECT * FROM thread WHERE url = "'.$base_url.$result_url['url'].'"';
    $_result = mysql_query($_query);
    $_num = mysql_num_rows($_result);
	if( $_num > 0 ){
	    $sql = "INSERT INTO thread (url, thread_name, date) VALUES ('".$base_url.$result_url['url']."','".$result_url['title']."', now())";
	    $result_flag = mysql_query($sql);
	}	
echo $result_url['url'].'<br>';
echo $result_url['title'].'<br>';
}


//接続を閉じる。(datebase_connect.php)
close($db);

?>
<?php
    $mysqli = new mysqli('localhost', 'sample', 'acute3154', 'sampledb');
    
    if($mysqli->connect_error) {
        die('Connect Error: (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
    }
    
    print 'mysqliクラスによる接続に成功しました。';
    
    // ここでデータベース関連の処理を行います。
    
    $mysqli->close();
    
    // ========== ========== ========== ========== ==========
    
    $pdo = new PDO('mysql:host=localhost;dbname=sampledb;charset=utf8', 'sample', 'acute3154');
    
    print 'PDOクラスによる接続に成功しました。';
    
    // ここでデータベース関連の処理を行います。
    
    $pdo = null;
    
    // PDO(PHP Data Object)
    // mysqliはMySQLサーバーのみを対象とするが、PDOは複数のデータベースを操作できる。
    
    // 開始ファイルの変更は「ファイル＞プロジェクトプロパティ＞実行構成」から変更
?>
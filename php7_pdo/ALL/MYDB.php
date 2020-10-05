<?php

// 【1】DSNの組み立て(接続のための情報を決まった順序に組み立てた文字列)
// 接続情報の外部化
function db_connect() {
    $db_type = "mysql";         // データベースの種類
    $db_host = "localhost";     // ホスト名
    $db_name = "sampledb";      // データベース名
    $db_user = "sample";        // ユーザー名
    $db_pass = "xxxxx";     // パスワード
    
    $dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";
    
    // 【2】データベースに接続
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass);
        // 接続オプションの設定(setAttribute)
        // エラーモードの設定
        // エラーの通知方法：例外を発生する
        // try～catch命令で処理したい場合は明示的にERRMODE_EXCEPTIONを設定する
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // プリペアードステートメント(SQL文のテンプレート)を利用可能にする
        // プリペアドステートメントのエミュレーションを有効または無効にする
        // false : ネイティブのプリペアードステートメントを利用する
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        print "接続しました... <br>";
        // 何らかの原因でPDOでエラーが発生したときはPDOException例外を投げてくれる
        // catchでPDOExceptionの発生を判定する
    } catch (PDOException $Exception) {
        die('エラー;' . $Exception->getMessage());
    }
    
    return $pdo;
}

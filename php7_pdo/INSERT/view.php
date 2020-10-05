<!DOCTYPE html>
<html>
<head>
    <title>PHPからSQLのINSERT操作をする</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php
        // 【3】外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();
    
        // 【4】データの挿入
        try {
            // トランザクション(関連する複数の処理をグループ化したもの)の開始
            $pdo->beginTransaction();
                // 「:名前」という記述はプレイスホルダ(パラメータの置き場所のこと)を表す
                $sql = "INSERT INTO member(last_name, first_name, age) VALUES(:last_name, :first_name, :age)";
                // PDOStatement(データベースに発行する一連の命令を管理する)オブジェクトの利用
                // prepareメソッドを呼び出すことでPDOStatementを取得できる
                $stmh = $pdo->prepare($sql);
                // bindValueメソッドでプレイスホルダに値をセットする
                // バインド: 英語で「関連付ける」という意味
                // bindValueメソッドの第3の引数にバイナリデータのデータ型を指定できる(デフォルトは文字列型(PDO::PARAM_STR))
                $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
                // executeメソッドで実際にデータベースに命令が送信/実行される
                $stmh->execute();
            // トランザクションの変更の確定(コミット)
            $pdo->commit();
            // 登録件数を返す
            $count = $stmh->rowCount();
            print "データを" . $count . "件、挿入しました。 <br>";
        } catch (PDOException $Exception) {
            // トランザクション処理が途中で失敗した場合は処理を破棄し、元の状態に戻す(ロールバック)
            // すべての処理をなかったことにする
            $pdo->rollBack();
            print "エラー：" . $Exception->getMessage();
        }
    ?>

</body>
</html>

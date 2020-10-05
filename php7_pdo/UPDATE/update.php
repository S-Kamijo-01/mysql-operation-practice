<?php 
    // 【3】セッションの開始
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHPからSQLのUPDATE操作をする</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php
        // 【4】外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();
        
        // 【7】データの更新
        // セクション変数から受け取ります。
        $id = $_SESSION['id'];
        
        try {
            // トランザクションの開始
            $pdo->beginTransaction();
                $sql = "UPDATE member SET last_name = :last_name, first_name = :first_name, age = :age WHERE id = :id";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
                $stmh->bindValue(':id', $id, PDO::PARAM_INT);
                $stmh->execute();
            // 変更の確定
            $pdo->commit();
            // 登録件数を返す
            print "データを" . $stmh->rowCount() . "件、更新しました。 <br>";
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            print "エラー：" . $Exception->getMessage();
        }
        
        // 【8】セッションの破棄
        // セクション変数を全て解除する。
        // 空の連想配列を代入
        $_SESSION = array();
        
        // 最終的に、セクションを破壊する。
        session_destroy();
    ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>PPHPからSQLのDELETE操作をする</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php
        // 【3】外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();

        // データをDELETE文で消去した後、SELECT文ですべてのフィールド値を取り出し、table要素に表示
        // 【4】データの消去
        if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0 ){
            try {
                $pdo->beginTransaction();
                $id = $_GET['id'];
                $sql = "DELETE FROM member WHERE id = :id";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':id', $id, PDO::PARAM_INT );
                $stmh->execute();
                $pdo->commit();
                $count = $stmh->rowCount();
                print "データを" . $count . "件、削除しました。<br>";
            } catch (PDOException $Exception) {
                $pdo->rollBack();
                print "エラー：" . $Exception->getMessage();
            }
        }

        // 【5】データの選択
        try {
            $pdo->beginTransaction();
                $sql = "SELECT * FROM member";
                $stmh = $pdo->query($sql);
            $pdo->commit();
            $count = $stmh->rowCount();
            print "検索結果は" . $count . "件です。<br>";
        } catch (PDOException $Exception) {
            print "エラー：" . $Exception->getMessage();
        }

        // 【6】データの表示
        if($count < 1){
            print "検索結果がありません。<br>";
        } else {
    ?>

    <table border="1">
        <tbody>
            <tr>
                <th>番号</th>
                <th>氏</th>
                <th>名</th>
                <th>年齢</th>
            </tr>

            <?php
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <tr>
                <td><?=htmlspecialchars($row['id'], ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['last_name'], ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['first_name'], ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['age'], ENT_QUOTES)?></td>
            </tr>

            <?php
                }    
            ?>

        </tbody>
    </table>

    <?php
        }
    ?>

</body>
</html>
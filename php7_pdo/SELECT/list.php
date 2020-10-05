<!DOCTYPE html>
<html>
<head>
    <title>PHPからSQLのSELECT操作をする</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php
        // 【3】外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();
        
        // 【4】データの選択
        $search_key = '%' . $_POST['search_key'] . '%';
        
        try {
            $pdo->beginTransaction();
                $sql = "SELECT * FROM member WHERE last_name LIKE :last_name OR first_name LIKE :first_name";
                $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':last_name', $search_key, PDO::PARAM_STR);
                    $stmh->bindValue(':first_name', $search_key, PDO::PARAM_STR);
                $stmh->execute();
            $pdo->commit();
            // 処理した件数を返して表示する
            $count = $stmh->rowCount();
            print "検索結果は" . $count . "件です。 <br>";
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            print "エラー：" . $Exception->getMessage();
        }
        
        // 【5】データの表示
        if($count < 1) {
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
                // fetchメソッドは、結果セットのレコードポインタを次のレコードに移動しながら、カレントレコードに含まれるフィールド値を読み込む
                // fetchメソッドの引数にフィールド値の取得形式を指定する
                // PDO::FETCH_ASSOC: 連想配列として取得
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <tr>
                <!-- 
                    htmlspecialchars関数: HTMLエスケープ処理(エスケープ処理を行わなかった場合、XSSという脆弱性の原因にもなる)
                    第1引数: エンコード対象の文字列, 
                    第2引数: エスケープの種類, 
                    第3引数: 使用する文字エンコーディング(デフォルトはdefault_charset) 
                -->
                <!--
                    第2引数にはENT_QUOTESまたはENT_HTML5を指定する
                    ENT_QUOTES: シングル
                    ENT_HTML5: ダブルクォートを双方エスケープし、HTML5文書として処理
                -->
                <td><?=htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') ?></td>
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

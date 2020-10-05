<?php
    // 【3】セッションの開始
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHPからSQLの四大命令操作をする</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <hr>
    <p>会員名簿一覧</p>
    <hr>
    <p>[<a href="form.html">新規登録</a>]</p>
    <br>
    
    <form name="form1" method="POST" action="list.php">
        <label for="name">名前：</label>
        <input type="text" name="search_key" id="name">
        <input type="submit" value="検索する">
    </form>
    
    <?php
        // 【4】外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();
        
        // データの消去
        if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0) {
            
            try {
                $pdo->beginTransaction();
                    $id = $_GET['id'];
                    $sql = "DELETE FROM member WHERE id = :id";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmh->execute();
                $pdo->commit();
                $count = $stmh->rowCount();
                print "データを" . $count . "件、消去しました。<br>";
            } catch (PDOException $Exception) {
                $pdo->rollBack();
                print "エラー: " . $Exception->getMessage();
            }
            
        }
        
        // データの挿入
        if(isset($_POST['action']) && $_POST['action'] == 'insert') {
            
            try {
                $pdo->beginTransaction();
                    $sql = "INSERT INTO member(last_name, first_name, age) VALUES(:last_name, :first_name, :age)";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                    $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                    $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
                    $stmh->execute();
                $pdo->commit();
                $count = $stmh->rowCount();
                print "データを" . $count . "件、挿入しました。<br>";
            } catch (PDOException $Exception) {
                $pdo->rollBack();
                print "エラー: " . $Exception->getMessage();
            }
            
        }
        
        // データの更新
        if(isset($_POST['action']) && $_POST['action'] == 'update') {
            
            $id = $_SESSION['id'];
            
            try {
                $pdo->beginTransaction();
                    $sql = "UPDATE member SET last_name = :last_name, first_name = :first_name, age = :age WHERE id = :id";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                    $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                    $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
                    $stmh->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmh->execute();
                $pdo->commit();
                $count = $stmh->rowCount();
                print "データを" . $count . "件、更新しました。<br>";
            } catch (PDOException $Exception) {
                $pdo->rollBack();
                print "エラー: " . $Exception->getMessage();
            }
            
            unset($_SESSION['id']);
            
        }
        
        // 検索及び現在の全データを表示します。
        // データの検索
        try {
            if(isset($_POST['search_key']) && $_POST['search_key'] != "") {
                $search_key = '%' . $_POST['search_key'] . '%';
                $pdo->beginTransaction();
                    $sql = "SELECT * FROM member WHERE last_name LIKE :last_name OR first_name LIKE :first_name";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':last_name', $search_key, PDO::PARAM_STR);
                    $stmh->bindValue(':first_name', $search_key, PDO::PARAM_STR);
                    $stmh->execute();
                $pdo->commit();
            } else {
                $pdo->beginTransaction();
                    $sql = "SELECT * FROM member";
                    $stmh = $pdo->query($sql);
                $pdo->commit();
            }
            $count = $stmh->rowCount();
            print "検索結果は、" . $count . "件です。<br>";
        } catch (PDOException $Exception) {
            print "エラー: " . $Exception->getMessage();
        }
        
        // データの表示
        if($count < 1) {
            print "検索結果はありません。<br>";
        } else {
    ?>
    
    <table border="1">
        <tbody>
            <tr>
                <th>番号</th>
                <th>氏</th>
                <th>名</th>
                <th>年齢</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <tr>
                <td><?=htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?=htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><a href="updateform.php?id=<?=htmlspecialchars($row['id'], ENT_QUOTES) ?>">更新</a></td>
                <td><a href="list.php?action=delete&id=<?=htmlspecialchars($row['id'], ENT_QUOTES) ?>">消去</a></td>
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
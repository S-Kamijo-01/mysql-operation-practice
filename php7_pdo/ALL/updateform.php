<?php
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
    <p>更新画面</p>
    <hr>
    <p>[<a href="list.php">戻る</a>]</p>
    <br>
    
    <?php
        // 外部スクリプトの読み込み
        require_once("MYDB.php");
        $pdo = db_connect();
        
        // データの選択
        if(isset($_GET['id']) && $_GET['id'] > 0) {
            $id = $_GET['id'];
            $_SESSION['id'] = $id;
        } else {
            exit('パラメータが不正です。');
        }
        
        try {
            $pdo->beginTransaction();
                $sql= "SELECT * FROM member WHERE id = :id ";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':id',  $id, PDO::PARAM_INT );
                $stmh->execute();
            $pdo->commit();
            $count = $stmh->rowCount();
            print "検索結果は" . $count . "件です。 <br>";
        } catch (PDOException $Exception) {
            print "エラー：" . $Exception->getMessage();
        }

        // データの表示
        if($count < 1){
            print "更新データがありません。<br>";
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);  
    ?>

    <form name="form1" method="post" action="list.php">
        <label for="id">番号：</label>
        <input type="text" name="number" value="<?=htmlspecialchars($row['id'])?>" id="id" disabled>
        <br>
        <label for="last_name">氏：</label>
        <input type="text" name="last_name" value="<?=htmlspecialchars($row['last_name'], ENT_QUOTES)?>" id="last_name">
        <br>
        <label for="first_name">名：</label>
        <input type="text" name="first_name" value="<?=htmlspecialchars($row['first_name'], ENT_QUOTES)?>" id="first_name">
        <br>
        <label for="age">年齢：</label>
        <input type="text" name="age" value="<?=htmlspecialchars($row['age'], ENT_QUOTES)?>" id="age">
        <br>
        <input type="submit" value="更　新">
    </form>

    <?php
        }
    ?>

</body>
</html>

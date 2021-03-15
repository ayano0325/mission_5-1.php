<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    <title>mission_5-1</title>
    </head>
    <body>
        <?php
        //データベース接続
        $dsn = 'データベース名';
        $user = 'ユーザ名';  
        $password = 'パスワード';  
        $pdo = new PDO($dsn, $user, $password, 
                       array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//データベース操作でエラーが発生した場合に警告
        //データを登録するためのテーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS mission_5_1"  //テーブルが作成されていない場合、テーブルを作成
            ."("                                    
            ."id INT AUTO_INCREMENT PRIMARY KEY,"   //id 自動登録されているナンバリング
            ."name char(32),"                       //名前　文字列英数字32文字以内
            ."comment TEXT,"                         //コメント
            ."date char(25),"                       //日時25文字以内
            . "password char(32)"                   //パスワード32文字以内
            .");";
        $stmt = $pdo->query($sql);
        
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        $editpost = $_POST["editpost"];
        $denum = $_POST["deletenumber"];
        $password2 = $_POST["password2"];
        $ednum = $_POST["editnumber"];
        $password3 = $_POST["password3"];
        
        if($name!="" && $comment!="" && $password!=""){
            //編集モード第2段階
            if($editpost != ""){
                $sql = 'SELECT * FROM mission_5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row){
                    if ($row['id'] == $editpost){
                        //4-7
                        $id = $_POST["editpost"];
                        $name = $_POST["name"];
                        $comment = $_POST["comment"];
                        $password = $_POST["password"];
                        $date = date("Y/m/d/ H:i:s");
                        $sql = 'UPDATE mission_5_1 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id ';
                        $stmt = $pdo->prepare($sql);
                        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt-> bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt-> bindParam(':password', $password, PDO::PARAM_STR);
                        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt -> execute(); 
                    }
                }
            }
            //新規投稿
            else{
                //mission4-5
                $sql = $pdo -> prepare("INSERT INTO mission_5_1 (name, comment,date,password) 
                VALUES (:name, :comment, :date, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["password"];
                $date = date("Y/m/d/ H:i:s");
                $sql -> execute();
            }
        }
        //削除
        elseif($denum!="" && $password2!=""){
            $sql = 'SELECT * FROM mission_5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['password'] == $password2){
                if($row['id'] == $denum){
                    $sql = 'SELECT * FROM mission_5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    $id = $denum;
                    $sql = 'delete from mission_5_1 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
                }
            }
        }
        
        
        //編集モード第1段階
        elseif($ednum!="" && $password3!=""){
            //4-6
            $sql = 'SELECT * FROM mission_5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll(); //linesの役割？
            foreach($results as $row){
                if($row['id']==$ednum && $row['password']==$password3){
                    $editname = $row['name'];
                    $editcom = $row['comment'];
                    $editpass = $row['password'];
                    $editpostnum = $row["id"];
                    break;
                }
            }
        }
            
        ?>
        <form action="" method="post">
            <h2>掲示板</h2>
           【投稿】<br>
            名前、コメント、パスワードを記入して投稿してください。<br>
            全て記入できていないと投稿はできません。<br>
            <input type="text" name="name" placeholder="名前" value="<?php echo $editname; ?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcom; ?>">
            <input type="text" name="password" placeholder="パスワード" value="<?php echo $editpass; ?>">
            <input type="hidden" name="editpost" placeholder="editpost" value="<?php echo $editpostnum; ?>">
            <input type="submit" name="submit" value="送信">
            <br>【投稿の削除】<br>
            削除する投稿の番号とパスワードを記入してください。<br>
            番号とパスワードが一致している場合のみ削除が可能です。<br>
            <input type="number" name="deletenumber" placeholder="削除する番号">
            <input type="text" name="password2" placeholder="パスワード" value="">
            <input type="submit" name="submit" value="削除">
            <br>【投稿の編集】<br>
            編集する投稿の番号とパスワードを記入してください。<br>
            番号とパスワードが一致している場合のみ編集が可能です。<br>
            <input type="number" name="editnumber" placeholder="編集する番号">
            <input type="text" name="password3" placeholder="パスワード">
            <input type="submit" name="submit" value="編集">
            <br><br>
        </form>
        <?php
            //表示 mission4-6
            $sql = 'SELECT * FROM mission_5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row) {
                echo $row['id'].' ';
                echo $row['name'].' ';
                echo $row['comment'].' ';
                echo $row['date'].'<br>';
            }
        ?>
    </body>
</html>
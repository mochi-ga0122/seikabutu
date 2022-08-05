 <!DOCTYPE html>  
 <html lang="ja">  
 <head>  
     <meta charset="UTF-8">  
     <title>mission_5-1</title>  
 </head>  
 <body>
     <?php
        //データベースに接続
        $dsn ='データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        /*$sql = 'DROP TABLE info';
        $stmt = $pdo->query($sql);*/
    
        //テーブルの作成・カラムの作成
        $sql = "CREATE TABLE IF NOT EXISTS info"
        . "("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        ."date DATETIME,"
        . "pass TEXT"
        .");";
        //SQLの実行
        $stmt = $pdo->query($sql);
        
        //変数の定義
        $chek=NULL;
        $namae=NULL;
        $komento=NULL;
        
        //名前・コメント・日付・パスワードの送信
        if (!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["chek"]) && !empty($_POST["pass"])){  
            $names=$_POST["name"];
            $comments=$_POST["comment"];
            $chek=$_POST["chek"];
            $pass=$_POST["pass"];
            $date = date("Y-m-d H:i:s");
             //レコードの作成
            $sql = $pdo -> prepare("INSERT INTO info (name,comment,date,pass) VALUES (:name, :comment,:date,:pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = ($names);
            $comment = ($comments); //好きな名前、好きな言葉は自分で決めること
            $date = ($date);
            $pass = ($pass);
            $sql -> execute();
            
         //編集機能(フラグ）
        }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["chek"])&& !empty($_POST["pass"])){
            $names=$_POST["name"];
            $comments=$_POST["comment"];
            $chek=$_POST["chek"];
            $pass=$_POST["pass"];
            $date = date("Y-m-d H:i:s");
             
            $id = $chek; //変更する投稿番号
            $name = ($names);
            $comment = ($comments); //変更したい名前、変更したいコメントは自分で決めること
            $sql = 'UPDATE info SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } 
        
        //消去機能
        if(!empty($_POST["number1"]) && !empty($_POST["pass2"])){
            $number=$_POST["number1"];
            $pass2=$_POST["pass2"];
            
            //レコードからパスワードを取得
            $sql = 'SELECT * FROM info';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $row['pass'];
                $pass=$row['pass'];
                
                if($pass==$pass2){
                     //SQLレコードの消去
                     $id = $number;
                     $sql = 'delete from info where id=:id';
                     $stmt = $pdo->prepare($sql);
                     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                     $stmt->execute();
                }
                    
            }
        }
        //フォームに値を送信する
        if(!empty($_POST["edit"]) && !empty($_POST["pass3"])){ 
            $pass3 = $_POST["pass3"];
            $edits = $_POST["edit"];
            //レコードからパスワードを取得
            $sql = 'SELECT * FROM info';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $row['id'];
                $row['pass'];
                $id=$row['id'];
                $pass=$row['pass'];
                
                if($pass==$pass3 && $id==$edits){
                    $row['name'];
                    $row['comment'];
                    $chek=$row['id'];
                    $namae=$row['name'];
                    $komento=$row['comment'];
                } 
            }
        }else{
            $chek =NULL;
        }
        
        

?>

     <h1>好きな言葉<h1>
     <form action="" method="post">  
         <input type="text" name="name" placeholder= "名前" value = <?php echo $namae?>>
         <input type="text" name="comment" placeholder= "コメント" value = <?php echo $komento?>> 
         <input type ="hidden" name = "chek" value = <?php echo $chek?> > 
         <input type="text" name="pass" placeholder="パスワード">
         <input type="submit" name="submit"> 
         <br>
         
         <input type = "number" name="number1" placeholder="削除対象番号"> 
         <input type="text" name="pass2"　 placeholder= "パスワード">
         <input type = "submit" name ="submit" value="削除" > 
         <br>
         
         <input type = "number" name="edit" placeholder="編集番号"> 
         <input type="text" name="pass3"　 placeholder= "パスワード">
         <input type = "submit" name ="submit" value="編集" > 
     </form>  
    <?php
         $sql = 'SELECT * FROM info';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'];
                echo $row['date'].',';
                echo "<hr>";
            }
    
    ?>
 </body>
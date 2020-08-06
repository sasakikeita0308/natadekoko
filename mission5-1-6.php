<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
 
 <?php
 
 //データベース接続
 
 $dsn ='mysql:dbname=tb220167db;host=localhost';
 $user = 'tb-220167';
 $password = 'KXk2Ac773K';
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

 //変数の初期設定
 $editname = "";
 $editcomm = "";
 $editnumber = "";
 //投稿機能
 

    if(!empty($_POST["name"])&&!empty($_POST["comm"])&&!empty($_POST["pass"])){
        $name = $_POST["name"];
        $comm = $_POST["comm"];
        $pass = $_POST["pass"];
            
         //編集実行
        if(!empty($_POST["edn"])){
            $id =$_POST["edn"]; //変更する投稿番号
    	    $sql = 'UPDATE tbtest666 SET name=:name,comment=:comment,
    	    uptime=cast(now() as datetime), pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
    	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    	    $stmt->bindParam(':comment', $comm, PDO::PARAM_STR);
    	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    	    $stmt->execute();
            }else{//新規投稿
                $sql = "INSERT INTO tbtest666 (name, comment, uptime, pass) 
                VALUES (:name, :comment, CAST(now() as datetime), :pass)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comm, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->execute();
            }
    }else{
        echo "----フォームに空欄があります----";
    }
 
 //レコードの削除
 
    if(!empty($_POST["delete"])&&!empty($_POST["delpass"])){
        $id = $_POST["delete"]; 
        $sql = 'SELECT * FROM tbtest666 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();                            
        $results = $stmt->fetchAll(); 
            foreach ($results as $row){
	        if($row[4]==$_POST["delpass"]){ //パスワードが一致したら
                $delete = $_POST["delete"];
                $sql = "DELETE FROM tbtest666 WHERE id = :delete;";
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(":delete", $delete, PDO::PARAM_INT);
                $stmt -> execute();
            }
	    }
    }
 
 //編集選択
 
    if(!empty($_POST["edit"])&&!empty($_POST["editpass"])){
        $id = $_POST["edit"]; 
        $sql = 'SELECT * FROM tbtest666 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();                             
        $results = $stmt->fetchAll(); 
            foreach ($results as $row){
	        if($row[4]==$_POST["editpass"]){
	            $editname=$row['name'];    //名前を取得
	            $editcomm=$row['comment']; //コメントを取得
	            $editnumber=$row['id'];
	        }
	    }
    }
 ?>
 
 <br>
投稿フォーム
    <form method="post" action="mission5-1-6.php">
      <input type="text" name="name" placeholder="名前" value="<?php echo $editname;?>"><br>
      <input type="text" name="comm" placeholder="コメント" value="<?php echo $editcomm;?>">
      <input type="hidden" name="edn" value="<?php echo $editnumber;?>"><br>
      <input type="text" name="pass" placeholder="パスワード">
      <input type="submit" value="送信">
    </form>
削除フォーム
    <form method="post" action="mission5-1-6.php">
      <input type="number" name="delete" placeholder="削除対象番号"><br>
      <input type="text" name="delpass" placeholder="パスワード">
      <input type="submit" value="削除">
    </form> 
編集フォーム
    <form method="post" action="mission5-1-6.php">
         <input type="number" name="edit" placeholder="編集対象番号"><br>
         <input type="text" name="editpass" placeholder="パスワード">
         <input type="submit" value="編集">
    </form>
   

 <?php
 
 //レコードの表示

	$sql = 'SELECT * FROM tbtest666';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['uptime'].'<br>';
	echo "<hr>";
	}
	
 ?>
</body>
</html>
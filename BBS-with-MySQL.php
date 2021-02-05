<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SimpleBBS</title>
</head>
<body>
<h3>簡易掲示板 ver. mySQL</h3>
<?php
//データ入力
	$dsn = 'database_name';
	$user = 'username';
	$password = 'password';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS keijibann"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);

//データベース編集
	if(!empty ($_POST["name"]) && !empty($_POST["comment"]) 
	&& !empty($_POST["editnum"]) && !empty($_POST["password"]) && !empty($_POST["submit"])){
	    $id = $_POST["editnum"]; //変更する投稿番号
	    $sql = ' SELECT * FROM keijibann WHERE id=:id'; 
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();                            
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
	    	$password = $row['password'];
	    }
	    if($password == $_POST["password"]){
	        $name = $_POST["name"];
    	    $comment = $_POST["comment"]; 
        	$date = date("Y年m月d日");
        	$sql = 'UPDATE keijibann SET name=:name,comment=:comment, date=:date WHERE id=:id';
        	$stmt = $pdo->prepare($sql);  //↑コンマやスペースの位置に注意
        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
        	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->execute();
	    }elseif($password != $_POST["password"]){
	        $message = "入力した内容に誤りがあります";}
	}

//データベース登録
	elseif(!empty ($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["submit"])){
    	$sql = $pdo -> prepare("INSERT INTO keijibann (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
    	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
    	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
    	$name = $_POST["name"];
    	$comment = $_POST["comment"]; 
    	$date = date("Y年m月d日");
    	$password = $_POST["password"];
    	$sql -> execute();}
	
//データベースから削除
	if(!empty($_POST["delete"]) && !empty($_POST["password2"]) && !empty($_POST["submit2"])){
	    $id = $_POST["delete"];
	    $sql = ' SELECT * FROM keijibann WHERE id=:id'; 
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();                             
        $results = $stmt->fetchAll(); 
	    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		    $password = $row['password'];
	    }
	    if($password == $_POST["password2"]){
	        $sql = 'delete from keijibann where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	    }elseif($password != $_POST["password2"]){
		   $message2 = "入力した内容に誤りがあります";
	    }
	}
	    
	if(!empty($_POST["edit"]) && !empty($_POST["submit3"])
	&& !empty($_POST["password3"])){
	    $id = $_POST["edit"];
	    $sql = ' SELECT * FROM keijibann WHERE id=:id'; 
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();                             
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
	    	$password = $row['password'];
	    }
        if($password == $_POST["password3"]){
            foreach ($results as $row){
	    	    $EDi = $row['id'];
		        $EDname = $row['name'];
		        $EDcomment = $row['comment'];
            }
	    }elseif($password != $_POST["password2"]){
		    $message3 = "入力した内容に誤りがあります";
	        
	    }
        
	}
?>
<form method="POST" action="">
    投稿<?php echo " ".$message;?><br>
    <input type="text" name="name" placeholder = "Username" value="<?php echo $EDname;?>">
	<input type="text" name="comment" placeholder = "Comment" value="<?php echo $EDcomment;?>">
	<input type="password" name="password" placeholder = "Password">
	<input type="hidden" name="editnum" placeholder = "EDIT Number" value="<?php echo $EDi;?>">
	<input type="submit" name="submit" value="送信">
	<br>
	<br>
	削除<?php echo " ".$message2;?><br>
	<input type="text" name="delete" placeholder = "Number">
	<input type="password" name="password2" placeholder = "Password">
	<input type="submit" name="submit2" value="削除">
	<br>
	<br>
	編集<?php echo " ".$message3;?><br>
	<input type="text" name="edit" placeholder = "Number">
	<input type="password" name="password3" placeholder = "Password">
	<input type="submit" name="submit3" value="編集">
</form>
	<br>
	<br>

	テーマ（仮）：あなたの主食はパン？それともご飯？
	<br>
	コメントで教えてください！
	<br>
<?php
    echo "___________________________________________________________________________<br>";
	$sql = ' SELECT * FROM keijibann'; 
    $stmt = $pdo->prepare($sql);                 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                            
    $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['id'].'. ';
		if(preg_match('/!!!/',$row['name'])){//Usernameに!!!をつけることでUsernameがイタリック体
		    $name = str_replace('!!!', '', $row['name']);//に表示→管理人のみイタリック体にできる
		    echo '<i>'.$name.'</i> ';
		}else{
		    echo $row['name'].' ';
		}
		echo '<i>'.$row['date'].'</i><br>';
		echo $row['comment'].'<br>';
	}
?>
</body>
</html>
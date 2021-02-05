<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>簡易掲示板</title>
</head>
<body>
<h3>簡易掲示板</h3>
<?php
 //値を変数代入、日付を設定、ファイルを指定
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $delnum =$_POST["delnumber"];
    $editnum = $_POST["editnumber"];
    $editnum2 = $_POST["editnumber2"];
    $pass_post = $_POST["password"];
    $pass_delete = $_POST["password2"];
    $pass_edit = $_POST["password3"];
    $date = date("Y年m月d日"); #H:i:sを足しても良い
    $filename = "ミッション_03-5.txt";
    $realpass = "abc";

//投稿番号の設定
    if (file_exists($filename)) {
        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        $lastline = $lines[count(file($filename)) - 1];
    $i = explode("<>", $lastline)[0] +1; 
        } else {
                $i = 1;
            }

//編集関連処理
    if(!empty($_POST["editnumber2"]) && !empty($_POST["password"]) 
            && isset($_POST["submit"]) && !empty($_POST["name"])){
        $fp = fopen($filename, "r");
        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
                $array = explode("<>", $line);
               if($array[0] == $editnum2){
                   $pass_check = $array[4];
               }
        }
        if($pass_check == $pass_post){
            $fp = fopen($filename, "a");
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            ftruncate($fp, 0);
            foreach($lines as $line){
                $array = explode("<>", $line);
                if($array[0] == $editnum2){
                    $array = array($editnum2,$name,$comment,$date,$pass_post);
                }
                $separated = implode("<>",$array);
                fwrite($fp,$separated.PHP_EOL);
                fclose($filename);
            }
            $message = $editnum2."番の投稿を編集しました";
        }
        elseif($pass_check != $pass_post){
                    $message = "パスワードが違います";
        }
    }

//ファイル書き込み 
   elseif(!empty($_POST["name"]) && empty($_POST["editnumber2"])){
            $fp = fopen($filename, "a");
            $array = array($i, $name, $comment, $date, $pass_post);
            $separated = implode("<>",$array);
            fwrite($fp,$separated.PHP_EOL);
            fclose($fp);
            $message = "投稿を受け付けました";
       }


//削除関連処理
    if(!empty($_POST["delnumber"]) && !empty($_POST["password2"]) && isset($_POST["submit2"])){
        $fp = fopen($filename, "r");
        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
                $array = explode("<>", $line);
               if($array[0] == $delnum){
                   $pass_check = $array[4];
               }
        }
        if($pass_check == $pass_delete){
            $fp = fopen($filename, "a");
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            ftruncate($fp, 0);
            foreach($lines as $line){
                $array = explode("<>", $line);
                $separated = implode("<>",$array);
                if($array[0] != $delnum){
                    fwrite($fp,$separated.PHP_EOL);
                }
            }
       fclose($filename);
       $message2 = $delnum."番の投稿を削除しました";
        }elseif($pass_check != $pass_delete){
            $fp = fopen($filename, "r");
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
             foreach($lines as $line){
                $array = explode("<>", $line);
                if($array[0] == $delnum){
                    $message2 = "パスワードが違います";
                }
             }
        }
    }

//編集関連処理
    if(!empty($_POST["editnumber"]) && !empty($_POST["password3"]) && isset($_POST["submit3"])){
        $fp = fopen($filename, "r");
        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
                $array = explode("<>", $line);
               if($array[0] == $editnum){
                   $pass_check = $array[4];
               }
        }
        if($pass_check == $pass_edit){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines as $line){
                    $array = explode("<>", $line);
                    if($array[0] == $editnum){
                        list($EDi, $EDname, $EDcomment, $date, $pass) = explode("<>", $line);
                    }
            }
        }
        elseif($pass_check != $pass_edit){
            $fp = fopen($filename, "r");
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines as $line){
                $array = explode("<>", $line);
                if($array[0] == $editnum){
                   $message3 = "パスワードが違います";
                }
            }
       }
    }
?>

投稿フォーム <?php echo $message;?><br>
<form method="POST" action="">
    <input type="text" name="name" placeholder = "Username" value="<?php echo $EDname;?>">
	<input type="text" name="comment" placeholder = "コメント" value="<?php echo $EDcomment;?>">
	<input type="password" name="password" placeholder = "パスワード">
    <input type="hidden" name="editnumber2" placeholder = "編集" value="<?php echo $EDi;?>">
	<input type="submit" name="submit" value="送信">
	<br>
	<br>
	</form>
投稿削除フォーム <?php echo $message2;?><br>
<form method="POST" action="">
    <input type="text" name="delnumber" placeholder = "半角数字">
    <input type="password" name="password2" placeholder = "パスワード" >
    <input type="submit" name="submit2" value="削除">
	<br>
	<br>
</form>
編集用フォーム <?php echo $message3;?><br>
<form method="POST" action="">
	<input type="text" name="editnumber" placeholder = "半角数字">
	<input type="password" name="password3" placeholder = "パスワード" >
	<input type="submit" name="submit3" value="編集">
</form>
<br>
<br>
テーマ（仮）好きな料理を教えてください！
<br>
<?php
//ウェブサイトに表示
    echo "___________________________________________________________________________<br>";
    if(file_exists($filename)){
        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
            if($line != ""){
                list($i, $name, $comment, $date, $pass) = explode("<>", $line);
                echo  "<br>".$i.". ".$name." <i>"." ".$date."</i><br>".$comment."<br>";
            }
        }
   }
?>

</body>
</html>
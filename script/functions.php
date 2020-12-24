<?php
if ($_SESSION['login'] == false){ 
 $_SESSION['user'] = "username";
 $_SESSION['pasw'] = "Paswoord";
}
session_start();
include 'dbConnect.php';
function str($Connect){
    try{ 
    $query = $Connect->PREPARE("SELECT * FROM comment");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return ($result);
    }
    catch (PDOExeption $e) {
        die ("error: ".$e->getMessage());
    }
}
if(isset($_POST['register'])){
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = sha1($_POST['passw']);
    $posting = htmlentities($_POST['posted']);
    login($name, $password, $posting, $Connect);
    }
Function login($name, $password, $posting, $Connect){
try{
    $check = $Connect->prepare("INSERT INTO comment(names, posted, pasw)values(:names,:posted, :pasw)");
    $check->bindparam('names', $name);
    $check->bindparam('posted', $posting);
    $check->bindparam('pasw', $password);
    if($check->execute()){
        //if($check->rowcount()==1){
        $_SESSION['login'] = true;
        $_SESSION['user'] = $name;
        $_SESSION['pasw'] = $password;   
        header('location: ../index.php');
        //
        }

    }
catch(PDOExeption $e){
    die('Error: '.$e->getMessage());
    }
}
if(isset($_POST['submit'])){
$name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
$posting = htmlentities($_POST['posted']);
proceed($name, $posting, $Connect);
}
function proceed($name, $posting, $Connect){
    try{ 
    $query = $Connect->PREPARE("INSERT INTO comment(names,posted) VALUES(:names, :posted)");
    $query->bindParam('names', $name);
    $query->bindParam('posted', $posting);
    if($query->execute()) {
        echo 'Gegevens zijn toegevoegd.';
    }
    else{
        echo 'Er ging iets fout.';
    } 
    }
    catch(PDOExeption $e){
        die('ERROR: '.$e->getMessage());
    }
  header("Location: ../index.php");
}
if(isset($_POST['log'])){
    $namess = filter_input(INPUT_POST, 'loginName', FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pswrd']);
    loginFunction($namess, $pass, $Connect);
} 
function loginFunction($namess, $pass, $Connect){
    try{
    $placehold = $namess;
    $check = $Connect->prepare("SELECT * FROM comment WHERE names = :namess AND pasw= :pass");
    $check->bindparam('namess', $namess);
    $check->bindparam('pass', $pass);
    $check->execute();
        if($check->rowCount() >= 1){
            var_dump ($namess);
            $_SESSION['login']=true;
            $_SESSION['user'] = $placehold;
            $_SESSION['pasw'] = sha1($pass);
            header('location: ../index.php');
        }
        else{
            $_SESSION['login']=false;
            header('location: ../index.php'); 
        } 
    }
    catch(PDOExeption $e){
        die('ERROR: '.$e->getMessage());
    } 
}
if (isset($_GET['del'])){
    $id = $_GET['del'];
    delete($Connect, $id);
}
function delete($Connect, $id){
    $verw = $Connect->prepare("DELETE FROM comment WHERE id=$id");
    $verw->execute();
    header('location: ../index.php');
}
function updat($Connect, $id){ 
    $elem = $Connect->prepare('SELECT * from comment where id='.$id);
    $elem->execute();
    $res = $elem->fetchAll(PDO::FETCH_ASSOC);
    return($res);
}
if (isset($_POST['updates'])){
    $namese = $_SESSION['user']; 
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $texten = filter_input(INPUT_POST, 'posted', FILTER_SANITIZE_STRING);
    pas_aan($Connect, $namese, $id, $texten);
} 
function pas_aan($Connect, $namese, $id, $texten){
    $pasAan = $Connect->prepare("UPDATE comment SET posted=:texten, names=:namese WHERE id=:id");
    $pasAan->bindparam('texten', $texten);
    $pasAan->bindparam('namese', $namese);
    $pasAan->bindparam('id', $id);
    $pasAan->execute();
    header('location: ../index.php');
}
if (isset($_POST['destroy'])){
    session_unset();
    header('location: ../index.php');
} 
<?php
include "config.php";
include "utils.php";

$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET['idCliente']))
    {
        $sql=$dbConn->prepare("SELECT * FROM cliente where idCliente=:idCliente");
        $sql->bindValue(':idCliente',$_GET['idCliente']);
        $sql->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
        exit();
    }
    else{
        $sql =$dbConn->prepare("SELECT * FROM cliente");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit();
    }
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $input=$_POST;
    $sql="INSERT INTO cliente
        (Dni,Nombres,Direccion,Email)
        VALUES
        (:Dni,:Nombres,:Direccion,:Email)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement,$input);
    $statement->execute();
    $postId=$dbConn->lastInsertId();
    if($postId)
    {
        $input['id']=$postId;
        header("HTTP/1.1 200 OK");
        echo json_encode($input);
        exit();
    }    
}

if($_SERVER['REQUEST_METHOD']=='DELETE')
{
    $id = $_GET['idCliente'];
    $statement = $dbConn->prepare("DELETE FROM cliente where idCliente=:idCliente");
    $statement->bindValue(':idCliente',$id);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

if($_SERVER['REQUEST_METHOD']=='PUT')
{
    $input=$_GET;
    $postId=$input['idCliente'];
    $fields =getParams($input);

    $sql="
        UPDATE cliente
        SET $fields
        WHERE id='$postId'
        ";
    
    $statement=$dbConn->prepare($sql);
    bindAllValues($statement,$input);
    
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

header("HTTP/1.1 400 Bad Request");
    
?>
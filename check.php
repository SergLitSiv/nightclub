<?php

header("Content-Type: text/html; charset=utf-8");

$data=$_POST;
$password=$_POST['password'];
$password = md5($password);
$response = array("status" => "ok");
$require = array("login", "password");
$error= array();

foreach ($require as $item){
    if( empty($data[$item])){
        $error[$item] = "Обязательные поля для заполнения";
        $response['status']="error";
    }

}

if(count($error)>0){
    $response['error']=$error;
    print json_encode($response);die;
}

extract($data);


include_once 'models/users.php';
$class_users = new users();

//сохраняем в бд
$data['created'] = Date("Y-m-d H:i:s");
$data['password'] = md5($password);
$result = $class_users->add($data);
if(!$result){
    $response["status"] = "error";
}
print json_encode($response);die;




?>
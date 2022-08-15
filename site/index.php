<?php 
session_start();
//router
$c = $_GET['c'] ?? 'home';//Nếu có giá trị c thì lấy tham số ra,ngược lại thì trả về home
$a = $_GET['a'] ?? 'index';//Nếu có giá trị a thì lấy tham số ra,ngược lại thì trả về index

//import config & connect database
require '../config.php';
require '../connectDB.php';

//import vendor file autoload.php
require '../vendor/autoload.php';

//import model
require '../bootstrap.php';


//Gọi hàm của controller tương ứng
//ucfirst() là upper character first - viết hoa kí tự đầu tiên
$str = ucfirst($c) . 'Controller';//e.g : HomeController,dấu . là nối chuỗi

//import file của controller tương ứng
require "controller/$str.php";//e.g : controller/HomeController.php

//Tạo đối tượng controller (tạo cái bánh)
$controller = new $str();//e.g : new HomeController();
//Gọi hàm 
$controller->$a();//e.g : $controller->create();
?>
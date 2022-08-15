<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ContactController
{
    function form()
    {
        require 'view/contact/form.php';
    }

    function sendEmail()
    {
        $name = $_POST['fullname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $message = $_POST['content'];
        $domain = get_domain();
        $emailService = new EmailService();
        $to = SHOP_OWNER;
        $subject = 'Godashop: Khách hàng liên hệ';
        $content = "
        Chào shop, <br>
        Thông tin khác hàng liên hệ:<br>
        Tên: $name<br>
        Email: $email<br>
        Số điện thoại: $mobile<br>
        Nội dung: $message<br>
        ======***======<br>
        Được gởi từ: $domain
        ";
        $emailService->send($to, $subject, $content);
    }
}
<?php 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CustomerController {
    function show() {
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        require 'view/customer/show.php';
    }

    function updateInfo() {
        $email = $_SESSION['email'];
        $name = $_POST['fullname'];
        $mobile = $_POST['mobile'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $customer->setName($name);
        $customer->setMobile($mobile);
        if(!empty($_POST['current_password']) && !empty($_POST['password'])) {
            $currentPassword = $_POST['current_password'];
            if(!password_verify($currentPassword,$customer->getPassword())) {
                $_SESSION['error'] = 'Mật khẩu không đúng';
                header('location:?c=customer&a=show');
                exit;
            }
        }
        if($customerRepository->update($customer)) {
            $_SESSION['name'] = $name;
            $_SESSION['success'] = 'Đã cập nhật thành công';
            header('location:?c=customer&a=show');
            exit;
        }
        $_SESSION['error'] = $customerRepository->getError();
        header('location:?c=customer&a=show');
        exit;
    }

    function orders() {
        require 'view/customer/orders.php';
    }

    function defaultShipping() {
        require 'view/customer/defaultShipping.php';
    }

    function register() {
        // process register
        // var_dump($_POST);


        $data = [];
        $data["name"] = $_POST['fullname'];
        $data["password"] = password_hash($_POST['password'],PASSWORD_BCRYPT);
        $data["mobile"]= $_POST['mobile'];
        $data["email"]= $_POST['email'];
        $data["login_by"]= $_POST['form'];
        $data["shipping_name"]= $_POST['fullname'];
        $data["shipping_mobile"]= $_POST['mobile'];
        $data["ward_id"]= null;
        $data["is_active"]= 0;
        $data["housenumber_street"]= '';

        $customerRepository = new CustomerRepository();
        if($customerRepository->save($data)) {
            $_SESSION['success'] = 'Đã đăng kí thành công';

            //Gởi mail để active account
                //khóa bí mật
               $key = JWT_KEY;
               $payload = [
                   'email' => $data['email']
               ];
               //Mã hóa payload
               $jwt = JWT::encode($payload, $key, 'HS256');

               $mailService = new EmailService();
               $to = $data['email'];
               $domain = get_domain();
               $activeLink = get_domain_site() . "?c=customer&a=active&token=$jwt";
               $subject = "Godashop: Verify Email";
               $content = "
               Dear, <br>
               Please click below link to active account <br>
               <a href= '$activeLink'>Active Account </a><br>
               ===========xxx=============<br>
               Sent form : $domain
               ";

               $mailService->send($to,$subject,$content);
               
               header('location:/');
               exit;
           }

            $_SESSION['error'] = $customerRepository->getError(); 
            header('location:/');
        }
        function active() {
            $token = $_GET['token'];
            $key = JWT_KEY;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $email = $decoded->email;
            $customerRepository = new CustomerRepository();
            $customer = $customerRepository->findEmail($email);
            if($email) {
                $customer->setIsActive(1);
                if(!$customerRepository->update($customer)) {
                    $_SESSION['error'] = $customerRepository->getError();
                    header('location:/');
                    exit;
                }
    
                $_SESSION['success'] = 'Đã kích hoạt tài khoản';
                header('location:/');
                exit;
    
            }
            $_SESSION['error'] = 'Email không tồn tại'; 
            header('location:/');
        }
    }


?>
<?php 
class AuthController {
    function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        //Nếu không tồn tại email thì hiển thị lỗi
        if(empty($customer)) {
            $_SESSION['error'] = "Error : Email $email không tồn tại";
            header('location:/');
            exit;
        }

        //Nếu mật khẩu không khớp
        if(!password_verify($password,$customer->getPassword())){
            $_SESSION['error'] = "Error : Sai mật khẩu";
            header('location:/');
            exit;
        }

        //Tài khoản bị vô hiệu hóa
        if(!$customer->getIsActive()){
            $_SESSION['error'] = "Error : Tài khoản bị vô hiệu hóa";
            header('location:/');
            exit;
        }

        //set name vs email cho session
        $_SESSION['name'] = $customer->getName();
        $_SESSION['email'] = $customer->getEmail();
        $_SESSION['success'] = 'Đăng nhập thành công';
        //về trang gốc
        header('location:/');
    }

    function logout() {
        session_destroy();
        header('location:/');
    }
}
?>
<?php
require_once('app/Requests/BaseRequest.php');
require_once('app/Models/Admin.php');

class UserRequest extends BaseRequest
{
    public function validateCreate($data)
    {

        // if (empty($data['name'])) {
        //     $this->errors['name'] = 'Tên không được để trống';
        // }
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        } else {
            $user_model = new User();
            $emailExists = $user_model->emailExists($data);
            if ($emailExists) {
                $this->errors['email'] = 'Email đã được sử dụng';
            }
        }
        if (empty($data['password'])) {
            $this->errors['password'] = 'Mật khẩu không được để trống';
        }
        return $this->errors;
    }

    public function validateUpdateInfo($data)
    {
        if (empty($data['id'])) {
            $this->errors['id'] = 'ID không được để trống';
        }

        if (empty($data['name'])) {
            $this->errors['name'] = 'Tên không được để trống';
        }

        if (empty($data['phone'])) {
            $this->errors['phone'] = 'SĐT không được để trống';
        }

        if (empty($data['address'])) {
            $this->errors['address'] = 'Địa chỉ không được để trống';
        }
        return $this->errors;
    }

    public function validateStatus($data) {
        if (!isset($data['id'])) {
            $this->errors['id'] = "Thiếu id";
        }
        if (!isset($data['active'])) {
            $this->errors['active'] = "Thiếu active";
        }
        return $this->errors;
    }
    
}

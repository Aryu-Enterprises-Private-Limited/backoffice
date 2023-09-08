<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->AdminModel = new \App\Models\AdminModel();
    }

    public function login()
    {

        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Dashboard';
            echo view(ADMIN_PATH . '/pages/dashboard', $this->data);
        } else {
            $this->data['title'] = 'Admin Login';
            // $this->session->setFlashdata('error_message', 'Please login!!!');
            return view(ADMIN_PATH . '/pages/login', $this->data);
        }
    }

    public function do_login()
    {
        $email = $this->request->getPostGet('email');
        $pword = $this->request->getPostGet('password');

        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('error_message', 'Login failed.');
            return redirect()->to('/' . ADMIN_PATH);
            // return view('/' . ADMIN_PATH);
        } else {
            $condition = ['email' => $email];
            $getAdminData = $this->AdminModel->get_selected_fields(ADMIN_USERS, $condition, array('status', 'password', 'id', 'user_name', 'email'));

            if ($getAdminData->getNumRows() == 1) {
                $status = $getAdminData->getRow()->status;
                if ($status == 1) {
                    $aPWord = $getAdminData->getRow()->password;
                    $isvalidPWord = password_verify($pword, $aPWord);
                    if ($isvalidPWord) {
                        $sessdata = array(
                            APP_NAME . '_session_admin_id' => $getAdminData->getRow()->id,
                            APP_NAME . '_session_admin_name' => $getAdminData->getRow()->user_name,
                            APP_NAME . '_session_admin_email' => $getAdminData->getRow()->email
                        );
                        $this->session->set($sessdata);
                        $this->session->setFlashdata('success_message', 'Login successful!');
                        return redirect()->to(ADMIN_PATH . '/dashboard');
                    } else {
                        $this->session->setFlashdata('error_message', 'Entered password is incorrect.');
                        return redirect()->to('/' . ADMIN_PATH);
                    }
                } else {
                    $this->session->setFlashdata('error_message', 'This account is not active.');
                    return redirect()->to('/' . ADMIN_PATH);
                }
            } else {
                $this->session->setFlashdata('error_message', 'This Email is not registered.');
                return redirect()->to('/' . ADMIN_PATH);
            }
        }

        // $this->session->setFlashdata('error_message', 'Login failed.');
        // return redirect()->to('/' . ADMIN_PATH);
    }

    // public function register(){

    // }
    public function dashboard()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Dashboard';
            echo view(ADMIN_PATH . '/pages/dashboard', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }


    public function logout(){
		$sessdata = array(
			APP_NAME . '_session_admin_id' => '',
			APP_NAME . '_session_admin_name' => '',
			APP_NAME . '_session_admin_email' => '',
		);
		$this->session->set($sessdata);
        $this->session->setFlashdata('success_message', 'Successfully logout from your account');
		// $this->setFlashMessage('success', 'Successfully logout from your account');
		return redirect()->to('/' . ADMIN_PATH);
    }

}

<?php

namespace App\Controllers\Admin;

// use App\Models\userModel;
use PhpParser\Node\Expr\Print_;
use App\Controllers\BaseController;

class Employee extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Employee List';
            echo view(ADMIN_PATH . '/employee/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $draw = $this->request->getPostGet('draw');
        $row_start = $this->request->getPostGet('start');
        $rowperpage = $this->request->getPostGet('length'); // Rows display per page

        $columnIndex = 0;
        if (isset($this->request->getPostGet('order')[0]['column'])) {
            $columnIndex = $this->request->getPostGet('order')[0]['column']; // Column index
        }
        $sortField = ''; // Column name
        if (isset($this->request->getPostGet('columns')[$columnIndex]['data'])) {
            $sortField = $this->request->getPostGet('columns')[$columnIndex]['data'];
        }
        $columnIndex = 'asc';
        if (isset($this->request->getPostGet('order')[0]['dir'])) {
            $sortJob = $this->request->getPostGet('order')[0]['dir']; // asc or desc
        }
        $dtSearchKeyVal = '';
        if (isset($this->request->getPostGet('search')['value'])) {
            $dtSearchKeyVal = $this->request->getPostGet('search')['value']; // Search value
        }
        $likeArr = [];
        $condition = array('is_deleted' => '0');
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'first_name' => trim($dtSearchKeyVal),
                'last_name' => strtolower(trim($dtSearchKeyVal)),
                'dob' => trim($dtSearchKeyVal),
                'phone' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
                'address' => trim($dtSearchKeyVal),
                'pin_code' => trim($dtSearchKeyVal),
                'city' => trim($dtSearchKeyVal),
                'state' => trim($dtSearchKeyVal),
                'blood_grp' => trim($dtSearchKeyVal),
                'aadhar_no' => trim($dtSearchKeyVal),
                'pan_no' => trim($dtSearchKeyVal),
                'relationship' => trim($dtSearchKeyVal),
                'r_name' => trim($dtSearchKeyVal),
                'r_phone' => trim($dtSearchKeyVal),
                'r_email' => trim($dtSearchKeyVal),
                'r_address' => trim($dtSearchKeyVal),
                'work_exp' => trim($dtSearchKeyVal),
                'notes' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        // if (isset($_GET['export']) && $_GET['export'] == 'excel') {
        //     $returnArr['status'] = '1';
        //     $returnArr['response'] = $ajaxDataArr;
        //     return $returnArr;
        // }
        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            // $notes_details = $this->LmsModel->table_join()->getResult();

            $rowId =  (string)$row->id;
            $disp_status = 'Inactive';
            $actTitle = 'Click to active';
            $mode = 1;
            $btnColr = 'btn-danger';
            if (isset($row->status) && $row->status == '1') {
                $disp_status = 'Active';
                $mode = 0;
                $btnColr = 'btn-success';
                $actTitle = 'Click to inactivate';
            }
            $statusTxt = $actTitle;
            $actionTxt = '';

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/employee/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/employee/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/employee/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/employee/delete"><i class="fas fa-trash-alt"></i></a>';

            // $source = '';
            // $logo = MEMBER_PROFILE_DEFAULT;
            // if (isset($row->logo) && $row->logo != '') {
            //     $logo = COMPANIES_LOGO_PATH . $row->logo;
            //     $source .= '<img src="' . base_url() . '/' . $logo . '" width="128" height="128" class="rounded-circle img-thumbnail" alt="' . $row->name . ' Image">';
            // } else {
            //     $source .= '<img src="assets/images/users/avatar-1.jpg" alt="user-img" class="rounded-circle user-img">';
            // }


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'dob' => $row->dob,
                'phone' => $row->phone,
                'email' => $row->email,
                'address' => $row->address,
                'pin_code' => $row->pin_code,
                'city' => $row->city,
                'state' => $row->state,
                "blood_grp" => $row->blood_grp,
                'aadhar_no' => $row->aadhar_no,
                'pan_no' => $row->pan_no,
                'relationship' => $row->relationship,
                'r_name' => $row->r_name,
                'r_phone' => $row->r_phone,
                'r_email' => $row->r_email,
                'r_address' => $row->r_address,
                'work_exp' => $row->work_exp,
                'notes' => $row->notes,
                "status" =>  $statusTxt,
                "action" =>  $actionTxt
            );
        }
        $response = array(
            "status" => '1',
            "draw" => intval($draw),
            "iTotalRecords" => $totCounts,
            "iTotalDisplayRecords" => $totCounts,
            "aaData" => $tblData
        );
        $returnArr = $response;
        echo json_encode($returnArr);
    }


    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            if ($id != '') {
                $condition = array('is_deleted' => '0');
                $this->data['info'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, $condition)->getRow();
                if (!empty($this->data['info'])) {
                    $this->data['title'] = 'Edit Employee Details';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Employee');
                    return redirect()->route(ADMIN_PATH . '/employee/list');
                }
            } else {
                $this->data['title'] = 'Add Employee';
            }
            echo view(ADMIN_PATH . '/employee/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function form_validation()
    {
        if ($this->checkSession('A') != '') {
            $type = (string)$this->request->getPostGet('type');
            $id = (string)$this->request->getPostGet('id');


            $first_name = (string)$this->request->getPostGet('first_name');
            $last_name = (string)$this->request->getPostGet('last_name');
            $date_of_birth = (string)$this->request->getPostGet('date_of_birth');
            $phone_number = (string)$this->request->getPostGet('phone_number');
            $email = (string)$this->request->getPostGet('email');
            $address = (string)$this->request->getPostGet('address');
            $pin_code = (string)$this->request->getPostGet('pin_code');
            $city = (string)$this->request->getPostGet('city');
            $state = (string)$this->request->getPostGet('state');
            $blood_group = (string)$this->request->getPostGet('blood_group');
            $aadhar_no = (string)$this->request->getPostGet('aadhar_no');
            $pan_no = (string)$this->request->getPostGet('pan_no');
            $password = (string)$this->request->getPostGet('password');
            $confirmpassword = (string)$this->request->getPostGet('confirmpassword');


            $relationship = (string)$this->request->getPostGet('relationship');
            $r_name = (string)$this->request->getPostGet('r_name');
            $r_phone = (string)$this->request->getPostGet('r_phone');
            $r_email = (string)$this->request->getPostGet('r_email');
            $r_address = (string)$this->request->getPostGet('r_address');
            $pin_code = (string)$this->request->getPostGet('pin_code');
            $city = (string)$this->request->getPostGet('city');
            $state = (string)$this->request->getPostGet('state');

            $fresher_experience = (string)$this->request->getPostGet('fresher_experience');
            $file = $this->request->getFile('cv_resume');
            // $file = $this->request->getFile('cv_resume');
            $notes = (string)$this->request->getPostGet('notes');
            $status = (string)$this->request->getPostGet('status');


            if ($id == '') {
                $validation = \Config\Services::validation();
                if (isset($type) && $type == 'step_1') {
                    $validation->setRules(
                        [
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'date_of_birth' => 'required',
                            'phone_number' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.phone]',
                            'email' => 'required|valid_email|is_unique[employee_details.email]',
                            'address' => 'required',
                            'pin_code' => 'required',
                            'city' => 'required',
                            'state' => 'required',
                            'blood_group' => 'required',
                            'aadhar_no' => 'required',
                            'pan_no' => 'required',
                            'password' => 'required|max_length[25]|min_length[8]',
                            'confirmpassword' => 'required|max_length[255]|matches[password]',
                        ],
                        [   // Errors
                            'first_name' => [
                                'required' => 'This field is required.',
                            ],
                            'last_name' => [
                                'required' => 'This field is required.',
                            ],
                            'date_of_birth' => [
                                'required' => 'This field is required.',
                            ],
                            'phone_number' => [
                                'required' => 'This field is required.',
                            ],
                            'email' => [
                                'required' => 'This field is required.',
                            ],
                            'address' => [
                                'required' => 'This field is required.',
                            ],
                            'pin_code' => [
                                'required' => 'This field is required.',
                            ],
                            'city' => [
                                'required' => 'This field is required.',
                            ],
                            'state' => [
                                'required' => 'This field is required.',
                            ],
                            'blood_group' => [
                                'required' => 'This field is required.',
                            ],
                            'aadhar_no' => [
                                'required' => 'This field is required.',
                            ],
                            'pan_no' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';
                    }
                } else if ($type == 'step_2') {
                    $validation->setRules(
                        [
                            'relationship' => 'required',
                            'r_name' => 'required',
                            'r_phone' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.r_phone]',
                            'r_email' => 'required|valid_email|is_unique[employee_details.r_email]',
                            'r_address' => 'required',

                        ],
                        [
                            'relationship' => [
                                'required' => 'This field is required.',
                            ],
                            'r_name' => [
                                'required' => 'This field is required.',
                            ],
                            'r_phone' => [
                                'required' => 'This field is required.',
                            ],
                            'r_email' => [
                                'required' => 'This field is required.',
                            ],
                            'r_address' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';
                    }
                } else {
                    if ($status == '') {
                        $status = 'off';
                    }
                    // $validation = \Config\Services::validation();
                    $validation->setRules(
                        [
                            'fresher_experience' => 'required',
                            'cv_resume' => 'required',
                            'notes' => 'required',
                        ],
                        [   // Errors
                            'fresher_experience' => [
                                'required' => 'This field is required.',
                            ],
                            'cv_resume' => [
                                'required' => 'This field is required.',
                            ],
                            'notes' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        if ($status == 'on') {
                            $status = '1';
                        } else {
                            $status = '0';
                        }
                        $dataArr = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'dob' => $date_of_birth,
                            'phone' => $phone_number,
                            'email' => $email,
                            'address' => $address,
                            'pin_code' => $pin_code,
                            'city' => $city,
                            'state' => $state,
                            'blood_grp' => $blood_group,
                            'aadhar_no' => $aadhar_no,
                            'pan_no' => $pan_no,
                            'relationship' => $relationship,
                            'r_name' => $r_name,
                            'r_phone' => $r_phone,
                            'r_email' => $r_email,
                            'r_address' => $r_address,
                            'work_exp' => $fresher_experience,
                            'status' => $status,
                            'notes' => $notes,
                            'is_deleted' => '0',
                        );
                        $dataArr['password'] = password_hash($password, PASSWORD_DEFAULT);
                        if ($file !== null) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move(WRITEPATH . EMPLOYEE_RESUME_DOC_PATH, $newName);
                                $dataArr['resume'] = $file->getName();
                            } else {
                                echo 'Upload failed.';
                            }
                        } else {
                        }
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';
                        $this->LmsModel->simple_insert(EMPLOYEE_DETAILS, $dataArr);
                        $redirectUrl = site_url('/' . ADMIN_PATH . '/employee/list');
                        return $this->response->setJSON(['redirect' => $redirectUrl]);
                    }
                }
            } else {
                $validation = \Config\Services::validation();
                if (isset($type) && $type == 'step_1') {
                    $validation->setRules(
                        [
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'date_of_birth' => 'required',
                            'phone_number' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.phone,id,' . $id . ']',
                            'email' => 'required|valid_email|is_unique[employee_details.email,id,' . $id . ']',
                            'address' => 'required',
                            'pin_code' => 'required',
                            'city' => 'required',
                            'state' => 'required',
                            'blood_group' => 'required',
                            'aadhar_no' => 'required',
                            'pan_no' => 'required',
                            // 'password' => 'required|max_length[25]|min_length[8]',
                            // 'confirmpassword' => 'required|max_length[255]|matches[password]',
                        ],
                        [   // Errors
                            'first_name' => [
                                'required' => 'This field is required.',
                            ],
                            'last_name' => [
                                'required' => 'This field is required.',
                            ],
                            'date_of_birth' => [
                                'required' => 'This field is required.',
                            ],
                            'phone_number' => [
                                'required' => 'This field is required.',
                            ],
                            'email' => [
                                'required' => 'This field is required.',
                            ],
                            'address' => [
                                'required' => 'This field is required.',
                            ],
                            'pin_code' => [
                                'required' => 'This field is required.',
                            ],
                            'city' => [
                                'required' => 'This field is required.',
                            ],
                            'state' => [
                                'required' => 'This field is required.',
                            ],
                            'blood_group' => [
                                'required' => 'This field is required.',
                            ],
                            'aadhar_no' => [
                                'required' => 'This field is required.',
                            ],
                            'pan_no' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';
                    }
                } else if ($type == 'step_2') {
                    $validation->setRules(
                        [
                            'relationship' => 'required',
                            'r_name' => 'required',
                            'r_phone' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.r_phone,id,' . $id . ']',
                            'r_email' => 'required|valid_email|is_unique[employee_details.r_email,id,' . $id . ']',
                            'r_address' => 'required',

                        ],
                        [
                            'relationship' => [
                                'required' => 'This field is required.',
                            ],
                            'r_name' => [
                                'required' => 'This field is required.',
                            ],
                            'r_phone' => [
                                'required' => 'This field is required.',
                            ],
                            'r_email' => [
                                'required' => 'This field is required.',
                            ],
                            'r_address' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';
                    }
                } else {
                    $validation->setRules(
                        [
                            'fresher_experience' => 'required',
                            // 'cv_resume' => 'required',
                            'notes' => 'required',
                        ],
                        [   // Errors
                            'fresher_experience' => [
                                'required' => 'This field is required.',
                            ],
                            'cv_resume' => [
                                'required' => 'This field is required.',
                            ],
                            'notes' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        $returnArr['status'] = '0';
                        $returnArr['response'] = $errors;
                        return $this->response->setStatusCode(422)->setJSON($returnArr);
                    } else {
                        if ($status == 'on') {
                            $status = '1';
                        } else {
                            $status = '0';
                        }
                        $dataArr = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'dob' => $date_of_birth,
                            'phone' => $phone_number,
                            'email' => $email,
                            'address' => $address,
                            'pin_code' => $pin_code,
                            'city' => $city,
                            'state' => $state,
                            'blood_grp' => $blood_group,
                            'aadhar_no' => $aadhar_no,
                            'pan_no' => $pan_no,
                            'relationship' => $relationship,
                            'r_name' => $r_name,
                            'r_phone' => $r_phone,
                            'r_email' => $r_email,
                            'r_address' => $r_address,
                            'work_exp' => $fresher_experience,
                            'status' => $status,
                            'notes' => $notes,
                            'is_deleted' => '0',
                        );
                        $dataArr['password'] = password_hash($password, PASSWORD_DEFAULT);
                        if ($file !== null) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move(WRITEPATH . EMPLOYEE_RESUME_DOC_PATH, $newName);
                                $dataArr['resume'] = $file->getName();
                            } else {
                                echo 'Upload failed.';
                            }
                        } else {
                        }
                        $returnArr['status'] = '1';
                        $returnArr['response'] = 'success';

                        $condition = array('id' => $id);
                        $this->LmsModel->update_details(EMPLOYEE_DETAILS, $dataArr, $condition);
                        $redirectUrl = site_url('/' . ADMIN_PATH . '/employee/list');
                        return $this->response->setJSON(['redirect' => $redirectUrl]);
                    }
                }
            }






            // if (isset($type) && $type == 'step_1') {
            //     $validation = \Config\Services::validation();
            //     if ($id == '') {
            //         $validation->setRules(
            //             [
            //                 'first_name' => 'required',
            //                 'last_name' => 'required',
            //                 'date_of_birth' => 'required',
            //                 'phone_number' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.phone]',
            //                 'email' => 'required|valid_email|is_unique[employee_details.email]',
            //                 'address' => 'required',
            //                 'pin_code' => 'required',
            //                 'city' => 'required',
            //                 'state' => 'required',
            //                 'blood_group' => 'required',
            //                 'aadhar_no' => 'required',
            //                 'pan_no' => 'required',
            //                 'password' => 'required|max_length[25]|min_length[8]',
            //                 'confirmpassword' => 'required|max_length[255]|matches[password]',
            //             ],
            //             [   // Errors
            //                 'first_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'last_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'date_of_birth' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'phone_number' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'email' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'address' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'pin_code' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'city' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'state' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'blood_group' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'aadhar_no' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'pan_no' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     } else {
            //         $validation->setRules(
            //             [
            //                 'first_name' => 'required',
            //                 'last_name' => 'required',
            //                 'date_of_birth' => 'required',
            //                 'phone_number' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.phone,id,' . $id . ']',
            //                 'email' => 'required|valid_email|is_unique[employee_details.email,id,' . $id . ']',
            //                 'address' => 'required',
            //                 'pin_code' => 'required',
            //                 'city' => 'required',
            //                 'state' => 'required',
            //                 'blood_group' => 'required',
            //                 'aadhar_no' => 'required',
            //                 'pan_no' => 'required',
            //                 // 'password' => 'required|max_length[25]|min_length[8]',
            //                 // 'confirmpassword' => 'required|max_length[255]|matches[password]',
            //             ],
            //             [   // Errors
            //                 'first_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'last_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'date_of_birth' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'phone_number' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'email' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'address' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'pin_code' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'city' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'state' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'blood_group' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'aadhar_no' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'pan_no' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     }
            //     if (!$validation->withRequest($this->request)->run()) {
            //         $errors = $validation->getErrors();
            //         $returnArr['status'] = '0';
            //         $returnArr['response'] = $errors;
            //         return $this->response->setStatusCode(422)->setJSON($returnArr);
            //     } else {
            //         $returnArr['status'] = '1';
            //         $returnArr['response'] = 'success';
            //     }
            // } else if ($type == 'step_2') {
            //     $validation = \Config\Services::validation();
            //     if ($id == '') {
            //         $validation->setRules(
            //             [
            //                 'relationship' => 'required',
            //                 'r_name' => 'required',
            //                 'r_phone' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.r_phone]',
            //                 'r_email' => 'required|valid_email|is_unique[employee_details.r_email]',
            //                 'r_address' => 'required',

            //             ],
            //             [
            //                 'relationship' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_phone' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_email' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_address' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     } else {
            //         $validation->setRules(
            //             [
            //                 'relationship' => 'required',
            //                 'r_name' => 'required',
            //                 'r_phone' => 'required|max_length[10]|min_length[10]|is_unique[employee_details.r_phone,id,' . $id . ']',
            //                 'r_email' => 'required|valid_email|is_unique[employee_details.r_email,id,' . $id . ']',
            //                 'r_address' => 'required',

            //             ],
            //             [
            //                 'relationship' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_name' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_phone' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_email' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'r_address' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     }
            //     if (!$validation->withRequest($this->request)->run()) {
            //         $errors = $validation->getErrors();
            //         $returnArr['status'] = '0';
            //         $returnArr['response'] = $errors;
            //         return $this->response->setStatusCode(422)->setJSON($returnArr);
            //     } else {
            //         $returnArr['status'] = '1';
            //         $returnArr['response'] = 'success';
            //     }
            // } else {
            //     if ($status == '') {
            //         $status = 'off';
            //     }
            //     $validation = \Config\Services::validation();
            //     if ($id = '') {
            //         $validation->setRules(
            //             [
            //                 'fresher_experience' => 'required',
            //                 'cv_resume' => 'required',
            //                 'notes' => 'required',
            //             ],
            //             [   // Errors
            //                 'fresher_experience' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'cv_resume' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'notes' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     } else {
            //         $validation->setRules(
            //             [
            //                 'fresher_experience' => 'required',
            //                 // 'cv_resume' => 'required',
            //                 'notes' => 'required',
            //             ],
            //             [   // Errors
            //                 'fresher_experience' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'cv_resume' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //                 'notes' => [
            //                     'required' => 'This field is required.',
            //                 ],
            //             ]
            //         );
            //     }

            //     if (!$validation->withRequest($this->request)->run()) {
            //         $errors = $validation->getErrors();
            //         $returnArr['status'] = '0';
            //         $returnArr['response'] = $errors;
            //         return $this->response->setStatusCode(422)->setJSON($returnArr);
            //     } else {
            //         if ($id = '') {
            //             if ($status == 'on') {
            //                 $status = '1';
            //             } else {
            //                 $status = '0';
            //             }
            //             $dataArr = array(
            //                 'first_name' => $first_name,
            //                 'last_name' => $last_name,
            //                 'dob' => $date_of_birth,
            //                 'phone' => $phone_number,
            //                 'email' => $email,
            //                 'address' => $address,
            //                 'pin_code' => $pin_code,
            //                 'city' => $city,
            //                 'state' => $state,
            //                 'blood_grp' => $blood_group,
            //                 'aadhar_no' => $aadhar_no,
            //                 'pan_no' => $pan_no,
            //                 'relationship' => $relationship,
            //                 'r_name' => $r_name,
            //                 'r_phone' => $r_phone,
            //                 'r_email' => $r_email,
            //                 'r_address' => $r_address,
            //                 'work_exp' => $fresher_experience,
            //                 'status' => $status,
            //                 'notes' => $notes,
            //                 'is_deleted' => '0',
            //             );
            //             $dataArr['password'] = password_hash($password, PASSWORD_DEFAULT);
            //             if ($file !== null) {
            //                 if ($file->isValid() && !$file->hasMoved()) {
            //                     $newName = $file->getRandomName();
            //                     $file->move(WRITEPATH . EMPLOYEE_RESUME_DOC_PATH, $newName);
            //                     $dataArr['resume'] = $file->getName();
            //                 } else {
            //                     echo 'Upload failed.';
            //                 }
            //             } else {
            //             }
            //             $returnArr['status'] = '1';
            //             $returnArr['response'] = 'success';
            //             $this->LmsModel->simple_insert(EMPLOYEE_DETAILS, $dataArr);
            //         } else {
            //             if ($status == 'on') {
            //                 $status = '1';
            //             } else {
            //                 $status = '0';
            //             }
            //             $dataArr = array(
            //                 'first_name' => $first_name,
            //                 'last_name' => $last_name,
            //                 'dob' => $date_of_birth,
            //                 'phone' => $phone_number,
            //                 'email' => $email,
            //                 'address' => $address,
            //                 'pin_code' => $pin_code,
            //                 'city' => $city,
            //                 'state' => $state,
            //                 'blood_grp' => $blood_group,
            //                 'aadhar_no' => $aadhar_no,
            //                 'pan_no' => $pan_no,
            //                 'relationship' => $relationship,
            //                 'r_name' => $r_name,
            //                 'r_phone' => $r_phone,
            //                 'r_email' => $r_email,
            //                 'r_address' => $r_address,
            //                 'work_exp' => $fresher_experience,
            //                 'status' => $status,
            //                 'notes' => $notes,
            //                 'is_deleted' => '0',
            //             );
            //             $dataArr['password'] = password_hash($password, PASSWORD_DEFAULT);
            //             if ($file !== null) {
            //                 if ($file->isValid() && !$file->hasMoved()) {
            //                     $newName = $file->getRandomName();
            //                     $file->move(WRITEPATH . EMPLOYEE_RESUME_DOC_PATH, $newName);
            //                     $dataArr['resume'] = $file->getName();
            //                 } else {
            //                     echo 'Upload failed.';
            //                 }
            //             } else {
            //             }
            //             $returnArr['status'] = '1';
            //             $returnArr['response'] = 'success';

            //             $condition = array('id' => $id);
            //             $this->LmsModel->update_details(EMPLOYEE_DETAILS, $dataArr, $condition);
            //         }
            //         $redirectUrl = site_url('/' . ADMIN_PATH . '/employee/list');
            //         return $this->response->setJSON(['redirect' => $redirectUrl]);
            //     }
            // }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function update_status()
    {
        if ($this->checkSession('A') != '') {
            $returnArr['status'] = '0';
            $returnArr['response'] = 'Failed to updated, Please try again';
            if ($this->checkSession('A') == '') {
                $returnArr['status'] = '00';
                $returnArr['response'] = 'Session has been timed out, Please login again and try.';
            } else {
                $mode = $this->request->getPostGet('mode');
                // echo $mode;die;
                $id = $this->request->getPostGet('record_id');
                $status = ($mode == '0') ? '0' : '1';
                $newdata = array('status' => $status);
                $condition = array('id' => $id);
                $this->LmsModel->update_details(EMPLOYEE_DETAILS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Employee Status Changed Successfully';
            }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function view($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            if ($id != '') {
                $condition = array('id' => $id, 'is_deleted' => '0');
                $this->data['empDetails'] = $this->LmsModel->get_all_details(EMPLOYEE_DETAILS, $condition)->getRow();
                // $this->data['lmsDetails'] = $this->LmsModel->get_all_details(LMS, $condition)->getRow();
                if (!empty($this->data['empDetails'])) {
                    $this->data['title'] = 'Employee view';
                    echo view(ADMIN_PATH . '/employee/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Crm');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/employee/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Employee');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/employee/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function delete()
    {
        if ($this->checkSession('A') != '') {
            $returnArr['status'] = '0';
            $returnArr['response'] = 'Failed to delete, Please try again';
            if ($this->checkSession('A') == '') {
                $returnArr['status'] = '00';
                $returnArr['response'] = 'Session has been timed out, Please login again and try.';
            } else {
                $record_id = $this->request->getPostGet('record_id');
                if (isset($record_id)) {
                    $this->LmsModel->isDelete(EMPLOYEE_DETAILS, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Record Deleted Successfully';
                }
                // $this->LmsModel->commonDelete(LMS, array('id' => $record_id));
            }

            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function showFile()
    {
        $uri = service('uri');
        $filename = $uri->getSegment(4);

        helper("filesystem");
        $path = WRITEPATH . 'uploads/employee_cv_doc/';

        $fullpath = $path . $filename;
        $file = new \CodeIgniter\Files\File($fullpath, true);
        $binary = readfile($fullpath);
        return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setHeader('Content-disposition', 'inline; filename="' . $file->getBasename() . '"')
            ->setStatusCode(200)
            ->setBody($binary);
    }
}

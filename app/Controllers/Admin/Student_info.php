<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Student_info extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Student info List';
            echo view(ADMIN_PATH . '/student_info/list', $this->data);
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
                'last_name' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
                'current_status' => trim($dtSearchKeyVal),
                'clg_comp_info' => trim($dtSearchKeyVal),
                'par_guard_name' => trim($dtSearchKeyVal),
                'parent_occ' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(STUDENT_INFO, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(STUDENT_INFO, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/student_info/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/student_info/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/student_info/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/student_info/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'first_name' => ucfirst($row->first_name).' '.ucfirst($row->last_name),
                'email' => ucfirst($row->email),
                'current_status' =>ucfirst(str_replace("_", " ", $row->current_status)),
                'clg_comp_info' => ucfirst($row->clg_comp_info),
                'par_guard_name' => ucfirst($row->par_guard_name),
                'parent_occ' => isset($row->parent_occ) && $row->parent_occ !='' ? ucfirst($row->parent_occ) : '-',
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
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['stu_info'] = $this->LmsModel->get_selected_fields(STUDENT_INFO, $condition)->getRow();
                if (!empty($this->data['stu_info'])) {
                    $this->data['title'] = 'Edit Student info';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Student info');
                    return redirect()->route(ADMIN_PATH . '/student_info/list');
                }
            } else {
                $this->data['title'] = 'Add Student info';
            }
            echo view(ADMIN_PATH . '/student_info/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $first_name = (string)$this->request->getPostGet('first_name');
            $last_name = (string)$this->request->getPostGet('last_name');
            $email = (string)$this->request->getPostGet('email');
            $current_status = (string)$this->request->getPostGet('current_status');
            $clg_comp_info = (string)$this->request->getPostGet('clg_comp_info');
            $par_guard_name = (string)$this->request->getPostGet('par_guard_name');
            $par_guard_phno = (string)$this->request->getPostGet('par_guard_phno');
            $parent_occ = (string)$this->request->getPostGet('parent_occ');
            $ref_no_1 = (string)$this->request->getPostGet('ref_no_1');
            $ref_no_2 = (string)$this->request->getPostGet('ref_no_2');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($first_name != '' && $last_name != '' && $email != '' && $current_status != '' && $clg_comp_info != '' && $par_guard_name != ''  && $par_guard_phno != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'current_status' => $current_status,
                    'clg_comp_info' => $clg_comp_info,
                    'par_guard_name' => $par_guard_name,
                    'par_guard_phno' => $par_guard_phno,
                    'parent_occ' => $parent_occ,
                    'ref_no_1' => $ref_no_1,
                    'ref_no_2' => $ref_no_2,
                    'status' => $status,
                    'is_deleted' => '0',
                );

                if ($id == '') {
                    $this->LmsModel->simple_insert(STUDENT_INFO, $dataArr);
                    $this->session->setFlashdata('success_message', 'Student info added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(STUDENT_INFO, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Student info update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/student_info/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/student_info/add';
                else $url = ADMIN_PATH . '/student_info/edit/' . $id;
            }
            return redirect()->to("$url");
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
                $id = $this->request->getPostGet('record_id');
                $status = ($mode == '0') ? '0' : '1';
                $newdata = array('status' => $status);
                $condition = array('id' => $id);
                $this->LmsModel->update_details(STUDENT_INFO, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Student info status Changed Successfully';
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
                $this->data['stuDetails'] = $this->LmsModel->get_all_details(STUDENT_INFO, $condition)->getRow();
                if (!empty($this->data['stuDetails'])) {
                    $this->data['title'] = 'Student info  view';
                    echo view(ADMIN_PATH . '/student_info/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Student info');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/student_info/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Student Info');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/student_info/list');
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
                    $this->LmsModel->isDelete(STUDENT_INFO, 'id', TRUE);
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
}

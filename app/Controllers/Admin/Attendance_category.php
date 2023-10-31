<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Attendance_category extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Attendance Category List';
            echo view(ADMIN_PATH . '/attendance_category/list', $this->data);
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
                'category_name' => trim($dtSearchKeyVal),
                'category_code' => trim($dtSearchKeyVal),
                'category_value' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(ATTENDANCE_CATEGORY, $condition, '', $likeArr);
        $sortArr = array('category_name' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(ATTENDANCE_CATEGORY, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/attendance_category/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/attendance_category/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/attendance_category/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/attendance_category/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'category_name' => ucfirst($row->category_name),
                'category_code' => ucfirst($row->category_code),
                'category_value' => ucfirst($row->category_value),
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
                $this->data['att_cat_info'] = $this->LmsModel->get_selected_fields(ATTENDANCE_CATEGORY, $condition)->getRow();
                if (!empty($this->data['att_cat_info'])) {
                    $this->data['title'] = 'Edit Attendance Category';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Attendance Category');
                    return redirect()->route(ADMIN_PATH . '/attendance_category/list');
                }
            } else {
                $this->data['title'] = 'Add Attendance Category';
            }
            echo view(ADMIN_PATH . '/attendance_category/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $category_name = (string)$this->request->getPostGet('category_name');
            $category_code = (string)$this->request->getPostGet('category_code');
            $category_value = (string)$this->request->getPostGet('category_value');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');

            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($category_name != '' && $category_code != '' && $category_value != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'category_name' => $category_name,
                    'category_code' => $category_code,
                    'category_value' => $category_value,
                    'status' => $status,
                    'is_deleted' => '0',
                );
                if ($id == '') {
                    $this->LmsModel->simple_insert(ATTENDANCE_CATEGORY, $dataArr);
                    $this->session->setFlashdata('success_message', 'Attendance Category added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(ATTENDANCE_CATEGORY, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Attendance Category update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/attendance_category/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/attendance_category/add';
                else $url = ADMIN_PATH . '/attendance_category/edit/' . $id;
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
                $this->LmsModel->update_details(ATTENDANCE_CATEGORY, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Attendance Category Changed Successfully';
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
                $this->data['att_category_details'] = $this->LmsModel->get_all_details(ATTENDANCE_CATEGORY, $condition)->getRow();
                if (!empty($this->data['att_category_details'])) {
                    $this->data['title'] = 'Attendance Category view';
                    echo view(ADMIN_PATH . '/attendance_category/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Attendance Category');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/attendance_category/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Attendance Category');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/attendance_category/list');
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
                    $this->LmsModel->isDelete(ATTENDANCE_CATEGORY, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Attendance Category Deleted Successfully';
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

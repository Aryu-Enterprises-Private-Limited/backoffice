<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Interview_task extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Interview Task List';
            echo view(ADMIN_PATH . '/interview_task/list', $this->data);
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
                'job_type_name' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(INTERVIEW_TASK, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(INTERVIEW_TASK, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/interview_task/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/interview_task/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/interview_task/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/interview_task/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'date' => $row->date,
                'candidate_name' => $row->candidate_name,
                'interview_task_sts' => ucfirst($row->interview_task_sts),
                'created_at' => $row->created_at,
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
            $this->data['candidate_opt'] = $this->LmsModel->get_selected_fields(CANDIDATES_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'first_name', 'last_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['inter_task_info'] = $this->LmsModel->get_selected_fields(INTERVIEW_TASK, $condition)->getRow();
            //    echo"<pre>"; print_r($this->data['inter_task_info']);die;
                if (!empty($this->data['inter_task_info'])) {
                    $this->data['title'] = 'Edit Interview Task';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Interview Task');
                    return redirect()->route(ADMIN_PATH . '/interview_task/list');
                }
            } else {
                $this->data['title'] = 'Add Interview Task';
            }
            echo view(ADMIN_PATH . '/interview_task/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        //   echo"<pre>";print_r($_POST);
        //   print_r(array_filter($_POST['addmore']));
        //   die;
        if ($this->checkSession('A') != '') {
            $date = (string)$this->request->getPostGet('date');
            $candidate_name = (string)$this->request->getPostGet('candidate_name');
            $interview_task_sts = (string)$this->request->getPostGet('interview_task_sts');
            $task_title_link = array_values($this->request->getPostGet('addmore'));
            $comment = (string)$this->request->getPostGet('comment');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            $str_arr = explode(",", $candidate_name);
            if ($status == '') {
                $status = 'off';
            }
            $array_fil = array_filter($task_title_link);
            $fSubmit = FALSE;
            if ($date != ''  && $interview_task_sts != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'date' => $date,
                    'candidate_id' => $str_arr[0],
                    'candidate_name' => $str_arr[1],
                    'comments' => $comment,
                    'interview_task_sts' => $interview_task_sts,
                    'status' => $status,
                    'is_deleted' => '0',
                );
                $dataArr['task_title_link'] = json_encode($array_fil);
                if ($id == '') {
                    $this->LmsModel->simple_insert(INTERVIEW_TASK, $dataArr);
                    $this->session->setFlashdata('success_message', 'Interview Task added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(INTERVIEW_TASK, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Interview Task update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/interview_task/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/interview_task/add';
                else $url = ADMIN_PATH . '/interview_task/edit/' . $id;
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
                $this->LmsModel->update_details(INTERVIEW_TASK, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Interview Task Status Changed Successfully';
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
                $this->data['interview_taskDetails'] = $this->LmsModel->get_all_details(INTERVIEW_TASK, $condition)->getRow();
                if (!empty($this->data['interview_taskDetails'])) {
                    $this->data['title'] = 'Interview Task view';
                    echo view(ADMIN_PATH . '/interview_task/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Job Tpye');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/interview_task/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Job Type');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/interview_task/list');
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
                    $this->LmsModel->isDelete(INTERVIEW_TASK, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Interview Task Deleted Successfully';
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

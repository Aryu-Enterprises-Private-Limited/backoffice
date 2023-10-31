<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Public_holiday extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Public Holiday List';
            echo view(ADMIN_PATH . '/public_holiday/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
    
    public function list_ajax($returnType = 'json')
    {
        $condition = array('is_deleted' => '0');
        $ajaxDataArr = $this->LmsModel->public_yr_group($condition)->getResult();
        // echo"<pre>";print_r($ajaxDataArr);die;
        $tblData = array();
        $totCounts = count($ajaxDataArr);
        foreach ($ajaxDataArr as $row) {
            $actionTxt = '';
            $qryString = 'year=' . $row->current_year;
            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/public_holiday/list_details?' . $qryString . '"><i class="fa fa-plus" aria-hidden="true"></i></a>';
            $tblData[] = array(
                'current_year' => ucfirst($row->current_year),
                "action" =>  $actionTxt
            );
        }
        $response = array(
            "status" => '1',
            // "draw" => intval($draw),
            "iTotalRecords" => $totCounts,
            "iTotalDisplayRecords" => $totCounts,
            "aaData" => $tblData
        );
        $returnArr = $response;
        echo json_encode($returnArr);
    }


    public function list_details()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Public Holiday List';
            echo view(ADMIN_PATH . '/public_holiday/details_list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }


    public function details_list_ajax($returnType = 'json')
    {
        $draw = $this->request->getPostGet('draw');
        $row_start = $this->request->getPostGet('start');
        $rowperpage = $this->request->getPostGet('length'); // Rows display per page
        $year = $this->request->getPostGet('year');

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
        $condition = array('is_deleted' => '0','status'=>'1','current_year'=> $year);
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'reason' => trim($dtSearchKeyVal),
                'current_year' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(PUBLIC_HOLIDAY, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(PUBLIC_HOLIDAY, $condition, $sortArr, $rowperpage, $row_start, $likeArr);
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/public_holiday/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/public_holiday/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/public_holiday/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/public_holiday/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'reason' => ucfirst($row->reason),
                'current_year' => $row->current_year,
                'date' => $row->date,
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
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['pholiday_info'] = $this->LmsModel->get_selected_fields(PUBLIC_HOLIDAY, $condition)->getRow();
                if (!empty($this->data['pholiday_info'])) {
                    $this->data['title'] = 'Edit Public Holiday';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Public Holiday');
                    return redirect()->route(ADMIN_PATH . '/public_holiday/list');
                }
            } else {
                $this->data['title'] = 'Add Public Holiday';
            }
            echo view(ADMIN_PATH . '/public_holiday/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {

        if ($this->checkSession('A') != '') {
            $reason = (string)$this->request->getPostGet('reason');
            $current_year = (string)$this->request->getPostGet('curr_year');
            $date = (string)$this->request->getPostGet('date');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($reason != '' && $current_year != '' && $date != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'reason' => $reason,
                    'current_year' => $current_year,
                    'date' => $date,
                    'status' => $status,
                    'is_deleted' => '0',
                );

                if ($id == '') {
                    $this->LmsModel->simple_insert(PUBLIC_HOLIDAY, $dataArr);
                    $this->session->setFlashdata('success_message', 'Holiday details added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(PUBLIC_HOLIDAY, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Holiday details update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/public_holiday/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/public_holiday/add';
                else $url = ADMIN_PATH . '/public_holiday/edit/' . $id;
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
                $this->LmsModel->update_details(PUBLIC_HOLIDAY, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Holiday Status Changed Successfully';
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
                $this->data['pholidayDetails'] = $this->LmsModel->get_all_details(PUBLIC_HOLIDAY, $condition)->getRow();
                if (!empty($this->data['pholidayDetails'])) {
                    $this->data['title'] = 'Holiday view';
                    echo view(ADMIN_PATH . '/public_holiday/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Holiday');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/public_holiday/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the holiday');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/public_holiday/list');
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
                    $this->LmsModel->isDelete(PUBLIC_HOLIDAY, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Holiday Deleted Successfully';
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

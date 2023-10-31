<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Links extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Links List';
            echo view(ADMIN_PATH . '/links/list', $this->data);
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
                'department_name' => trim($dtSearchKeyVal),
                'links_tags' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(LINKS, $condition, '', $likeArr);
        $sortArr = array('department_name' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(LINKS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();

        foreach ($ajaxDataArr->getResult() as $row) {
            // $job_type_id = $row->job_type_id;
            // $job_type = $this->LmsModel->get_selected_fields(JOB_TYPE, ['id' =>  $job_type_id])->getRow();

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/links/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/links/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/links/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/links/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'department_name' => ucfirst($row->department_name),
                'links_tags' =>  ucfirst($row->links_tags),
                'link' =>  isset($row->link) && $row->link !='' ? ucfirst($row->link) : '-',
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
            $this->data['department_opt'] = $this->LmsModel->get_selected_fields(DEPARTMENT_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'department_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['links_info'] = $this->LmsModel->get_selected_fields(LINKS, $condition)->getRow();
                if (!empty($this->data['links_info'])) {
                    $this->data['title'] = 'Edit Links';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the links');
                    return redirect()->route(ADMIN_PATH . '/links/list');
                }
            } else {
                $this->data['title'] = 'Add Links';
            }
            echo view(ADMIN_PATH . '/links/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $department_name = (string)$this->request->getPostGet('department_name');
            $link = (string)$this->request->getPostGet('link');
            $links_tags = $this->request->getPostGet('links_tags');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            $str_arr = explode(",", $department_name);
            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($department_name != '' && $link != '' && $links_tags != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $tags_str = implode(",", $links_tags);
                $dataArr = array(
                    'department_id' => $str_arr[0],
                    'department_name' => $str_arr[1],
                    'links_tags' => $tags_str,
                    'link' => $link,
                    'status' => $status,
                    'is_deleted' => '0',
                );

                if ($id == '') {
                    $this->LmsModel->simple_insert(LINKS, $dataArr);
                    $this->session->setFlashdata('success_message', 'Links added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(LINKS, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Links update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/links/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/links/add';
                else $url = ADMIN_PATH . '/links/edit/' . $id;
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
                $this->LmsModel->update_details(LINKS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'links Changed Successfully';
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
                $this->data['links_Details'] = $this->LmsModel->get_all_details(LINKS, $condition)->getRow();
                if (!empty($this->data['links_Details'])) {
                    $this->data['title'] = 'Links view';
                    echo view(ADMIN_PATH . '/links/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Links');
                    return redirect()->route(ADMIN_PATH . '/links/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Links');
                return redirect()->route(ADMIN_PATH . '/links/list');
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
                    $this->LmsModel->isDelete(LINKS, 'id', TRUE);
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Links Record Deleted Successfully';
                }
            }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}

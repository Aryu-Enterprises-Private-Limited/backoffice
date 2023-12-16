<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;


class Gst extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Gst List';
            echo view(MANAGER_PATH . '/gst/list', $this->data);
        } else {
            $this->data['title'] = 'Manager Login';
            return view(MANAGER_PATH . '/pages/login', $this->data);
        }
    }

    public function list_ajax($returnType = 'json')
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
        $condition = array('is_deleted' => '0');
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'ref_no' => trim($dtSearchKeyVal),
                'filed_date' => trim($dtSearchKeyVal),
                'month' => trim($dtSearchKeyVal),
            );
        }
        if (isset($year) && !empty($year)) {
            if (!empty($year)) {
                $condition['filed_date'] = $year;
            }
        }

        $totCounts = $this->LmsModel->get_all_counts(GST_DETAILS, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(GST_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        $x =1;
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/gst/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/gst/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/gst/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/gst/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                's_no' => $x,
                'month' => $row->month,
                'filed_date' => $row->filed_date,
                'ref_no' => $row->ref_no,
            );
            $x++;
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

    public function get_income_data()
    {
        if ($this->checkSession('M') != '') {
            $billed_acc_id = (string)$this->request->getPostGet('billed_acc_id');
            $condition = array('is_deleted' => '0', 'status' => '1', 'billed_acc_id' => $billed_acc_id);
            $income_dts = $this->LmsModel->get_all_details(INCOME_DETAILS, $condition)->getResult();
            $html =  '<table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" >
            <thead>
                <tr>
                    <th> S.NO </th>
                    <th> Date </th>
                    <th> Invoice No </th>
                    <th> Company Name </th>
                    <th> Amount </th>
                </tr>
            </thead>
        <tbody>';
            if (!empty($income_dts)) {
                $x = 1;
                foreach ($income_dts as $item) { 
                    $html .= "<tr>";
                    $html .= "<td> " . $x . "</td>";
                    $html .= '<td>' . $item->date . '</td>';
                    $html .= '<td>' . $item->invoice_no . '</td>';
                    $html .= '<td>' . $item->company_name . '</td>';
                    $html .= '<td>' . $item->amount . '</td>';
                    $html .= "</tr>";
                    $x++;
                }
            } else { ?>
                <td>No Records Found</td>
<?php   }
            $html .= '</table></tbody>';
            echo json_encode($html);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . MANAGER_PATH);
        }
    }
}

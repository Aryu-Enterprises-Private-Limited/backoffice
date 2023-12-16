<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;


class Expense extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Expense List';
            echo view(MANAGER_PATH . '/expense/list', $this->data);
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
        $status = $this->request->getPostGet('status');

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
        $condition = array('is_deleted' => '0', 'status' => '1');
        // if (isset($status) && $status != '') {
        //     $condition = array('is_deleted' => '0', 'status' => $status);
        // }
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'company_name' => trim($dtSearchKeyVal),
                'invoice_no' => trim($dtSearchKeyVal),
                'billed_acc_name' => strtolower(trim($dtSearchKeyVal)),
                'amount' => trim($dtSearchKeyVal),

            );
        }


        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $expense_grp = $this->LmsModel->grp_by_expense($condition);
        $totCounts = count($expense_grp->getResult());

        $tblData = array();
        $x = 1;
       
        foreach ($expense_grp->getResult() as $item) {
            $rowId =  (string)$item->category_id;
            $cond = ['id' => $item->category_id];
            $category_dts = $this->LmsModel->get_all_details(CATEGORY, $cond)->getRow();

            $actionTxt = '<button type="button" class="btn  details_btn" data-act_url="/' . MANAGER_PATH . '/expense/get-expense_details"  data-row_id="' . $rowId . '"><i class="fas fa-eye"></i></button>';

            $amounts = explode('~', str_replace(',', '', $item->concatenated_amount));
            // $totalConcatenatedAmount[$item->billed_acc_id] = array_sum(array_map('floatval', $amounts));
            $totalConcatenatedAmount = array_sum(array_map('floatval', $amounts));
            $tblData[] = array(
                's_no' => $x,
                'category_name' => ucfirst($category_dts->category_name),
                "amount" =>  number_format($totalConcatenatedAmount, 2),
                "action" =>  $actionTxt
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

        // print_r($totalConcatenatedAmount);
    }

    public function get_expense_data()
    {
        if ($this->checkSession('M') != '') {
            $category_id = (string)$this->request->getPostGet('category_id');
            $condition = array('is_deleted' => '0', 'status' => '1', 'category_id' => $category_id);
            $income_dts = $this->LmsModel->get_all_details(EXPENSE_DETAILS, $condition)->getResult();
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
                    $html .= '<td>' . $item->category_name . '</td>';
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

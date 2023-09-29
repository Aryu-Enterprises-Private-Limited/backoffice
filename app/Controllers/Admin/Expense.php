<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use DateTime;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Expense extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Expense List';
            $this->data['alert_msg'] = $this->LmsModel->get_selected_fields(REMINDER_ALERT, ['status' => '1', 'is_deleted' => '0'], ['id', 'alert_name'])->getResult();
            $this->data['cat_opt'] = $this->LmsModel->get_selected_fields(CATEGORY, ['status' => '1', 'is_deleted' => '0'], ['id', 'category_name'])->getResult();
            if ($this->request->getPostGet('export') == 'excel') {
                $ajaxDataArr = $this->list_ajax('array');

                if ($ajaxDataArr['status'] == '1') {
                    $expenses = $ajaxDataArr['response'];
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'Date');
                    $sheet->setCellValue('B1', 'Invoice No');
                    $sheet->setCellValue('C1', 'Category Name');
                    $sheet->setCellValue('D1', 'Amount');
                    $sheet->setCellValue('E1', 'Description');
                    $sheet->setCellValue('G1', 'Error Data');
                    $sheet->setCellValue('H1', 'Total:');
                    $rows = 2;
                    $totalAmount = 0;
                    foreach ($expenses->getResult() as $st) {
                        $sheet->setCellValue('A' . $rows, $st->date);
                        $sheet->setCellValue('B' . $rows, $st->invoice_no);
                        $sheet->setCellValue('C' . $rows, $st->category_name);
                        $sheet->setCellValue('D' . $rows, $st->amount);
                        $sheet->setCellValue('E' . $rows, $st->description);
                        if (isset($st->error_data)) {
                            $error_data = $st->error_data;
                        } else {
                            $error_data = '';
                        }
                        $totalAmount += $st->amount;
                        $sheet->setCellValue('G' . $rows, $error_data);
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $sheet->setCellValue('H' . $rows, $totalAmount);
                    // $footerText = 'Total: ' . $totalAmount;
                    // // $headerFooter->setOddFooter('&L&G&C&"Times New Roman,Regular"Total: ' . $totalAmount);
                    // $sheet->getHeaderFooter()->setOddFooter('&L&G&C&"Times New Roman,Regular"'.$footerText);

                    // $sheet->getPageSetup()->setFooterMargin(0.7);

                    $file_name = 'Expenses-' . time() . '.xlsx';
                    $writer->save(WRITEPATH . 'downloads' . DIRECTORY_SEPARATOR . $file_name);
                    return $this->response->download(WRITEPATH . 'downloads' . DIRECTORY_SEPARATOR . $file_name, null)->setFileName($file_name);
                } else {
                    $this->setFlashMessage('error', $ajaxDataArr['response']);
                    return redirect()->route(ADMIN_PATH . '/categories/list');

                    $this->session->setFlashdata('error_message', 'Please login!!!');
                    return redirect()->to('/' . ADMIN_PATH);
                }
            }

            echo view(ADMIN_PATH . '/expense/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {

        $Date = $this->request->getPostGet('date');
        $month = $this->request->getPostGet('month');
        $category = $this->request->getPostGet('category');
        $filter = $this->request->getPostGet('filter');
        $year = $this->request->getPostGet('year');

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
        $currentDate = date('Y-m-d');
        $condition = array('is_deleted' => '0', 'date' => $currentDate);
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'invoice_no' => trim($dtSearchKeyVal),
                'category_name' => trim($dtSearchKeyVal),
                'amount' => trim($dtSearchKeyVal),
            );
        }
        if (isset($filter) && $filter == '1') {
            if (isset($Date) && !empty($Date)) {
                $date = date('Y-m-d', strtotime($Date));
                if (!empty($date)) {
                    $condition = array('is_deleted' => '0');
                    $condition['date'] = $date;
                }
            }

            if (isset($month) && !empty($month)) {
                if (!empty($month)) {
                    $dateStringWithSpace = str_replace(',', ', ', $month);

                    $dateString = $dateStringWithSpace;
                    // Parse the date string to obtain the year and month
                    $dateComponents = explode(', ', $dateString);
                    if (count($dateComponents) === 2) {
                        $month = $dateComponents[0];
                        $year = $dateComponents[1];

                        // Calculate the first day of the specified month and year
                        $firstDayOfMonth = date('Y-m-d', strtotime("{$year}-{$month}-01"));

                        // Calculate the last day of the specified month and year
                        $lastDayOfMonth = date('Y-m-t', strtotime("{$year}-{$month}-01"));
                        $condition = array('is_deleted' => '0');
                        $condition['date >='] = $firstDayOfMonth;
                        $condition['date <='] = $lastDayOfMonth;
                    }
                    if (isset($category) && !empty($category)) {
                        $condition['category_id'] = $category;
                    }
                }
            }

            if (isset($category) && !empty($category) && empty($month)) {
                $condition = array('is_deleted' => '0');
                $condition['category_id'] = $category;
            }
            if (isset($year) && !empty($year) && empty($month)) {
                if (!empty($year)) {
                    $condition = array('is_deleted' => '0');
                    $condition['date'] = $year;
                }
            }
        }
        $totCounts = $this->LmsModel->get_all_counts(EXPENSE_DETAILS, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(EXPENSE_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        if (isset($_GET['export']) && $_GET['export'] == 'excel') {
            $returnArr['status'] = '1';
            $returnArr['response'] = $ajaxDataArr;
            return $returnArr;
        }

        $tblData = array();
        $totalAmount = 0;
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/expense/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/expense/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/expense/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/expense/delete"><i class="fas fa-trash-alt"></i></a>';
            $totalAmount += $row->amount;
            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'date' => $row->date,
                'invoice_no' => $row->invoice_no,
                'category_name' => ucfirst($row->category_name),
                'amount' => $row->amount,
                "status" =>  $statusTxt,
                "action" =>  $actionTxt,

            );
        }
        $response = array(
            "status" => '1',
            "draw" => intval($draw),
            "iTotalRecords" => $totCounts,
            "iTotalDisplayRecords" => $totCounts,
            "aaData" => $tblData,
            "totalAmount" => $totalAmount
        );
        $returnArr = $response;
        echo json_encode($returnArr);
    }



    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            $this->data['category_opt'] = $this->LmsModel->get_selected_fields(CATEGORY, ['status' => '1', 'is_deleted' => '0'], ['id', 'category_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['exp_info'] = $this->LmsModel->get_selected_fields(EXPENSE_DETAILS, $condition)->getRow();
                if (!empty($this->data['exp_info'])) {
                    $this->data['title'] = 'Edit Expense';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Expense');
                    return redirect()->route(ADMIN_PATH . '/expense/list');
                }
            } else {
                $this->data['title'] = 'Add Expense';
            }
            echo view(ADMIN_PATH . '/expense/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $date = (string)$this->request->getPostGet('date');
            $invoice_no = (string)$this->request->getPostGet('invoice_no');
            $description = (string)$this->request->getPostGet('description');
            $category_name = (string)$this->request->getPostGet('category_name');
            $amount = (string)$this->request->getPostGet('amount');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            $str_arr = explode(",", $category_name);
            if ($status == '') {
                $status = 'off';
            }

            $fSubmit = FALSE;
            if ($date != '' && $invoice_no != '' && $description != '' && $category_name != '' && $amount != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'date' => $date,
                    'invoice_no' => $invoice_no,
                    'description' => $description,
                    'category_id' => $str_arr[0],
                    'category_name' => $str_arr[1],
                    'amount' => $amount,
                    'status' => $status,
                    'is_deleted' => '0',
                );
                if ($id == '') {
                    $this->LmsModel->simple_insert(EXPENSE_DETAILS, $dataArr);
                    $this->session->setFlashdata('success_message', 'Expenses added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(EXPENSE_DETAILS, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Expenses update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/expense/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/expense/add';
                else $url = ADMIN_PATH . '/expense/edit/' . $id;
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
                $this->LmsModel->update_details(EXPENSE_DETAILS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Expenses Changed Successfully';
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
                $this->data['exp_details'] = $this->LmsModel->get_all_details(EXPENSE_DETAILS, $condition)->getRow();
                if (!empty($this->data['exp_details'])) {
                    $this->data['title'] = 'Expenses view';
                    echo view(ADMIN_PATH . '/expense/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Expenses');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/expense/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Expenses');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/expense/list');
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
                    $this->LmsModel->isDelete(EXPENSE_DETAILS, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Expenses Deleted Successfully';
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
    public function alert_close_ajax()
    {
        if ($this->checkSession('A') != '') {
            $returnArr['status'] = '0';
            $returnArr['response'] = 'fail';
            $alert_id = $this->request->getPostGet('id');
            if (isset($alert_id)) {
                $newdata = array('status' => '0');
                $condition = array('id' => $alert_id);
                $this->LmsModel->update_details(REMINDER_ALERT, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Status changed';
            }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}

<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use DateTime;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Income extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Income List';
            $this->data['alert_msg'] = $this->LmsModel->get_selected_fields(REMINDER_ALERT, ['status' => '1', 'is_deleted' => '0'], ['id', 'alert_name'])->getResult();
            if ($this->request->getPostGet('export') == 'excel') {
                $ajaxDataArr = $this->list_ajax('array');

                if ($ajaxDataArr['status'] == '1') {
                    $income = $ajaxDataArr['response'];
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'Date');
                    $sheet->setCellValue('B1', 'Invoice No');
                    $sheet->setCellValue('C1', 'Company Name');
                    $sheet->setCellValue('D1', 'Amount');
                    $sheet->setCellValue('E1', 'Billed Account');
                    $sheet->setCellValue('G1', 'Error Data');
                    $sheet->setCellValue('H1', 'Total:');
                    $rows = 2;
                    $totalAmount = 0;
                    foreach ($income->getResult() as $st) {
                        $sheet->setCellValue('A' . $rows, $st->date);
                        $sheet->setCellValue('B' . $rows, $st->invoice_no);
                        $sheet->setCellValue('C' . $rows, $st->company_name);
                        $sheet->setCellValue('D' . $rows, $st->amount);
                        $sheet->setCellValue('E' . $rows, $st->billed_acc_name);
                        if (isset($st->error_data)) {
                            $error_data = $st->error_data;
                        } else {
                            $error_data = '';
                        }
                        $str_r = str_replace(",","", $st->amount);
                        $totalAmount += $str_r;
                        // $totalAmount += $st->amount;
                        $sheet->setCellValue('G' . $rows, $error_data);
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $sheet->setCellValue('H' . $rows, number_format($totalAmount,2));
                    // $footerText = 'Total: ' . $totalAmount;
                    // // $headerFooter->setOddFooter('&L&G&C&"Times New Roman,Regular"Total: ' . $totalAmount);
                    // $sheet->getHeaderFooter()->setOddFooter('&L&G&C&"Times New Roman,Regular"'.$footerText);

                    // $sheet->getPageSetup()->setFooterMargin(0.7);

                    $file_name = 'Income-' . time() . '.xlsx';
                    $writer->save(WRITEPATH . 'downloads' . DIRECTORY_SEPARATOR . $file_name);
                    return $this->response->download(WRITEPATH . 'downloads' . DIRECTORY_SEPARATOR . $file_name, null)->setFileName($file_name);
                } else {
                    $this->setFlashMessage('error', $ajaxDataArr['response']);
                    return redirect()->route(ADMIN_PATH . '/income/list');

                    $this->session->setFlashdata('error_message', 'Please login!!!');
                    return redirect()->to('/' . ADMIN_PATH);
                }
            }

            echo view(ADMIN_PATH . '/income/list', $this->data);
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
        // $currentDate = date('Y-m-d');
        $current_yr = date('Y');
        $current_mnth = date('m');
        $firstDayOfMonth = date('Y-m-d', strtotime("{$current_yr}-{$current_mnth}-01"));
        $lastDayOfMonth = date('Y-m-t', strtotime("{$current_yr}-{$current_mnth}-01"));

        $condition = array('is_deleted' => '0');
        $condition['date >='] = $firstDayOfMonth;
        $condition['date <='] = $lastDayOfMonth;
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'invoice_no' => trim($dtSearchKeyVal),
                'company_name' => trim($dtSearchKeyVal),
                'amount' => trim($dtSearchKeyVal),
                'billed_acc_name' => trim($dtSearchKeyVal),
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
        // print_r($condition);die;
        $totCounts = $this->LmsModel->get_all_counts(INCOME_DETAILS, $condition, '', $likeArr);
        $sortArr = array('date' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(INCOME_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/income/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/income/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/income/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/income/delete"><i class="fas fa-trash-alt"></i></a>';
            $str_r = str_replace(",","", $row->amount);
            $totalAmount += $str_r;
            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'date' => $row->date,
                'invoice_no' => $row->invoice_no,
                'company_name' => ucfirst($row->company_name),
                'billed_acc_name' => ucfirst($row->billed_acc_name),
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
            "totalAmount" => number_format($totalAmount,2) 
        );
        $returnArr = $response;
        echo json_encode($returnArr);
    }



    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            $this->data['billed_opt'] = $this->LmsModel->get_selected_fields(BILLED_ACC_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'billed_acc_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['income_info'] = $this->LmsModel->get_selected_fields(INCOME_DETAILS, $condition)->getRow();
                if (!empty($this->data['income_info'])) {
                    $this->data['title'] = 'Edit Income';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Income');
                    return redirect()->route(ADMIN_PATH . '/income/list');
                }
            } else {
                $this->data['title'] = 'Add Income';
            }
            echo view(ADMIN_PATH . '/income/add_edit', $this->data);
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
            $company_name = (string)$this->request->getPostGet('company_name');
            $billed_acc_name = (string)$this->request->getPostGet('billed_acc_name');
            $amount = (string)$this->request->getPostGet('amount');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            $str_arr = explode(",", $billed_acc_name);
            if ($status == '') {
                $status = 'off';
            }

            $fSubmit = FALSE;
            if ($date != '' && $invoice_no != '' && $company_name != '' && $billed_acc_name != '' && $amount != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'date' => $date,
                    'invoice_no' => $invoice_no,
                    'company_name' => $company_name,
                    'billed_acc_id' => $str_arr[0],
                    'billed_acc_name' => $str_arr[1],
                    'amount' => $amount,
                    'status' => $status,
                    'is_deleted' => '0',
                );
                if ($id == '') {
                    $this->LmsModel->simple_insert(INCOME_DETAILS, $dataArr);
                    $this->session->setFlashdata('success_message', 'Income added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(INCOME_DETAILS, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Income update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/income/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/income/add';
                else $url = ADMIN_PATH . '/income/edit/' . $id;
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
                $this->LmsModel->update_details(INCOME_DETAILS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Income Changed Successfully';
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
                $this->data['income_details'] = $this->LmsModel->get_all_details(INCOME_DETAILS, $condition)->getRow();
                if (!empty($this->data['income_details'])) {
                    $this->data['title'] = 'Income view';
                    echo view(ADMIN_PATH . '/income/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Income');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/income/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Income');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/income/list');
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
                    $this->LmsModel->isDelete(INCOME_DETAILS, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Income Deleted Successfully';
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

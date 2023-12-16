<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Schedule extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index($returnType = 'json')
    {
        if ($this->checkSession('A') != '') {
            $daterange = $this->request->getGetPost('month');
            $currentMonth = date('F');
            $currentYear = date('Y');
            $this->data['filter_month'] = $firstDateOfMonth = date('Y-m-01');
            if (isset($daterange) && $daterange != '') {
                $timestamp = strtotime($daterange);
                $currentMonth =  date("F", $timestamp);
                $currentYear = date("Y", $timestamp);
                $monthNumber = date('m', strtotime($currentMonth));
                $this->data['filter_month'] = $firstDateOfMonth = date('' . $currentYear . '-' . $monthNumber . '-01');
            }
            $this->data['dates'] = $datesForCurrentMonth = $this->generateDatesForMonth($currentMonth, $currentYear);

            $this->data['title'] = 'Schedule List';
            $condition = ['date' => $firstDateOfMonth];
            $sortArr = array('employee_email' => 'asc');
            $this->data['dataArray'] = $this->LmsModel->get_selected_fields(SCHEDULE_HOURS, $condition, ['id', 'employee_email', 'employee_id', 'date', 'daily_working_hrs'],$sortArr)->getResult();
            echo view(ADMIN_PATH . '/schedule/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    private function generateDatesForMonth($currentMonth, $currentYear)
    {
        $firstDayOfMonth = date('Y-m-01', strtotime("{$currentYear}-{$currentMonth}"));
        $lastDayOfMonth = date('Y-m-t', strtotime("{$currentYear}-{$currentMonth}"));

        $datesForCurrentMonth = [];
        $currentDate = $firstDayOfMonth;

        while ($currentDate <= $lastDayOfMonth) {
            $datesForCurrentMonth[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        return $datesForCurrentMonth;
    }




    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);

            $daterange = $this->request->getGetPost('month');

            $currentMonth = date('F');
            $currentYear = date('Y');
            $this->data['filter_month'] = $firstDateOfMonth = date('Y-m-01');
            if (isset($daterange) && $daterange != '') {
                $timestamp = strtotime($daterange);
                $currentMonth =  date("F", $timestamp);
                $currentYear = date("Y", $timestamp);
                $monthNumber = date('m', strtotime($currentMonth));
                $this->data['filter_month'] = $firstDateOfMonth = date('' . $currentYear . '-' . $monthNumber . '-01');
            }
            $this->data['dates'] = $datesForCurrentMonth = $this->generateDatesForMonth($currentMonth, $currentYear);

            $condition = ['date' => $firstDateOfMonth];

            $sch_details = $this->LmsModel->get_selected_fields(SCHEDULE_HOURS, $condition, ['id', 'employee_email', 'employee_id', 'date', 'daily_working_hrs']);

            if ($sch_details->getNumRows() == 0) {
                $this->data['emp_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'email'])->getResult();
            } else {
                $this->data['dataArray'] = $sch_details->getResult();
            }

            if ($id != '') {
                // $condition = array('is_deleted' => '0', 'id' => $id);
                // $this->data['dept_info'] = $this->LmsModel->get_selected_fields(DEPARTMENT_DETAILS, $condition)->getRow();
                // if (!empty($this->data['dept_info'])) {
                //     $this->data['title'] = 'Edit Schedule';
                // } else {
                //     $this->session->setFlashdata('error_message', 'Couldnot find the Schedule');
                //     return redirect()->route(ADMIN_PATH . '/schedule/list');
                // }
            } else {
                $this->data['title'] = 'Add Edit Schedule';
            }
            echo view(ADMIN_PATH . '/schedule/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $sch_hrs = $this->request->getPostGet('sch_hrs');
            $date = (string)$this->request->getPostGet('date');
            $id = $this->request->getPostGet('id');

            $fSubmit = FALSE;
            if ($date != '') {
                $condition = ['date' => $date];
                $sch_details = $this->LmsModel->get_selected_fields(SCHEDULE_HOURS, $condition);

                $dataArr = array(
                    'date' => $date,
                );
                if ($sch_details->getNumRows() == 0) {
                    foreach ($sch_hrs as $key => $data) {
                        $keyArr = explode(',', $key);
                        $dataArr['employee_id'] = $keyArr[1];
                        $dataArr['employee_email'] = $keyArr[0];
                        $dataArr['daily_working_hrs'] = json_encode($data);
                        $this->LmsModel->simple_insert(SCHEDULE_HOURS, $dataArr);
                    }
                    $this->session->setFlashdata('success_message', 'Scheduled details added successfully.');
                    $fSubmit = TRUE;
                } else {
                    foreach ($sch_hrs as $key => $data) {
                        $keyArr = explode(',', $key);
                        $dataArr['employee_email'] = $keyArr[0];
                        $dataArr['employee_id'] = $keyArr[1];
                        $condition = array('date' => $date, 'id' => $keyArr[2]);
                        $dataArr['daily_working_hrs'] = json_encode($data);
                        $this->LmsModel->update_details(SCHEDULE_HOURS, $dataArr, $condition);
                    }
                    $this->session->setFlashdata('success_message', 'Scheduled details Update successfully.');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/schedule/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/schedule/add';
                else $url = ADMIN_PATH . '/schedule/edit/' . $id;
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

// use Dompdf\Dompdf;
// use Dompdf\Options;
use Dompdf\Dompdf;

class Invoice extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
        // $dompdf = new Dompdf();
        // $dompdf = service('dompdf');
    }

    public function index($data = '')
    {
        // print_r($data['pdf']);die;
        if ($this->checkSession('A') != '') {
            if (isset($data['pdf']) && $data['pdf'] == 1) {
                $dompdf = new Dompdf();
                $add_count = count($data['addmore']);
                $amnt_count = count($data['amntmore']);
                $qnty_count = count($data['qntymore']);
                if ($add_count == $amnt_count && $amnt_count == $qnty_count && $qnty_count == $add_count) {
                    $html = view(ADMIN_PATH . '/invoice/pdf', $data);
                        $dompdf->loadHtml($html);
    
                        $dompdf->setPaper('A4', 'landscape');
        
                        $dompdf->render();
                        $pdfString = $dompdf->output();
                        $title = 'Invoice';
                        $client_opt = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
                        // return view(ADMIN_PATH . '/invoice/pdf_preview', ['pdfString' => $pdfString]);
                        return view(ADMIN_PATH . '/invoice/add', ['pdfString' => $pdfString, 'title' => $title, 'client_opt' => $client_opt, 'form_data' => $data]);
                }
                 else{
                     return 0;
                 }
                
            } else {
                $this->data['title'] = 'Invoice';
                $this->data['client_opt'] = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
                $this->data['invoice_id'] = $this->LmsModel->get_selected_fields(INVOICE_DETAILS,'', ['id'],'desc')->getRow();
                // print_r($this->data['invoice_id']);die;
                echo view(ADMIN_PATH . '/invoice/add', $this->data);
            }
        } else {
            
        }
    }

    public function invoice_generate()
    {

        if ($this->checkSession('A') != '') {
            $dompdf = new Dompdf(); //service('dompdf');

            $this->data['from_name'] =   $from_name = (string)$this->request->getPostGet('from_name');
            $this->data['to_name'] = $to_name = (string)$this->request->getPostGet('to_name');
            $this->data['addmore'] = $addmore = array_values($this->request->getPostGet('addmore'));
            $this->data['amntmore'] = $amntmore = array_values($this->request->getPostGet('amntmore'));
            $this->data['qntymore'] = $qntymore = array_values($this->request->getPostGet('qntymore'));
            // $this->data['amount'] =  $amount = $this->request->getPostGet('amount');
            $this->data['invoice_no'] = $invoice_no = $this->request->getPostGet('invoice_no');
            $this->data['invoice_date'] = $invoice_date = $this->request->getPostGet('invoice_date');
            $this->data['pdf'] = '1';
            $this->data['header_logo'] = base_url()."images/aryuinvoiceheader.png";

            //  print_r(array_values($addmore));die;
            $validation = \Config\Services::validation();
            $validation->setRules(
                [
                    'invoice_no' => 'required|is_unique[invoice_details.invoice_no]',
                ],
            );
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                $returnArr['status'] = '0';
                $returnArr['response'] = $errors;
                $this->session->setFlashdata('error_message', 'The invoice no field must contain a unique value.');
                return redirect()->to('/' . ADMIN_PATH . '/invoice');
                // return $this->response->setStatusCode(422)->setJSON($returnArr);
            } else {
                $dataArr = array(
                    'from_name' => $from_name,
                    'to_name' => $to_name,
                    'invoice_no' => $invoice_no,
                    'invoice_date' => $invoice_date,
                );
                $dataArr['description'] = json_encode($addmore);
                $dataArr['amount'] = json_encode($amntmore);
                $dataArr['quantity'] = json_encode($qntymore);
    
                $result = $this->index($this->data);
              
                
                if($result != 0){
                    // $this->LmsModel->simple_insert(INVOICE_DETAILS, $dataArr);
                    print_r($result);
                }else{
                    $this->session->setFlashdata('error_message', 'Enter Correct value');
                    return redirect()->to('/' . ADMIN_PATH . '/invoice');
                }
            }

            
            // $html = view(ADMIN_PATH . '/invoice/pdf', $this->data);
            // $dompdf->loadHtml($html);

            // $dompdf->setPaper('A4', 'landscape');

            // $dompdf->render();
            // $pdfString = $dompdf->output();
            // return view(ADMIN_PATH . '/invoice/pdf_preview', ['pdfString' => $pdfString]);

        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use Dompdf\Dompdf;
use Dompdf\Options;

class Invoice extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index($data = '')
    {
        if ($this->checkSession('A') != '') {
            if (isset($data['id']) && $data['id'] != '') {
                $this->data['title'] = 'Invoice';
                $this->data['form_data'] = $data;
                // print_r($this->data['form_data']);die;
                $this->data['client_opt'] = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
                // $this->data['pdf_opt'] = $this->LmsModel->get_selected_fields(INVOICE_DETAILS, ['id' => $data['id']])->getRow();
                echo view(ADMIN_PATH . '/invoice/add', $this->data);
                // $html = view(ADMIN_PATH . '/invoice/pdf', $this->data);

                /*$dompdf = new Dompdf();
                $add_count = count($data['addmore']);
                $amnt_count = count($data['amntmore']);
                $qnty_count = count($data['qntymore']);
                if ($add_count == $amnt_count && $amnt_count == $qnty_count && $qnty_count == $add_count) {
                    // $data['css_link'] = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
                    // $data['script_link'] = '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>';
                    // $data['header_logo'] = $header_logo =base_url() . "images/aryuinvoiceheader.png";
                    $html = view(ADMIN_PATH . '/invoice/pdf', $data);
                    
                    $dompdf->loadHtml($html);
                    // $dompdf->setBasePath('http://localhost:8080/images/aryuinvoiceheader.png');
                    $dompdf->setPaper('A4', 'landscape');

                    $dompdf->render();
                    $pdfString = $dompdf->output();
                    $title = 'Invoice';
                    $client_opt = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
                    // return view(ADMIN_PATH . '/invoice/pdf_preview', ['pdfString' => $pdfString]);
                    return view(ADMIN_PATH . '/invoice/add', ['pdfString' => $pdfString, 'title' => $title, 'client_opt' => $client_opt, 'form_data' => $data]);
                } else {
                    return 0;
                }*/
            } else {
                $this->data['title'] = 'Invoice';
                $this->data['client_opt'] = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
                $this->data['invoice_id'] = $this->LmsModel->get_selected_fields(INVOICE_DETAILS, '', ['id'], 'desc')->getRow();
                echo view(ADMIN_PATH . '/invoice/add', $this->data);
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    /*  public function invoice_generate()
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

            // echo $this->data['header_logo'];die;
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


                if ($result != 0) {
                    // $this->LmsModel->simple_insert(INVOICE_DETAILS, $dataArr);
                    print_r($result);
                } else {
                    $this->session->setFlashdata('error_message', 'Enter Correct value');
                    return redirect()->to('/' . ADMIN_PATH . '/invoice');
                }
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }*/

    public function insertUpdate_preview()
    {
        //  echo"<pre>";print_r($_POST);die;
        //   echo"<pre>";  print_r(array_filter($this->request->getPostGet('addmore')));die;
        if ($this->checkSession('A') != '') {
            $this->data['from_name'] =   $from_name = (string)$this->request->getPostGet('from_name');
            $this->data['to_name'] = $to_name = (string)$this->request->getPostGet('to_name');
            $addmore = $this->request->getPostGet('addmore');
            $amntmore = $this->request->getPostGet('amntmore');
            $qntymore = $this->request->getPostGet('qntymore');
            $this->data['invoice_no'] = $invoice_no = $this->request->getPostGet('invoice_no');
            $this->data['invoice_date'] = $invoice_date = $this->request->getPostGet('invoice_date');
            $id = (string)$this->request->getPostGet('id');

            $this->data['addmore'] = $addmore_array_fil = array_values(array_filter($addmore));
            $this->data['amntmore'] = $amtmore_array_fil = array_values(array_filter($amntmore));
            $this->data['qntymore'] = $qnty_array_fil = array_values(array_filter($qntymore));
            $to_arr = explode(",", $to_name);

            $dataArr = array(
                'from_name' => $from_name,
                'to_name' => $to_arr[0],
                'client_id' => $to_arr[1],
                'invoice_no' => $invoice_no,
                'invoice_date' => $invoice_date,
            );
            $dataArr['description'] = json_encode($addmore_array_fil);
            $dataArr['amount'] = json_encode($amtmore_array_fil);
            $dataArr['quantity'] = json_encode($qnty_array_fil);

            if ($id == '') {
                if(isset($invoice_no)){
                  $invoice_no_dt =   $this->LmsModel->get_selected_fields(INVOICE_DETAILS, ['invoice_no' => $invoice_no]);
                  if ($invoice_no_dt->getNumRows() == 1) {
                    $condition = array('invoice_no' => $invoice_no);
                    $this->LmsModel->update_details(INVOICE_DETAILS, $dataArr, $condition);
                  }else{
                    $this->LmsModel->simple_insert(INVOICE_DETAILS, $dataArr);
                    $this->data['id'] = $id = $this->LmsModel->get_last_insert_id();
                    $this->session->setFlashdata('success_message', 'Invoice added successfully.');
                }
                }
                // $fSubmit = TRUE;
            } else {
                $this->data['id'] = $id;
                $condition = array('id' => $id);
                $this->LmsModel->update_details(INVOICE_DETAILS, $dataArr, $condition);
                $this->session->setFlashdata('success_message', 'Invoice update successfully');
                // $fSubmit = TRUE;
            }

            //  return view(ADMIN_PATH . '/invoice/add', $this->data);
            $result = $this->index($this->data);
            // $url = ADMIN_PATH . '/preview_invoice/' . $id;
            // return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
    public function preview_pdf($id)
    {
        $data = [];
        $data['title'] = 'Invoice';

        $data['pdf_opt'] = $clients_info = $this->LmsModel->get_selected_fields(INVOICE_DETAILS, ['id' => $id])->getRow();


        $data['client_details'] = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, ['id' => $clients_info->client_id])->getRow();

        $options = new Options();
        $options->setIsPhpEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->set('dpi', 150);
        $dompdf = new Dompdf($options);

        $parser = \Config\Services::parser();
        // $template = '<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><title>{blog_title}</title></head><body><div class="row"><div class="col-md-12 text-danger">col-12</div></div></body>';
        // $data     = ['blog_title' => 'My ramblings'];

        // return $parser->renderString(view(ADMIN_PATH . '/invoice/pdf', $data));
        // exit();
        // $data['bootstrap'] = file_get_contents("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.css");
        $body = view(ADMIN_PATH . '/invoice/pdf', $data);
        $body = $parser->renderString($body);
        return $body;

        $dompdf->loadHtml($parser->renderString(view(ADMIN_PATH . '/invoice/pdf', $data)));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("file.pdf", array("Attachment" => 0));
    }
}

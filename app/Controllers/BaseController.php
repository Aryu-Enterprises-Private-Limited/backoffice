<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->setUriSegments();
    }

    public function checkSession($type = '')
    {
        $session = \Config\Services::session();
        // print_r($_SESSION);die;
        $sessData = '';
        if ($type == 'A') {
            $sessData = $session->get(APP_NAME . '_session_admin_id');
        }
        if ($type == 'E') {
            $sessData = $session->get(APP_NAME . '_session_employee_id');
        }
        if ($type == 'M') {
            $sessData = $session->get(APP_NAME . '_session_manager_id');
        }
        if ($sessData == NULL) {
            $sessData = '';
        }
        return $sessData;
    }

    public function forceRedirect($url = '')
    {
        //print_r($url);die;
        header('Location: /' . $url);
        exit();
    }

    public function setUriSegments()
    {
        $uri = service('uri');
        $base_url =  base_url();
        if (isset($uri) && $uri != $base_url) {
            $curr_panel = ($uri->getSegment(1) != '') ? $uri->getSegment(1) : '';
            $curr_module = ($uri->getSegment(2) != '') ? $uri->getSegment(2) : '';
            // $curr_method = ($uri->getSegment(3) != '') ? $uri->getSegment(3) : '';
            // $curr_id = ($uri->getSegment(4) != '') ? $uri->getSegment(4) : '';

            $this->data['curr_panel'] = $this->data['uriSegment1'] = $curr_panel;
            $this->data['curr_module'] = $this->data['uriSegment2'] = $curr_module;
            // $this->data['curr_method'] = $this->data['uriSegment3'] = $curr_method;
            // $this->data['curr_id'] = $this->data['uriSegment4'] = $curr_id;
        }
    }
}

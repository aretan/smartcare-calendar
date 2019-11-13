<?php namespace App\Controllers;

use CodeIgniter\Controller;

class WebController extends Controller
{
    protected $helpers = ['form'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

}

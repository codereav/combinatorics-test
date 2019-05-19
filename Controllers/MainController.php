<?php

namespace App\Controllers;

use App\Models\PermModelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controllers
 */
class MainController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var string
     */
    private $filename;

    /**
     * MainController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->filename = microtime() . 'data.txt';
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $this->response->setContent(require __DIR__ . '/../templates/main.tpl');
        return $this->response;
    }

    /**
     * @param PermModelInterface $model
     * @return Response
     */
    public function buildPerms(PermModelInterface $model)
    {

        $this->response->headers->set('Content-type', 'application/octet-stream');
        $this->response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));

        $model->writeDataToFile($this->filename);

        $this->response->setContent(file_get_contents($this->filename));

        $model->deleteDataFile($this->filename);

        return $this->response->send();
    }

}
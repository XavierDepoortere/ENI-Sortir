<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ErrorController extends AbstractController
{
    public function showException(Request $request, HttpExceptionInterface $exception)
    {
        $statusCode = $exception->getStatusCode();

        if ($statusCode === 404) {
            return $this->render('error404.html.twig');
        } elseif ($statusCode === 403) {
            return $this->render('error403.html.twig');
        } else {
            return $this->render('error.html.twig');
        }
    }
}

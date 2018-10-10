<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FrontpageController extends Controller
{
    public function index(): Response
    {
        return $this->render('frontpage/index.html.twig', [
            'cityList' => ['berlin', 'hamburg', 'stuttgart']
        ]);
    }
}

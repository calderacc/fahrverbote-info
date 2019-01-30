<?php declare(strict_types=1);

namespace App\Controller;

use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends Controller
{
    public function faq(CacheInterface $cache): Response
    {
        return $this->render('faq/faq.html.twig', [
            'entryList' => $cache->get('faq') ?? [],
        ]);
    }
}

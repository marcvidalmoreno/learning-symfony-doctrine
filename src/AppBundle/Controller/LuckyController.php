<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{page}", name="lucky_number", requirements={"page"="\d+"})
     */
    public function numberAction($page)
    {
        $number = mt_rand(0, 100);

        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
            'page' => $page,
        ));
    }
}
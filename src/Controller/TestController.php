<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 17:04
 */

namespace App\Controller;


use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function test()
    {
        return $this->render(
            'emails/test.html.twig',
            array(
                'code' => md5(uniqid())
            )
        );
    }
}

<?php

namespace MyWheels\TwigSpreadsheetBundle\Tests\Functional\Fixtures\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController.
 */
class TestController extends Controller
{
    /**
     * @param $templateName
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/default/{templateName}.{_format}", name="test_default", defaults={"templateName" = "simple", "_format" = "xlsx"})
     */
    public function defaultAction($templateName): Response
    {
        return $this->render(
            '@Test/Default/'.$templateName.'.twig',
            [
                'data' => [
                    ['name' => 'Everette Grim', 'salary' => 5458.0],
                    ['name' => 'Nam Poirrier', 'salary' => 3233.0],
                    ['name' => 'Jolynn Ell', 'salary' => 5718.0],
                    ['name' => 'Ta Burdette', 'salary' => 1255.0],
                    ['name' => 'Aida Salvas', 'salary' => 5226.0],
                    ['name' => 'Gilbert Navarrette', 'salary' => 1431.0],
                    ['name' => 'Kirk Figgins', 'salary' => 7429.0],
                    ['name' => 'Rashad Cloutier', 'salary' => 8457.0],
                    ['name' => 'Traci Schmitmeyer', 'salary' => 7521.0],
                    ['name' => 'Cecila Statham', 'salary' => 7180.0],
                    ['name' => 'Chong Robicheaux', 'salary' => 3511.0],
                    ['name' => 'Romona Stockstill', 'salary' => 2943.0],
                    ['name' => 'Roseann Sather', 'salary' => 9126.0],
                    ['name' => 'Vera Racette', 'salary' => 4566.0],
                    ['name' => 'Tennille Waltripv', 'salary' => 4485.0],
                    ['name' => 'Dot Hedgpeth', 'salary' => 7687.0],
                    ['name' => 'Thersa Havis', 'salary' => 2264.0],
                    ['name' => 'Long Kenner', 'salary' => 4051.0],
                    ['name' => 'Kena Kea', 'salary' => 4090.0],
                    ['name' => 'Evita Chittum', 'salary' => 4639.0],
                ],
                'kernelPath' => $this->get('kernel')->getRootDir(),
            ]
        );
    }

    /**
     * @param $templateName
     *
     * @throws \InvalidArgumentException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/custom-response/{templateName}.{_format}", name="test_custom_response", defaults={"templateName" = "simple", "_format" = "xlsx"})
     */
    public function customResponseAction($templateName): Response
    {
        $response = new Response(
            $this->render(
                '@Test/Default/'.$templateName.'.twig',
                [
                    'data' => [
                        ['name' => 'Everette Grim', 'salary' => 5458.0],
                        ['name' => 'Nam Poirrier', 'salary' => 3233.0],
                        ['name' => 'Jolynn Ell', 'salary' => 5718.0],
                    ],
                ]
            )
        );

        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'foobar.bin'
        ));
        $response->setMaxAge(600);

        return $response;
    }
}

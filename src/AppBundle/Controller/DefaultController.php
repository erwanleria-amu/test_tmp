<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/app/coordinates/{lat}/{lon}", name="app-coordinates")
     * @param Request $request
     * @param $lat
     * @param $lon
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appCoordinatesAction($lat, $lon, Request $request)
    {
        $coordinates = [
            'lat' => $lat,
            'lon' => $lon
        ];
        // replace this example code with whatever you need
        return $this->render('default/app.html.twig', [
            'coordinates' => $coordinates
        ]);
    }

    /**
     * @Route("/app", name="app")
     */
    public function appAction(Request $request)
    {
        $coordinates = [
            'lat' => '43.2313',
            'lon' => '5.44100'
        ];
        // replace this example code with whatever you need
        return $this->render('default/app.html.twig', [
            'coordinates' => $coordinates
        ]);
    }

}

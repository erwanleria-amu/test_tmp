<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * Page d'accueil du site
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('app');
    }

    /**
     * @Route("/app/coordinates/{lat}/{lon}", name="app-coordinates")
     * @param Request $request
     * @param $lat
     * @param $lon
     * Carte météo avec comme point de départ les coordonnées lat/long
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appCoordinatesAction(Request $request, $lat, $lon)
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
     * Carte météo avec comme point de départ les coordonnées fixées de base (Luminy)
     */
    public function appAction(Request $request)
    {
        $coordinates = [
            'lat' => '43.2313',
            'lon' => '5.44100'
        ];

        return $this->render('default/app.html.twig', [
            'coordinates' => $coordinates
        ]);
    }
}

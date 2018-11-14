<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Location;
use AppBundle\Entity\Review;
use AppBundle\Form\ReviewForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReviewController
 * @package AppBundle\Controller
 * @Route("/reviews")
 */
class ReviewController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/location/{id}", name="reviews-index")
     */
    public function indexAction(Request $request, $id)
    {
        $location = $this->getDoctrine()->getRepository('AppBundle:Location')->find($id);
        if($location == null) $this->createNotFoundException();

        return $this->render('default/review-index.html.twig', [
            'location' => $location
        ]);
    }

    /**
     * @param Request $request
     * @param $mapId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/mapId/{mapId}/{name}/{lat}/{lng}", name="reviews-mapid-finder", options={"expose" = true})
     */
    public function findLocationFromMapIdAction(Request $request, $mapId, $name, $lat, $lng)
    {
        $favorite = $this->getDoctrine()->getRepository('AppBundle:Favorite')->findOneBy([
            'map_id' => $mapId
        ]);

        if($favorite == null) {
            $location = $this->getDoctrine()->getRepository('AppBundle:Location')->findOneBy([
                'latitude' => $lat,
                'longitude' => $lng
            ]);
            if($location == null) {
                $location = new Location();
                $location->setName($name);
                $location->setLatitude($lat);
                $location->setLongitude($lng);

                $em = $this->getDoctrine()->getManager();
                $em->persist($location);
                $em->flush();
            }
            return $this->redirectToRoute('reviews-index', [
                'id' => $location->getId()
            ]);
        }
        return $this->redirectToRoute('reviews-index', [
            'id' => $favorite->getLocation()->getId()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/new/{id}", name="reviews-new")
     */
    public function createReviewAction(Request $request, $id)
    {
        $location = $this->getDoctrine()->getRepository('AppBundle:Location')->find($id);
        if($location == null) $this->createNotFoundException();
        foreach($this->getUser()->getReviews()->toArray() as $review){
            if($review->getLocation() == $location) {
                return $this->redirectToRoute('reviews-index', [
                    'id' => $location->getId()
                ]);
            }
        }

        $review = new Review();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ReviewForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $review->setAuthor($this->getUser());
            $review->setLocation($location);
            $review->setComment($form->get('comment')->getData());
            $review->setCreationDate(new DateTime());
            $review->setIsPositive($form->get('isPositive')->getData());

            $em->persist($review);
            $em->flush();

            $this->addFlash('reviews-new', "Votre évaluation a bien été soumise.");

            return $this->redirectToRoute('reviews-index', [
                'id' => $location->getId()
            ]);
        }
        return $this->render('default/review-location.html.twig', [
            'form' => $form->createView(),
            'location' => $location
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/delete/{id}", name="reviews-delete")
     */
    public function removeReviewAction(Request $request, $id)
    {
        $review = $this->getDoctrine()->getRepository('AppBundle:Review')->find($id);
        if($review == null) $this->createNotFoundException();

        $locationId = $review->getLocation()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        return $this->redirectToRoute('reviews-index', [
            'id' => $locationId
        ]);
    }
}

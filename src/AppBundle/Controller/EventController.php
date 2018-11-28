<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Event;
use AppBundle\Entity\EventComment;
use AppBundle\Entity\Location;
use AppBundle\Form\EventCommentForm;
use AppBundle\Form\NewEventForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class EventController
 * @package AppBundle\Controller
 * @Route("/events")
 */
class EventController extends Controller
{
    /**
     *  @Route("/", name="events-index")
     * @param Request $request
     * Affichage de tous les évènements en cours par ordre décroissant
     * @return Response
     */
    public function eventIndexAction(Request $request)
    {
        $events = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->findBy([], ['creationDate' => 'desc']);

        return $this->render('default/feed.html.twig', [
            'events' => $events
        ]);
    }

    /**
     *  @Route("/new", name="events-new")
     * @param Request $request
     * Création d'un nouvel évènement
     * @return RedirectResponse|Response
     */
    public function eventNewAction(Request $request)
    {

        $event = new Event();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(NewEventForm::class, [
            'itineraries' => $this->getUser()->getItineraries()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setName($form->get('name')->getData());
            $event->setAuthor($this->getUser());
            $event->setCreationDate(new DateTime());
            $event->setDescription($form->get('description')->getData());
            $event->setItinerary($form->get('itinerary')->getData());

            $merge = new DateTime(
                $form->get('tripDate')->getData()->format('Y-m-d') . ' ' . $form->get('tripTime')->getData()->format('H:i:s')
            );

            $event->setTripDate($merge);
            $event->setNbParticipants(
                ($form->get('nbParticipants')->getData() <= 10) ? $form->get('nbParticipants')->getData() : 10
            );
            $event->addParticipant($this->getUser());

            $em->persist($event);
            $em->flush();

            $this->addFlash('events-new', "Votre évènement a bien été créé.");

            return $this->redirectToRoute('events-index');
        }

        return $this->render('default/events-new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/view/{eventId}", name="events-view")
     * Vue détaillée d'un évènement
     * @param Request $request
     * @param $eventId
     * @return RedirectResponse|Response|NotFoundHttpException
     */
    public function eventDisplayAction(Request $request, $eventId)
    {
        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($eventId);

        if($event == null) return $this->createNotFoundException();

        $eventComment = new EventComment();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EventCommentForm::class, $eventComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $eventComment->setAuthor($this->getUser());
            $eventComment->setCreationDate(new DateTime());
            $eventComment->setEvent($event);

            $em->persist($eventComment);
            $em->flush();

            return $this->redirectToRoute('events-view', [
                'eventId' => $eventId
            ]);
        }
        return $this->render('default/display-event.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete/{eventId}", name="events-delete")
     * Suppression d'un évènement
     * @param Request $request
     * @param $eventId
     * @return RedirectResponse|NotFoundHttpException|AccessDeniedException
     */
    public function eventDeleteAction(Request $request, $eventId)
    {
        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($eventId);

        if($event == null) return $this->createNotFoundException();
        if($this->getUser() != $event->getAuthor() || !$this->getUser()->getRole()->isAdmin())
            return $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('events-index');
    }
}
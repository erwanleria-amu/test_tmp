<?php
/**
 * Created by PhpStorm.
 * User: d15000320
 * Date: 01/11/2018
 * Time: 15:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Event;
use AppBundle\Entity\Location;
use AppBundle\Form\NewEventForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 * @package AppBundle\Controller
 * @Route("/events")
 */
class EventController extends Controller
{
    /**
     * @Route("/", name="events-index")
     */
    public function eventIndexAction(Request $request){

        $events = $this->getDoctrine()->getRepository('AppBundle:Event')
            ->findBy([], ['creationDate' => 'desc']);

        return $this->render('default/feed.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/new", name="events-new")
     */
    public function feedNewAction(Request $request) {

        $event = new Event();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(NewEventForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $locationCheck = $this->getDoctrine()->getRepository('AppBundle:Location')
                ->findOneBy(['latitude' => $form->get('latitude')->getData(), 'longitude' => $form->get('longitude')->getData()]);
            if($locationCheck == null){
                $location = new Location();
                $location->setLatitude($form->get('latitude')->getData());
                $location->setLongitude($form->get('longitude')->getData());
                $em->persist($location);

                $event->setStartPoint($location);
            } else {
                $event->setStartPoint($locationCheck);
            }
            $event->setName($form->get('name')->getData());
            $event->setAuthor($this->getUser());
            $event->setCreationDate(new DateTime());
            $event->setDescription($form->get('description')->getData());

            $merge = new DateTime($form->get('tripDate')->getData()->format('Y-m-d') . ' ' . $form->get('tripTime')->getData()->format('H:i:s'));
            $event->setTripDate($merge);
            $event->setNbParticipants(($form->get('nbParticipants')->getData() <= 10) ? $form->get('nbParticipants')->getData() : 10);
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
     */
    public function eventDisplayAction(Request $request, $eventId){

        $event = $this->getDoctrine()->getRepository('AppBundle:Event')
            ->find($eventId);
        if($event == null) return $this->createNotFoundException();

        return $this->render('default/display-event.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/delete/{eventId}", name="events-delete")
     */
    public function eventDeleteAction(Request $request, $eventId){

        $event = $this->getDoctrine()->getRepository('AppBundle:Event')
            ->find($eventId);
        if($event == null) return $this->createNotFoundException();
        if($this->getUser() != $event->getAuthor() || !$this->getUser()->getRole()->isAdmin()) return $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('events-index');
    }
}
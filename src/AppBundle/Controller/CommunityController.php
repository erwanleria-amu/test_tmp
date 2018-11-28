<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Favorite;
use AppBundle\Entity\Itinerary;
use AppBundle\Entity\ItineraryStep;
use AppBundle\Entity\Location;
use AppBundle\Entity\Waypoint;
use AppBundle\Form\ProfileEditForm;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * Ensemble des pages liées à la communauté.
 *
 * Class CommunityController
 * @package AppBundle\Controller
 * @Route("/community")
 */
class CommunityController extends Controller
{
    /**
     * Affichage du profil de l'utilisateur
     *
     * @Route("/profile/{username}", name="profile-index")
     * @param Request $request
     * @param string $username
     * @return Response
     */
    public function profileAction(Request $request, $username)
    {
        // Récupération de l'utilisateur (si il existe) depuis la base de données via son username
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('username' => $username));

        // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
        if ($user == null) $this->createNotFoundException();

        // Sinon on renvoie la vue de profil avec l'objet User.
        return $this->render('community/profile.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     *
     * Edition du profil de l'utilisateur
     *
     * @Route("/profile/{username}/edit", name="profile-edit")
     * @param Request $request
     * @param string $username
     * @return RedirectResponse|Response
     */
    public function profileEdit(Request $request, $username)
    {
        // Récupération de l'utilisateur (si il existe) depuis la base de données via son username
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('username' => $username));

        // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
        if ($user == null) $this->createNotFoundException();
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on renvoie une erreur 401 Forbidden
        if ($user != $this->getUser()) $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        // On prépare le formulaire
        $form = $this->createForm(ProfileEditForm::class);
        $form->get('description')->setData($user->getDescription());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('avatar')->getData();

            // Si l'utilisateur a modifié son avatar
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('avatars_dir'), $fileName);

                // Si l'utilisateur à un avatar autre que celui par défaut, on le supprime
                if ($user->getAvatar() !== 'default.png')
                    unlink($this->getParameter('avatars_dir') . '/' . $user->getAvatar());

                $user->setAvatar($fileName);
            }

            $user->setDescription($form->get('description')->getData());
            $em->flush();

            return $this->redirectToRoute('profile-index', [
                'username' => $username
            ]);
        }
        return $this->render('community/profile-edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add-friend", name="add-Friend", options={"expose" = true})
     * @param Request $request
     * Action AJAX permettant d'envoyer une requête d'ami
     * @return Response
     */
    public function addFriendAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['id' => $data['userId']]);

        // Si l'utilisateur n'est pas trouvé, on renvoie l'échec de la requête
        if ($user == null) return new Response('FAIL');

        // Sinon on l'ajoute en attente d'acceptation dans la liste d'amis de l'autre utilisateur
        $user->addPendingFriendRequest($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response('DONE');
    }

    /**
     * @Route("/remove-friend", name="remove-Friend", options={"expose" = true})
     * @param Request $request
     * Action AJAX permettant de retirer un ami
     * @return Response
     */
    public function removeFriendAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['id' => $data['userId']]);

        // Si l'utilisateur n'est pas trouvé, on renvoie l'échec de la requête
        if ($user == null) return new Response('FAIL');

        // Sinon on le supprime de la liste d'amis de l'utilisateur
        $user->removeFriendBothWays($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response('DONE');
    }

    /**
     * @Route("/accept-request", name="accept-request", options={"expose" = true})
     * @param Request $request
     * Action AJAX permettant d'accepter une requête d'ami
     * @return Response
     */
    public function acceptFriendRequestAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['id' => $data['userId']]);

        // Si l'utilisateur n'est pas trouvé, on renvoie l'échec de la requête
        if ($user == null) return new Response('FAIL');

        // Utilisateur courant authentifié
        $currentUser = $this->getUser();

        // On ajoute l'utilisateur à la liste d'amis et on le retire de la liste d'attente
        $currentUser->addFriendBothWays($user);
        $currentUser->removePendingFriendRequest($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response('DONE');
    }

    /**
     * @Route("/remove-request", name="remove-request", options={"expose" = true})
     * @param Request $request
     * Action AJAX permettant de refuser une requête d'ami
     * @return Response
     */
    public function removeFriendRequestAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['id' => $data['userId']]);

        // Si l'utilisateur n'est pas trouvé, on renvoie l'échec de la requête
        if ($user == null) return new Response('FAIL');

        $currentUser = $this->getUser();
        $currentUser->removePendingFriendRequest($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response('DONE');
    }

    /**
     * @Route("/add-favorite", name="add-favorite", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet d'ajouter un lieu à ses favoris
     * @return Response
     */
    public function addFavoriteAction(Request $request)
    {
        $data = $request->request->all();

        // On vérifie si cet endroit est déjà enregistré en base de données
        $locationCheck = $this->getDoctrine()
            ->getRepository('AppBundle:Location')
            ->findOneBy(['latitude' => $data['lat'], 'longitude' => $data['lng']]);

        $em = $this->getDoctrine()->getManager();
        $favorite = new Favorite();

        // Si le lieu n'existe pas, on l'ajoute
        if ($locationCheck == null) {
            $location = new Location();
            $location->setName($data['name']);
            $location->setLatitude($data['lat']);
            $location->setLongitude($data['lng']);

            $em->persist($location);
            $favorite->setLocation($location);
        } else {
            // Sinon on définit le lieu comme favori directement
            $favorite->setLocation($locationCheck);
        }
        $favorite->setMapId($data['map_id']);
        $favorite->setUser($this->getUser());

        $em->persist($favorite);
        $em->flush();

        // Si tout se passe correctement, on renvoie l'id du nouveau favori créé
        return new Response($favorite->getId());
    }

    /**
     * @Route("/remove-favorite", name="remove-favorite", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet de supprimer un lieu de ses favoris
     * @return Response
     */
    public function removeFavoriteAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();

        $favorite = $this->getDoctrine()
            ->getRepository('AppBundle:Favorite')
            ->find($data['id']);

        // Si le favori n'est pas trouvé
        if ($favorite == null) return new Response('FAIL');

        $em = $this->getDoctrine()->getManager();
        $em->remove($favorite);
        $em->flush();

        return new Response('DONE');
    }

    /**
     * @Route("/get-location", name="get-location", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet lors de l'affichage de la carte, d'afficher les lieus que l'utilisateur à déjà ajouté en favoris
     * @return Response
     */
    public function getLocationAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();

        $favorite = $this->getDoctrine()
            ->getRepository('AppBundle:Favorite')
            ->findOneBy(['map_id' => $data['map_id']]);

        if ($favorite != null)
            return new Response($favorite->getId());
        return new Response(0);
    }

    /**
     * @Route("/join-event", name="join-event", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet à un utilisateur de rejoindre un évènement
     * @return Response
     */
    public function joinEventAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();

        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($data['eventId']);

        if ($event == null) return new Response('FAIL');

        $event->addParticipant($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new Response($this->getUser()->getAvatar());
    }

    /**
     * @Route("/quit-event", name="quit-event", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet à un utilisateur de quitter un évènement
     * @return Response
     */
    public function quitEventAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();

        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($data['eventId']);

        if ($event == null) return new Response('FAIL');

        $event->removeParticipant($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response($this->getUser()->getUsername());
    }

    /**
     * @Route("/update-cities", name="update-stats-cities", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet de comptabiliser le nombre de villes cherchées par les utilisateurs (purement statistiques)
     * @return Response
     */
    public function updateCitiesAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();
        $user = $this->getUser();

        if ($user == null) return new Response('FAILED');

        $user->setCities($user->getCities() + $data['cities']);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new Response('DONE');
    }

    /**
     * @Route("/add-route", name="add-route", options={"expose" = true})
     */
    public function addRouteAction(Request $request)
    {
        $data = $request->request->all();
        $user = $this->getUser();

        if ($user == null) return new Response('FAILED');

        $itinerary = new Itinerary();

        if(!array_key_exists('name', $data)) $itineraryName = "Itinéraire sans nom";
        else $itineraryName = $data['name'];

        if($data['duration'] >= 86400) $itineraryDuration = null;
        else $itineraryDuration = new DateTime(gmdate("H:i:s", $data['duration']));

        $em = $this->getDoctrine()->getManager();

        $itinerary->setName($itineraryName);
        $itinerary->setAuthor($user);
        $itinerary->setDistance($data['distance']);
        $itinerary->setDuration($itineraryDuration);
        $itinerary->setPolyline(addslashes($data['polyline']));
        $em->persist($itinerary);

        foreach($data['waypoints'] as $coordinates)
        {
            $waypoint = new Waypoint();
            $waypoint->setLatitude($coordinates[1]);
            $waypoint->setLongitude($coordinates[0]);
            $waypoint->setItinerary($itinerary);
            $em->persist($waypoint);
        }
        $em->flush();
        return new Response('DONE');
    }

    /**
     * @Route("/itinerary/{itineraryId}", name="view-itinerary")
     * @param Request $request
     * @param $itineraryId
     * @return string|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function viewItineraryAction(Request $request, $itineraryId)
    {
        $itinerary = $this->getDoctrine()
            ->getRepository('AppBundle:Itinerary')
            ->find($itineraryId);

        if($itinerary == null) return $this->createNotFoundException();

        return $this->render('default/itinerary-view.html.twig', [
            'itinerary' => $itinerary
        ]);
    }

    /**
     * @Route("/remove-itinerary", name="remove-itinerary", options={"expose" = true})
     * @param Request $request
     * Action AJAX qui permet de supprimer un itinéraire
     * @return Response
     */
    public function removeItineraryAction(Request $request)
    {
        // On récupère les données de la requête
        $data = $request->request->all();

        $itinerary = $this->getDoctrine()
            ->getRepository('AppBundle:Itinerary')
            ->find($data['id']);

        // Si le favori n'est pas trouvé
        if ($itinerary == null) return new Response('FAIL');

        $em = $this->getDoctrine()->getManager();
        $em->remove($itinerary);
        $em->flush();

        return new Response('DONE');
    }
}
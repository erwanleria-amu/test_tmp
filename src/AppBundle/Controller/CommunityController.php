<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Artwork;
use AppBundle\Entity\Location;
use AppBundle\Entity\Report;
use AppBundle\Entity\SurveyResult;
use AppBundle\Entity\User;
use AppBundle\Form\ProfileEditForm;
use AppBundle\Form\VoteForm;
use AppBundle\Form\EpisodeForm;
use AppBundle\Repository\SurveyRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommunityController extends Controller
{
    /**
     * @Route("/profile/{username}", name="profile-index")
     * @param $username
     * @param Request $request
     * @return Response
     */
    public function profileAction($username, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('username' => $username));

        if($user != null) {

            return $this->render('community/profile.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/profile/{username}/edit", name="profile-edit")
     * @param Request $request
     * @param string $username
     * @return RedirectResponse|Response
     */
    public function profileEdit(Request $request, string $username)
    {
        if($username === $this->getUser()->getUsername()) {

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $form = $this->createForm(ProfileEditForm::class);

            $form->get('description')->setData($user->getDescription());

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $form->get('avatar')->getData();

                if($file != null){
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    $file->move(
                        $this->getParameter('avatars_dir'),
                        $fileName
                    );

                    if($user->getAvatar() !== 'default.png'){
                        unlink($this->getParameter('avatars_dir') . '/' . $user->getAvatar());
                    }
                    $user->setAvatar($fileName);
                }
                $user->setDescription($form->get('description')->getData());
                $em->flush();

                return $this->redirectToRoute('profile-index', ['username' => $username]);
            }

            return $this->render('community/profile-edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('profile-index', [
           'username' => $username
        ]);
    }

    /**
     * @Route("/community/add-friend", name="add-Friend", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function addFriend(Request $request){
        $data = $request->request->all();

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['id' => $data['userId']]);

        $currentUser = $this->getUser();
        $user->addPendingFriendRequest($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new Response('OK');
    }

    /**
     * @Route("/community/remove-friend", name="remove-Friend", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function removeFriend(Request $request){
        $data = $request->request->all();

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['id' => $data['userId']]);

        $currentUser = $this->getUser();
        $user->removeFriendBothWays($currentUser);
        $this->getDoctrine()->getManager()->flush();

        return new Response('OK');
    }

    /**
     * @Route("/community/accept-request", name="accept-request", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function acceptFriendRequest(Request $request){

        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $user = $doctrine->getRepository('AppBundle:User')->findOneBy(['id' => $data['userId']]);

        $currentUser = $this->getUser();
        $currentUser->addFriendBothWays($user);
        $currentUser->removePendingFriendRequest($user);
        $doctrine->getManager()->flush();

        return new Response('OK');
    }

    /**
     * @Route("/community/remove-request", name="remove-request", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function removeFriendRequest(Request $request){

        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $user = $doctrine->getRepository('AppBundle:User')->findOneBy(['id' => $data['userId']]);

        $currentUser = $this->getUser();
        $currentUser->removePendingFriendRequest($user);
        $doctrine->getManager()->flush();

        return new Response('OK');
    }

    /**
     * @Route("/community/add-favorite", name="add-favorite", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function addFavoriteAction(Request $request){

        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $user = $this->getUser();

        $favorite = new Location();
        $favorite->setName($data['name']);
        $favorite->setLat($data['lat']);
        $favorite->setLon($data['lng']);
        $favorite->setMapId($data['map_id']);
        $favorite->setUser($user);

        $doctrine->getManager()->persist($favorite);
        $doctrine->getManager()->flush();

        return new Response($favorite->getId());
    }

    /**
     * @Route("/community/remove-favorite", name="remove-favorite", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function removeFavoriteAction(Request $request){

        $data = $request->request->all();

        $doctrine = $this->getDoctrine();

        $favorite = $doctrine->getRepository('AppBundle:Location')
            ->find($data['id']);

        $doctrine->getManager()->remove($favorite);
        $doctrine->getManager()->flush();

        return new Response('OK');
    }

    /**
     * @Route("/community/get-location", name="get-location", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function getLocationAction(Request $request){

        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $favorite = $doctrine->getRepository('AppBundle:Location')
            ->findOneBy(['map_id' => $data['map_id']]);

        if($favorite != null)
            return new Response($favorite->getId());
        return new Response(0);
    }
}
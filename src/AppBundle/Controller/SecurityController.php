<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     * Page de création de compte
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if($this->getUser()) return $this->redirectToRoute('index');

        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Le traitement des données se fait via le UserListener
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('register', "Votre inscription a bien été prise en compte, vous pouvez maintenant vous connecter");
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     * Page de connexion
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function loginAction(Request $request)
    {
        if($this->getUser()) return $this->redirectToRoute('homepage');

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * Page de déconnexion (Automatiquement liée via le security.yml)
     */
    public function logoutAction(){}
}

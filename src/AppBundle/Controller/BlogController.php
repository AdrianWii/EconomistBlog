<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Helper\Journal\Journal;
use AppBundle\Helper\DataProvider;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Entity\Register;

/**
 * Description of BlogController
 *
 * @author adrian
 * 
 */
class BlogController extends Controller {

    /**
     * @Route(
     *      "/index",
     *      name="blog_index"
     * )
     * 
     * @Template
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route(
     *      "/journal",
     *      name="blog_journal"
     * )
     * 
     * @Template
     */
    public function journalAction() {

        return array(
            'history' => Journal::getHistoryAsArray()
                //   'history' => array()
        );
    }

    /**
     * @Route(
     *      "/about",
     *      name="blog_about"
     * )
     * 
     * @Template
     */
    public function aboutAction() {
        return array();
    }

    /**
     * @Route(
     *          "/contact",
     *          name="blog_contact"
     * )
     * 
     * @Template
     */
    public function contactAction() {
        return array();
    }

    /**
     * 
     * @Template("AppBundle:Blog/Widgets:secondWidget.html.twig")
     */
    public function secondWidgetAction() {
        return array(
            'list' => DataProvider::getFollowings()
        );
    }

    /**
     * 
     * @Template("AppBundle:Blog/Widgets:thirdWidget.html.twig")
     */
    public function thirdWidgetAction() {
        return array(
            'list2' => DataProvider::getWallet()
        );
    }

    /**
     * @Route(
     *          "/register",
     *          name="blog_register"
     * )
     * 
     * @Template
     */
    public function registerAction(Request $request) {

        $register = new Register();

        $session = $this->get('session');

        if (!$session->has('registered')) {
            $form = $this->createForm(RegisterType::class, $register);
            $form->handleRequest($request);

            if ($request->isMethod('POST')) {
                if ($form->isValid()) {

                    $savePath = $this->get('kernel')->getRootDir() . '/../web/uploads/';
                    $register->save($savePath);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($register);
                    $em->flush();

                    //send mail
                    $msgBody = $this->renderView('AppBundle:Email:base.html.twig', array('name' => $register->getName()));
                    $message = \Swift_Message::newInstance()
                            ->setSubject('Potwierdzenie uczestnictwa')
                            ->setFrom('adrian.widlak@interia.pl', 'Adrian')
                            ->setTo(array($register->getEmail() => $register->getName()))
                            ->setBody($msgBody, 'text/html');

                    $this->get('mailer')->send($message);

                    $session->getFlashBag()->add('success', 'zgłoszenie zapisane');
                    $this->get('notification')->addSuccess('zgłoszenie zapisane');
                    //$session->set('registered', true);
                    return $this->redirect($this->generateUrl('blog_register'));
                } else {
                    //$session->getFlashBag()->add('danger', 'zgłoszenie nie zostało zapisane');
                    $this->get('notification')->addError('popraw błędy');
                }
            }
        }

        return array(
            'form' => isset($form) ? $form->createView() : NULL
        );
    }

}

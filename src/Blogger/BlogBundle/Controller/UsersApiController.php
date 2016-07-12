<?php
/**
 * Created by PhpStorm.
 * User: zaytsev
 * Date: 12.07.16
 * Time: 14:42
 */

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UsersApiController
 * @package Blogger\BlogBundle\Controller
 */
class UsersApiController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     */
    public function getUsersAllAction()
    {
        $users = $this->getDoctrine()->getRepository('BloggerBlogBundle:User')->findAll();

        $view = $this->view($users, 200);
        return $this->handleView($view);
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('BloggerBlogBundle:User')->find($id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found');
        }

        $view = $this->view($user, 200);
        return $this->handleView($view);
    }
}
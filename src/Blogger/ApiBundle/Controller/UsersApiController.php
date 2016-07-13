<?php
/**
 * Created by PhpStorm.
 * User: zaytsev
 * Date: 12.07.16
 * Time: 14:42
 */

namespace Blogger\ApiBundle\Controller;

use Blogger\BlogBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UsersApiController
 * @package Blogger\ApiBundle\Controller
 */
class UsersApiController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     * @ApiDoc(
     *  resource=true,
     *  description="Get all users",
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Access-Token",
     *           "required"=true
     *      }
     *  }
     * )
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
     * @ApiDoc(
     *  description="Get user by id",
     * resource=true,
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Where get user by ID"
     *      }
     *  },
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Access-Token",
     *           "required"=true
     *      }
     *  }
     * )
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
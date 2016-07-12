<?php
/**
 * Created by PhpStorm.
 * User: zaytsev
 * Date: 12.07.16
 * Time: 14:42
 */

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BlogApiController
 * @package Blogger\BlogBundle\Controller
 */
class BlogApiController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"blog"})
     */
    public function getPostsAllAction()
    {
        $posts = $this->getDoctrine()->getRepository('BloggerBlogBundle:Blog')->findAll();

        $view = $this->view($posts, 200);
        return $this->handleView($view);
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"blog"})
     */
    public function getPostAction($id)
    {
        $post = $this->getDoctrine()->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$post instanceof Blog) {
            throw new NotFoundHttpException('Post not found');
        }

        $view = $this->view($post, 200);
        return $this->handleView($view);
    }
}
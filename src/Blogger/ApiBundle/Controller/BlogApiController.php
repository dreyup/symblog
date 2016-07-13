<?php
/**
 * Created by PhpStorm.
 * User: zaytsev
 * Date: 12.07.16
 * Time: 14:42
 */

namespace Blogger\ApiBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class BlogApiController
 * @package Blogger\ApiBundle\Controller
 */
class BlogApiController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"blog"})
     * * @ApiDoc(
     *  resource=true,
     *  description="Get all posts",
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Access-Token",
     *           "required"=true
     *      }
     *  }
     * )
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
     * @ApiDoc(
     *  description="Get post by id",
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Where get post by ID"
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
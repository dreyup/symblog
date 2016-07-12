<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\UploadImage;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Blogger\BlogBundle\Form\BlogType;

/**
 * Blog controller.
 *
 */
class BlogController extends Controller
{
    /**
     * Lists all Blog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')->findAll();

        return $this->render('BloggerBlogBundle:Blog:index.html.twig', array(
            'blogs' => $blogs,
        ));
    }

    /**
     * Creates a new Blog entity.
     *
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm('Blogger\BlogBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }

        return $this->render('BloggerBlogBundle:Blog:new.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Blog entity.
     *
     */
    public function showAction($id, $slug, $comments)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('BloggerBlogBundle:Comment')
            ->getCommentsForBlog($blog->getId());

        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments
        ));
    }

    /**
     * Displays a form to edit an existing Blog entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);
        $deleteForm = $this->createDeleteForm($blog);
        $editForm = $this->createForm('Blogger\BlogBundle\Form\BlogType', $blog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted())
        {
            $uploaded_file = $editForm['image']->getData();
            if ($uploaded_file) {
                $image = BlogType::processImage($uploaded_file, $blog);
                if (isset ($image['error'])) $editForm->addError(new FormError($image['error']));
                $blog->setImage($image);
            }
            
            if ($editForm->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($blog);
                $em->flush();

                return $this->redirectToRoute('blog_edit', array('id' => $blog->getId()));
            }
        } 

        return $this->render('BloggerBlogBundle:Blog:edit.html.twig', array(
            'blog' => $blog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Blog entity.
     *
     */
    public function deleteAction(Request $request, Blog $blog)
    {
        $form = $this->createDeleteForm($blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * Creates a form to delete a Blog entity.
     *
     * @param Blog $blog The Blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

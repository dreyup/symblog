<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('author', 'entity', array(
                'class' => 'BloggerBlogBundle:User',
                'property' => 'username',
                'expanded' => false,
                'multiple' => false
            ))
//            ->add('author')
            ->add('blog')
            ->add('image', FileType::class,array(
                'data_class' => null,
            ))
            ->add('tags')
        ;
    }

    
    public static function processImage(UploadedFile $uploaded_file, Blog $blog)
    {
        $path = 'images/';
        //getClientOriginalName() => Returns the original file name.
        $uploaded_file_info = pathinfo($uploaded_file->getClientOriginalName());
        $file_name =
            "post_" .
            $blog->getId() . '_'. $uploaded_file_info['filename'].
            "." .
            $uploaded_file_info['extension']
        ;

        $uploaded_file->move($path, $file_name);

        return $file_name;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(array(
            'data_class' => 'Blogger\BlogBundle\Entity\Blog'
        ));
    }


}

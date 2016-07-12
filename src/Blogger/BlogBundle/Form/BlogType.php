<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Controller\BlogController;
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
            ->add('blog')
            ->add('image', FileType::class,array(
                'data_class' => null,
            ))
            ->add('tags');
    }
    
    public static function processImage(UploadedFile $uploaded_file, Blog $blog)
    {
        $types = array('gif', 'png', 'jpeg', 'jpg');
        $path = 'images/';
        $min_size = 650;

        $result = [];

        $uploaded_file_info = pathinfo($uploaded_file->getClientOriginalName());

        if (!in_array($uploaded_file_info['extension'], $types))
        {
            $result['error'] = 'Invalid file type';
            return $result;
        }


        switch ($uploaded_file->getMimeType()) {
            case 'image/jpeg' :  $source = imagecreatefromjpeg($uploaded_file->getRealPath());
                break;
            case 'image/png' : $source = imagecreatefrompng($uploaded_file->getRealPath());
                break;
            case 'image/gif' : $source = imagecreatefromgif($uploaded_file->getRealPath());
                break;
            default : $result['error'] = 'Invalid file type';
        }
        $file_name =
            "post_" .
            $blog->getId() . '_'. $uploaded_file_info['filename'].
            "." .
            $uploaded_file_info['extension']
        ;
        $w_src = imagesx($source);
        $h_src = imagesy($source);

        if ($w_src < $min_size) {
             $result['error'] = 'Invalid Image size';
        } else {
            $ratio = $w_src/650;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);

            $dest = imagecreatetruecolor($w_dest, $h_dest);

            imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            imagejpeg($dest, $path.$file_name, 75);

            imagedestroy($dest);
            imagedestroy($source);
            
            return $file_name;
        }

        return $result;
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

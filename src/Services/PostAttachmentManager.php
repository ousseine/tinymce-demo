<?php

namespace App\Services;

use App\Entity\Post;
use App\Entity\PostAttachment;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostAttachmentManager
{
    private ContainerInterface $parameterBag;
    private EntityManagerInterface $manager;

    public function __construct(ContainerInterface $parameterBag, EntityManagerInterface $manager)
    {
        $this->parameterBag = $parameterBag;
        $this->manager = $manager;
    }

    #[ArrayShape(['filename' => "string", 'path' => "string"])]
    public function uploadPostAttachment(UploadedFile $file, Post $post): array
    {
        $filename = md5(uniqid()) . '.' .$file->guessExtension();

        $file->move(
            $this->getUploadDir(),
            $filename
        );

        $postAttachment = new PostAttachment();
        $postAttachment->setFilename($filename)
            ->setPath('/uploads/' .$filename)
            ->setPost($post);

        $post->addPostAttachment($postAttachment);

        $this->manager->persist($postAttachment);
        $this->manager->flush();

        return [
            'filename' => $filename,
            'path' => '/uploads/' . $filename
        ];
    }

    private function getUploadDir()
    {
        return $this->parameterBag->get('uploads');
    }
}
<?php

namespace App\Controller;

use App\Entity\Post;
use App\Services\PostAttachmentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostAttachmentController extends AbstractController
{
    private PostAttachmentManager $attachmentManager;

    public function __construct(PostAttachmentManager $attachmentManager)
    {
        $this->attachmentManager = $attachmentManager;
    }

    #[Route('/post/attachment/{id}', name: 'app_post_attachment')]
    public function index(Request $request, Post $post): Response
    {
        $file = $request->files->get('file');
        $basePath = $this->attachmentManager->uploadPostAttachment($file, $post);

        return $this->json([
            'location' => $basePath['path']
        ]);
    }
}

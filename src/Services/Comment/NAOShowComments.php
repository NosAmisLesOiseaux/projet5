<?php

// src/Services/Comment/NAOShowComments.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAOShowComments extends NAOManager
{
    /**
     * @param $comments
     * @return JsonResponse
     */
	public function formatCapturePublishedComments($comments)
	{
        $formatted = [];
        foreach ($comments as $comment) {
            if($comment->getAuthor()->getAvatar() == null)
            {
                $authorAvatar = $comment->getAuthor()->getAvatar();
            }
            else 
            {
                $authorAvatar = $comment->getAuthor()->getAvatar()->getPath();
            }
            $formatted[] = [
                'id' => $comment->getId(),
                'authorAvatar' => $authorAvatar,
                'authorFirstname' => $comment->getAuthor()->getFirstname(),
                'authorLastname' => $comment->getAuthor()->getLastname(),
                'content' => $comment->getContent(),
                'createdDate' => $comment->getCreatedDate(),
            ];
        }

        return new JsonResponse($formatted);
	}
}

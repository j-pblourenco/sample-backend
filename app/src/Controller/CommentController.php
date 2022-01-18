<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\CommentLikeRepository;
use App\Lib\Responses;

/**
 * @Route("/comment", name="comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/create", name="create_comment", methods={"POST"})
     */
    public function create(Request $request, CommentRepository $commentRepository, PostRepository $postRepository): Response
    {
        // retrieve request body contents
        $data = json_decode($request->getContent());

        // check if the provided Post exists
        if(!$post = $postRepository->findOneById($data->post_id)) return $this->json(array_merge(
            Responses::RESPONSE_ERROR,
            Responses::ERRORS['resource_not_exist']
          ));

        // check if the provided Comment exists
        if(isset($data->comment_id) && !$commentRepository->findOneById($data->comment_id)) return $this->json(array_merge(
            Responses::RESPONSE_ERROR,
            Responses::ERRORS['resource_not_exist']
          ));

        // add Post object to data
        $data->post = $post;

        // create Comment and persist to DB
        $comment = $commentRepository->create($data);

        //convert Comment object into an array to return in response
        $commentArr = $commentRepository->commentToArr($comment);

        return $this->json(array_merge(
          Responses::RESPONSE_SUCCESS,
          [
            'comment' => $commentArr,
          ]
        ));
    }

    /**
     * @Route("/like", name="like_comment", methods={"POST"})
     */
    public function like(Request $request, CommentRepository $commentRepository, CommentLikeRepository $commentLikeRepository): Response
    {
      // retrieve request body contents
      $data = json_decode($request->getContent());

      // fetch Comment from DB, return error if no Coment matches the requested ID
      if(
          !$comment = $commentRepository->findOneById($data->comment_id)
      ) return $this->json(array_merge(
          Responses::RESPONSE_ERROR,
          Responses::ERRORS['resource_not_exist']
        ));

      // add Comment to data
      $data->comment = $comment;

      // get user IP
      $ip = $request->getClientIp();

      // add user IP to data
      $data->ip = $ip;

      // check if the Comment has already been liked by the current IP address
      if($commentLikeRepository->findOneBy([
        'comment' => $comment,
        'ip' => $ip
      ])) return $this->json(array_merge(
            Responses::RESPONSE_ERROR,
            Responses::ERRORS['user_already_liked_comment']
          ));

      // create CommentLike and persist to DB
      $commentLike = $commentLikeRepository->create($data);

      return $this->json(array_merge(
        Responses::RESPONSE_SUCCESS,
        [
          'postLike' => $commentLikeRepository->commentLikeToArr($commentLike)
        ]
      ));
    }
}

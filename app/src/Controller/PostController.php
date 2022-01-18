<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostDescriptionRepository;
use App\Repository\PostLinkRepository;
use App\Repository\PostLikeRepository;
use App\Lib\Responses;

/**
 * @Route("/post", name="post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/create", name="create_post", methods={"POST"})
     */
    public function create(Request $request, PostRepository $postRepository, PostDescriptionRepository $postDescriptionRepository, PostLinkRepository $postLinkRepository): Response
    {
        // retrieve request body contents
        $data = json_decode($request->getContent());

        // create Post and persist to DB
        $post = $postRepository->create($data);

        // check wether to add a Description or Link to the Post and persist that to DB
        if(isset($data->description)) $postDescription = $postDescriptionRepository->create($data, $post);
        else if(isset($data->link)) $postLink = $postLinkRepository->create($data, $post);

        //convert Post object into an array to return in response
        $postArr = $postRepository->postToArr($post);

        return $this->json(array_merge(
          Responses::RESPONSE_SUCCESS,
          [
            'post' => $postArr
          ]
        ));
    }

    /**
     * @Route("/details/{id}", name="post_details", methods={"GET"})
     */
    public function details(Request $request, PostRepository $postRepository, $id): Response
    {
        // fetch Post from DB, return error if no Post matches the requested ID
        if(
            !$post = $postRepository->findOneById($id)
        ) return $this->json(array_merge(
            Responses::RESPONSE_ERROR,
            Responses::ERRORS['resource_not_exist']
          ));

        // convert Post to array
        $postArr = $postRepository->postToArr($post);

        return $this->json(array_merge(
          Responses::RESPONSE_SUCCESS,
          [
            'post' => $postArr,
          ]
        ));
    }

    /**
     * @Route("/list/{limit}/{offset}", name="post_list", methods={"GET"})
     */
    public function list(Request $request, PostRepository $postRepository, $limit, $offset): Response
    {
        // fetch Post from DB, return error if no Post matches the requested ID
        $posts = $postRepository->paginate($limit, $offset);

        // find ammount of Posts
        $maxPosts = $postRepository->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $postsArr = [];
        foreach($posts as $post){
           $postsArr[] = $postRepository->postToArrBasic($post);
        }

        return $this->json(array_merge(
          Responses::RESPONSE_SUCCESS,
          [
            'posts' => $postsArr,
            'maxPosts' => $maxPosts
          ]
        ));
    }

    /**
     * @Route("/like", name="post_like", methods={"POST"})
     */
    public function like(Request $request, PostRepository $postRepository, PostLikeRepository $postLikeRepository): Response
    {
      // retrieve request body contents
      $data = json_decode($request->getContent());

      // fetch Post from DB, return error if no Post matches the requested ID
      if(
          !$post = $postRepository->findOneById($data->post_id)
      ) return $this->json(array_merge(
          Responses::RESPONSE_ERROR,
          Responses::ERRORS['resource_not_exist']
        ));

      // add post to data
      $data->post = $post;

      // get user IP
      $ip = $request->getClientIp();

      // add user IP to data
      $data->ip = $ip;

      // check if the Post has already been liked by the current IP address
      if($postLikeRepository->findOneBy([
        'post' => $post,
        'ip' => $ip
      ])) return $this->json(array_merge(
            Responses::RESPONSE_ERROR,
            Responses::ERRORS['user_already_liked_post']
          ));

      // create PostLike and persist to DB
      $postLike = $postLikeRepository->create($data);

      return $this->json(array_merge(
        Responses::RESPONSE_SUCCESS,
        [
          'postLike' => $postLikeRepository->postLikeToArr($postLike)
        ]
      ));
    }
}

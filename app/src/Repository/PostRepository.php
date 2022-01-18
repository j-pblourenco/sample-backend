<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CommentRepository;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, CommentRepository $commentRepository)
    {
        parent::__construct($registry, Post::class);
        $this->commentRepository = $commentRepository;
    }

    public function create($data){
      try{
        $post = new Post();

        $post->setTitle($data->title);
        $post->setCreatedAt(new \DateTimeImmutable ());

        $this->_em->persist($post);
        $this->_em->flush();

        return $post;
      }catch(Exception $e){
        return $e;
      }
    }

    // converts Post to array and lists all comments
    public function postToArr($post){
      // find Comments on current Post
      $comments = $this->commentRepository->findBy(['post' => $post], ['created_at' => 'DESC']);
      $commentsAmmount = count($comments);

      // convert Comments to array
      $commentsArr = [];
      foreach ($comments as $comment) {
        // if comment is reply to another comment, skip everything
        if($comment->getComment() === null){
          // find Comment replies
          $replies = $comment->getComments();

          // add replies to array
          $repliesArr = [];
          foreach ($replies as $reply) {
            // find reply likes count
            $likes = count($reply->getCommentLikes());
            $repliesArr[] = [
              'id' => $reply->getId(),
              'text' => $reply->getText(),
              'likes' => $likes,
              'created_at' => $reply->getCreatedAt(),
            ];
          }
          // sort replies by date descending
          usort($repliesArr, function ($a, $b) {
              return $b['created_at'] <=> $a['created_at'];
          });

          // find CommentLikes count
          $likes = count($comment->getCommentLikes());

          // push comment to array
          $commentsArr[] = [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'likes' => $likes,
            'replies' => $repliesArr,
            'created_at' => $comment->getCreatedAt()
          ];
        }
      }

      // find PostLikes count
      $likes = count($post->getPostLikes());

      return [
        'id' => $post->getId(),
        'title' => $post->getTitle(),
        'comments' => $commentsArr,
        'comments_ammount' => $commentsAmmount,
        'likes' => $likes,
        'description' => $post->getDescription() !== null
          ?
            $post->getDescription()->getDescription()
          : null,
        'link' => $post->getLink() !== null
          ?
            $post->getLink()->getLink()
          : null,
        'created_at' => $post->getCreatedAt()
      ];
    }

    // converts Post to array without listing all comments
    public function postToArrBasic($post){
      // find Comments on current Post and count them
      $comments = $this->commentRepository->findBy(['post' => $post], ['created_at' => 'DESC']);
      $commentsAmmount = count($comments);

      // find PostLikes count
      $likes = count($post->getPostLikes());

      return [
        'id' => $post->getId(),
        'title' => $post->getTitle(),
        'comments_ammount' => $commentsAmmount,
        'likes' => $likes,
        'description' => $post->getDescription() !== null
          ?
            $post->getDescription()->getDescription()
          : null,
        'link' => $post->getLink() !== null
          ?
            $post->getLink()->getLink()
          : null,
        'created_at' => $post->getCreatedAt()
      ];
    }

    // returns all Post in descending chronological order
    public function findAll()
    {
        return $this->findBy(array(), array('created_at' => 'DESC'));
    }

    public function paginate($limit, $offset)
    {
        return $this->findBy(array(), array('created_at' => 'DESC'), $limit, $offset);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

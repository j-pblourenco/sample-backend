<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function create($data){
      try{
        $comment = new Comment();

        $comment->setText($data->text);
        $comment->setPost($data->post);
        if(isset($data->comment_id)) $comment->setComment($this->findOneById($data->comment_id));
        $comment->setCreatedAt(new \DateTimeImmutable ());

        $this->_em->persist($comment);
        $this->_em->flush();

        return $comment;
      }catch(Exception $e){
        return $e;
      }
    }

    public function commentToArr($comment){
      // find CommentLikes count
      $likes = count($comment->getCommentLikes());

      return [
        'id' => $comment->getId(),
        'text' => $comment->getText(),
        'likes' => $likes,
        'created_at' => $comment->getCreatedAt(),
        'post' => [
          'id' => $comment->getPost()->getId(),
        ],
        'comment' => $comment->getComment() !== null
          ?
            [
              'id' => $comment->getComment()->getId(),
            ]
          : null,
        'created_at' => $comment->getCreatedAt()
      ];
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\PostLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLink[]    findAll()
 * @method PostLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLink::class);
    }

    public function create($data, $post){
      try{
        $postLink = new PostLink();

        $postLink->setLink($data->link);
        $postLink->setPost($post);

        $this->_em->persist($postLink);
        $this->_em->flush();

        return $postLink;
      }catch(Exception $e){
        return $e;
      }
    }

    public function postLinkToArr($postLink){
      return [
        'id' => $postLink->getId(),
        'link' => $postLink->getLink(),
        'post_id' => $postLink->getPost()->getId()
      ];
    }

    // /**
    //  * @return PostLink[] Returns an array of PostLink objects
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
    public function findOneBySomeField($value): ?PostLink
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

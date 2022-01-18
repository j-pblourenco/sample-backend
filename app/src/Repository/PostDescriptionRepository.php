<?php

namespace App\Repository;

use App\Entity\PostDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostDescription[]    findAll()
 * @method PostDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostDescription::class);
    }

    public function create($data, $post){
      try{
        $postDescription = new PostDescription();

        $postDescription->setDescription($data->description);
        $postDescription->setPost($post);

        $this->_em->persist($postDescription);
        $this->_em->flush();

        return $postDescription;
      }catch(Exception $e){
        return $e;
      }
    }

    public function postDescriptionToArr($postDescription){
      return [
        'id' => $postDescription->getId(),
        'description' => $postDescription->getDescription(),
        'post_id' => $postDescription->getPost()->getId()
      ];
    }

    // /**
    //  * @return PostDescription[] Returns an array of PostDescription objects
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
    public function findOneBySomeField($value): ?PostDescription
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

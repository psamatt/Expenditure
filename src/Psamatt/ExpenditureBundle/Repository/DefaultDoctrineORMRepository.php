<?php

namespace Psamatt\ExpenditureBundle\Repository;

use Psamatt\ExpenditureBundle\Repository\Exception\NamespaceNotFoundException;

use Doctrine\ORM\EntityManager;

class DefaultDoctrineORMRepository implements RepositoryInterface
{
    /**
     * The entity manager
     *
     */
    protected $em;

    /**
     * The ORM repository
     *
     */
    protected $repository = null;
    
    /**
     * The namespace to use to find the specific entity
     *
     */
    protected $namespace = null;
    
    /**
     * Constructor
     *
     */
    public function __construct(EntityManager $em) 
    {
        $this->em = $em;
    }
    
    /**
     * {inheritdoc}
     */
    public function findById($id) 
    {
        return $this->getRepository()->find($id);
    }
    
    /**
     * {inheritdoc}
     */
    public function findOneBy(array $clauses = array())
    {
        return $this->getRepository()->findOneBy($clauses);
    }
    
    /**
     * {inheritdoc}
     */
    public function findBy(array $clauses = array(), $order = array(), $limit = array())
    {
        return $this->getRepository()->findBy(
            $clauses, 
            $order, 
            isset($limit['numRows'])? (int)$limit['numRows']:null, 
            isset($limit['offset'])? (int)$limit['offset']:null
        );
    }
    
    /**
     * {inheritdoc}
     */
    public function save($item, $flush = null)
    {
        $this->em->persist($item);
        
        if ($flush === true) {
            $this->em->flush();
        }
    }
    
    /**
     * {inheritdoc}
     */
    public function delete($item, $flush = null)
    {
        $this->em->remove($item);
        
        if ($flush === true) {
            $this->em->flush();
        }
    }
    
    /**
     * {inheritdoc}
     */
    public function getRepository()
    {
        if ($this->namespace == null) {
            throw new NamespaceNotFoundException;
        }
    
        if ($this->repository != null) {
            return $this->repository;
        }

        return $this->repository = $this->em->getRepository($this->namespace);
    }

}
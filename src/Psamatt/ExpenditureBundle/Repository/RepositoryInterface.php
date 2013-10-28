<?php

namespace Psamatt\ExpenditureBundle\Repository;

interface RepositoryInterface
{
    /**
     * Find a record by its particular ID
     *
     * @param integer|object The ID to find the object by
     * @return mixed If the record found, thus is returned, otherwise null
     */
    function findById($id);
    
    /**
     * Find a result set of objects by specific criteria
     *
     * @param array $clauses The list of clauses to refine the result set by  
     * @param array $order The order of which to get the result set id, key = column, value = order
     * @param array $limit How many items to return. offset and numRows
     */
    function findBy(array $clauses = array(), $order = array(), $limit = array());
    
    /**
     * Save the record 
     *
     * @param mixed $item The item to save
     * @return boolean
     */
    function save($item);
    
    /**
     * Delete the record
     *
     * @param mixed $item The item to delete
     * @return boolean
     */
    function delete($item);
    
    /**
     * Get the repository to return the result set by
     *
     * @param object
     */
    function getRepository();
}
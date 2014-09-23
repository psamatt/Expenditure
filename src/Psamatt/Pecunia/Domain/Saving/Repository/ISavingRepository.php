<?php

namespace Psamatt\Pecunia\Domain\Saving\Repository;

use Psamatt\Pecunia\Domain\Saving\Entity\Saving;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;

interface ISavingRepository
{
    /**
     * Find a saving
     *
     * @param SavingId $id
     * @return Saving
     */
    public function find(SavingId $id);

    /**
     * Save a Saving
     *
     * @param Saving $saving
     */
    public function save(Saving $saving);

    /**
     * Remove a Saving
     *
     * @param Saving $saving
     */
    public function remove(Saving $saving);
}
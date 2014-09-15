<?php

namespace Psamatt\Pecunia\Domain\Saving\ValueObject;

use Rhumsaa\Uuid\Uuid;

/**
 *
 */
class SavingId
{
    protected $id;

    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Generate a new Uuid v4
     * @return self
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }

    /**
     * Bind an id
     *
     * @return self
     */
    public static function bind($id)
    {
        return new self($id);
    }

    /** @return string */
    public function __toString()
    {
        return (string)$this->id;
    }
}
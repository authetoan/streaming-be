<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Traits;

use DateTimeInterface;

trait TimestampableTrait
{
    protected DateTimeInterface $created_at;
    protected DateTimeInterface $updated_at;

    /**
     * @param DateTimeInterface $created_at
     */
    public function setCreatedAt(DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $updated_at
     */
    public function setUpdatedAt(DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @return void
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtOnPrePersist(): void
    {
        $now = new \DateTime('now');
        $this->setCreatedAt($now);
        $this->setUpdatedAt($now);
    }

    /**
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAtOnPreUpdate(): void
    {
        $now = new \DateTime('now');
        $this->setUpdatedAt($now);
    }
}

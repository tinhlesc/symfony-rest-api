<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Persistence\ManagerRegistry;

class OrderItemRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
}

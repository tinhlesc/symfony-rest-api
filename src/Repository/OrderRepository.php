<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
}

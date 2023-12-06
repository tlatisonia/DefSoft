<?php
namespace App\Event;

use App\Entity\Products;
use Symfony\Contracts\EventDispatcher\Event;



/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class AllProductEvent extends Event
{
    public const ALL_PRODUCTS_EVENT = 'product.all';

    public function __construct(
        protected array $products,
    ) {   }

    public function getProduct(): int
    {
        return count($this->products);
    }
}
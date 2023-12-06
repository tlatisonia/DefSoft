<?php
namespace App\Event;

use App\Entity\Products;
use Symfony\Contracts\EventDispatcher\Event;



/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class AddProductEvent extends Event
{
    public const ADD_PRODUCTS_EVENT = 'product.add';

    public function __construct(
        protected Products $products,
    ) {   }

    public function getProduct(): Products
    {
        return $this->products;
    }
}
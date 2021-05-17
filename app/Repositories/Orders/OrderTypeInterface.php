<?php

namespace App\Repositories\Orders;

interface OrderTypeInterface
{
    public function validateData();

    public function makeOrder();

    public function redirectOrInform();
}
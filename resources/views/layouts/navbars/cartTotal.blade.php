<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       
    </head>
    <body>
    <li class="nav-item">
        <a class="btn btn-success btn-sm ml-3" href="{{route('cart.checkout')}}">
            <i class="fa fa-shopping-cart"></i>
            <span class="badge badge-light">{{Cart::getTotalQuantity()}}</span>
        </a>
    </li>
    </body>
</html>
<?php

namespace App\Controllers;

use App\Models\ProductsModel;

class HomeController
{
    public function index(): void
    {
        $products = ProductsModel::all()->get();
        view('home.index', ['products' => $products]);
    }
}

<?php

namespace App\Controllers;

use App\Models\ProductsModel;

class ShowProductController
{
    public function index()
    {
        $id = $_GET['id'];
        $products = ProductsModel::all()->where('id', '=', $id)->get();
        view('home.product', ['products' => $products]);
    }
}

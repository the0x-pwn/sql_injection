<?php

namespace App\Controllers;

use App\Models\ProductsModel;

class SearchController
{
    public function index()
    {
        $query = request()->input('query');

        if (!$query) {
            response()->back();
        }

        $result = ProductsModel::all()->search($query)->get();

        if (count($result) > 0) {
            dd($result);
        } else {
            $_SESSION['flash_search'] = ['type' => 'error', 'msg' => 'Not Found'];
            response()->back();
            exit();
        }
    }
}

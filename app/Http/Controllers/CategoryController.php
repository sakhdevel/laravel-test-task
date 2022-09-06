<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * // todo логику работы с сущностью вынести в отдельный сервис
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $record = Category::create($request->all());

        return $record;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Category::findOrFail($id);

        $allRelatedIds = $record->items()->allRelatedIds();

        if (!$allRelatedIds->isEmpty()) {
            return Response::json('Невозможно удалить категорию, т.к. она содержит товары');
        }

        return $record->delete();
    }
}

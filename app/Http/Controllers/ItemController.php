<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

/**
 * // todo логику работы с сущностью вынести в отдельный сервис
 */
class ItemController extends Controller
{

    // todo тоже вынести в сервис
    const MIN_REQUIRED_CATEGORIES = 2;
    const MAX_REQUIRED_CATEGORIES = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $builder = Item::query();

        if ($request->has('name')) {
            $builder->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('price_min')) {
            $builder->where('price', '>=', $request->get('price_min'));
        }

        if ($request->has('price_max')) {
            $builder->where('price', '<=', $request->get('price_max'));
        }

        if ($request->has('is_published')) {
            $builder->where('is_published', '=', $request->get('is_published'));
        }

        if ($request->has('is_deleted')) {
            $builder->where('is_deleted', '=', $request->get('is_deleted'));
        }

        if ($request->has('category_id')) {
            $builder->whereHas('categories', function ($q) use ($request) {
                $table = $q->getModel()->getTable();
                $q->where("{$table}.id", '=', $request->get('category_id'));
            });
        }

        if ($request->has('category_name')) {
            $builder->whereHas('categories', function ($q) use ($request) {
                $table = $q->getModel()->getTable();
                $q->where("{$table}.name", 'like', '%' . $request->get('category_name') . '%');
            });
        }

        return $builder->get();
    }

    public function search($name)
    {
        return Item::where('name', 'like', '%' . $name . '%')->get();
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
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:2|max:10',
        ]);

        try {
            $categories = $this->findCategoriesOrFail($request);
        } catch (ValidationException $e) {
            return Response::json($e->getMessage());
        }

        $item = Item::create($request->all());
        $item->categories()->attach($categories);

        return $item;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        if ($item === null) {
            return Response::json('Товар не найден');
        }

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:2|max:10',
        ]);

        try {
            $categories = $this->findCategoriesOrFail($request);
        } catch (ValidationException $e) {
            return Response::json($e->getMessage());
        }

        $item->update($request->all());

        $item->categories()->detach();
        $item->categories()->attach($categories);

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Item::where('id', $id)->update(array('is_deleted' => 1));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function findCategoriesOrFail(Request $request)
    {
        $categoryIds = $request->get('categories');
        $categories = Category::whereIn('id', $categoryIds)->get();

        if (count($categories) < self::MIN_REQUIRED_CATEGORIES || count($categories) > self::MAX_REQUIRED_CATEGORIES) {
            $errorMessage = sprintf(
                'Укажите от %d до %d существующих категорий',
                self::MIN_REQUIRED_CATEGORIES,
                self::MAX_REQUIRED_CATEGORIES
            );
            throw ValidationException::withMessages([$errorMessage]);
        }
        return $categories;
    }
}

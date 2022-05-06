<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $paginator = BlogCategory::paginate(5);

       return view('blog.admin.category.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogCategory();
        $categoryList = BlogCategory::all();

        return view('blog.admin.category.edit',
        compact('item','categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|min:5|max:200',
            'slug' => 'max:200',
            'description'=>'string|max:500|min:3',
            'parent_id' => ' required|integer|exists:blog_categories,id'
        ];
        $validatedData = $request->validate($rules);
        $data = $request->input();
        if(empty($data['slug'])){
            $data['slug'] = Str_slug($data['title']);
        }

        $item = new BlogCategory($data);
        $item->save();

        if($item){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success'=>'Успешно сохранено']);
        }
        else{
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput(); /* С Сохранением данных в полях */
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = BlogCategory::FindOrFail($id);
        $categoryList = BlogCategory::all();

        return view('blog.admin.category.edit',compact('item','categoryList'));
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
        $rules = [
            'title' => 'required|min:5|max:200',
            'slug' => 'max:200',
            'description'=>'string|max:500|min:3',
            'parent_id' => ' required|integer|exists:blog_categories,id'
        ];
        $validatedData = $request->validate($rules);
        $item = BlogCategory::Find($id);
            if(empty($item)){
                return back()
                    ->withErrors(['msg'=>"Запись id=[{$id}] не найдена"])
                    ->withInput();
            }

            $data = $request->all();
            if(empty($data['slug'])){
                $data['slug'] = str_slug($data['title']);
            }
            $result = $item->update($data);

            if($result){
                return redirect()
                    ->route('blog.admin.categories.edit', $item->id)
                    ->with(['success'=>'Успешно сохранено']);
            }
            else{
                return back()
                    ->withErrors(['msg'=>'Ошибка сохранения'])
                    ->withInput(); /* С Сохранением данных в полях */
            }
    }
}

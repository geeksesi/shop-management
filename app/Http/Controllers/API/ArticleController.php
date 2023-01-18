<?php

namespace App\Http\Controllers\API;

use App\Article;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['categories', 'tags', 'author'])
            ->when(\request('category_id'), function ($query) {
                return $query->whereHas('categories', function ($q) {
                    return $q->where('id', \request('category_id'));
                });
            })
            ->when(\request('tag_id'), function ($query) {
                return $query->whereHas('tags', function ($q) {
                    return $q->where('id', \request('tag_id'));
                });
            })
            ->when(\request('query'),function ($query){
                return $query->where('title','like'.\request('query').'%');
            })
            ->orderBy('id','desc')
            ->paginate(3);
        $all_categories=Category::all();
        $all_tags=Tag::all();
        return view('welcome',compact('articles','all_categories','all_tags'));
    }
/*    public function create()
    {
        $categories=Category::orderBy('name')->get();
        return view('articles.create',compact('categories'));
    }*/





}

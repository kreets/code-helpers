<?php

namespace App\Http\Controllers;

use App\Models\CodeSnippet;
use App\Models\Tag;
use Illuminate\Http\Request;


class SnippetController extends Controller
{
    public function home(){
        return view('home');
    }

    public function tags(){
        return response()->json(Tag::orderBy("created_at", "DESC")->pluck('name', 'slug'));
    }

    public function snippets(){
        $snippets = CodeSnippet::orderBy('created_at', 'desc')->get();
        $output = [];
        foreach($snippets as $snippet){
            $output[] = [
                "id" => $snippet->id,
                "name" => $snippet->name,
                "params" => $snippet->params,
                "snippet" => $snippet->snippet,
                "tags" => $snippet->tags->pluck('name', 'slug')
            ];
        }
        return response()->json($output);
    }

    public function saveSnippet(Request $request){
        $snippet = new CodeSnippet();
        $snippet->name = $request->get('name');;
        $snippet->params =  $request->get('params');
        $snippet->snippet =  $request->get('raw_snippet');
        $snippet->save();

        foreach($request->get('tags', []) as $slug){
            $tag = Tag::where('slug', $slug)->first();
            if(!$tag){
                $tag = new Tag();
                $tag->slug = $slug;
                $tag->name = ucfirst($slug);
                $tag->save();
            }
            $snippet->tags()->attach($tag);
        }
        return response()->json([
            "id" => $snippet->id,
            "name" => $snippet->name,
            "params" => $snippet->params,
            "snippet" => $snippet->snippet,
            "tags" => $snippet->tags->pluck('name', 'slug')
        ]);

    }
}
<?php

namespace App\Http\Controllers;

use App\Models\CodeSnippet;
use App\Models\RawSnippet;
use App\Models\Tag;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;


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
                "raws" => $snippet->rawSnippets->pluck('instruction', 'raw'),
                "tags" => $snippet->tags->pluck('name', 'slug')
            ];
        }
        return response()->json($output);
    }

    public function saveSnippet(Request $request){
        $snippet = new CodeSnippet();
        $snippet->name = $request->get('name');
        $snippet->params =  $request->get('params');
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

        foreach($request->get('raw_snippets', []) as $raw){
            $rawSnippet = new RawSnippet();
            $rawSnippet->raw = $raw["raw"];
            if(isset($raw["instruction"]) && $raw["instruction"] != null)$rawSnippet->instruction = $raw["instruction"];
            $snippet->rawSnippets()->save($rawSnippet);
        }

        return response()->json([
            "id" => $snippet->id,
            "name" => $snippet->name,
            "params" => $snippet->params,
            "raws" => $snippet->rawSnippets->pluck('instruction', 'raw'),
            "tags" => $snippet->tags->pluck('name', 'slug')
        ]);
    }

}
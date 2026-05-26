<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Feeder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = Post::withCount('feeders')->orderBy('name')->paginate(15);
        return view('dashboard.settings.posts', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.posts-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:posts',
            'feeders' => 'nullable|array',
            'feeders.*' => 'nullable|string|max:100'
        ]);
        
        $post = Post::create(['name' => $request->name]);
        
        if ($request->has('feeders') && is_array($request->feeders)) {
            foreach ($request->feeders as $feederName) {
                if (!empty($feederName)) {
                    Feeder::create([
                        'name' => $feederName,
                        'post_id' => $post->id
                    ]);
                }
            }
        }
        
        return redirect()->route('dashboard.posts.index')->with('success', 'پست با موفقیت اضافه شد');
    }

    public function edit(Post $post)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $post->load('feeders');
        $item = $post;  // این خط رو اضافه کن
        return view('dashboard.settings.posts-form', compact('item', 'post'));
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:posts,name,' . $post->id,
            'feeders' => 'nullable|array',
            'feeders.*' => 'nullable|string|max:100',
            'existing_feeders' => 'nullable|array',
            'existing_feeders.*' => 'nullable|exists:feeders,id'
        ]);
        
        $post->update(['name' => $request->name]);
        
        $keepFeederIds = $request->existing_feeders ?? [];
        $post->feeders()->whereNotIn('id', $keepFeederIds)->delete();
        
        if ($request->has('feeders') && is_array($request->feeders)) {
            foreach ($request->feeders as $feederName) {
                if (!empty($feederName)) {
                    Feeder::create([
                        'name' => $feederName,
                        'post_id' => $post->id
                    ]);
                }
            }
        }
        
        return redirect()->route('dashboard.posts.index')->with('success', 'پست با موفقیت ویرایش شد');
    }

    public function destroy(Post $post)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $post->feeders()->delete();
        $post->delete();
        
        return redirect()->route('dashboard.posts.index')->with('success', 'پست با موفقیت حذف شد');
    }
}
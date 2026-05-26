<?php

namespace App\Http\Controllers;

use App\Models\Feeder;
use App\Models\Post;
use Illuminate\Http\Request;

class FeederController extends Controller
{
public function index()
{
    if (auth()->user()->role !== 'admin') abort(403);
    $feeders = Feeder::with('post')->orderBy('name')->paginate(50);
    return view('dashboard.settings.feeders', compact('feeders'));
}

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $posts = Post::orderBy('name')->get(['id', 'name']);
        return view('dashboard.settings.feeders-form', compact('posts'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:feeders',
            'post_id' => 'required|exists:posts,id'
        ]);
        
        Feeder::create($request->only('name', 'post_id'));
        
        return redirect()->route('dashboard.feeders.index')->with('success', 'فیدر با موفقیت اضافه شد');
    }

public function edit(Feeder $feeder)
{
    if (auth()->user()->role !== 'admin') abort(403);
    $posts = Post::orderBy('name')->get(['id', 'name']);
    $item = $feeder;
    return view('dashboard.settings.feeders-form', compact('feeder', 'posts', 'item'));
}

    public function update(Request $request, Feeder $feeder)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:feeders,name,' . $feeder->id,
            'post_id' => 'required|exists:posts,id'
        ]);
        
        $feeder->update($request->only('name', 'post_id'));
        
        return redirect()->route('dashboard.feeders.index')->with('success', 'فیدر با موفقیت ویرایش شد');
    }

    public function destroy(Feeder $feeder)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $feeder->delete();
        
        return redirect()->route('dashboard.feeders.index')->with('success', 'فیدر با موفقیت حذف شد');
    }
}
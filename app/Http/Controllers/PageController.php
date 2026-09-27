<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller {
    /**
     * Display a listing of the resource.
     * page controller here
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $pages = Page::paginate(10);

        return view('page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('page.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|string|unique:pages',
        ]);
        Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->filled('status'),
        ]);

        notify()->success('Post Successfully Added.', 'Added');

        return redirect()->route('page.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show($slug) {
        $page = Page::where('slug', $slug)->first();

        return view('frontend.argon.single-page', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page) {
        // return $page;
        return view('page.form', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page) {
        // return $request->all();
        $this->validate($request, [
            'title' => 'required|string',
        ]);
        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->filled('status'),
        ]);

        notify()->success('Page Successfully Added.', 'Added');

        return redirect()->route('page.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page) {
        $page->delete();

        return back();
    }
}

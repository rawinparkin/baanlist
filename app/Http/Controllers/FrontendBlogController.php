<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Seo;
use Illuminate\Support\Carbon;

class FrontendBlogController extends Controller
{
    public function BlogDetails($id, $slug)
    {
        $blog = BlogPost::findOrFail($id);
        $latestBlog = BlogPost::latest()->limit(3)->get();
        $seo = Seo::first();
        $formattedDate = thaiDate($blog->created_at);

        // Add the formatted date as a custom property to $blog
        $blog->formatted_date_thai = $formattedDate;

        $bcategory = BlogCategory::withCount('posts')->latest()->get();
        $bprelate = BlogPost::where('blogcat_id', $blog->blogcat_id)
            ->where('id', '!=', $id)
            ->latest()
            ->limit(2)
            ->get();

        return view('frontend.blog.blog_details', compact('blog', 'bcategory', 'latestBlog', 'bprelate', 'seo'));
    }

    public function BlogList()
    {
        $blog = BlogPost::latest()->paginate(5);
        $seo = Seo::first();
        $bcategory = BlogCategory::withCount('posts')->latest()->get();
        $latestBlog = BlogPost::inRandomOrder()->limit(3)->get();
        return view('frontend.blog.blog_list', compact('blog', 'bcategory', 'latestBlog', 'seo'));
    } // End Method

    public function BlogCatList($id)
    {
        $blog = BlogPost::where('blogcat_id', $id)->paginate(5);
        $breadcat = BlogCategory::where('id', $id)->first();
        $bcategory = BlogCategory::withCount('posts')->latest()->get();
        $latestBlog = BlogPost::inRandomOrder()->limit(3)->get();
        $seo = Seo::first();
        return view('frontend.blog.blog_category', compact('blog', 'breadcat', 'bcategory', 'latestBlog', 'seo'));
    } // End Method



}
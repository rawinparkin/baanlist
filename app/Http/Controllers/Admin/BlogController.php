<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function AllBlogCategory()
    {
        $category = BlogCategory::latest()->get();
        return view('admin.blog.all_blog_category', compact('category'));
    } // End Method 

    public function AddBlogCategory()
    {
        return view('admin.blog.add_blog_category');
    } // End method

    public function EditBlogCategory($id)
    {
        $btype = BlogCategory::findOrFail($id);
        return view('admin.blog.edit_blog_category', compact('btype'));
    } // End method

    public function BlogCategoryUpdate(Request $request)
    {
        $pid = $request->id;
        BlogCategory::findOrFail($pid)->update([

            'category_name' => $request->category_name,
            'category_slug' => $request->category_name,

        ]);
        return redirect()->route('all.blog.category')->with('success', 'แก้ไขประเภทเรียบร้อยแล้ว!');
    } // End Method 

    public function BlogCategoryStore(Request $request)
    {
        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => $request->category_name,
        ]);
        return redirect()->route('all.blog.category')->with('success', 'เพิ่มประเภทเรียบร้อยแล้ว!');
    }

    public function DeleteBlogCategory($id)
    {
        BlogCategory::findOrFail($id)->delete();
        return redirect()->route('all.blog.category')->with('success', 'ลบประเภทเรียบร้อยแล้ว!');
    } // End Method 


    public function AllBlogPost()
    {
        $bpost = BlogPost::latest()->limit(4)->get();
        return view('admin.blog.all_blog_post', compact('bpost'));
    } // End Method 

    public function AddBlogPost()
    {
        $blogcat = BlogCategory::latest()->get();
        return view('admin.blog.add_blog_post', compact('blogcat'));
    } // End Method 

    public function BlogPostStore(Request $request)
    {
        $result = Str::limit(strip_tags($request->long_descp), 70, ' ...');
        $blog_id = BlogPost::insertGetId([
            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
            'long_descp' => $request->long_descp,
            'short_descp' => $result,
            'video_link' => $request->video_link,
            'created_at' => now(),
        ]);

        $data = BlogPost::find($blog_id);

        if ($request->file('photo')) {
            $file = $request->file('photo');

            $ext = strtolower($file->getClientOriginalExtension());
            $saveDir = public_path('upload/blog/' . $blog_id);

            // Make directory if needed
            if (!file_exists($saveDir)) {
                File::makeDirectory($saveDir, 0777, true, true);
            }
            // Set filename (with .webp extension)
            $filename = date('YmdHi') . '.webp';
            $savePath = $saveDir . '/' . $filename;

            if ($ext === 'heic') {
                // Directly convert HEIC to WebP with 85% quality, resize if width > 1920px
                $inputPath = escapeshellarg($file->getRealPath());
                $outputPath = escapeshellarg($savePath);

                // Use ImageMagick to resize if width > 1920
                exec("magick $inputPath -resize '1920>' -quality 85 webp:$outputPath");
            } else {
                // Convert to WebP using Intervention Image with optional resize
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image = $image->scaleDown(width: 1920);
                $image->toWebp(60)->save($savePath);
            }

            $data->post_image = "upload/blog/" . $blog_id . "/" . $filename;

            // ✅ Save thumbnail version
            $thumbnailDir = $saveDir . '/thumbnails';
            if (!is_dir($thumbnailDir)) mkdir($thumbnailDir, 0777, true);
            $thumbnailPath = $thumbnailDir . '/' . $filename;
            $thumbnail = $manager->read($savePath);
            $thumbnail->scaleDown(1080, 1080); // Or use scaleDown(...) if you want to keep aspect ratio
            $thumbnail->toWebp(50)->save($thumbnailPath); // lower quality to reduce size
        }

        // === Handle video ===
        if ($request->file('video')) {
            $video = $request->file('video');
            $videoExt = $video->getClientOriginalExtension();
            $videoName = 'video_' . time() . '.' . $videoExt;

            $videoDir = public_path('upload/blog/' . $blog_id);
            if (!file_exists($videoDir)) {
                File::makeDirectory($videoDir, 0777, true, true);
            }

            $video->move($videoDir, $videoName);
            $data->post_video = "upload/blog/" . $blog_id . "/" . $videoName;
        }

        $data->save(); // Save path to DB

        return redirect()->route('all.blog.post')->with('success', 'ลงบทความเรียบร้อยแล้ว!');
    } // End Method 

    public function searchBlogById(Request $request)
    {

        $blog = BlogPost::find($request->id);
        if ($blog) {
            return response()->json([
                'success' => true,
                'item' => [
                    'img' => $blog->post_image ?? '',
                    'id' => $blog->id,
                    'title' => $blog->post_title ?? '',
                    'slug' => $blog->post_slug ?? '',
                ]
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function EditBlogPost($id)
    {
        $bpost = BlogPost::findOrFail($id);
        $blogcat = BlogCategory::latest()->get();
        return view('admin.blog.edit_blog_post', compact('bpost', 'blogcat'));
    } // End method

    public function BlogPostUpdate(Request $request)
    {
        $bid = $request->id;
        $result = Str::limit(strip_tags($request->long_descp), 70, ' ...');
        BlogPost::findOrFail($bid)->update([

            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
            'short_descp' => $result,

            'long_descp' => $request->long_descp,
            'video_link' => $request->video_link,

        ]);
        $data = BlogPost::find($bid);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $oldImage = $request->old_img;
            $ext = strtolower($file->getClientOriginalExtension());
            $saveDir = public_path('upload/blog/' . $bid);

            // Make directory if needed
            if (!file_exists($saveDir)) {
                File::makeDirectory($saveDir, 0777, true, true);
            }

            // Delete old image if exists
            if (!empty($oldImage) && file_exists($oldImage)) {
                unlink($oldImage);
            }

            // Set filename (with .webp extension)
            $filename = date('YmdHi') . '.webp';
            $savePath = $saveDir . '/' . $filename;

            if ($ext === 'heic') {
                // Directly convert HEIC to WebP with 85% quality, resize if width > 3000px
                $inputPath = escapeshellarg($file->getRealPath());
                $outputPath = escapeshellarg($savePath);

                // Use ImageMagick to resize if width > 3000
                exec("magick $inputPath -resize '3000x>' -quality 85 webp:$outputPath");
            } else {
                // Convert to WebP using Intervention Image with optional resize
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image = $image->scaleDown(width: 3000);
                $image->toWebp(85)->save($savePath);
            }


            $data->post_image = "upload/blog/" . $bid . "/" . $filename;
        }

        // === Handle video ===
        if ($request->file('video')) {
            $video = $request->file('video');
            $videoExt = $video->getClientOriginalExtension();
            $videoName = 'video_' . time() . '.' . $videoExt;

            $oldVideo = $request->old_video;
            // Delete old video if exists
            if (!empty($oldVideo) && file_exists($oldVideo)) {
                unlink($oldVideo);
            }

            $videoDir = public_path('upload/blog/' . $bid);
            if (!file_exists($videoDir)) {
                File::makeDirectory($videoDir, 0777, true, true);
            }
            $video->move($videoDir, $videoName);
            $data->post_video = "upload/blog/" . $bid . "/" . $videoName;
        }

        $data->save(); // Save path to DB
        return redirect()->route('all.blog.post')->with('success', 'แก้ไขบทตวามเรียบร้อยแล้ว!');
    } // End method

    public function DeleteBlogPost($id)
    {
        // === Delete folder (e.g., public/upload/property/{id}) ===
        $folderPath = public_path("upload/blog/$id");
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }
        BlogPost::findOrFail($id)->delete();
        return redirect()->route('all.blog.post')->with('success', 'ลบบทความเรียบร้อยแล้ว!');
    } // End Method 


    //------------- Fix Add Thumbnails for Existed Blog ------------
    public function MakeBlogThumbnail()
    {
        $manager = new ImageManager(new Driver());

        $posts = \App\Models\BlogPost::all();
        $count = 0;

        foreach ($posts as $post) {
            $filename = $post->post_image; // e.g. upload/blog/1/image.jpg

            $originalPath = public_path($filename);

            if (!File::exists($originalPath)) {
                continue; // Skip if the original file is missing
            }

            // Extract the base filename (e.g. image.jpg)
            $basename = basename($filename);

            // Construct thumbnail directory (e.g. upload/blog/1/thumbnails)
            $thumbnailDir = preg_replace('#(upload/blog/\d+)/[^/]+$#', '$1/thumbnails', $filename);
            $thumbnailDirPath = public_path($thumbnailDir);

            // Ensure the thumbnail directory exists
            if (!File::exists($thumbnailDirPath)) {
                File::makeDirectory($thumbnailDirPath, 0777, true);
            }

            // Set the full path to save the thumbnail
            $thumbnailPath = $thumbnailDirPath . '/' . pathinfo($basename, PATHINFO_FILENAME) . '.webp';

            try {
                $img = $manager->read($originalPath);
                $img->scaleDown(400, 400)->toWebp()->save($thumbnailPath);
                $count++;
            } catch (\Throwable $e) {
            }
        }

        return response()->json(['success' => "Thumbnails created: {$count} images."]);
    }
}
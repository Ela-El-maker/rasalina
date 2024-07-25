<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\MultiImage;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Carbon;



class BlogController extends Controller
{
    //
    public function allBlog()
    {
        $blogs = Blog::latest()->get();
        return view('admin.blogs.all_blogs', compact('blogs'));
    }

    public function addBlog()
    {
        $blogCategories = BlogCategory::orderBy('blog_category','ASC')->get();
        return view('admin.blogs.add_blog',compact('blogCategories'));
    }

    public function storeBlog(Request $request)
{
    // Validate the request data
    $request->validate([
        'blog_category_id' => ['required', 'max:100'],
        'blog_tags' => ['required', 'max:100'],
        'blog_title' => ['required','max:200'],
        'blog_description' => ['required'],
        'blog_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'blog_image_1' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'blog_image_2' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ],[
        'blog_category_id.required' => 'Blog Category is Required',
        'blog_tags.required' => 'Blog Tags is Required',
        'blog_title.required' => 'Blog Title is Required',
        'blog_description.required' => 'Blog Description is Required',
        'blog_image.required' => 'Blog Image is Required',
        'blog_image_1.required' => 'Additional Blog Image 1 is Required',
        'blog_image_2.required' => 'Additional Blog Image 2 is Required',
    ]);

    // Process blog images
    $blogImagePath = $this->processImage($request->file('blog_image'), $request->blog_title, 'main');
    $blogImage1Path = $this->processImage($request->file('blog_image_1'), $request->blog_title, 'image1');
    $blogImage2Path = $this->processImage($request->file('blog_image_2'), $request->blog_title, 'image2');

    // Insert the blog post into the database
    Blog::create([
        'blog_category_id' => $request->blog_category_id,
        'blog_tags' => $request->blog_tags,
        'blog_title' => $request->blog_title,
        'blog_description' => $request->blog_description,
        'blog_image' => $blogImagePath,
        'blog_image_1' => $blogImage1Path,
        'blog_image_2' => $blogImage2Path,
        'created_at' => Carbon::now(),
    ]);

    $notification = [
        'message' => 'Blog Post Added Successfully.',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.blog')->with($notification);
}
    private function processImage($uploadedFile, $title, $type)
    {
        $height = 636; // Maximum width for the image
        $width = 852; // Maximum height for the image
    
        $currentTimestamp = time(); // Get the current timestamp
        $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension
    
        // Generate a slug from the title
        $imageSlug = Str::slug($title);
        $imageName = $currentTimestamp . '.' . $extension;
        $uploadedFile->move('uploads/blogs', $imageName);
    
        $imgManager = new ImageManager(new Driver());
        $thumbImage = $imgManager->read('uploads/blogs/' . $imageName);
    
        // Construct the file name using the slug, timestamp, and original extension
        $fileName = $imageSlug . '-' . $currentTimestamp . '-' . $type . '.' . $extension;
        $publicDir = 'uploads/blogs/' . $fileName; // Define the path to save the file
    
        // Determine whether to set width or height to null for aspect ratio resizing
        $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);
    
        // Resize the image while maintaining the aspect ratio
        $thumbImage->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    
        // Save the resized image to the specified path in the public directory
        $thumbImage->save(public_path($publicDir));
        unlink(public_path('uploads/blogs/' . $imageName));
    
        return $publicDir;
    }

    public function editBlog($id)
    {
        $editBlog = Blog::findorfail($id);
        $blogCategories = BlogCategory::orderBy('blog_category','ASC')->get();
        return view('admin.blogs.edit_blog', compact('editBlog','blogCategories'));
    }


    public function updateBlog(Request $request)
    {
        $blogId = $request->id;
        // Validate the request data
        $request->validate([
            'blog_category_id' => ['required', 'max:100'],
            'blog_tags' => ['required', 'max:100'],
            'blog_title' => ['required','max:200'],
            'blog_description' => ['required'],
            'blog_image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'blog_image_1' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'blog_image_2' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ],[
            'blog_category_id.required' => 'Blog Category is Required',
            'blog_tags.required' => 'Blog Tags is Required',
            'blog_title.required' => 'Blog Title is Required',
            'blog_description.required' => 'Blog Description is Required',
        ]);
    
        $blog = Blog::findOrFail($blogId);
    
        // Process blog images
        if ($request->hasFile('blog_image')) {
            // Delete the old image if it exists
            if (Str::startsWith($blog->blog_image, 'uploads/blogs/')) {
                unlink(public_path($blog->blog_image));
            }
            $blogImagePath = $this->processImage($request->file('blog_image'), $request->blog_title, 'main');
        } else {
            $blogImagePath = $blog->blog_image;
        }
    
        if ($request->hasFile('blog_image_1')) {
            // Delete the old image if it exists
            if (Str::startsWith($blog->blog_image_1, 'uploads/blogs/')) {
                unlink(public_path($blog->blog_image_1));
            }
            $blogImage1Path = $this->processImage($request->file('blog_image_1'), $request->blog_title, 'image1');
        } else {
            $blogImage1Path = $blog->blog_image_1;
        }
    
        if ($request->hasFile('blog_image_2')) {
            // Delete the old image if it exists
            if (Str::startsWith($blog->blog_image_2, 'uploads/blogs/')) {
                unlink(public_path($blog->blog_image_2));
            }
            $blogImage2Path = $this->processImage($request->file('blog_image_2'), $request->blog_title, 'image2');
        } else {
            $blogImage2Path = $blog->blog_image_2;
        }
    
        // Update the blog post in the database
        $blog->update([
            'blog_category_id' => $request->blog_category_id,
            'blog_tags' => $request->blog_tags,
            'blog_title' => $request->blog_title,
            'blog_description' => $request->blog_description,
            'blog_image' => $blogImagePath,
            'blog_image_1' => $blogImage1Path,
            'blog_image_2' => $blogImage2Path,
            'updated_at' => Carbon::now(),
        ]);
    
        $notification = [
            'message' => 'Blog Post Updated Successfully.',
            'alert-type' => 'success',
        ];
    
        return redirect()->route('all.blog')->with($notification);
    }

    
public function deleteBlog($id)
{
    // Find the item by its ID and throw a 404 error if not found
    try {
        $item = Blog::findOrFail($id);
        // Check if the project has an associated image
        if (Str::startsWith($item->blog_image, 'uploads/blogs/')) {
            $imagePath = public_path($item->blog_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        // Check if the project has an associated image
        if (Str::startsWith($item->blog_image, 'uploads/blogs/')) {
            $imagePath = public_path($item->blog_image_1);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        // Check if the project has an associated image
        if (Str::startsWith($item->blog_image, 'uploads/blogs/')) {
            $imagePath = public_path($item->blog_image_2);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($item->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Blog Item deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the item.'
            ], 500);
        }
    } catch (\Exception $e) {
        Log::error('Error deleting item: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the item.'
        ], 500);
    }

    return redirect()->route('all.portfolio')->with($notification);

}


public function HomeBlogDetails($id)
{
    $allBlogs = Blog::latest()->limit(5)->get();
    $blogItems = Blog::findorfail($id);
    $blogCategories = BlogCategory::orderBy('blog_category','ASC')->get();
    $allMultiImage = MultiImage::all();

    return view('frontend.home_all.blog_details', compact('blogItems','allMultiImage','allBlogs','blogCategories'));
}

public function CategoryBlog($id)
{
    $allBlogs = Blog::latest()->limit(5)->get();
    $blogPost = Blog::where('blog_category_id',$id)->orderBy('id','DESC')->get();
    $blogCategories = BlogCategory::orderBy('blog_category','ASC')->get();
    $categoryName = BlogCategory::findorfail($id);
    return view('frontend.home_all.category_blog_details', compact('blogPost','blogCategories','allBlogs','categoryName'));
}

public function homeBlog()
{
    $allBlogs = Blog::latest()->get();
    $blogCategories = BlogCategory::orderBy('blog_category','ASC')->get();
    return view('frontend.home_all.blogs',compact('allBlogs','blogCategories'));
}

}

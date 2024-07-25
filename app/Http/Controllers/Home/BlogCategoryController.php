<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class BlogCategoryController extends Controller
{
    //
    public function allBlogCategory()
    {
        $blogCategory = BlogCategory::latest()->get();
        return view('admin.blog_category.all_blog_caregory', compact('blogCategory'));
    }
    public function addBlogCategory()
    {
        
        return view('admin.blog_category.add_blog_caregory');
    }

    public function storeBlogCategory(Request $request)
    {
        
    // Validate the request data
    $request->validate([
        'blog_category' => ['required', 'max:100'],
        
    ],[
        'blog_category.required' => 'Blog Category Name is Required',
       
    ]);

        // Insert the portfolio item into the database
        BlogCategory::create([
            'blog_category' => $request->blog_category,
           
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Blog Category Added Successfully.',
            'alert-type' => 'success',
        ];
    
    

    return redirect()->route('all.blog.category')->with($notification);
    }

    public function editBlogCategory($id)
    {
        $blogCategory = BlogCategory::findorfail($id);
        return view('admin.blog_category.edit_blog_caregory', compact('blogCategory'));
    }

    public function updateBlogCategory(Request $request)
    {
        
        $blogCategoryId = $request->id;
    // Validate the request data
    $request->validate([
        'blog_category' => ['required', 'max:100'],
        
    ],[
        'blog_category.required' => 'Blog Category Name is Required',
       
    ]);

        // Insert the portfolio item into the database
        BlogCategory::findorfail($blogCategoryId)->update([
            'blog_category' => $request->blog_category,
           
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Blog Category Updated Successfully.',
            'alert-type' => 'success',
        ];
    
    

    return redirect()->route('all.blog.category')->with($notification);
    }


    public function deleteBlogCategory($id)
{
    // Find the item by its ID and throw a 404 error if not found
    try {
        $item = BlogCategory::findOrFail($id);

        if ($item->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Blog Category Item deleted successfully.'
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

    return redirect()->route('all.blog.category')->with($notification);

}
}

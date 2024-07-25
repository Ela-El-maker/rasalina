<?php

namespace App\Http\Controllers\Home;

use App\Models\About;
use App\Models\MultiImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Carbon;

class AboutController extends Controller
{
    //
    public function AboutPage()
    {
        $aboutPage = About::find(1);
        return view('admin.about_page.about_page_all', compact('aboutPage'));
    }

    
    public function updateAbout(Request $request)
    {
        $aboutId = $request->id;
        if ($request->file('about_image')) {
            $findAbout = About::findOrFail($aboutId);
            $width = 636; // Maximum width for the image
            $height = 950; // Maximum height for the image

            $currentTimestamp = time(); // Get the current timestamp, e.g., 1631703954
            $uploadedFile = $request->file('about_image'); // Retrieve the uploaded file from the request
            $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension

            // Generate a slug from the title
            $imageSlug = Str::slug($request->title);
            $imageName = time().'.'.$extension;
            $uploadedFile->move('uploads/home_about',$imageName);

            $imgManager = new ImageManager(new Driver());
            $thumbImage= $imgManager->read('uploads/home_about/'.$imageName);

            // Construct the file name using the slug, timestamp, and original extension
            $fileName = $imageSlug . '-' . $currentTimestamp . '.' . $extension;
            $originalPublicDir = 'uploads/home_about/' . $fileName; // Define the path to save the file

            // Create an instance of the image from the uploaded file and correct its orientation

            // Determine whether to set width or height to null for aspect ratio resizing
            $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

            // Resize the image while maintaining the aspect ratio
            $thumbImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save the resized image to the specified path in the public directory
            $thumbImage->save(public_path($originalPublicDir));
            if (Str::startsWith($findAbout->about_image, 'uploads/home_about/')) {
                unlink(public_path($findAbout->about_image));
            }
            unlink(public_path('uploads/home_about/'.$imageName));

            About::findOrFail($aboutId)->update([
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $request->description,
                'sub_description' => $request->sub_description,
                'about_image' => $originalPublicDir,
            ]);

            $notification = [
                'message' => 'About Page Updated with image Successfully.',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);
        } else {
            About::findorfail($aboutId)->update([
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $request->description,
                'sub_description' => $request->sub_description,
                
            ]);

            $notification = [
                'message' => 'About Page Updated without image Successfully.',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);
        }
    }


    public  function HomeAbout()
    {
        $about = About::find(1);
        $allMultiImage = MultiImage::all();
        return view('frontend.home_all.about', compact('about','allMultiImage'));
    }

    public function aboutMultiImage()
    {
        return view('admin.about_page.multi_image');
    }

    public function storeMultiImage(Request $request)
{
    $request->validate([
        'multi_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $images = $request->file('multi_image');

    foreach ($images as $multi_image) {
        $width = 636; // Maximum width for the image
        $height = 950; // Maximum height for the image

        $uploadedFile = $multi_image;
        $extension = $uploadedFile->getClientOriginalExtension();
        $uniqueId = uniqid();
        $imageName = $uniqueId . '.' . $extension;
        $uploadedFile->move('uploads/multi_image', $imageName);

        $imgManager = new ImageManager(new Driver());
        $thumbImage = $imgManager->read('uploads/multi_image/' . $imageName);

        // Construct the file name using a slug, unique ID, and original extension
        $imageSlug = Str::slug($request->title);
        $fileName = $imageSlug . '-' . $uniqueId . '.' . $extension;
        $originalPublicDir = 'uploads/multi_image/' . $fileName;

        // Determine whether to set width or height to null for aspect ratio resizing
        $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

        // Resize the image while maintaining the aspect ratio
        $thumbImage->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the resized image to the specified path in the public directory
        $thumbImage->save(public_path($originalPublicDir));

        // Remove the original image
        $filePath = public_path('uploads/multi_image/' . $imageName);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        MultiImage::insert([
            'multi_image' => $originalPublicDir,
            'created_at' => Carbon::now()
        ]);
    }

    $notification = [
        'message' => 'MultiImage Page Updated with images Successfully.',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.multi.image')->with($notification);
}



    // public function storeMultiImage(Request  $request)
    // {

    //         $request->validate([
    //             'multi_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
        
    //         $images = $request->file('multi_image');

    //     foreach($images as $multi_image){
       
            
    //         $width = 636; // Maximum width for the image
    //         $height = 950; // Maximum height for the image

    //         $currentTimestamp = time(); // Get the current timestamp, e.g., 1631703954
    //         $uploadedFile = $multi_image; // Retrieve the uploaded file from the request
    //         $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension

    //         // Generate a slug from the title
    //         $imageSlug = Str::slug($request->title);
    //         $imageName = time().'.'.$extension;
    //         $uploadedFile->move('uploads/multi_image',$imageName);

    //         $imgManager = new ImageManager(new Driver());
    //         $thumbImage= $imgManager->read('uploads/multi_image/'.$imageName);

    //         // Construct the file name using the slug, timestamp, and original extension
    //         $fileName = $imageSlug . '-' . $currentTimestamp . '.' . $extension;
    //         $originalPublicDir = 'uploads/multi_image/' . $fileName; // Define the path to save the file

    //         // Create an instance of the image from the uploaded file and correct its orientation

    //         // Determine whether to set width or height to null for aspect ratio resizing
    //         $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

    //         // Resize the image while maintaining the aspect ratio
    //         $thumbImage->resize($width, $height, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });

    //         // Save the resized image to the specified path in the public directory
    //         $thumbImage->save(public_path($originalPublicDir));

    //         $filePath = public_path('uploads/multi_image/' . $imageName);
    //         if (file_exists($filePath)) {
    //             unlink($filePath);
    //         }

    //         MultiImage::insert([
                
    //             'multi_image' => $originalPublicDir,
    //             'created_at' => Carbon::now()
    //         ]);
    //     }
    //         $notification = [
    //             'message' => 'MultiImage Page Updated with images Successfully.',
    //             'alert-type' => 'success',
    //         ];

    //         return redirect()->route('all.multi.image')->with($notification);
        
    // }


    public function allMultiImage()
    {
        $allMultiImage = MultiImage::all();
        return view('admin.about_page.all_multi_image', compact('allMultiImage'));
    }

    public function editMultiImage($id)
    {
        $editMultiImage = MultiImage::findorfail($id);
        return view('admin.about_page.edit_multi_image', compact('editMultiImage'));
    }

    public function updateMultiImage(Request $request)
    {
        $width = 220; // Maximum width for the image
        $height = 320; // Maximum height for the image

        $multi_imageId = $request->id;
        if ($request->file('multi_image')) {


            $currentTimestamp = time(); // Get the current timestamp, e.g., 1631703954
            $uploadedFile = $request->file('multi_image'); // Retrieve the uploaded file from the request
            $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension

            // Generate a slug from the title
            $imageSlug = Str::slug($request->title);
            $imageName = time().'.'.$extension;
            $uploadedFile->move('uploads/multi_image',$imageName);

            $imgManager = new ImageManager(new Driver());
            $thumbImage= $imgManager->read('uploads/multi_image/'.$imageName);

            // Construct the file name using the slug, timestamp, and original extension
            $fileName = $imageSlug . '-' . $currentTimestamp . '.' . $extension;
            $originalPublicDir = 'uploads/multi_image/' . $fileName; // Define the path to save the file

            // Create an instance of the image from the uploaded file and correct its orientation

            // Determine whether to set width or height to null for aspect ratio resizing
            $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

            // Resize the image while maintaining the aspect ratio
            $thumbImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });


            // Save the resized image to the specified path in the public directory
            $thumbImage->save(public_path($originalPublicDir));
            $filePath = public_path('uploads/multi_image/' . $imageName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            MultiImage::findOrFail($multi_imageId)->update([
                
                'multi_image' => $originalPublicDir,
            ]);

            $notification = [
                'message' => 'Multi Image Updated  Successfully.',
                'alert-type' => 'success',
            ];
            return redirect()->route('all.multi.image')->with($notification);
        }
    }


    // public function deleteMultiImage(string $id)
    // {

    //     $multi_image = MultiImage::findOrFail($id);
    //     $img = $multi_image->multi_image;
    //     unlink($img);

    //     MultiImage::findOrFail($id)->delete();

    //     $notification = [
    //         'message' => 'Multi Image Deleted  Successfully.',
    //         'alert-type' => 'success',
    //     ];
    //     return redirect()->back()->with($notification);
    // }

    public function deleteMultiImage($id)
{
    try {
        $multi_image = MultiImage::findOrFail($id);
        $img = $multi_image->multi_image;
        unlink($img);
        $multi_image->delete();

        return response()->json(['status' => 'success', 'message' => 'Multi Image Deleted Successfully.']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Item not found or cannot be deleted.']);
    }
}

}

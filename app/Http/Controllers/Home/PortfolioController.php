<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Carbon;
use App\Models\Portfolio;
use App\Models\MultiImage;
use Illuminate\Support\Facades\Log;


class PortfolioController extends Controller
{
    //

    public function AllPortfolio()
    {
        $portfolio = Portfolio::latest()->get();
        return view('admin.portfolio.portfolio_all', compact('portfolio'));
    }
    public function addPortfolio()
    {
        return view('admin.portfolio.add_portfolio');
    }

    public function storePortfolio(Request $request)
{
    // Validate the request data
    $request->validate([
        'portfolio_name' => ['required', 'max:100'],
        'portfolio_title' => ['required','max:200'],
        'portfolio_description' => ['required'],
        'portfolio_image' => ['required'],
    ],[
        'portfolio_name.required' => 'Portfolio Name is Required',
        'portfolio_title.required' => 'Portfolio Title is Required',
        'portfolio_description.required' => 'Portfolio Description is Required',
        'portfolio_image.required' => 'Portfolio Image is Required',
    ]);

    // Handle the image upload
    if ($request->file('portfolio_image')) {
        $width = 1020; // Maximum width for the image
        $height = 519; // Maximum height for the image

        $currentTimestamp = time(); // Get the current timestamp
        $uploadedFile = $request->file('portfolio_image'); // Retrieve the uploaded file from the request
        $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension

        // Generate a slug from the portfolio name
        $imageSlug = Str::slug($request->portfolio_name);
        $imageName = time() . '.' . $extension;
        $uploadedFile->move('uploads/portfolio', $imageName);

        $imgManager = new ImageManager(new Driver());
        $thumbImage = $imgManager->read('uploads/portfolio/' . $imageName);

        // Construct the file name using the slug, timestamp, and original extension
        $fileName = $imageSlug . '-' . $currentTimestamp . '.' . $extension;
        $originalPublicDir = 'uploads/portfolio/' . $fileName; // Define the path to save the file

        // Determine whether to set width or height to null for aspect ratio resizing
        $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

        // Resize the image while maintaining the aspect ratio
        $thumbImage->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the resized image to the specified path in the public directory
        $thumbImage->save(public_path($originalPublicDir));

        // Clean up the temporary uploaded image
        unlink(public_path('uploads/portfolio/' . $imageName));

        // Insert the portfolio item into the database
        Portfolio::create([
            'portfolio_name' => $request->portfolio_name,
            'portfolio_title' => $request->portfolio_title,
            'portfolio_description' => $request->portfolio_description,
            'portfolio_image' => $originalPublicDir,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Portfolio Item Added Successfully with Image.',
            'alert-type' => 'success',
        ];
    } else {
        // Insert the portfolio item into the database without an image
        Portfolio::create([
            'name' => $request->portfolio_name,
            'title' => $request->portfolio_title,
            'description' => $request->portfolio_description,
            'created_at' => Carbon::now(),

        ]);

        $notification = [
            'message' => 'Portfolio Item Added Successfully without Image.',
            'alert-type' => 'success',
        ];
    }

    return redirect()->route('all.portfolio')->with($notification);
}


public function editPortfolio($id)
{
    $editPortfolio = Portfolio::findorfail($id);
    return view('admin.portfolio.edit_portfolio',compact('editPortfolio'));
}

public function updatePortfolio(Request $request)
{
    $portfolioId = $request->id;
        if ($request->file('portfolio_image')) {
            $findPortfolio = Portfolio::findOrFail($portfolioId);
            $width = 636; // Maximum width for the image
            $height = 852; // Maximum height for the image

            $currentTimestamp = time(); // Get the current timestamp, e.g., 1631703954
            $uploadedFile = $request->file('portfolio_image'); // Retrieve the uploaded file from the request
            $extension = $uploadedFile->getClientOriginalExtension(); // Get the original file extension

            // Generate a slug from the title
            $imageSlug = Str::slug($request->title);
            $imageName = time().'.'.$extension;
            $uploadedFile->move('uploads/portfolio',$imageName);

            $imgManager = new ImageManager(new Driver());
            $thumbImage= $imgManager->read('uploads/portfolio/'.$imageName);

            // Construct the file name using the slug, timestamp, and original extension
            $fileName = $imageSlug . '-' . $currentTimestamp . '.' . $extension;
            $originalPublicDir = 'uploads/portfolio/' . $fileName; // Define the path to save the file

            // Create an instance of the image from the uploaded file and correct its orientation

            // Determine whether to set width or height to null for aspect ratio resizing
            $thumbImage->height() > $thumbImage->width() ? ($width = null) : ($height = null);

            // Resize the image while maintaining the aspect ratio
            $thumbImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save the resized image to the specified path in the public directory
            $thumbImage->save(public_path($originalPublicDir));
            if (Str::startsWith($findPortfolio->portfolio_image, 'uploads/portfolio/')) {
                unlink(public_path($findPortfolio->portfolio_image));
            }
            unlink(public_path('uploads/portfolio/'.$imageName));

            Portfolio::findOrFail($portfolioId)->update([
                'portfolio_name' => $request->portfolio_name,
                'portfolio_title' => $request->portfolio_title,
                'portfolio_description' => $request->portfolio_description,
                'portfolio_image' => $originalPublicDir,
            ]);

            $notification = [
                'message' => 'Portfolio Item Updated with image Successfully.',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.portfolio')->with($notification);
        } else {
            Portfolio::findorfail($portfolioId)->update([
                'portfolio_name' => $request->portfolio_name,
                'portfolio_title' => $request->portfolio_title,
                'portfolio_description' => $request->portfolio_description,
            ]);

            $notification = [
                'message' => 'Portfolio Item Updated without image Successfully.',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.portfolio')->with($notification);
        }
}



public function deletePortfolio($id)
{
    // Find the item by its ID and throw a 404 error if not found
    try {
        $item = Portfolio::findOrFail($id);

        if ($item->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Portfolio Item deleted successfully.'
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

public function HomePortfolioDetails($id)
{
    $portfolioItems = Portfolio::findorfail($id);
    $allMultiImage = MultiImage::all();

    return view('frontend.home_all.portfolio_details', compact('portfolioItems','allMultiImage'));
}


}

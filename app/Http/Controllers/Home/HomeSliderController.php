<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlide;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class HomeSliderController extends Controller
{
    //
    public function HomeSlider() {
        
        $homeSlide = HomeSlide::find(1);
        return view('admin.home_slide.home_slide_all',compact('homeSlide'));
    }

    public function UpdateSlider(Request $request){
        $slideId = $request ->id;
        if($request->file('home_slide'))
        {
            $image = $request->file('home_slide');
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            $nameGenerate = hexdec(uniqid()).'.'.$request->file('home_slide')->getClientOriginalExtension();

            $img = $manager ->read($request->file('home_slide'));
            //dd($img);
            $img = $img->resize(636,852);

            $img ->toJpeg(80)->save(base_path('public/uploads/home_slide/'.$nameGenerate));
            $saveUrl = 'uploads/home_slide/'.$nameGenerate;
            HomeSlide::findorfail($slideId)->update([
                'title' => $request ->title,
                'sub_title' => $request ->sub_title,
                'video_url' => $request ->video_url,
                'home_slide' => $saveUrl,
            ]);



            $notification = array(
                'message' => 'Home Slide Updated with image Successfully.',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        }else{
            HomeSlide::findorfail($slideId)->update([
                'title' => $request ->title,
                'sub_title' => $request ->sub_title,
                'video_url' => $request ->video_url,
                
            ]);

            $notification = array(
                'message' => 'Home Slide Updated without image Successfully.',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        }
    }
} 

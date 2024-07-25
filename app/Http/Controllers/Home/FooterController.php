<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;

class FooterController extends Controller
{
    //
    public function footerSetup()
    {
        $footer = Footer::find(1);
        return view('admin.footer.footer', compact('footer'));
    }


    public function footerUpdate(Request $request)
    {
        $footerId = $request->id;
        
        Footer::findOrFail($footerId)->update([
            'number' => $request->number,
            'sub_description' => $request->sub_description,
            'address' => $request->address,
            'email' => $request->email,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'copyright' => $request->copyright,
        ]);

        $notification = [
            'message' => 'Footer Section Page Successfully.',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function HomeFooter()
    {
        
    }
}

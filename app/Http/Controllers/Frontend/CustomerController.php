<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Order_cancelMail;
use App\Models\Order_detail;
use App\Models\Order_return;
use App\Models\Order_summery;
use Illuminate\Support\Str;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->google_id = $user->getId();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->password = bcrypt(Str::random(16));
            $newUser->email_verified_at = Carbon::now();
            $newUser->save();

            auth()->login($newUser, true);
        }
        return redirect('/dashboard');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->password = bcrypt(Str::random(16));
            $newUser->save();

            auth()->login($newUser, true);
        }

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        $order_summeries = Order_summery::where('user_id', Auth::user()->id)->get();
        return view('frontend.dashboard', compact('order_summeries'));
    }

    public function changeProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'phone_number' => 'nullable|digits:11',
        ]);
        User::find(auth()->id())->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        // Photo Upload
        if($request->hasFile('profile_photo')){
            // Photo Delete
            if(User::find(auth()->id())->profile_photo != "default_profile_photo.png"){
                unlink(base_path("public/uploads/profile_photo/"). User::find(auth()->id())->profile_photo);
            }
            // Stap 01 : New Profile Photo Name Create (photo.jpg)
            $profile_photo_name =  "Customer-Profile-Photo-".User::find(auth()->id())->id.".". $request->file('profile_photo')->getClientOriginalExtension();
            // Stap 02 : New Profile Photo Upload
            $upload_link = base_path("public/uploads/profile_photo/").$profile_photo_name;
            Image::make($request->file('profile_photo'))->resize(300,300)->save($upload_link);
            // Stap 03 : New Profile Photo Name Update at Database
            User::find(auth()->id())->update([
                'profile_photo' => $profile_photo_name,
            ]);
        }
        return back()->with('success', 'Profile Updated Successfully');
    }

    public function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);
        if($request->old_password == $request->password){
            return back()->withErrors(['password_error' => 'New password can not same as old password']);
        }
        if(Hash::check($request->old_password, auth()->user()->password)){
            User::find(auth()->id())->update([
                'password' => bcrypt($request->password)
            ]);
            return back()->with('success', 'Password Change Successfully.');
        }else{
            return back()->withErrors(['password_error' => 'Your Old Password is Wrong!']);
        }
    }

    public function viewInvoice($id)
    {
        $id = Crypt::decrypt($id);
        $order_summery = Order_summery::where('id', $id)->first();
        $order_details = Order_detail::where('order_no', $id)->get();
        return view('frontend.invoice', compact('order_summery', 'order_details'));
    }

    public function downloadInvoice($id)
    {
        $id = Crypt::decrypt($id);
        $order_summery = Order_summery::where('id', $id)->first();
        $order_details = Order_detail::where('order_no', $id)->get();
        $pdf = Pdf::loadView('frontend.invoice', compact('order_summery', 'order_details'));
        return $pdf->download(Carbon::now()->format('d-M-Y').'-INVOICE-ID-'.$order_summery->id.'.pdf');
    }

    public function cancelOrder($id)
    {
        Order_summery::find($id)->update([
            'order_status' => 'Cancel',
        ]);
        $order_summery = Order_summery::where('id', $id)->first();
        Mail::to($order_summery->billing_email)
                ->cc($order_summery->shipping_email)
                ->send(new Order_cancelMail($order_summery));
        return back()->with('error', 'Order Cancel success.');
    }

    public function returnRequest($id)
    {
        $id = Crypt::decrypt($id);
        $order_summery = Order_summery::where('id', $id)->first();
        $order_details = Order_detail::where('order_no', $id)->get();
        return view('frontend.return', compact('order_summery', 'order_details'));
    }

    public function orderReturnPost(Request $request, $id)
    {
        $request->validate([
            '*' => 'required',
            'return_reason_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg',
        ]);

        $order_detail = Order_detail::where('id', $id)->first();

        $return_id = Order_return::insertGetId([
            'user_id' => Auth::user()->id,
            'order_no' => $order_detail->order_no,
            'order_detail_id' => $order_detail->id,
            'return_reason_details' => $request->return_reason_details,
            'account_holder_name' => $request->account_holder_name,
            'account_type' => $request->account_type,
            'account_number' => $request->account_number,
            'return_status' => "Return Request",
            'created_at' => Carbon::now(),
        ]);

        if($request->hasFile('return_reason_photo')){
            // Stap 01 : New Return-Reason Photo Name Create (photo.jpg)
            $return_reason_photo_name =  "Return-Reason-Photo-".Str::random(5).".". $request->file('return_reason_photo')->getClientOriginalExtension();
            // Stap 02 : New Return-Reason Photo Upload
            $upload_link = base_path("public/uploads/return_reason_photo/").$return_reason_photo_name;
            Image::make($request->file('return_reason_photo'))->save($upload_link);

            Order_return::find($return_id)->update([
                'return_reason_photo' => $return_reason_photo_name,
            ]);
        }

        return back()->with('error', 'Order Return success.');
    }

    public function orderReview($id)
    {
        $id = Crypt::decrypt($id);
        $order_summery = Order_summery::where('id', $id)->first();
        $order_details = Order_detail::where('order_no', $id)->get();
        return view('frontend.review', compact('order_summery', 'order_details'));
    }

    public function orderReviewPost(Request $request, $id){

        $request->validate([
            '*' => 'required'
        ]);

        $order_detail = Order_detail::where('id', $id)->first();

        Review::insert([
            'order_detail_id' => $order_detail->id,
            'product_id' => $order_detail->product_id,
            'color_id' => $order_detail->color_id,
            'size_id' => $order_detail->size_id,
            'user_id' => auth()->id(),
            'review' => $request->review,
            'rating' => $request->rating,
            'created_at' => Carbon::now(),
        ]);
        return back();
    }
}

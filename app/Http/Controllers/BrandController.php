<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function BrandPage(Request $request){
        $user_id = $request->header('id');
        $brands = Brand::where('user_id', $user_id)->get();
        return Inertia::render('BrandPage',['brands'=>$brands]);
    }//end method

    public function BrandSavePage(Request $request){
        $brand_id = $request->query('id');
        $user_id = $request->header('id');
        $brand = Brand::where('id', $brand_id)->where('user_id', $user_id)->first();
        return Inertia::render('BrandSavePage',['brand'=>$brand]);
    }
    public function CreateBrand(Request $request){
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        $data = [
            'name' => $request->name,
            'user_id' => $user_id
        ];
        if($request->hasFile('image')){
            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/brands/'.$fileName;

            $image->move(public_path('uploads/brands'), $fileName);
            $data['image'] = $filePath;
        }
        Brand::create($data);

        $data = ['message'=>'Brand created successfully','status'=>true,'error'=>''];
        return redirect('/BrandPage')->with($data);
    }//end method

    public function BrandList(Request $request){
        $user_id = $request->header('id');
        $categories = Brand::where('user_id', $user_id)->get();
        return $categories;
    }//end method

    public function BrandById(Request $request){
        $user_id = $request->header('id');
        $brand =Brand::where('id', $request->id)->where('user_id', $user_id)->first();
        return $brand;
    }//end method

    public function BrandUpdate(Request $request){
        $user_id = $request->header('id');
        $id = $request->input('id');

        $brand = Brand::where('user_id', $user_id)->findOrFail($id);
        $brand->name = $request->name;
        if($request->hasFile('image')){
            if($brand->image && file_exists(public_path($brand->image))){
                unlink(public_path($brand->image));
            }

            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]);
            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/brands/'.$fileName;

            $image->move(public_path('uploads/brands'), $fileName);
            $brand->image = $filePath;
        }
        $brand->save();
        $data = ['message'=>'Brand Updaetd successfully','status'=>true,'error'=>''];
        return redirect('/BrandPage')->with($data);
    }//end method

    public function BrandDelete(Request $request,$id){
        $user_id = $request->header('id');
        Brand::where('user_id', $user_id)->where('id', $id)->delete();
        $data = ['message'=>'Brand Deleted successfully','status'=>true,'error'=>''];
        return redirect('/BrandPage')->with($data);
    }//end method
}

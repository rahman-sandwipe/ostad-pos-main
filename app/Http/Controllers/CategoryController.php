<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function CategoryPage(Request $request){
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage',['categories'=>$categories]);
    }//end method

    public function CategorySavePage(Request $request){
        $category_id = $request->query('id');
        $user_id = $request->header('id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CategorySavePage',['category'=>$category]);
    }
    public function CreateCategory(Request $request){
        $user_id = $request->header('id');

        Category::create([
            'name' => $request->name,
            'user_id' => $user_id
        ]);
        $data = ['message'=>'Category created successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }//end method

    public function CategoryList(Request $request){
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return $categories;
    }//end method

    public function CategoryById(Request $request){
        $user_id = $request->header('id');
        $category =Category::where('id', $request->id)->where('user_id', $user_id)->first();
        return $category;
    }//end method

    public function CategoryUpdate(Request $request){
        $user_id = $request->header('id');
        $id = $request->input('id');
        Category::where('id', $id)->where('user_id', $user_id)->update([
            'name' => $request->input('name')
        ]);
        $data = ['message'=>'Category Updaetd successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }//end method

    public function CategoryDelete(Request $request,$id){
        $user_id = $request->header('id');
        Category::where('user_id', $user_id)->where('id', $id)->delete();
        $data = ['message'=>'Category Deleted successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }//end method
}

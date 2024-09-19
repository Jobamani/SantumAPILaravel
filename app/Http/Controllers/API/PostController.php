<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts']= POST::all();

        return response()->json([
            'status' =>true,
            'message'=>'All Post Data',
            'data'=>$data,
        ],200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' =>'required',
                'descripion'=>'required',
                'image'=> 'required|mimes:png,jpg,jpeg,gif',
            ]
            

            );
            //if validation fails
            if($validateUser->fails()){
                return response()->json([
                    'status' =>false,
                    'message'=>'validation Error',
                    'errors'=>$validateUser->error()->all()
                ],401);
            }
            
            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time(). '.' . $ext;
            $img->move(public_path(). '/uploads',$imageName);

            $post = Post::create([
                'title' => $request->title,
                'descripion'=> $request->description,
                'image'=> $request-> $imageName,
            ]);

            return response()->json([
                'status' =>true,
                'message'=>'Post Created Successfully',
                'post'=>$post,
            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id' => $id])->get(); 
        
        return response()->json([
            'status' =>true,
            'message'=>'Your Single Post',
            'data'=>$data,
        ],200);

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' =>'required',
                'descripion'=>'required',
                'image'=> 'required|mimes:png,jpg,jpeg,gif',
            ]
            

            );
            //if validation fails
            if($validateUser->fails()){
                return response()->json([
                    'status' =>false,
                    'message'=>'validation Error',
                    'errors'=>$validateUser->error()->all()
                ],401);
            }
            //will check here first is there any old image is present, if its exist will remove the old image & replace it with new one
            $postImage = Post::select('id', 'image') 
                ->where(['id' => $id])->get();
            return $postImage[0]->image;    

            if($request->image !=''){
                $path = public_path(). '/uploads';

                if($postImage[0]->image != '' && $postImage[0]->image != null){
                    $old_file = $path. $postImage[0]->image;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }
                }
    
                $img = $request->image;
                $ext = $img->getClientOriginalExtension();
                $imageName = time(). '.' . $ext;
                $img->move(public_path(). '/uploads',$imageName);
            }else{
                $imageName = $postImage->image;
            }

            $post = Post::where(['id' =>$id])->update([
                'title' => $request->title,
                'descripion'=> $request->description,
                'image'=> $request-> $imageName,
            ]);

            return response()->json([
                'status' =>true,
                'message'=>'Post Updated Successfully',
                'post'=>$post,
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //here we have to give a path for image then unlink the path for delete the image

        $imagePath = Post::select('image')->where('id',$id)->get();
        $filePath = public_path(). '/uploads/'. $imagePath[0]['image'];

        unlink($filePath);

        
        $post = Post::where(id,$id)->delete();

        return response()->json([
            'status' =>true,
            'message'=>'Your Post has been removed.',
            'post'=> $post,
        ],200);

    }

}
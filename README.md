#What does mean FastApi:
A Fastapi Laravel package to help you generate CRUD API Controllers and Resources, Model.. etc

#What actually do?
Suppose you are building an api, and you want to create controller and resources and model and factory.. etc, then you have to do a ton of other tedious and to be honest, boring things like creating migrations, model factories, the controller, form validation and adding all the logic and what not.

So what Fastapi does is when you tell it the model name, it will do all those boring things. When it's done you have the following:

Blog.php
BlogController.php ship with code already exists.
BlogStoreRequest.php and BlogUpdateRequest.php
BlogResoure.php
Timestamped create_blogs_table.php migration file
BlogFactory.php
Installation
composer require larafast/fastapi --dev
Then publish the stubs
php artisan vendor:publish --tag=fastApi
It will generate stubs to your resources/stubs directory.

#Example
php artisan fastApi Blog
Once done, it will show you the details of the files generated.

Factory created successfully

Created Migration: 2020_07_14_125128_create_blogs_table

Model created successfully

Controller created successfully

Request created successfully

Request created successfully

Resource created successfully

#Snapshot of BlogController
namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Resources\BlogResources;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = BlogResources::collection(Blog::fetchData(request()->all()));
        return response()->json(['data' => $rows], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogStoreRequest $request)
    {
        try {
            Blog::create($request->all());
            return response()->json(['message' => ''], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to create entry, '. $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $row = new BlogResources(Blog::find($blog));
        return response()->json(['row' => $row], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderUpdateRequest  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        try {
            $blog->update($request->all());
            return response()->json(['message' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to update entry, '. $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();
            return response()->json(['message' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to delete entry, '. $e->getMessage()], 500);
        }
    }
}

Now add the necessary fields and run

php artisan migrate
And that saved you an hour worth of repetitive and boring work which you can spend on more important development challenges.

Security
If you discover any security related issues, please email mahmoud.italy@outlook.com instead of using the issue tracker.

Credits
Mahmoud Italy
All Contributors
License
The MIT License (MIT). Please see License File for more information.
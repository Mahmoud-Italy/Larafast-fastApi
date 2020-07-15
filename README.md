# Larafast FastApi
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# What does mean FastApi:
A Fastapi Laravel package to help you generate CRUD API Controllers and Resources, Model.. etc

# What actually do?
Suppose you are building an api, and you want to create controller and resources and model and factory.. etc, then you have to do a ton of other tedious and to be honest, boring things like creating migrations, model factories, the controller, form validation and adding all the logic and what not.

So what Fastapi does is when you tell it the model name, it will do all those boring things. When it's done you have the following:
<ul>
    <li>Blog.php</li>
    <li>BlogController.php ship with code already exists</li>
    <li>BlogStoreRequest.php and BlogUpdateRequest.php</li>
    <li>BlogResoure.php</li>
    <li>Timestamped create_blogs_table.php migration file</li>
    <li>BlogFactory.php</li>
</ul>

# Installation
<pre>composer require larafast/fastapi --dev</pre>

# Then publish the config
<pre>php artisan vendor:publish --tag=fastApi</pre>


# Example
<pre>php artisan fastApi Blog</pre>
Once done, it will show you the details of the files generated.

<pre>
Factory created successfully

Created Migration: 2020_07_14_125128_create_blogs_table

Model created successfully

Controller created successfully

Request created successfully

Request created successfully

Resource created successfully
</pre>

# Snapshot of BlogController
<pre>
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

</pre>

# Snapshot of Blog Model
<pre>
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    // use SoftDeletes;
    protected $guarded = [];

    // imageable polymorphic
    public function image() {
        // return $this->morphOne(Image::class, 'imageable');
    }

    // handle attributes
    public function getImageAttribute()
    {
        // $img = upload image ..
        return self::image()->save($img);
    }

    // fetch Data
    public static function fetchData($value='')
    {
        $obj = self::query();

          // langauges..
          if(isset($value['locale'])) {
             app()->setLocale($value['locale']);
          }

          // search..
          if(isset($value['search'])) {
            $obj->where(function($q){
                $q->where('title', 'like','%'.$value['search'].'%')
                $q->orWhere('body', 'like', '%'.$value['search'].'%')
                $q->orWhere('id', $value['search']);
            });
          }

          // order..
          if(isset($value['order'])) {
            $obj->orderBy('id', $value['sort']);
          } else {
            $obj->orderBy('id', 'DESC');
          }



          // feel free to add any query filter as much as you want...




        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }
}

</pre>



Now add the necessary fields and run

<pre>php artisan migrate</pre>
And that saved you an hour worth of repetitive and boring work which you can spend on more important development challenges.

# Security
If you discover any security related issues, please email mahmoud.italy@outlook.com instead of using the issue tracker.

# Credits

  <ul>
    <li><a href="https://github.com/Mahmoud-Italy">Mahmoud Italy</a></li>
    <li><a href="https://github.com/Mahmoud-Italy/Larafast-fastApi/graphs/contributors">All Contributors</a></li>
  </ul>
  
# License
The MIT License (MIT). Please see License File for more information.

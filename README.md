# Larafast FastAPI
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Mahmoud-Italy/Larafast-fastApi/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
<a href="https://packagist.org/packages/larafast/fastapi"><img src="https://poser.pugx.org/larafast/fastapi/d/total.svg" alt="Total Downloads"></a>
![fast-api](assets/background.jpg)


# What does mean FastAPI:
A Fastapi Laravel package to help you generate CRUD API Controllers and Resources, Model.. etc

# What actually do?
Suppose you are building an api, and you want to create controller and resources and model and factory.. etc, then you have to do a ton of other tedious and to be honest, boring things like creating migrations, model factories, the controller, form validation and adding all.

So what FastAPI does is when you tell it the model name, it will do all those boring things. When it's done you have the following:
<ul>
    <li>Blog.php</li>
    <li>BlogController.php ship with code already exists</li>
    <li>BlogStoreRequest.php and BlogUpdateRequest.php</li>
    <li>BlogResource.php</li>
    <li>Timestamped create_blogs_table.php migration file</li>
    <li>BlogFactory.php</li>
</ul>

# Installation
<pre>composer require larafast/fastapi</pre>

# Then publish the config
<pre>php artisan vendor:publish --tag=fastApi</pre>

# For Lumen
Just Add this line into bootstrap/app.php
<pre>$app->register(Larafast\Fastapi\FastapiServiceProvider::class);</pre>

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
use App\Http\Resources\BlogResource;

class BlogController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view_blogs', ['only' => ['index', 'show']]);
        // $this->middleware('permission:add_blogs',  ['only' => ['store']]);
        // $this->middleware('permission:edit_blogs', ['only' => ['update']]);
        // $this->middleware('permission:delete_blogs', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = BlogResource::collection(Blog::fetchData(request()->all()));
        return response()->json([
            'rows'        => $rows,
            'paginate'    => $this->paginate($rows)
        ], 200);
    }

    public function store(BlogStoreRequest $request)
    {
        $row = Blog::createOrUpdate(NULL, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new BlogResource(Blog::findOrFail(decrypt($id)));
        return response()->json(['row' => $row], 200);
    }

    public function update(BlogUpdateRequest $request, $id)
    {
        $row = Blog::createOrUpdate(decrypt($id), request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $row = Blog::query();

            if(strpos($id, ',') !== false) {
                foreach(explode(',',$id) as $sid) {
                    $ids[] = $sid;
                }
                $row->whereIN('id', $ids);
            } else {
                $row->where('id', $id);
            }   
            $row->delete();

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

use DB;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = [];

    // imageable polymorphic
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }


    // fetch Data
    public static function fetchData($value='')
    {
        // this way will fire up speed of the query
        $obj = self::query();

          // langauges in case you use multilanguages transactions package..
          if(isset($value['locale']) && $value['locale']) {
             app()->setLocale($value['locale']);
          }

          // search for multiple columns..
          if(isset($value['search']) && $value['search']) {
            $obj->where(function($q) use ($value){
                $q->where('title', 'like','%'.$value['search'].'%');
                $q->orWhere('id', $value['search']);
            });
          }

          // order By..
          if(isset($value['sort']) && $value['sort']) {
            $obj->orderBy('id', $value['sort']);
          } else {
            $obj->orderBy('id', 'DESC');
          }


          // feel free to add any query filter as much as you want...



        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }

    // Create or Update
    public static function createOrUpdate($id, $value)
    {
        try {

            // begin Transaction between tables
            DB::beginTransaction();

                // find Or New
                $row              = (isset($id)) ? self::find($id) : new self;
                $row->title       = $value['title'] ?? NULL;
                $row->body        = $value['body'] ?? NULL;
                $row->save();

                // Image
                if(isset($value['image'])) {
                    $row->image()->delete();
                    if($value['image']) {
                        if(!Str::contains($value['image'], [ Imageable::contains() ])) {
                            $image = Image::uploadImage($value['image']);
                        } else {
                            $image = explode('/', $value['image']);
                            $image = end($image);
                        }
                        $row->image()->create([ 'url' => $image ]);
                    }
                }


            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}

</pre>




# Snapshot of Blog Resource
<pre>
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'encrypt_id'    => encrypt($this->id),
            'image'         => ($this->image) ? $this->image->url : NULL,

            'title'         => $this->title,
            'body'          => $this->body,

            'dateForHumans' => $this->created_at->diffForHumans(),
            'timestamp'     => $this->created_at
        ];
    }
}
</pre>



Now add the necessary fields and run

<pre>php artisan migrate</pre>
And that saved you an hour worth of repetitive and boring work which you can spend on more important development challenges.

# Credits

  <ul>
    <li><a href="https://github.com/Mahmoud-Italy">Mahmoud Italy</a></li>
    <li><a href="https://github.com/Mahmoud-Italy/Larafast-fastApi/graphs/contributors">All Contributors</a></li>
  </ul>
  
# License
The MIT License (MIT). Please see License File for more information.

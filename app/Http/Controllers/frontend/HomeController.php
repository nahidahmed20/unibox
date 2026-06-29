<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\OurService;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Slider;
use App\Models\Team;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class HomeController extends Controller
{
    public function index()
    {
        // Sliders
        $sliders = DB::table('sliders')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();
        

        $categories = Category::where('status', 1)
                ->with(['subcategories' => function ($query) {
                    $query->withCount(['products' => function ($q) {
                        $q->where('status', 1);
                    }]);
                }])
                ->withCount(['products' => function ($query) {
                    $query->where('status', 1); 
                }])
                ->orderBy('created_at', 'asc')
                ->get();

        $filteredCategories = DB::table('categories')
                ->join('products', function ($join) {
                    $join->on('categories.id', '=', 'products.category_id')
                        ->where('products.status', 1);
                })
                ->where('categories.status', 1)
                ->select(
                    'categories.id',
                    'categories.name',
                    'categories.image',
                    'categories.slug',
                    DB::raw('COUNT(products.id) as products_count')
                )
                ->groupBy(
                    'categories.id',
                    'categories.name',
                    'categories.image',
                    'categories.slug'
                )
                ->orderBy('categories.created_at', 'desc')
                ->get();
        $blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
        $products = DB::table('products')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin(
                    DB::raw("
                        (
                            SELECT product_id, SUM(stock) as total_quantity
                            FROM product_variants 
                            GROUP BY product_id
                        ) as stocks
                    "),
                    'products.id',
                    '=',
                    'stocks.product_id'
                )
                ->select(
                    'products.*',
                    'brands.name as brand_name',
                    DB::raw('IFNULL(stocks.total_quantity, products.stock) as total_quantity') 
                )
                ->where('products.status', 1)
                ->orderBy('products.id', 'desc')
                ->get();
        // dd($products);
        $about_us = DB::table('about_us')->first();
        $services = DB::table('our_services')->where('status', 1)->get();
        $features = DB::table('features')->where('status', 1)->orderBy('sort_order', 'asc')->get();
        $counters = DB::table('counters')->where('status', 1)->orderBy('sort_order', 'asc')->get();
        $materials = DB::table('materials')->where('status', 1)->orderBy('sort_order', 'asc')->get();
        $clients  = DB::table('clients')->where('status', 1)->orderBy('sort_order', 'asc')->get();

        return view('frontend.home.index', compact(
            'sliders', 'categories', 'products', 'blogs', 'filteredCategories',
            'about_us', 'services', 'features', 'counters', 'materials', 'clients'
        ));
    }

    public function modalData($id)
    {
        $product = Product::with([
            'images',
            'category',
            'colors.color',
            'colors.images',
            'variants.size', 
            'variants.color' 
        ])->findOrFail($id);

        $uniqueSizes = $product->variants->pluck('size')->filter()->unique('id');
        
        $sizes = $uniqueSizes->map(function ($size) use ($product) {
            $stock = $product->variants
                ->where('size_id', $size->id)
                ->sum('stock'); 

            return [
                'id'    => $size->id,
                'name'  => $size->name ?? 'N/A', 
                'stock' => $stock,
            ];
        })->values(); 

        $colors = $product->colors->map(function ($color) use ($product) {
            $stock = $product->variants
                ->where('color_id', $color->color_id)
                ->sum('stock'); 

            return [
                'id'     => $color->color_id,
                'name'   => $color->color->name ?? '',
                'code'   => $color->color->code ?? '#ccc',
                'stock'  => $stock,
                'images' => $color->images ?? [],
            ];
        });

        $variants = $product->variants->map(function ($variant) {
            return [
                'size_id'       => $variant->size_id,
                'color_id'      => $variant->color_id,
                'stock'         => $variant->stock, 
                'selling_price' => $variant->selling_price, 
            ];
        });

        $totalStock = $product->product_type === 'multiple' 
                        ? $product->variants->sum('stock') 
                        : $product->stock;

        return response()->json([
            'product' => [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'price'       => $product->selling_price,
                'description' => $product->description,
                'category'    => $product->category,
                'images'      => $product->images,
            ],

            'sizes'       => $sizes,
            'colors'      => $colors,
            'variants'    => $variants, 
            'has_sizes'   => $sizes->count() > 0,
            'has_colors'  => $colors->count() > 0,
            'total_stock' => $totalStock
        ]);
    }

    public function categoryProducts(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->first();
        $isSubcategory = false;

        if (!$category) {
            $category = SubCategory::where('slug', $slug)->firstOrFail();
            $isSubcategory = true;
        }

        $query = Product::with(['category', 'brand'])
            ->where('status', 1);

        if (!$isSubcategory) {
            $subIds = SubCategory::where('category_id', $category->id)->pluck('id')->toArray();
            $query->where(function($q) use ($category, $subIds) {
                $q->where('category_id', $category->id)
                ->orWhereIn('subcategory_id', $subIds);
            });
        } else {
            $query->where('subcategory_id', $category->id);
        }

        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->filled('brand')) {
            $query->whereIn('brand_id', $request->brand);
        }

        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('size_id', $request->size);
            });
        }

        if ($request->filled('min_price')) $query->where('selling_price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('selling_price', '<=', $request->max_price);

        switch ($request->sort) {
            case 'price_low':  $query->orderBy('selling_price', 'asc'); break;
            case 'price_high': $query->orderBy('selling_price', 'desc'); break;
            default:           $query->latest(); break;
        }

        $products = $query->paginate(12)->appends($request->all());

        $categories = Category::where('status', 1)->withCount(['products' => fn($q) => $q->where('status', 1)])->get();
        $brands = Brand::withCount(['products' => fn($q) => $q->where('status', 1)])->get();
        
        $sizes = Size::all(); 

        $filterUrl = route('category.show', $slug);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.home.partials.product_list', compact('products'))->render()
            ]);
        }

        return view('frontend.home.category-products', compact(
            'category', 'products', 'categories', 'brands', 'sizes', 'filterUrl'
        ));
    }


    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with([
                'images',
                'category',
                'brand',         
                'colors.color',
                'colors.images',
                'variants.size', 
                'variants.color' 
            ])
            ->firstOrFail();

        $uniqueSizes = $product->variants->pluck('size')->filter()->unique('id');

        $product->setRelation('sizes', $uniqueSizes);
// dd($product);
        return view('frontend.home.single', compact('product'));
    }

    public function search(Request $request)
    {
        $query = Product::with([
            'category',
            'brand',
            'sizes'
        ])->where('status', 1);

        // Product Name Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $category = null;

        // Category Search
        if ($request->filled('category_id')) {

            $query->where('category_id', $request->category_id);

            $category = Category::where('id', $request->category_id)
                ->where('status', 1)
                ->first();
        }

        $products = $query->latest()
            ->paginate(12)
            ->appends($request->all());

        /*
        |------------------------------------------------------
        | If category not selected but products found
        | then get first product category for ajax filter
        |------------------------------------------------------
        */
        if (!$category && $products->count() > 0) {
            $category = $products->first()->category;
        }

        // Ajax Filter URL
        if ($category) {
            $filterUrl = route('category.show', $category->slug);
        } else {
            $filterUrl = route('search');
        }

        $categories = Category::where('status', 1)
            ->withCount('products')
            ->get();

        $brands = Brand::withCount('products')->get();

        $sizes = Size::get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'frontend.home.partials.product_list',
                    compact('products')
                )->render()
            ]);
        }

        return view(
            'frontend.home.category-products',
            compact(
                'category',
                'products',
                'categories',
                'brands',
                'sizes',
                'filterUrl'
            )
        );
    }

    public function searchSuggestion(Request $request)
    {
        $products = Product::where('status',1)
            ->where('name','like','%'.$request->search.'%')
            ->take(8)
            ->get();

        return view('frontend.home.search-suggestion',compact('products'));
    }

    public function privacyPolicy()
    {
        return view('frontend.home.privacy-policy');
    }

    public function termsConditions()
    {
        return view('frontend.home.terms-conditions');
    }

    public function returnPolicy()
    {
        return view('frontend.home.return-policy');
    }

    public function shippingPolicy()
    {
        return view('frontend.home.shipping-policy');
    }

    public function contactUs()
    {
        return view('frontend.home.contact-us');
    }

    public function contactUsSend(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'subject' => 'required|string',
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'subject' => $request->subject,
            'phone' => $request->phone,
        ];
        Contact::create($data);

        return back()->with('success', 'Thank you for contacting us!');
    }

    public function about()
    {
        $about_us       = AboutUs::first(); 
        $teams          = Team::where('status', 1)->get();
        $services       = OurService::where('status', 1)->take(3)->get(); 
        $testimonials   = Testimonial::where('status', 1)->get();
        $clients        = Client::where('status', 1)->get(); 

        return view('frontend.home.about-us', compact('about_us', 'teams', 'services', 'testimonials', 'clients'));
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ],[
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already subscribed.',
        ]);
        Newsletter::create([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Thanks for subscribing to Unibox!');
    }

    public function thankYou($order)
    {
        $order = Order::where('order_number', $order)->firstOrFail();
        return view('frontend.home.thank-you', compact('order'));
    }
    public function orderTrack()
    {
        return view('frontend.home.order-track');
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        
        // User jodi order_id diye search kore
        if ($request->has('order_id') && $request->order_id != '') {
            // Apnar Order model er nam onujayi khujbe (e.g., order_number ba id)
            $order = Order::where('order_number', $request->order_id)->first();
            
            if (!$order) {
                return redirect()->back()->with('error', 'Order ID ti sothik noy!');
            }
        }

        return view('frontend.home.order-track', compact('order'));
    }

    public function ourBlogs()
    {
        $blogs = Blog::with('category')->where('status', 'active')->latest()->paginate(9);
        
        return view('frontend.home.our_blogs', compact('blogs'));
    }

    public function blogDetails($slug)
    {
        $blog = Blog::with(['category', 'user'])
                    ->where('slug', $slug)
                    ->where('status', 'active')
                    ->firstOrFail();

        $recent_blogs = Blog::where('status', 'active')
                            ->where('id', '!=', $blog->id) 
                            ->latest()
                            ->take(4)
                            ->get();
                            
        $categories = BlogCategory::withCount('blogs')->get();

        return view('frontend.home.blog_details', compact('blog', 'recent_blogs', 'categories'));
    }

    public function serviceDetails($slug)
    {
        $service = DB::table('our_services')->where('slug', $slug)->where('status', 1)->first();

        if (!$service) {
            abort(404);
        }

        return view('frontend.service.details', compact('service'));
    }

}

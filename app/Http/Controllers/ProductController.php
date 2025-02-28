<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\Product;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//
//
//class ProductController extends Controller
//
//{
//    public function index(Request $request)
//    {
//        return response()->json(Product::all(), 200);
//    }
//
//    public function store(Request $request)
//    {
//        try {
//            $validated = $request->validate([
//                'name' => 'required|string|max:255',
//                'description' => 'required|string|max:1000',
//                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
//            ]);
//
//            $imagePath = $request->file('image')->storePublicly('images', 'public');
//
//            $product = Product::create([
//                'name' => $request->name,
//                'description' => $request->description,
//                'image' => $imagePath,
//            ]);
//
//            $product->image = url('/storage/' . $product->image);
//
//            return response()->json($product, 201);
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'An error occurred while creating the product.'], 500);
//        }
//    }
//
//    public function update(Request $request, $id)
//    {
//        $validatedData = $request->validate([
//            'name' => 'required|string|max:255',
//            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//        ]);
//
//        $image = Product::findOrFail($id);
//
//        $image->name = $validatedData['name'];
//
//        if ($request->hasFile('file')) {
//            $file = $request->file('file');
//            $filePath = $file->store('images', 'public');
//
//            if ($image->file_path) {
//                \Storage::disk('public')->delete($image->file_path);
//            }
//
//            $image->file_path = $filePath;
//        }
//
//        $image->save();
//
//        return response()->json([
//            'message' => 'Изображение успешно обновлено',
//            'image' => $image,
//        ]);
//    }
//}
//

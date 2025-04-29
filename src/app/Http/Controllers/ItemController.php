<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    $query = Item::query();

    // 検索機能の実装
    if ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 自分が出品した商品を除外
    if (Auth::check()) {
      $query->where('seller_id', '!=', Auth::id());
    }

    $items = $query->latest()->paginate(10);

    return view('items.index', compact('items'));
  }


  public function mylist(Request $request)
  {
    if (!Auth::check()) {
      return view('auth.login');
    }
    $likedItems = Auth::user()->likes()->pluck('item_id');
    $query = Item::whereIn('id', $likedItems);

    // 検索機能の実装
    if ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 自分が出品した商品を除外
    $query->where('seller_id', '!=', Auth::id());

    $items = $query->latest()->paginate(10);

    return view('items.mylist', compact('items'));
  }


  // 商品出品のフォームを表示
  public function create()
  {
    $categories = Category::all();
    return view('items.create', compact('categories'));
  }


  // 商品出品
  public function store(Request $request)
  {
    $data = $request->validated();
    $data['seller_id'] = Auth::id();

    if ($request->hasFile('image')) {
      $imagePath = $request->file('image')->store('items', 'public');
      $data['image'] = $imagePath;
    }

    $item = Item::create($data);
    $item->categories()->attach($request->categories);

    return redirect()->route('mypage', ['page' => 'sell'])->with('success', '商品を出品しました');
  }


  public function show(Item $item)
  {
    $item->load(['categories', 'comments.user']);
    $isLiked = false;

    if (Auth::check()) {
      $isLiked = $item->isLikedBy(Auth::id());
    }

    return view('items.show', compact('item', 'isLiked'));
  }


  public function completePurchase(Request $request, Item $item)
  {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $item->update([
      'buyer_id' => Auth::id(),
      'sold' => true
    ]);

    return redirect()->route('mypage', ['page' => 'buy'])->with('success', '商品を購入しました');
  }


  public function toggleLike(Item $item)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $existing = Like::where('user_id', Auth::id())
    ->where('item_id', $item->id)
    ->first();

    if ($existing) {
      $existing->delete();
      $action = 'unliked';
    } else {
      Like::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id
      ]);
      $action = 'liked';
    }

    return redirect()->back()->with('success', "商品を{$action}しました");
  }


  public function storeComment(Request $request, Item $item)
  {
    Comment::create([
      'user_id' => Auth::id(),
      'item_id' => $item->id,
      'content' => $request->content
    ]);

    return redirect()->back()->with('success', 'コメントを投稿しました');
  }
}
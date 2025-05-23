<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
// use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $query = Item::query();

    // 検索機能の実装
    if ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 自分が出品した商品を除外
    if (Auth::check()) {
      $query->where('seller_id', '!=', Auth::id());
    }

    $items = $query->latest()->get();

    return view('items.index', compact('items'));
  }


  public function mylist(Request $request)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    if (!Auth::check()) {
      // 未認証の場合は空のコレクションをビューに渡す
      $items = collect();
      return view('items.index', compact('items'));
    }

    // ログインユーザーがいいねした商品のIDを取得
    $likedItems = Auth::user()->likes()->pluck('item_id');
    $query = Item::whereIn('id', $likedItems);

    // 検索機能の実装
    if ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 自分が出品した商品を除外
    $query->where('seller_id', '!=', Auth::id());

    $items = $query->latest()->get();

    return view('items.index', compact('items'));
  }

  public function mypage(Request $request)
  {
    if (!Auth::check()) {
      // 未認証の場合は空のコレクションをビューに渡す
      $items = collect();
      return view('items.index', compact('items'));
    }

    $route = $request->route()->getName();
    if ($route === 'mypage.buy') {
      $items = Auth::user()->purchasedItems()->latest()->get();
    } elseif ($route === 'mypage.sell') {
      $items = Auth::user()->sellingItems()->latest()->get();
    } else {
      $items = Auth::user()->purchasedItems()->latest()->get();
    }

    return view('mypage.index', compact('items'));
  }


  // 商品出品のフォームを表示
  public function create()
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $categories = Category::all();
    $conditions = Condition::all();
    return view('items.create', compact('categories', 'conditions'));
  }


  // 商品出品
  public function store(ExhibitionRequest $request)
  {
    $data = $request->validated();
    $data['seller_id'] = Auth::id();

    if ($request->hasFile('image')) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->storeAs('images/items', $filename);
      $data['image'] = 'storage/images/items/' . $filename;
    }

    $item = Item::create($data);
    $item->categories()->attach($request->categories);
    $item->conditions()->attach($request->conditions);

    return redirect()->route('mypage.sell');
  }


  //商品詳細
  public function show(Item $item)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $item->load(['categories', 'comments.user']);
    $isLiked = false;

    if (Auth::check()) {
      $isLiked = $item->isLikedBy(Auth::id());
    }

    return view('items.show', compact('item', 'isLiked'));
  }


  public function purchase(Item $item)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    return view('items.purchase', compact('item'));
  }


  public function selectPayment(Request $request, Item $item)
  {
    $validated = $request->validate([
        'payment_method' => 'required|string',
    ]);

    session(['selected_payment' => $validated['payment_method']]);

    return redirect()->route('items.purchase', $item);
  }


  public function changeAddress(Item $item)
    {
        return view('items.changeAddress', ['user' => Auth::user()], compact('item'));
    }


  public function AddressUpdate(AddressRequest $addressrequest, Item $item)
  {
    $user = Auth::user();

    $user->postal_code = $addressrequest->postal_code;
    $user->address = $addressrequest->address;
    $user->building = $addressrequest->building;

    $user->save();

    return redirect()->route('items.purchase', $item);
  }


  public function completePurchase(Request $request, Item $item)
  {
    $item->update([
      'buyer_id' => Auth::id(),
      'sold' => true,
    ]);

    session()->forget(['selected_payment', 'current_purchase_item_id']);

    return redirect()->route('mypage.buy', $item);
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

    return redirect()->back();
  }


  public function storeComment(Request $request, Item $item)
  {
    Comment::create([
      'user_id' => Auth::id(),
      'item_id' => $item->id,
      'content' => $request->content
    ]);

    return redirect()->back();
  }
}
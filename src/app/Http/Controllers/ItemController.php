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

    $items = $query->latest()->paginate(10);

    return view('items.index', compact('items'));
  }


  public function mylist(Request $request)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    if (!Auth::check()) {
      // 未認証の場合は空のコレクションをビューに渡す
      $items = collect()->paginate(10);
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

    $items = $query->latest()->paginate(10);

    return view('items.index', compact('items'));
  }


  // 商品出品のフォームを表示
  public function create()
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $categories = Category::all();
    return view('items.create', compact('categories'));
  }


  // 商品出品
  public function store(ExhibitionRequest $request)
  {
    $data = $request->validated();
    $data['seller_id'] = Auth::id();

    if ($request->hasFile('image')) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->store('images/items', 'public');
      $data['image'] = 'storage/images/items/' . $filename;
    }

    $item = Item::create($data);
    $item->categories()->attach($request->categories);

    return redirect()->route('mypage', ['page' => 'sell']);
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


  public function AddressUpdate(Request $request, Item $item)
  {
    $validated = $request->validate([
      'postal_code' => ['required', 'string', 'max:8', 'regex:/^\d{3}-\d{4}$/'],
      'address' => ['required', 'string', 'max:255'],
      'building' => ['required', 'string', 'max:255'],
    ],[
      'postal_code.required' => '郵便番号を入力してください',
      'postal_code.max' => '郵便番号はハイフンありの8文字で入力してください',
      'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください',
      'address.required' => '住所を入力してください',
      'building.required' => '建物名を入力してください',
    ]);

    $user = Auth::user();

    $user->postal_code = $request->postal_code;
    $user->address = $request->address;
    $user->building = $request->building;

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
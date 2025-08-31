<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Mail\TransactionCompletedMail;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $query = Item::query();
    $search = $request->input('search', '');
    $tab = $request->input('tab', 'recommend');

    if ($search) {
      $query->where('name', 'like', '%' . $search . '%');
    }

    if ($tab === 'mylist'){
      if(Auth::check()){
      $likedItems = Auth::user()->likes()->pluck('item_id');
      $query->whereIn('id', $likedItems);
      }else{
        $items = collect();
        return view('items.index', compact('items', 'search', 'tab'));
      }
    }

    if (Auth::check()) {
      $query->where('seller_id', '!=', Auth::id());
    }

    $items = $query->latest()->get();

    return view('items.index', compact('items', 'search', 'tab'));
  }


  public function mypage(Request $request)
  {
    $tab = $request->input('tab', 'sell');
    $user = Auth::user();
    $unreadCounts = [];

    if ($tab === 'sell') {
      $items = Auth::user()->sellingItems()->latest()->get();
    } elseif ($tab === 'buy') {
      $items = Auth::user()->purchasedItems()->latest()->get();
    } else {
      $items = Auth::user()->tradingItems();
      $unreadCounts = Auth::user()->getAllUnreadCounts();
    }

    if ($tab === 'trade'){
      return view('mypage.index', compact('items', 'tab', 'user', 'unreadCounts'));
    } else {
      return view('mypage.index', compact('items', 'tab', 'user'));
    }
  }


  // 商品出品のフォームを表示
  public function create()
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $categories = Category::all();
    $conditions = Condition::all();
    return view('items.create', compact('categories', 'conditions'));
  }

  public function store(ExhibitionRequest $request)
  {
    $data = $request->validated();
    $data['seller_id'] = Auth::id();

    if ($request->hasFile('image')) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->storeAs('images/items', $filename, 'public');
      $data['image'] = 'storage/images/items/' . $filename;
    }

    $item = Item::create($data);
    $item->categories()->attach($request->categories);

    return redirect()->route('mypage', ['tab' => 'sell']);
  }

  public function show($item_id)
  {
    session()->forget(['selected_payment', 'current_purchase_item_id']);

    $item = Item::with(['categories', 'comments.user'])->findOrFail($item_id);
    $isLiked = false;

    if (Auth::check()) {
      $isLiked = $item->isLikedBy(Auth::id());
    }

    return view('items.show', compact('item', 'isLiked'));
  }


  public function purchase($item_id)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $item = Item::findOrFail($item_id);
    return view('items.purchase', compact('item'));
  }


  public function changeAddress($item_id)
    {
      $item = Item::findOrFail($item_id);
      return view('items.changeaddress', ['user' => Auth::user()], compact('item'));
    }


  public function AddressUpdate(AddressRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    $user->postal_code = $request->postal_code;
    $user->address = $request->address;
    $user->building = $request->building;

    $user->save();

    return redirect()->route('items.purchase', $item_id);
  }


  public function completePurchase(PurchaseRequest $request, $item_id)
  {
    try {
      $item = Item::findOrFail($item_id);

      if ($item->sold) {
        return redirect()->route('index');
      }

      if ($item->seller_id == Auth::id()) {
        return redirect()->route('index');
      }

      $item->update([
        'buyer_id' => Auth::id(),
        'sold' => true,
        'payment_method' => $request->payment_method,
      ]);

      return redirect()->route('mypage', ['tab' => 'buy']);

    } catch (\Exception $e) {
      \Log::error('Purchase failed', [
        'item_id' => $item_id,
        'user_id' => Auth::id(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput();
    }
  }


  public function completeTransaction($item_id)
  {
    try {
      $item = Item::findOrFail($item_id);

      if (Auth::id() != $item->buyer_id) {
        return redirect()->back();
      }

      if ($item->is_transaction_completed) {
        return redirect()->back();
      }

      $item->is_transaction_completed = true;
      $item->save();

      $seller = User::findOrFail($item->seller_id);
      $buyer = Auth::user();

      try {
        Mail::to($seller->email)->send(new TransactionCompletedMail($item, $seller, $buyer));
        \Log::info('Transaction completion email sent', [
          'item_id' => $item->id,
          'seller_email' => $seller->email,
          'buyer_id' => $buyer->id
        ]);
      } catch (\Exception $e) {
        \Log::error('Failed to send transaction completion email', [
          'item_id' => $item->id,
          'seller_email' => $seller->email,
          'buyer_id' => $buyer->id,
          'error' => $e->getMessage()
        ]);
      }

      session(['show_buyer_evaluation_modal' => $item_id]);

      return redirect()->route('chat.show', $item_id);

    } catch (\Exception $e) {
      \Log::error('Transaction completion failed', [
        'item_id' => $item_id,
        'user_id' => Auth::id(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back();
    }
  }


  public function toggleLike($item_id)
  {
    $item = Item::findOrFail($item_id);

    $existing = Like::where('user_id', Auth::id())
    ->where('item_id', $item->id)
    ->first();

    if ($existing) {
      $existing->delete();
    } else {
      Like::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id
      ]);
    }

    return redirect()->back();
  }


  public function storeComment(CommentRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);

    Comment::create([
      'user_id' => Auth::id(),
      'item_id' => $item->id,
      'content' => $request->content
    ]);

    return redirect()->back();
  }
}
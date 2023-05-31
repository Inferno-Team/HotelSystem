<?php

namespace App\Http\Controllers\customer;

use App\Models\CustomerFav;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\customer\AddFavRequest;
use App\Http\Requests\customer\RemoveFavRequest;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    public function addToFav(AddFavRequest $request)
    {
        CustomerFav::create($request->values());
        return LocalResponse::returnMessage('Added to fav');
    }
    public function reomveFromFav(RemoveFavRequest $request)
    {
        $customer_fav = CustomerFav::where('id', $request->id)->first();
        $customer_fav->delete();
        return LocalResponse::returnMessage('Removed From fav');
    }
    public function getMyFav()
    {
        $customer_favs = CustomerFav::where('customer_id', Auth::user()->id)->get();
        return LocalResponse::returnData('favs', $customer_favs);
    }
}

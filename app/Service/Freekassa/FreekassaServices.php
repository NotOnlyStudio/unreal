<?php

namespace App\Service\Freekassa;

use App\Models\Payment;
use App\Models\Product;
use App\Service\Payment\PaymentServices;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class FreekassaServices extends PaymentServices
{
    protected int $merchat_id = 41922;
    protected string $secret_word = '/+YaDX$1Fdg5XWX';
    protected string $apiKey = '5bd6bc75cef52cb11e1a4869be1006dc';
    protected string $currency = 'RUB';

    public function freekassa(int $product_id)
    {
        $product = Product::query()->where('id', $product_id)->first();
        $sign = md5($this->merchat_id.':'. $product->price .':'. $this->secret_word .':'. $this->currency .':'. Auth::user()->id);

        Payment::query()->where('token', $sign)->delete();
        Payment::query()->where([['user_id', \auth()->user()->id], ['product_id', $product_id]])->delete();
        $this->store($product_id, $sign);

        $link = "https://pay.freekassa.ru/?m=".$this->merchat_id."&oa=". $product->price ."&o=". Auth::user()->id ."&s=$sign&currency=". $this->currency ."&us_sign=" . $sign;
        return redirect($link);
    }

}

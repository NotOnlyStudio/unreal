<?php

namespace App\Service\Conclusion;

use App\Models\User;
use App\Service\Freekassa\FreeKassaWithdrawlServices;
use Illuminate\Validation\ValidationException;

class ConclusionServices
{
    /**
     * @throws ValidationException
     */
    public function conclusion(array $data)
    {
        if ($data['amount'] <= auth()->user()->balance) {
            if ($data['amount'] > 10) {
                $withdraw = new FreeKassaWithdrawlServices();
                $withdraw = $withdraw->withDrawl($data['amount'], $data['accountName']);
                if ($withdraw['type'] === 'success') {
                    $user = User::query()->find(auth()->user()->id);
                    $user['balance'] += $data['amount'];
                    $user->save();
                }
                throw ValidationException::withMessages([
                    'freekassa' => $withdraw['message'],
                ]);
            }
            throw ValidationException::withMessages([
                'amount' => trans('validation.conclusion.min'),
            ]);
        }

        throw ValidationException::withMessages([
            'amount' => trans('validation.conclusion.amount'),
        ]);
    }
}

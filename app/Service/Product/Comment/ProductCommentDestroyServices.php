<?php

namespace App\Service\Product\Comment;

use App\Models\Comment;
use Illuminate\Validation\ValidationException;

class ProductCommentDestroyServices
{
    /**
     * @throws ValidationException
     */
    public function destroy(int $comment_id)
    {

        $check = Comment::query()->where('id', $comment_id)->first();

        if ($check) {
            if ($check->user_id === auth()->user()->id) {
                $check->delete();
                throw ValidationException::withMessages([
                    'message' => trans('post.comment.delete3'),
                ]);
            }

            throw ValidationException::withMessages([
                'message' => trans('post.comment.delete2'),
            ]);
        }
        throw ValidationException::withMessages([
            'message' => trans('post.comment.delete1'),
        ]);
    }
}

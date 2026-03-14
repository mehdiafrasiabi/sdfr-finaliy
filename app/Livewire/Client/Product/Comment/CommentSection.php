<?php

namespace App\Livewire\Client\Product\Comment;
use App\Models\CommentLike;
use App\Models\ProductComment;
use Livewire\Component;
class CommentSection extends Component
{
    public $productId;
    public $perPage = 20;
    public $commentText = '';
    public $hasMore = false;
    protected $rules = [
        'commentText' => 'required|min:10|max:1000',
    ];
    protected $messages = [
        'commentText.required' => 'لطفا متن دیدگاه خود را وارد کنید.',
        'commentText.min' => 'متن دیدگاه باید حداقل ۱۰ کاراکتر باشد.',
        'commentText.max' => 'متن دیدگاه نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
    ];
    public function mount($productId)
    {
        $this->productId = $productId;
    }
    public function loadMore()
    {
        $this->perPage += 20;
    }
    public function submitComment()
    {
        if (!auth()->check()) {
            return $this->redirect(route('client.auth.login'));
        }
        $this->validate();
        ProductComment::create([
            'product_id' => $this->productId,
            'user_id' => auth()->id(),
            'comment' => $this->commentText,
            'status' => 'pending',
        ]);
        $this->commentText = '';
        session()->flash('success', 'دیدگاه شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.');
    }
    public function toggleLike($commentId)
    {
        if (!auth()->check()) {
            return $this->redirect(route('client.auth.login'));
        }
        $existingLike = CommentLike::where('comment_id', $commentId)
            ->where('user_id', auth()->id())
            ->first();
        if ($existingLike) {
            $existingLike->delete();
        } else {
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => auth()->id(),
            ]);
        }
    }
    public function reportComment($commentId)
    {
        if (!auth()->check()) {
            return $this->redirect(route('client.auth.login'));
        }
        $comment = ProductComment::find($commentId);
        if ($comment && $comment->status !== 'reported') {
            $comment->update(['status' => 'reported']);
            session()->flash('success', 'گزارش شما با موفقیت ثبت شد.');
        }
    }
    public function render()
    {
        $userId = auth()->id();
        // Get comments: published OR owned by current user (pending only, not reported)
        // Reported comments are hidden from everyone except the owner
        $commentsQuery = ProductComment::where('product_id', $this->productId)
            ->with(['user', 'reply.admin'])
            ->withCount('likes')
            ->where(function ($query) use ($userId) {
                $query->where('status', 'published');
                if ($userId) {
                    // Show user's own pending comments (but not reported ones to others)
                    $query->orWhere(function ($q) use ($userId) {
                        $q->where('user_id', $userId)
                            ->whereIn('status', ['pending', 'reported']);
                    });
                }
            })
            ->orderBy('created_at', 'desc');
        $totalComments = $commentsQuery->count();
        $comments = $commentsQuery->take($this->perPage)->get();
        // Check which comments the user has liked
        $likedCommentIds = [];
        if ($userId) {
            $likedCommentIds = CommentLike::where('user_id', $userId)
                ->whereIn('comment_id', $comments->pluck('id'))
                ->pluck('comment_id')
                ->toArray();
        }
        $this->hasMore = $totalComments > $this->perPage;
        // All logged in users can comment
        $canComment = auth()->check();
        return view('livewire.client.product.comment.comment-section', [
            'comments' => $comments,
            'totalComments' => $totalComments,
            'likedCommentIds' => $likedCommentIds,
            'canComment' => $canComment,
        ]);
    }
}

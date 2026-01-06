<?php


namespace App\Livewire\Manager\Comment;


use App\Models\ProductComment;

use App\Models\CommentReply;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Index extends Component

{

    use WithPagination, SEOTools;


    public $search = '';

    public $statusFilter = 'all';

    public $perPage = 10;


    // Modal states

    public $showReplyModal = false;

    public $showEditModal = false;

    public $showDeleteModal = false;


    // Selected comment data

    public $selectedCommentId = null;

    public $replyText = '';

    public $editComment = '';

    public $editLikes = 0;


    protected $rules = [

        'replyText' => 'required|min:3',

        'editComment' => 'required|min:3',

        'editLikes' => 'required|integer|min:0',

    ];


    protected $messages = [

        'replyText.required' => 'متن پاسخ الزامی است.',

        'replyText.min' => 'متن پاسخ باید حداقل ۳ کاراکتر باشد.',

        'editComment.required' => 'متن دیدگاه الزامی است.',

        'editComment.min' => 'متن دیدگاه باید حداقل ۳ کاراکتر باشد.',

        'editLikes.required' => 'تعداد پسندیدن الزامی است.',

        'editLikes.integer' => 'تعداد پسندیدن باید عدد صحیح باشد.',

        'editLikes.min' => 'تعداد پسندیدن نمی‌تواند منفی باشد.',

    ];


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت دیدگاه محصولات');

    }


    public function updating($field)

    {

        if (in_array($field, ['search', 'statusFilter'])) {

            $this->resetPage();

        }

    }


    public function setStatus($status)

    {

        $this->statusFilter = $status;

        $this->resetPage();

    }


    public function approveComment($commentId)

    {

        $comment = ProductComment::find($commentId);

        if ($comment) {

            $comment->update(['status' => 'published']);

            session()->flash('message', 'دیدگاه با موفقیت منتشر شد.');

        }

    }


    public function openReplyModal($commentId)

    {

        $this->selectedCommentId = $commentId;

        $comment = ProductComment::with('reply')->find($commentId);

        $this->replyText = $comment->reply?->reply ?? '';

        $this->showReplyModal = true;

    }


    public function saveReply()

    {

        $this->validate(['replyText' => 'required|min:3']);


        $comment = ProductComment::find($this->selectedCommentId);

        if ($comment) {

            CommentReply::updateOrCreate(

                ['comment_id' => $this->selectedCommentId],

                [

                    'admin_id' => auth('manager')->id(),

                    'reply' => $this->replyText,

                ]

            );


            // Mark comment as published when admin replies

            if ($comment->status === 'pending') {

                $comment->update(['status' => 'published']);

            }


            session()->flash('message', 'پاسخ با موفقیت ثبت شد.');

        }


        $this->closeModals();

    }


    public function deleteReply($commentId)

    {

        CommentReply::where('comment_id', $commentId)->delete();

        // Set comment back to pending when admin reply is deleted

        $comment = ProductComment::find($commentId);

        if ($comment && $comment->status === 'published') {

            $comment->update(['status' => 'pending']);

        }


        session()->flash('message', 'پاسخ حذف شد و دیدگاه به حالت عدم انتشار تغییر کرد.');
        $this->dispatch('success', 'پاسخ حذف شد و دیدگاه به حالت عدم انتشار تغییر کرد.');

    }


    public function openEditModal($commentId)

    {

        $this->selectedCommentId = $commentId;

        $comment = ProductComment::withCount('likes')->find($commentId);

        $this->editComment = $comment->comment;

        $this->editLikes = $comment->likes_count;

        $this->showEditModal = true;

    }


    public function saveEdit()

    {

        $this->validate([

            'editComment' => 'required|min:3',

            'editLikes' => 'required|integer|min:0',

        ]);


        $comment = ProductComment::find($this->selectedCommentId);

        if ($comment) {

            $comment->update(['comment' => $this->editComment]);


            // Update likes count

            $currentLikes = $comment->likes()->count();

            $diff = $this->editLikes - $currentLikes;


            if ($diff > 0) {

                // Add fake likes (using user_id = 0 for system)

                for ($i = 0; $i < $diff; $i++) {

                    $comment->likes()->create(['user_id' => 1]);

                }

            } elseif ($diff < 0) {

                // Remove likes

                $comment->likes()->limit(abs($diff))->delete();

            }


            session()->flash('message', 'دیدگاه با موفقیت ویرایش شد.');

        }


        $this->closeModals();

    }


    public function openDeleteModal($commentId)

    {

        $this->selectedCommentId = $commentId;

        $this->showDeleteModal = true;

    }


    public function deleteComment()

    {

        $comment = ProductComment::find($this->selectedCommentId);

        if ($comment) {

            $comment->delete();

            session()->flash('message', 'دیدگاه با موفقیت حذف شد.');

        }


        $this->closeModals();

    }

    public function unpublishComment($commentId)

    {

        $comment = ProductComment::find($commentId);

        if ($comment) {

            $comment->update(['status' => 'pending']);

            session()->flash('message', 'دیدگاه به حالت عدم انتشار تغییر کرد.');

        }

    }


    public function reportComment($commentId)

    {

        $comment = ProductComment::find($commentId);

        if ($comment) {

            $comment->update(['status' => 'reported']);

            session()->flash('message', 'دیدگاه گزارش شد و دیگر نمایش داده نمی‌شود.');

        }

    }

    public function closeModals()

    {

        $this->showReplyModal = false;

        $this->showEditModal = false;

        $this->showDeleteModal = false;

        $this->selectedCommentId = null;

        $this->replyText = '';

        $this->editComment = '';

        $this->editLikes = 0;

    }


    public function render()

    {

        $commentsQuery = ProductComment::query()
            ->with(['user', 'product', 'reply.admin'])
            ->withCount('likes')
            ->when($this->search, function ($q) {

                $q->where('comment', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($query) => $query->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('product', fn($query) => $query->where('name', 'like', "%{$this->search}%"));

            })
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter));


        $comments = $commentsQuery
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);


        // Stats

        $stats = ProductComment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');


        return view('livewire.manager.comment.index', [

            'comments' => $comments,

            'totalComments' => array_sum($stats->toArray()),

            'publishedComments' => $stats['published'] ?? 0,

            'pendingComments' => $stats['pending'] ?? 0,

            'reportedComments' => $stats['reported'] ?? 0,

        ])->layout('layouts.manager.app');

    }

}

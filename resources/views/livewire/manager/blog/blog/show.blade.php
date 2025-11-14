<div>
    <div>
        <h3>عنوان بلاگ :
            <span class="text-primary">{{ $blog->title }}</span>
        </h3>
        <p>کد بلاگ: {{ $blog->blog_code }}</p>
        <p class="btn btn-outline-primary">وضعیت: {{ $blog->status }}</p>
        <p>توضیحات تخصصی:</p>
        <div>{!! $blog->description !!}</div>

        <div class="d-flex flex-wrap mt-3">
            @foreach($blog->images as $image)
                <img src="{{ asset('/blogs/'.$blog->id.'/photo/'.$image->path) }}" class="img-thumbnail m-2" width="150">
            @endforeach
        </div>

        @if($blog->status == 'pending')
            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-success" wire:click="approve">تایید</button>
                <button class="btn btn-danger" wire:click="reject">رد</button>
            </div>
        @endif
    </div>
</div>

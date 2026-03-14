<div>
    <div class="space-y-5">
        <!-- Comments Title -->
        <div class="flex items-center gap-3 mb-5">
            <div class="flex items-center gap-1">
                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                <div class="w-2 h-2 bg-foreground rounded-full"></div>
            </div>
            <div class="font-black text-foreground">دیدگاه و نظرات ({{ $totalComments }})</div>
        </div>
        <!-- Flash Messages -->
        @if(session()->has('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session()->has('error'))
            <div
                class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <!-- Comment Form -->
        <div class="bg-background border border-border rounded-3xl p-5 mb-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                </div>
                <div class="font-black text-xs text-foreground">ارسال دیدگاه</div>
            </div>
            @auth
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden bg-secondary flex items-center justify-center">
                        @if(auth()->user()->picture)
                            <img src="{{ asset('storage/' . auth()->user()->picture) }}"
                                 class="w-full h-full object-cover" alt="{{ auth()->user()->name }}"/>
                        @else
                            <span class="text-foreground font-bold">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex flex-col items-start space-y-1">
                        <span class="font-semibold text-sm text-foreground">{{ auth()->user()->name }}</span>
                    </div>
                </div>
                <form wire:submit="submitComment" class="flex flex-col space-y-5">
                <textarea wire:model="commentText" rows="5"
                          class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-0 focus:border-border border-border rounded-xl text-sm text-foreground p-5 @error('commentText') border-red-500 @enderror"
                          placeholder="نظر خود را درباره این محصول بنویسید..."></textarea>
                    @error('commentText')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                    <button type="submit"
                            class="h-10 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4 mr-auto">
                        <span class="font-semibold text-sm" wire:loading.remove
                              wire:target="submitComment">ثبت دیدگاه</span>
                        <span wire:loading wire:target="submitComment"
                              class="font-semibold text-sm">در حال ارسال...</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"
                             wire:loading.remove wire:target="submitComment">
                            <path fill-rule="evenodd"
                                  d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <svg wire:loading wire:target="submitComment" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 animate-spin">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                        </svg>
                    </button>
                </form>
            @else
                <div class="bg-secondary rounded-xl p-5 text-center">
                    <p class="text-muted mb-3">برای ثبت دیدگاه لطفا وارد حساب کاربری خود شوید.</p>
                    <a href="{{ route('client.auth.login') }}"
                       class="h-10 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-6">
                        <span class="font-semibold text-sm">ورود به حساب کاربری</span>
                    </a>
                </div>
            @endauth
        </div>
        <!-- Comments List -->
        <div class="space-y-3">
            @forelse($comments as $comment)
                <div class="space-y-3">
                    <div class="bg-background border border-border rounded-3xl
                        space-y-3 p-5 {{ $comment->status === 'pending' ? 'border-yellow-400 dark:border-yellow-600' : '' }} {{ $comment->status === 'reported' ? 'border-red-400 dark:border-red-600 opacity-70' : '' }}">
                        <!-- Comment Header -->
                        <div class="flex sm:flex-nowrap flex-wrap sm:flex-row flex-col sm:items-center sm:justify-between gap-5 border-b border-border pb-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden bg-secondary flex items-center justify-center">
                                    @if($comment->user->picture)
                                        <img src="{{ asset('storage/' . $comment->user->picture) }}" class="w-full h-full object-cover" alt="{{ $comment->user->name }}"/>
                                    @else
                                        <span class="text-foreground font-bold">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-col items-start space-y-1">
                                    <span class="line-clamp-1 font-semibold text-sm text-foreground">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-muted">{{\Morilog\Jalali\Jalalian::fromDateTime($comment->created_at)->ago() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 sm:mr-0 mr-auto">
                                <!-- Pending Badge -->
                                <!-- Status Badges -->
                                @if($comment->status === 'pending' && auth()->check() && $comment->user_id === auth()->id())
                                    <span
                                        class="text-xs text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-2 py-1 rounded-full">
                                    در انتظار تایید
                                </span>
                                @elseif($comment->status === 'reported' && auth()->check() && $comment->user_id === auth()->id())
                                    <span
                                        class="text-xs text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-full">
                                    گزارش شده
                                </span>
                                @endif
                                <!-- Report Button -->
                                @if($comment->status === 'published' && auth()->check() && $comment->user_id !== auth()->id())
                                    <button type="button" wire:click="reportComment({{ $comment->id }})"
                                            class="flex items-center h-9 gap-1 bg-secondary rounded-full text-muted transition-colors hover:text-red-500 px-4"
                                            title="گزارش تخلف">
                                        <span class="text-xs">گزارش</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M3.5 2A1.5 1.5 0 002 3.5v13A1.5 1.5 0 003.5 18h13a1.5 1.5 0 001.5-1.5v-13A1.5 1.5 0 0016.5 2h-13zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endif
                                <!-- Like Button -->
                                <button type="button" wire:click="toggleLike({{ $comment->id }})"
                                        class="flex items-center justify-center relative w-9 h-9 bg-secondary rounded-full text-muted transition-colors {{ in_array($comment->id, $likedCommentIds) ? 'text-red-500' : 'hover:text-red-500' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5">
                                        <path
                                            d="m9.653 16.915-.005-.003-.019-.01a20.759 20.759 0 0 1-1.162-.682 22.045 22.045 0 0 1-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 0 1 8-2.828A4.5 4.5 0 0 1 18 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 0 1-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 0 1-.69.001l-.002-.001Z"></path>
                                    </svg>
                                    @if($comment->likes_count > 0)
                                        <span
                                            class="absolute -top-1 -right-1 inline-flex bg-red-500 rounded-full text-xs text-white px-1">
                                        {{ $comment->likes_count }}
                                    </span>
                                    @endif
                                </button>
                            </div>
                        </div>
                        <!-- Comment Body -->
                        <p class="text-sm text-muted leading-7">{{ $comment->comment }}</p>
                    </div>
                    <!-- Admin Reply -->
                    @if($comment->reply)
                        <div
                            class="relative before:content-[''] before:absolute before:-top-3 before:right-8 before:w-px before:h-[calc(100%-24px)] before:bg-border after:content-[''] after:absolute after:bottom-9 after:right-8 after:w-8 after:h-px after:bg-border space-y-3 pr-16">
                            <div class="bg-background border border-primary/30 rounded-3xl space-y-3 p-5">
                                <div class="flex items-center gap-3 border-b border-border pb-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden bg-primary/10 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="w-5 h-5 text-primary">
                                            <path
                                                d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col items-start space-y-1">
                                    <span class="line-clamp-1 font-semibold text-sm text-primary">
                                        {{ $comment->reply->admin->name ?? 'مدیر سایت' }}
                                    </span>
                                        <span class="text-xs text-muted">پاسخ مدیریت</span>
                                    </div>
                                </div>
                                <p class="text-sm text-foreground leading-7">{{ $comment->reply->reply }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-background border border-border rounded-3xl p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" class="w-12 h-12 mx-auto text-muted mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                    </svg>
                    <h5 class="font-semibold text-foreground mb-2">هنوز دیدگاهی ثبت نشده</h5>
                    <p class="text-sm text-muted">اولین نفری باشید که دیدگاه خود را ثبت می‌کنید!</p>
                </div>
            @endforelse
        </div>
        <!-- Load More Button -->
        @if($hasMore)
            <div class="flex justify-center mt-8">
                <button type="button" wire:click="loadMore" wire:loading.attr="disabled"
                        class="h-11 inline-flex items-center justify-center gap-1 bg-secondary rounded-full text-primary px-8 transition-all hover:opacity-80">
                    <span class="font-semibold text-sm" wire:loading.remove wire:target="loadMore">مشاهده بیشتر</span>
                    <span class="font-semibold text-sm" wire:loading wire:target="loadMore">در حال بارگذاری</span>
                    <svg wire:loading wire:target="loadMore" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="w-5 h-5 animate-spin">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="loadMore" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>

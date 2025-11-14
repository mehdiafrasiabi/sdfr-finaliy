<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <!-- user:info -->

            <!-- user:menus -->
            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">
                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اعلانات</div>
                    </div>
                    <!-- end section:title -->

                    <!-- section:notifications:wrapper -->
                    <div class="space-y-5" wire:poll.visible>
                        @forelse($notifications as $notif)
                            <div
                                class="flex md:items-center items-start gap-5 bg-background border border-border rounded-xl p-5">
                                <div class="flex items-center gap-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-warning">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                                    </svg>
                                    <div class="w-px h-4 bg-border"></div>
                                </div>
                                <div class="flex flex-col items-start space-y-1">
                                    <div class="font-bold text-xs text-foreground">
                                        {{$notif->title}}
                                    </div>
                                    <div class="font-medium text-xs text-muted">
                                        {{$notif->body}}
                                    </div>
                                    <div class="flex items-center gap-1 font-medium text-xs text-muted">
                                        <p wire:ignore>
                                            {{ Date::parse($notif->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                @if(!$notif->is_read)
                                    <button wire:click="markAsRead({{ $notif->id }})"
                                            class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                                        <span wire:loading.remove class="font-semibold text-xs">خوانده نشده</span>
                                        <div wire:loading>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px"
                                                 height="40px"
                                                 style="shape-rendering: auto; display: block; background: transparent;">
                                                <g>
                                                    <path stroke="none" fill="#ffffff"
                                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                                                          repeatCount="indefinite" dur="0.8130081300813008s"
                                                                          type="rotate" attributeName="transform"/>
                                                    </path>
                                                    <g/>
                                                </g>
                                            </svg>
                                        </div>
                                    </button>
                                @else
                                    <button
                                        class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-green-500/20 rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                                        <span class="font-semibold text-xs">خوانده شده</span>
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center space-y-12">
                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                     alt="..."/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">
                                        نوتیفیکیشنی وجود ندارد
                                    </h2>
                                </div>
                            </div>
                        @endforelse

                    </div>
                    <!-- end section:notifications:wrapper -->
                </div>
                <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                    {{$notifications->links('layouts.client.pagination')}}
                </div>
            </div>
        </div>
    </div>
</div>

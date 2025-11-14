<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <!-- user:info -->

                <!-- end user:info -->

                <!-- user:menus -->
                <livewire:client.profile.sidebar />
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
                            <div class="font-black text-foreground">تیکت های من</div>

                            <a wire:navigate href="{{route('client.profile.ticket.create')}}"
                               class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                                <span class="font-semibold text-xs">ایجاد تیکت</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                            </a>
                        </div>
                        <!-- end section:title -->

                        <!-- section:tickets:wrapper -->
                        <div class="space-y-5">
                            <!-- table container -->
                            <div class="relative @if($tickets->isNotEmpty()) overflow-x-auto @endif">
                                <!-- table -->
                                <table class="min-w-full divide-y">
                                    <!-- table:thead -->
                                    <thead>
                                    @if($tickets->isNotEmpty())
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">
                                                شماره تیکت
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">
                                                عنوان تیکت
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">
                                                وضعیت
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">
                                                دپارتمان
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">
                                                زمان ثبت تیکت
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 whitespace-nowrap text-start text-xs font-semibold text-muted">

                                            </th>
                                        </tr>

                                    @endif
                                    </thead><!-- end table:thead -->

                                    <!-- table:tbody -->
                                    <tbody class="divide-y">
                                    <!-- table:row -->
                                    @forelse($tickets as $ticket)
                                        <tr class="even:bg-secondary/50 odd:bg-background hover:bg-secondary">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-xs text-muted">
                                                #{{$ticket->ticket_number}}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-xs text-muted">
                                                {{$ticket->title}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div
                                                    class="flex items-center gap-3 @if($ticket->status=='waiting') text-yellow-500 @elseif($ticket->status=='answered') text-green-500  @elseif($ticket->status=='closed') text-red-500 @endif before:inline-block before:w-1.5 before:h-1.5 before @if($ticket->status=='waiting') bg-yellow-500 @elseif($ticket->status=='answered') bg-green-500/20  @elseif($ticket->status=='closed') bg-red-500/20 @endif ">
                                                    <span class="font-semibold text-xs">
                                                           @if($ticket->status=='waiting')
                                                            در انتظار پاسخ ادمین
                                                        @elseif($ticket->status=='answered')
                                                            پاسخ داده شده
                                                        @elseif($ticket->status=='closed')
                                                            بسته شده
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-xs text-muted">
                                                {{$ticket->department->name ?? '-'}}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-xs text-muted">
                                                {{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <a wire:navigate href="{{ route('client.profile.ticket.show', $ticket->id) }}"
                                                       class="inline-flex items-center gap-x-1 text-cyan-400"
                                                       wire:navigate>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 16 16" fill="currentColor"
                                                             class="size-4">
                                                            <path fill-rule="evenodd"
                                                                  d="M11.013 2.513a1.75 1.75 0 0 1 2.475 2.474L6.226 12.25a2.751 2.751 0 0 1-.892.596l-2.047.848a.75.75 0 0 1-.98-.98l.848-2.047a2.75 2.75 0 0 1 .596-.892l7.262-7.261Z"
                                                                  clip-rule="evenodd" />
                                                        </svg>
                                                        <span
                                                            class="whitespace-nowrap font-semibold text-xs">مشاهده</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="flex flex-col items-center justify-center space-y-12">
                                            <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                                            <div class="text-center space-y-3">
                                                <h2 class="font-bold text-xl text-foreground">
                                                    تیکتی  برای شما وجود ندارد.
                                                </h2>
                                            </div>
                                        </div>
                                    @endforelse

                                    <!-- end table:row -->

                                    </tbody><!-- end table:tbody -->
                                </table>
                                <!-- end table -->
                            </div>
                            <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                {{$tickets->links('layouts.client.pagination')}}
                            </div>
                            <!-- end table container -->
                        </div>
                        <!-- end section:tickets:wrapper -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

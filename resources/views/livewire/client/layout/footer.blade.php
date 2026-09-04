<div>
    <!-- Start FlowChat Widget -->
{{--    <script>--}}
{{--        window.flowchatSettings = {--}}
{{--            chatbot_id: "6a773c2a4074cb2779d85858",--}}
{{--        };--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        (()=>{var t=window,e=document;if(!t.FlowChat){var a=function(){for(var t=[],e=0;e<arguments.length;e++)t[e]=arguments[e];a.storeArguments(t)};a.commandQueue=[],a.storeArguments=function(t){a.commandQueue.push(t)},t.FlowChat=a;var n=function(){var n,o=e.createElement("script");o.type="text/javascript",o.async=!0,o.src="https://widget.flowchat.tech/assets/script.js",o.onload=function(){a.commandQueue.forEach((function(e){t.FlowChat.apply(t,e)}))};var r=e.getElementsByTagName("script")[0];null===(n=r.parentNode)||void 0===n||n.insertBefore(o,r)};"complete"===e.readyState?n():t.attachEvent?t.attachEvent("onload",n):t.addEventListener("load",n,!1)}})();--}}
{{--    </script>--}}
    <!-- End FlowChat Widget -->

    <footer class="pt-20 {{ Route::is('client.profile*') ? 'hidden md:block' : '' }}">
        <div class="max-w-7xl px-4 mx-auto">
            <div class="flex items-center gap-3">
                <div class="flex-grow border-t border-border border-dashed"></div>
                <button type="button"
                        class="flex-shrink-0 h-11 inline-flex items-center gap-3 bg-secondary rounded-full text-foreground transition-colors hover:text-primary px-4"
                        id="scrollToTopBtn">
                    <span class="text-xs">برگشت به بالا</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
                    </svg>
                </button>
            </div>
            <div class="flex lg:flex-nowrap flex-wrap gap-8 py-5">
                <div class="md:w-5/12 w-full">
                    <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">
                        <span class="flex flex-col items-start">
                              <img src="/client/assets/images//favicon.svg" style="width: 40px">
                        </span>
                    </a>
                </div>
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-8">
                <div class="md:w-5/12 w-full">
                    <div class="bg-secondary rounded-3xl space-y-5 p-8">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">دربــــاره</div>
                        </div>
                        <p class="font-semibold text-sm text-muted">
                            {{ $settings->site_description ??
 'SDFR، اولین سامانه هوشمند مشاوره و آنالیز دقیق تحصیلی
 در ایران! با صرفه جویی در وقت و هزینه، پشتیبانی تحصیلی
 روزانه و ابزار های حرفه ای و هوشمند آموزشی حس پیشرفت در آزمون های تشریحی و تستی را تجربه کنید!' }}
                        </p>
                    </div>
                </div>
                <div class="md:w-7/12 w-full">
                    <div class="grid sm:grid-cols-5 gap-8">
                        <div class="sm:col-span-2 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">لینک های مفید</div>
                            </div>
                            <ul class="flex flex-col space-y-1">
                                <li>
                                    <a href="{{route('client.terms')}}"
                                       class="inline-flex font-semibold text-sm text-muted hover:text-primary">
                                        قوانین و مقررات
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('client.about-us')}}"
                                       class="inline-flex font-semibold text-sm text-muted hover:text-primary">
                                        درباره ما
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('client.contact-us')}}"
                                       class="inline-flex font-semibold text-sm text-muted hover:text-primary">
                                        ارتباط با ما
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="sm:col-span-3 space-y-5">
                            <div class="space-y-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                    </div>
                                    <div class="font-black text-foreground">نشان های اعتماد</div>
                                </div>
                                <div class="text-sm text-muted flex flex-wrap gap-3">
                                    @if($settings?->enamad_script)
                                        {!! $settings->enamad_script !!}
                                    @endif
                                    @if($settings?->samandehi_script)
                                        {!! $settings->samandehi_script !!}
                                    @endif
                                    @if($settings?->etehaddiye_script)

                                        {!! $settings->etehaddiye_script !!}
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                    </div>
                                    <div class="font-black text-foreground">شبکه های اجتماعی</div>
                                </div>
                                <ul class="flex flex-wrap items-center gap-5">
                                    @if($settings?->instagram)
                                        <li>
                                            <a href="{{ $settings->instagram }}"
                                               class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round" class="w-5 h-5">
                                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                                </svg>
                                            </a>
                                        </li>
                                    @endif
                                    @if($settings?->telegram)
                                        <li>
                                            <a href="{{ $settings->telegram }}"
                                               class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                                                     viewBox="0 0 24 24" fill="#000000">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                          d="M21.997 12C21.997 17.5228 17.5198 22 11.997 22C6.47415 22 1.99699 17.5228 1.99699 12C1.99699 6.47715 6.47415 2 11.997 2C17.5198 2 21.997 6.47715 21.997 12ZM12.3553 9.38244C11.3827 9.787 9.43876 10.6243 6.52356 11.8944C6.05018 12.0827 5.8022 12.2669 5.77962 12.4469C5.74147 12.7513 6.12258 12.8711 6.64155 13.0343C6.71214 13.0565 6.78528 13.0795 6.86026 13.1038C7.37085 13.2698 8.05767 13.464 8.41472 13.4717C8.7386 13.4787 9.10009 13.3452 9.49918 13.0711C12.2229 11.2325 13.629 10.3032 13.7172 10.2831C13.7795 10.269 13.8658 10.2512 13.9243 10.3032C13.9828 10.3552 13.977 10.4536 13.9708 10.48C13.9331 10.641 12.4371 12.0318 11.6629 12.7515C11.4216 12.9759 11.2504 13.135 11.2154 13.1714C11.137 13.2528 11.0571 13.3298 10.9803 13.4038C10.506 13.8611 10.1502 14.204 11 14.764C11.4083 15.0331 11.7351 15.2556 12.0611 15.4776C12.4171 15.7201 12.7722 15.9619 13.2317 16.2631C13.3487 16.3398 13.4605 16.4195 13.5694 16.4971C13.9837 16.7925 14.3559 17.0579 14.8158 17.0155C15.083 16.991 15.359 16.7397 15.4992 15.9903C15.8305 14.2193 16.4817 10.382 16.6322 8.80081C16.6454 8.66228 16.6288 8.48498 16.6154 8.40715C16.6021 8.32932 16.5743 8.21842 16.4731 8.13633C16.3533 8.03911 16.1683 8.01861 16.0856 8.02C15.7095 8.0267 15.1324 8.22735 12.3553 9.38244Z"
                                                           stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </li>
                                    @endif
                                        <li>
                                            <a href="https://web.bale.ai/@sdfr_me"
                                               class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 999.7"
                                                     class="w-5 h-5 text-white">
                                                    <g id="File">
                                                        <g>
                                                            <path fill="currentColor"
                                                                  d="M514,1011.85q-23.87,0-49-2.42c-30.39-2.35-62.93-8.66-98.82-19.23l-22-6.47-.16-1.1C198,932.06,80.66,810.7,34.86,661,24,626.11,17.21,591.57,14.6,558.43c-2.82-25.29-2.57-50.07-2.32-74,.08-8,.16-15.94.12-23.9-.23-25.23-.23-50.7-.08-76.16-.15-25.61-.15-51.71,0-77.82-.11-16.34-.09-33.13-.08-49.9V239.94c0-8.12,0-16.15,0-24.17,0-14.3,0-28.62.15-42.9-.19-14.14-.17-28.28-.15-42.42q0-11.19,0-22.41C10.2,79.7,20.8,52.3,40.81,34.46c23-20.8,56.7-27.85,85.6-17.78,12.23,4.13,22.14,10.44,30.88,16l4.6,2.91c28.76,18.7,55.75,38.17,79.76,56.09,10.29-6.72,20.61-12.85,31.09-18.5a477.66,477.66,0,0,1,45.46-22c15.67-6.54,31.91-12.41,49.87-18l3.66-1c16.56-4.56,33.69-9.28,52.11-11.93a401.11,401.11,0,0,1,61.56-7.18,452.33,452.33,0,0,1,76.5,1.75A414.55,414.55,0,0,1,619.17,24C747.67,51.09,862.22,130.89,933.5,243A490.75,490.75,0,0,1,1000,402.84c5.46,24.22,8.56,44.62,9.72,63.9a418.71,418.71,0,0,1,.72,80.39c-.86,20-3.07,37.52-6.71,53.28-1.78,15.87-5.92,30.76-9.58,44L992.85,649c-5.36,20.35-13.31,40.5-19.68,56.69l-.45,1.07c-5.82,13.48-12.54,27.39-21.78,45.13l-.65,1.18c-8.38,14.71-16.23,27.45-24,39-9.31,13.69-19.52,27.23-30.43,40.38-10.23,12-22.19,25.66-35.56,38.34a502.07,502.07,0,0,1-50.93,43.19A453.61,453.61,0,0,1,761,945.46c-20.35,12.11-40.93,21.41-58.42,28.9a547.3,547.3,0,0,1-66.11,22l-9.19,2.09c-16.06,3.62-32.66,7.37-50.51,8.88A422.74,422.74,0,0,1,514,1011.85ZM408.37,927.42A380,380,0,0,0,471.25,938q52.22,5,95.87-1.73l2.68-.31c13.2-1,27-4.15,41.66-7.45l7.25-1.62a473.12,473.12,0,0,0,56.19-18.68c14.83-6.37,32.9-14.5,49.8-24.59l1-.56a382.18,382.18,0,0,0,41.11-26.83A428.3,428.3,0,0,0,810.65,819c11.28-10.7,21.65-22.54,30.36-32.77,9.17-11.06,18-22.78,26-34.49,6.56-9.72,13.36-20.77,20.72-33.64,8.12-15.62,14-27.79,19-39.31,5.93-15.05,12.61-32.05,16.94-48.5l1.41-5.1c3.38-12.16,6.57-23.64,7.56-34.17l.87-5.15c2.92-12,4.68-25.9,5.36-42.57l.11-1.67a347.17,347.17,0,0,0-.61-68l-.15-1.87c-.88-15.52-3.48-32.47-8.19-53.36A419.67,419.67,0,0,0,873.07,281.5C812.86,186.81,712.27,116.74,604,94l-.94-.21a345.57,345.57,0,0,0-48.23-7.67,383.17,383.17,0,0,0-65.33-1.5l-1,0a331,331,0,0,0-51.74,6.09l-2,.33c-14.19,2-28.67,6-44,10.19l-1.87.51c-15.6,4.9-29.63,10-42.87,15.49a411.55,411.55,0,0,0-38.88,18.79,361,361,0,0,0-36.39,22.44,110.18,110.18,0,0,1-13.63,8.57l-20.25,10.82L218.55,164c-27.61-21-60.5-45.43-95.08-67.93l-4.64-2.92c-5.84-3.72-11.35-7.22-15.68-8.69-4.57-1.57-10.65-.11-14.47,3.32-3.62,3.24-5.49,9.27-4.92,15.52l.15,3.13c0,8,0,16.08,0,24.13,0,13.94,0,27.87.15,41.81v1c-.19,14.09-.17,28.21-.15,42.31,0,8.14,0,16.28,0,24.4v16.6c0,16.61,0,33.21.08,49.82-.15,26.28-.15,52.1,0,77.85-.15,25.53-.15,50.63.08,75.67,0,8.47,0,16.79-.12,25.11-.24,23-.46,44.76,1.94,65.89l.12,1.28c2.16,28,8,57.47,17.35,87.6,40.53,132.48,151.57,242.73,282.94,281Z"
                                                                  transform="translate(-12 -12.15)"/>
                                                            <path fill="currentColor"
                                                                  d="M450.91,763.62h-1.64l-1.86-.07c-30.64-2-59-14.32-79.85-34.82L226.68,587.91c-15.23-15.06-26.55-35-32.59-57.67l-.52-2.24c-6.22-31.24-1.49-62.59,13.33-88.29,10.95-19.33,28.06-36.07,49.33-48.18l2.55-1.32a132.12,132.12,0,0,1,71.16-11.84l1.17.15c27.68,4.15,53,17,71.36,36.21l12.88,12.93q18.86,18.9,37.75,37.8c9.76-9.46,19.27-19,28.29-28.47l1.37-1.36c8.64-8.12,17.61-17.1,28.2-28.25l2.17-2.09c3.9-3.47,7.88-7.54,12.07-11.84,2.07-2.1,4.13-4.21,6.21-6.28,10.9-10.61,21.24-21.11,31.57-31.6,8.8-8.52,16.78-16.6,24.78-24.68l4.93-5c6.6-6.44,12.68-12.55,18.76-18.65,4.26-4.28,8.52-8.57,12.81-12.82a130.68,130.68,0,0,1,51.9-32.23l1.32-.41a131.24,131.24,0,0,1,76.76,1.55L756,264c31.37,12,57.4,36.8,71.4,68l1,2.41c10.69,29.28,11,60.73.82,88.56-7,18.68-18.81,35.75-35.25,51Q779.33,488.38,764.83,503l-10.22,10.19q-10.7,10.68-21.35,21.39l-10,10c-6.82,6.8-13.64,13.58-20.43,20.41L689.5,578.29q-9.88,9.87-19.74,19.77l-15.2,15.18q-11.25,11.21-22.47,22.47l-52.2,52.19q-9.74,9.69-19.41,19.41c-3.45,3.33-6.31,6.26-9.18,9.19-8.13,8.28-17.33,17.68-28.53,25.87l-2.1,1.42A130.77,130.77,0,0,1,450.91,763.62ZM263.63,512.87c2.8,9.84,7.48,18.19,13.56,24.21L418,677.82c8.07,7.91,20.14,13.11,33.07,14.13a59.34,59.34,0,0,0,30.42-8.18c6.11-4.66,12.21-10.87,18.64-17.44,3.32-3.38,6.64-6.77,10.05-10.06,6-6.06,12.56-12.59,19.1-19.11l52-52c7.5-7.56,15.08-15.11,22.66-22.67l15-15c6.56-6.61,13.22-13.26,19.88-19.92l13.2-13.2c6.83-6.86,13.74-13.74,20.65-20.63l9.73-9.7c7-7.12,14.32-14.38,21.59-21.64l10.06-10C724,442.45,734,432.46,744,422.54l.89-.85C753.24,414,759,406,762,398.07c4.14-11.36,4-24.87-.48-37.93a57.93,57.93,0,0,0-30.25-28.91,61.11,61.11,0,0,0-33.45-.7A59.47,59.47,0,0,0,675,345.06c-4.46,4.43-8.6,8.59-12.75,12.76-6.33,6.36-12.66,12.71-19.07,19l-4.45,4.51c-8.38,8.45-16.74,16.91-25.3,25.18-10.08,10.24-20.79,21.1-31.73,31.75-1.53,1.53-3.37,3.41-5.2,5.3-4.56,4.65-9.26,9.46-14.56,14.27-10.85,11.38-20.23,20.76-29.38,29.39-10.06,10.5-20.66,21.14-31.53,31.62l-1.24,1.28c-7,7.21-14.27,14.68-21.94,22L452.63,566l-24.69-24.45Q396.13,510,364.61,478.26l-13.83-13.9c-7.31-7.71-17.83-13-29.69-14.87a60.62,60.62,0,0,0-30.67,5.05c-9.44,5.62-16.79,12.76-21.31,20.72C263.1,485.7,261.17,499.27,263.63,512.87Z"
                                                                  transform="translate(-12 -12.15)"/>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </li>
                                    @if($settings?->youtube)
                                        <li>
                                            <a href="{{ $settings->youtube }}"
                                               class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round" class="w-5 h-5">
                                                    <path
                                                        d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                                                    </path>
                                                    <path d="m10 15 5-3-5-3z"></path>
                                                </svg>
                                            </a>
                                        </li>
                                    @endif
                                    @if($settings?->aparat)
                                        <li>
                                            <a href="{{ $settings->aparat }}"
                                               class="flex items-center justify-center w-12 h-12 bg-secondary rounded-full text-foreground transition-colors hover:text-primary">

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000000"
                                                     class="w-5 h-5"
                                                     viewBox="0 0 24 24" role="img">
                                                    <path fill="currentColor"
                                                          d="M12.001 1.594c-9.27-.003-13.913 11.203-7.36 17.758a10.403 10.403 0 0 0 17.76-7.355c0-5.744-4.655-10.401-10.4-10.403zM6.11 6.783c.501-2.598 3.893-3.294 5.376-1.103 1.483 2.19-.422 5.082-3.02 4.582A2.97 2.97 0 0 1 6.11 6.783zm4.322 8.988c-.504 2.597-3.897 3.288-5.377 1.096-1.48-2.192.427-5.08 3.025-4.579a2.97 2.97 0 0 1 2.352 3.483zm1.26-2.405c-1.152-.223-1.462-1.727-.491-2.387.97-.66 2.256.18 2.04 1.334a1.32 1.32 0 0 1-1.548 1.053zm6.198 3.838c-.501 2.598-3.893 3.293-5.376 1.103-1.484-2.191.421-5.082 3.02-4.583a2.97 2.97 0 0 1 2.356 3.48zm-1.967-5.502c-2.598-.501-3.293-3.896-1.102-5.38 2.19-1.483 5.081.422 4.582 3.02a2.97 2.97 0 0 1-3.48 2.36zM13.59 23.264l2.264.61a3.715 3.715 0 0 0 4.543-2.636l.64-2.402a11.383 11.383 0 0 1-7.448 4.428zm7.643-19.665L18.87 2.97a11.376 11.376 0 0 1 4.354 7.62l.65-2.459A3.715 3.715 0 0 0 21.231 3.6zM.672 13.809l-.541 2.04a3.715 3.715 0 0 0 2.636 4.543l2.107.562a11.38 11.38 0 0 1-4.203-7.145zM10.357.702 8.15.126a3.715 3.715 0 0 0-4.547 2.637l-.551 2.082A11.376 11.376 0 0 1 10.358.702z"/>
                                                </svg>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 py-5">
                <p class="text-xs text-muted">&#169; کليه حقوق محفوظ است</p>
                <div class="flex-grow border-t border-border border-dashed"></div>
            </div>
        </div>
    </footer>
</div>

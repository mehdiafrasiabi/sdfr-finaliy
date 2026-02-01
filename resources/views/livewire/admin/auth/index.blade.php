<div>
    <div class="row g-0 min-vh-100">
        <div class="col-xl-5 col-lg-6 ms-auto px-sm-4 align-self-center py-4 d-none d-lg-block">
            <img alt="" class="img-fluid" src="/admin/assets/images/auth/vector2.svg"/>
        </div>
        <div class="col-xl-5 col-lg-6 ms-auto px-sm-4 align-self-center py-4">
            <div class="card card-body p-4 p-sm-5 maxw-450px m-auto rounded-4">
                <div class="mb-4 text-center">
                    <a aria-label="NexLink logo" href="./index.html">
                        <img alt="NexLink logo" class="visible-light" src="/admin/assets/images/logo.svg"/>
                    </a>
                </div>
                <div class="text-center mb-4">
                    <h5 class="mb-1">
                        به SDFR خوش آمدید
                    </h5>
                    <p>
                        برای دسترسی به داشبورد مدیریت امن خود وارد سیستم شوید.
                    </p>
                </div>
                <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label" for="email">
                            ایمیل یا نام کاربری
                        </label>

                        <div class="position-relative">
                            <input
                                class="form-control @error('email') is-invalid @enderror"
                                dir="ltr"
                                id="email"
                                name="email"
                                wire:model="email"
                                placeholder="ایمیل یا نام کاربری خود را وارد کنید"
                                type="text"
                                autofocus
                            />

                            @error('email')
                            <span
                                class="position-absolute top-50 translate-middle-y"
                                style="left: 12px;"
                            >
                <i class="ti ti-alert-circle text-danger" style="font-size:18px;"></i>
            </span>
                            @enderror
                        </div>

                        @error('email')
                        <div class="form-text text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <!-- Mobile -->
                    <div class="mb-4">
                        <label class="form-label" for="mobile">
                            موبایل
                        </label>

                        <div class="position-relative">
                            <input
                                class="form-control @error('mobile') is-invalid @enderror"
                                dir="ltr"
                                id="mobile"
                                name="mobile"
                                wire:model="mobile"
                                placeholder="09123456789"
                                type="tel"
                            />

                            @error('mobile')
                            <span
                                class="position-absolute top-50 translate-middle-y"
                                style="left: 12px;"
                            >
                <i class="ti ti-alert-circle text-danger" style="font-size:18px;"></i>
            </span>
                            @enderror
                        </div>

                        @error('mobile')
                        <div class="form-text text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <!-- Password -->
                    <div class="mb-4">
                        <label class="form-label" for="password">
                            رمز عبور
                        </label>

                        <div class="password-wrapper position-relative">
                            <input
                                class="form-control password-input @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                wire:model="password"
                                placeholder="********"
                                type="password"
                            />

                            @error('password')
                            <span
                                class="position-absolute top-50 translate-middle-y"
                                style="left: 45px;"
                            >
                <i class="ti ti-alert-circle text-danger" style="font-size:18px;"></i>
            </span>
                            @enderror

                            <!-- Toggle Password Button -->
                            <button
                                aria-label="Show password"
                                aria-pressed="false"
                                class="toggle-password"
                                id="togglePassword"
                                title="Show password"
                                type="button"
                            >
                                <i aria-hidden="true" class="close fi fi-rr-eye-crossed"></i>
                                <i aria-hidden="true" class="open fi fi-rr-eye"></i>
                            </button>
                        </div>

                        @error('password')
                        <div class="form-text text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <!-- Session Error Message -->
                    @if(session()->has('message'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <span class="alert-icon text-danger me-2">
                <i class="ti ti-ban ti-xs"></i>
            </span>
                            {{session('message')}}
                        </div>
                    @endif


                    <!-- Submit Button -->
                    <div class="mb-3">
                        <button class="btn btn-primary waves-effect waves-light w-100" type="submit">
                            لاگین
                        </button>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>

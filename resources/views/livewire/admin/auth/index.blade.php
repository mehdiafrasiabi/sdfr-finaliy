<div class="card-body">
    <!-- Logo -->
    <div class="app-brand justify-content-center mb-4 mt-2">
        <a class="app-brand-link gap-2" href="https://sdfr.me">

            <span class="app-brand-text demo text-body fw-bolder">SDFR</span>
        </a>
    </div>
    <!-- /Logo -->
    <h4 class="mb-1 pt-2">به پنل ادمین  خوش آمدید! 👋</h4>
    <p class="mb-4">لطفا با حساب کاربری خود وارد شوید تا از امکانات سامانه استفاده کنید.</p>
    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
        <div class="mb-3">
            <label class="form-label" for="email">ایمیل یا نام کاربری</label>
            <input autofocus class="form-control" dir="ltr" id="email" name="email" wire:model="email"
                   placeholder="ایمیل یا نام کاربری خود را وارد کنید" type="text"/>
            @error('email')
            <div class="form-text text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="mobile">موبایل</label>
            <input autofocus class="form-control" dir="ltr" id="mobile" name="mobile" wire:model="mobile"
                   placeholder="09123456789" type="tel"/>
            @error('mobile')
            <div class="form-text text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">رمز عبور</label>
                <a href="#">
                    <small>فراموش کرده‌اید؟</small>
                </a>
            </div>
            <div class="input-group input-group-merge">
                <input aria-describedby="password" class="form-control" wire:model="password" id="password"
                       name="password"
                       placeholder="············" type="password"/>
                <span class="input-group-text cursor-pointer">
                                    <i class="ti ti-eye-off"></i>
                                </span>

            </div>
            @error('password')
            <div class="form-text text-danger">{{$message}}</div>
            @enderror
        </div>
        @if(session()->has('message'))

            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <span class="alert-icon text-danger me-2">
                                            <i class="ti ti-ban ti-xs"></i>
                                        </span>
                {{session('message')}}
            </div>
        @endif
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">ورود به سیستم</button>
        </div>
    </form>

    <div class="divider my-4">
    </div>
</div>

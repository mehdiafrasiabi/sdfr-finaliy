<div class="container mx-auto align-self-center">

    <div class="row">

        <div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
            <div class="auth-cover-bg-image"></div>
            <div class="auth-overlay"></div>

            <div class="auth-cover">

                <div class="position-relative">

                    <img src="/admin/src/assets/img/auth-cover.svg" alt="auth-img">
                    <h2 class="mt-5 text-white font-weight-bolder px-2">مدیر عزیز به پنل مدیریت خوش آمدی!</h2>
                    <p class="text-white px-2">ادب پیرایه‌ نادان و داناست
                        <br>
                        خوش آن کو از ادب خود را بیاراست</p>
                </div>

            </div>

        </div>

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
            <div class="card">
                <div class="card-body">

                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <a href="{{route('client.home')}}">
                                    <img src="/client/assets/images/theme/intro/header.png" style="width: 219px; margin-right: 111px;margin-bottom: 55px;">
                                </a>
                                <h2>ورود به پنل مدیریت SDFR</h2>
                                <p>مدیران عزیز !
                                    <br>
                                    لطفا نام کاربری و رمزعبور خود را وارد کنید.</p>

                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">نام کاربری :</label>
                                    <input type="text" class="form-control" name="email" wire:model="email">
                                    @error('email')
                                    <div class="text-danger" style="margin-top: 5px">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">تلفن همراه :</label>
                                    <input type="tel" class="form-control" name="mobile" wire:model="mobile">
                                    @error('mobile')
                                    <div class="text-danger" style="margin-top: 5px">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label">رمز عبور :</label>
                                    <input type="password" class="form-control" name="password" wire:model="password">
                                    @error('password')
                                    <div class="text-danger" style="margin-top: 5px">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            @if(session()->has('message'))
                                <div class="text-center text-white alert  text-danger bg-danger">{{session('message')}}</div>
                            @endif
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-success w-100">ورود</button>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>

    </div>

</div>

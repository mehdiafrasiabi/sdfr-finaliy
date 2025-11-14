<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="index.html" class="d-inline-block auth-logo">
                            <img src="/manager/assets/images/logo-light.png" alt="" height="100">
                        </a>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">پنل مدیران</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">مدیر عزیز خوش آمدی!</h5>
                        </div>
                        @if(session()->has('message'))

                            <div class="alert alert-danger alert-dismissible alert-additional fade show" role="alert">
                                <div class="alert-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-user-smile-line label-icon"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="alert-heading">شما دسترسی لازم را ندارید!</h5>
                                            <p class="mb-0">لطفا هرچه سریع تر خارج شوید </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="p-2 mt-4">
                            <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

                                <div class="mb-3">
                                    <label for="email" class="form-label">نام کاربری</label>
                                    <input type="text" class="form-control" id="email" placeholder="نام کاربری را وارد کنید" wire:model="email" name="email">
                                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                                </div>
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">تلفن</label>
                                    <input type="tel" class="form-control" id="mobile" placeholder="" wire:model="mobile" name="mobile">
                                    @error('mobile') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password-input">رمز عبور</label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input type="password" class="form-control pe-5 password-input" name="password" placeholder="رمز عبور را وارد کنید" id="password-input" wire:model="password">
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                    @error('password') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                                </div>



                                <div class="mt-4 text-center">
                                            <button type="submit" class="btn btn-success">
                                                <span wire:loading.remove>ورود</span>
                                                <span wire:loading="">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                 preserveAspectRatio="xMidYMid" width="30" height="30"
                                                 style="shape-rendering: auto; display: block; background: transparent;"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink"><g><circle
                                                        stroke-linecap="round" fill="none"
                                                        stroke-dasharray="50.26548245743669 50.26548245743669"
                                                        stroke="#ffffff"
                                                        stroke-width="8" r="32" cy="50" cx="50">
                                              <animateTransform values="0 50 50;360 50 50" keyTimes="0;1"
                                                                dur="0.6097560975609756s" repeatCount="indefinite"
                                                                type="rotate"
                                                                attributeName="transform"></animateTransform>
                                            </circle><g></g></g><!-- [ldio] generated by https://loading.io -->
                                            </svg>
                                        </span>
                                            </button>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->



            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

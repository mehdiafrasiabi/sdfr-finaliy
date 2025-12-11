<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
                    <h4 class="card-title mb-0">خبرنامه</h4>

                    <div class="d-flex align-items-center gap-3 flex-wrap">

                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#newsletterExportModal">
                            <i class="ri-download-2-fill align-middle me-1"></i>
                            خروجی اکسل
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link {{ $tab === 'all' ? 'active' : '' }}"
                                    type="button"
                                    wire:click="setTab('all')">
                                همه
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link {{ $tab === 'phone' ? 'active' : '' }}"
                                    type="button"
                                    wire:click="setTab('phone')">
                                شماره موبایل
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link {{ $tab === 'email' ? 'active' : '' }}"
                                    type="button"
                                    wire:click="setTab('email')">
                                ایمیل
                            </button>
                        </li>
                    </ul>


                    <div class="tab-content pt-3">
                        <div class="tab-pane fade {{ $tab === 'all' ? 'show active' : '' }}">

                        <div class="table-responsive table-card">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th style="width: 60px">#</th>

                                        <th>شماره موبایل</th>
                                        <th>ایمیل</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($allContacts as $user)
                                        <tr wire:key="all-{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->mobile ?? '---' }}</td>
                                            <td>{{ $user->email ?? '---' }}</td>
                                            <td>{{ jalali($user->created_at)->format('%d %B %Y | H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">موردی برای نمایش وجود ندارد.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="tab-pane fade {{ $tab === 'phone' ? 'show active' : '' }}">

                        <div class="table-responsive table-card">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th style="width: 60px">#</th>

                                        <th>شماره موبایل</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($mobileContacts as $user)
                                        <tr wire:key="mobile-{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->mobile ?? '---' }}</td>
                                            <td>{{ jalali($user->created_at)->format('%d %B %Y | H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">شماره موبایلی ثبت نشده است.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade {{ $tab === 'email' ? 'show active' : '' }}">

                        <div class="table-responsive table-card">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th style="width: 60px">#</th>

                                        <th>ایمیل</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($emailContacts as $user)
                                        <tr wire:key="email-{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->email ?? '---' }}</td>
                                            <td>{{ jalali($user->created_at)->format('%d %B %Y | H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">ایمیلی ثبت نشده است.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newsletterExportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">خروجی اکسل خبرنامه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="export" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="exportType" id="exportEmail"
                                   value="email" wire:model="exportType">
                            <label class="form-check-label" for="exportEmail">
                                 ایمیل‌
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportType" id="exportMobile"
                                   value="mobile" wire:model="exportType">
                            <label class="form-check-label" for="exportMobile">
                                 شماره موبایل‌
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">دانلود</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div>

    <div class="grid grid-cols-3 gap-4">

    </div>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">لیست پروژه</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:%20void(0);">پروژه ها</a></li>
                            <li class="breadcrumb-item active">لیست پروژه</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row g-4 mb-3">
            <div class="col-sm-auto">
                <div>
                    <a href="apps-projects-create.html" class="btn btn-success"><i
                                class="ri-add-line align-bottom me-1"></i>افزودن جدید</a>
                </div>
            </div>
            <div class="col-sm">
                <div class="d-flex justify-content-sm-end gap-2">
                    <div class="search-box ms-2">
                        <input type="text" class="form-control" placeholder="جستجو...">
                        <i class="ri-search-line search-icon"></i>
                    </div>

                    <div class="col-xxl-1 col-sm-4 choices">
                        <select class="choices__inner mb-3" wire:model.live.debounce.500ms="userId">
                            <option value="">همه</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach(['todo' => 'برای انجام', 'in_progress' => 'در حال انجام', 'done' => 'انجام‌شده'] as $status => $label)
                <div class="bg-gray-100 p-3 rounded-lg shadow">
                    <h2 class="font-bold mb-3 text-center text-warning">{{ $label }}</h2>

                    @foreach($tasks->where('status', $status) as $task)
                        <div class="col-xxl-3 col-sm-6 project-card">
                            <div class="card card-height-100">
                                <div class="card-body">
                                    <div class="d-flex flex-column h-100">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-4">  {{ Date::parse($task->created_at)->diffForHumans() }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <button type="button" class="btn avatar-xs mt-n1 p-0 favourite-btn">
                                                        <span class="avatar-title bg-transparent fs-15">
                                                            <i class="ri-star-fill"></i>
                                                        </span>
                                                    </button>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                 class="feather feather-more-horizontal icon-sm">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="19" cy="12" r="1"></circle>
                                                                <circle cx="5" cy="12" r="1"></circle>
                                                            </svg>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @if($task->status !== 'todo')
                                                                <div wire:click="updateStatus({{ $task->id }}, 'todo')" >
                                                                    <a class="dropdown-item" href="apps-projects-overview.html"><i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted text-primary"></i>
                                                                        برای انجام
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            @if($task->status !== 'in_progress')
                                                                <div wire:click="updateStatus({{ $task->id }}, 'in_progress')" >
                                                                    <a class="dropdown-item" href="apps-projects-overview.html"><i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted text-warning"></i>
                                                                        درحال انجام
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            @if($task->status !== 'done')
                                                                <div wire:click="updateStatus({{ $task->id }}, 'done')" >
                                                                    <a class="dropdown-item" href="apps-projects-overview.html"><i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted text-green-500"></i>
                                                                         انجام شده
                                                                    </a>
                                                                </div>
                                                            @endif

                                                            <a class="dropdown-item" href="apps-projects-create.html"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>ویرایش
                                                                کنید</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                               data-bs-target="#removeProjectModal"><i
                                                                    class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>حذف
                                                                کنید</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-warning-subtle rounded p-2">
                                                        <img src="/manager/assets/images/brands/slack.png" alt=""
                                                             class="img-fluid p-1">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fs-16">
                                                    <a href="#" class="text-body">
                                                        {{$task->title}}
                                                    </a>
                                                </h5>
                                                <p class="text-muted text-truncate-two-lines mb-3">
                                                    {{ Str::limit($task->description, 50) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="d-flex mb-2">
                                                <div class="flex-grow-1">
                                                    <div>وظایف</div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div><i class="ri-list-check align-bottom me-1 text-muted"></i>18/42</div>
                                                </div>
                                            </div>
                                            <div class="progress progress-sm animated-progress">
                                                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="34"
                                                     aria-valuemin="0" aria-valuemax="100" style="width: 34%;"></div>
                                                <!-- /.progress-bar -->
                                            </div><!-- /.progress -->
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-2 flex gap-1">

                                </div>
                                <!-- end card body -->
                                <div class="card-footer bg-transparent border-top-dashed py-2">
                                    <div class="d-flex align-items-center">
                                            <div class="avatar-group">
                                                <a href="javascript:%20void(0);" class="av                                        <div class="flex-grow-1">
                                                atar-group-item"
                                                   data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                                   aria-label="{{ $task->assignee?->name ?? '---' }}" data-bs-original-title="{{ $task->assignee?->name ?? '---' }}">
                                                    <div class="avatar-xxs">
                                                        <img src="/manager/assets/images/users/avatar-2.jpg" alt=""
                                                             class="rounded-circle img-fluid">
                                                    </div>
                                                </a>
                                                <a href="javascript:%20void(0);" class="avatar-group-item"
                                                   data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                                   data-bs-original-title="Add Members">
                                                    <div class="avatar-xxs">
                                                        <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                                                            +
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="text-muted">
                                                <i class="ri-calendar-event-fill me-1 align-bottom">

                                                </i>
                                                {{jalali($task->deadline)->format('%d %B %Y | H:i')}}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- end card footer -->
                            </div>
                            <!-- end card -->
                        </div>
                    @endforeach
                </div>
            @endforeach


            <!-- end col -->

        </div>
        <!-- end row -->



    </div>
</div>

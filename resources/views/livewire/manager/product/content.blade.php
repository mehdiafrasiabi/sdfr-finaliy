<div>
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        محتوا محصول :
                        <span style="color: #f50000">{{$productName}}</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">

            <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                <div class="row mb-4">
                    <div class="col-sm-12" wire:ignore>
                        <label for="description" class="form-label">توضیحات تخصصی:</label>
                        <sup style="color: red">*</sup>
                        <textarea class="form-control" id="description" wire:model="description"
                                  name="description"></textarea>
                    </div>
                </div>
                @error('description')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert" wire:loading.remove
                     wire:loading.remove>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <strong>خطا !</strong> <br>
                    {{$message}}
                </div>
                @enderror

                <div class="text-left">
                    <a href="{{route('manager.product.index')}}" class="btn btn-danger _effect--ripple waves-effect waves-light">بازگشت</a>
                    <button type="submit" class="btn btn-success _effect--ripple waves-effect waves-light">ذخیره</button>
                </div>
            </form>


        </div>

        @push('script')
            <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
            <script>
                document.addEventListener('livewire:init', () => {

                    const editor=CKEDITOR.replace('description',{
                        filebrowserUploadUrl: "{{route('manager.ck-upload', [$productId,'_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        contentsLangDirection: 'rtl',
                        height: 500,
                    })
                    editor.on('change',function (event) {
                        console.log(event.editor.getData());
                    @this.set('description',event.editor.getData())
                    })
                })

            </script>

        @endpush
    </div>

</div>

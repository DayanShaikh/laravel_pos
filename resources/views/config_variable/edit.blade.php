<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 ">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">Edit Configuration Types</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" me-3 my-3 text-end">
                            <a href="{{route('config_variable.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{ route('config_variable.update', $config->id) }}" class="text-start">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-outline is-filled form-select mb-4">
                                    <label class="form-label">Configuration Type <span class="login-danger"> *</span></label>
                                    <select class="form-control pt-5 px-2" name="config_type_id">
                                        <option value="">Select Configuration Type </option>
                                        @foreach($ctype as $ctypes)
                                        <option value="{{$ctypes->id}}" @if($ctypes->id == $config->config_type_id) selected @endif>{{$ctypes->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('config_type_id')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->title) null is-filled @endif mt-4">
                                    <label class="form-label">Title <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control px-2" name="title" value="{{ $config->title }}">
                                </div>
                                @error('title')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->notes) null is-filled @endif mt-4">
                                    <label class="form-label">Notes <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control px-2" name="notes" value="{{ $config->notes }}">
                                </div>
                                @error('notes')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->type) is-filled @endif form-select mt-4">
                                    <label class="form-label">Type</label>
                                    <select class="form-control pt-2 px-2" name="type">
                                        <option value="">Select Type</option>
                                        <option value="text" @if($config->type == 'text') selected @endif>Text</option>
                                        <option value="checkbox" @if($config->type == 'checkbox') selected @endif>Checkbox</option>
                                        <option value="radio" @if($config->type == 'radio') selected @endif>Radio</option>
                                        <option value="textarea" @if($config->type == 'textarea') selected @endif>Textarea</option>
                                        <option value="editor" @if($config->type == 'editor') selected @endif>Editor</option>
                                        <option value="file" @if($config->type == 'file') selected @endif>File</option>
                                        <option value="combobox" @if($config->type == 'combobox') selected @endif>Combobox</option>
                                    </select>
                                </div>
                                @error('type')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->default_values) null is-filled @endif mt-4">
                                    <label class="form-label">Default Values (seprated by semi-colon ';') <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control px-2" name="default_values" value="{{ $config->default_values }}">
                                </div>
                                @error('default_values')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->key) null is-filled @endif mt-4">
                                    <label class="form-label">Key <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control px-2" name="key" value="{{ $config->key }}">
                                </div>
                                @error('key')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->value) null is-filled @endif mt-4">
                                    <label class="form-label">Value <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control px-2" name="value" value="{{ $config->value }}">
                                </div>
                                @error('value')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline @if($config->sortorder) null is-filled @endif mt-4">
                                    <label class="form-label">Sortorder <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control px-2" name="sortorder" value="{{ $config->sortorder }}">
                                </div>
                                @error('sortorder')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-primary w-100 my-4 mb-2" type="submit" data-target="successToast">Submit</button>
                                </div>
                                {{-- <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
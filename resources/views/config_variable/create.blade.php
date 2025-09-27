<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 ">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">Add Configuration Types</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" me-3 my-3 text-end">
                            <a href="{{route('config_variable.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('config_variable.store') }}" class="text-start">
                                @csrf
                                <div class="input-group input-group-outline is-filled form-select mt-3">
                                    {{-- <label class="form-label">Configuration Type</label> --}}
                                    <select class="form-control ps-3 py-0" name="config_type_id">
                                        <option value="">Select Configuration Type </option>
                                        @foreach($ctype as $ctypes)
                                        <option value="{{$ctypes->id}}" @if(old('config_type_id')==$ctypes->id) selected @endif>{{$ctypes->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('config_type_id')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Title <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                                </div>
                                @error('title')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Notes </label>
                                    <input type="text" class="form-control" name="notes" value="{{ old('notes') }}">
                                </div>
                                @error('notes')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline null is-filled form-select mt-3">
                                    {{-- <label class="form-label">Type</label> --}}
                                    <select class="form-control ps-3 py-0" name="type">
                                        <option value="">Select Type</option>
                                        <option value="text" @if(old('type')=='text' ) selected @endif>Text</option>
                                        <option value="checkbox" @if(old('type')=='checkbox' ) selected @endif>Checkbox</option>
                                        <option value="radio" @if(old('type')=='radio' ) selected @endif>Radio</option>
                                        <option value="textarea" @if(old('type')=='textarea' ) selected @endif>Textarea</option>
                                        <option value="editor" @if(old('type')=='editor' ) selected @endif>Editor</option>
                                        <option value="file" @if(old('type')=='file' ) selected @endif>File</option>
                                        <option value="combobox" @if(old('type')=='combobox' ) selected @endif>Combobox</option>
                                    </select>
                                </div>
                                @error('type')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Default Values (seprated by semi-colon ';') </label>
                                    <input type="text" class="form-control" name="default_values" value="{{ old('default_values') }}">
                                </div>
                                @error('default_values')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Key <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control" name="key" value="{{ old('key') }}">
                                </div>
                                @error('key')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Value</label>
                                    <input type="text" class="form-control" name="value" value="{{ old('value') }}">
                                </div>
                                @error('value')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Sortorder</label>
                                    <input type="text" class="form-control" name="sortorder" value="{{ old('sortorder') }}">
                                </div>
                                @error('sortorder')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-info w-100 my-4 mb-2" type="submit" data-target="successToast">Submit</button>
                                </div>
                                {{-- <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Sign in</button>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

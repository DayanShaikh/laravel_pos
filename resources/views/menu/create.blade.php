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
                                        <h6 class="text-white text-capitalize ps-3">Add Menu</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('menu.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('menu.store') }}" class="text-start" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-outline mt-3">
                                    <select class="form-control ps-3 py-0 select_multiple" name="" multiple="multiple" style="display:none">
                                        <option value="">Select Role</option>
                                        @foreach($role as $roles)
                                        <option value="{{ $roles->id }}">{{ $roles->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group input-group-outline mt-3 @if(old('title')) null is-filled @endif">
                                    <label class="form-label">Title <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                                </div>
                                @error('title')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 @if(old('url')) null is-filled @endif">
                                    <label class="form-label">Url <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                                </div>
                                @error('url')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline is-filled form-select mt-3">
                                    <select class="form-control ps-3 py-0" name="parent_id">
                                        <option value="">No Parent</option>
                                        @foreach($menu as $menus)
                                        <option value="{{ $menus->id }}">{{ $menus->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group input-group-outline mt-3 @if(old('small_icon')) null is-filled @endif">
                                    <label class="form-label">Small Icon url</label>
                                    <input type="text" class="form-control" name="small_icon" value="{{old('small_icon')}}">
                                </div>
                                <div class="input-group input-group-outline mt-3 null is-filled">
                                    <label class="form-label">Icon</label>
                                    <input type="file" class="form-control" name="icon">
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-primary w-100 my-4 mb-2 p-3" type="submit" data-target="successToast">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 ">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">Add Roles</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" me-3 my-3 text-end">
                            <a href="{{route('role.index')}}" class="btn bg-gradient-dark"><i class="material-icons">arrow_back</i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{ route('role.store') }}" class="text-start">
                                @csrf
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                @error('name')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="card-body">
                                    <div class="form-group">
                                        @foreach($menus as $key => $menu)
                                        @if($menu->model_name != null)
                                        <div class="card-title">
                                            <strong>{{$menu->title}}</strong>
                                            <div class="SelectAllPermissions">
                                                <div>
                                                    <label class="check-wrap">
                                                        <input type="checkbox" id="select-all{{$key}}">
                                                        <span class="custom-box"></span>
                                                    </label>
                                                    <label class="form-check-label" for="select-all{{$key}}" style="vertical-align: middle;">
                                                        Select All
                                                    </label>
                                                </div>
                                                <div class="d-flex permissions_checked">
                                                    @foreach(App\Utility::$permissions as $permissionKey => $permission)
                                                    <div class="">
                                                        <label class="check-wrap">
                                                            <input type="checkbox" name="permissions[{{ $menu->model_name }}][]" value="{{ strtolower($permission) }}">
                                                            <span class="custom-box"></span>
                                                        </label>
                                                        <label class="form-check-label" for="permission{{$key}}{{$permissionKey}}" style="vertical-align: middle;">
                                                            {{$permission}}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-info w-100 my-4 mb-2" type="submit" data-target="successToast">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

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
                                        <h6 class="text-white text-capitalize ps-3">Edit User</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" me-3 my-3 text-end">
                            <a href="{{route('user.index')}}" class="btn bg-gradient-dark"><i class="material-icons">arrow_back</i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('user.update', $user->id) }}" class="text-start">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-outline null is-filled mt-3">
                                    <label class="form-label">Name <span class="login-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                                @error('name')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline null is-filled mt-3">
                                    <label class="form-label">Email <span class="login-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                                @error('email')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" name="password" value="{{ old('password') }}">
                                </div>
                                @error('password')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline is-filled form-select mt-3">
                                    <select class="form-control ps-3 py-0 select2" name="roles[]" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if($user->userRoles->contains($role->id)) selected @endif>{{$role->title}}</option>
                                        @endforeach
                                    </select>
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

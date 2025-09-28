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
                                        <h6 class="text-white text-capitalize ps-3">Add New Account</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('account.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{route('account.store') }}" class="text-start">
                                @csrf

                                <div class="input-group input-group-outline mt-3 null is-filled">
                                    <label  class="form-label">Title </label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                                    <br>
                                </div>
                                    @error('title')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                <div class="input-group input-group-outline mt-3 form-select">
                                    <select name="type" id="action" class="form-control">
                                        <option value="">Select Account Type</option>
                                        <option value="1">Current Asset</option>
                                        <option value="2">Fixed Asset</option>
                                        <option value="3">Capital</option>
                                    </select>
                                    @error('type')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if(old('payment')) null is-filled @endif">
                                        <label class="form-label">Pyament</label> 
                                        <input type="number" class="form-control" name="payment" value="{{ old('payment') }}">
                                    </div>
                                    @error('payment')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if(old('description')) null is-filled @endif">
                                        <label class="form-label">Description </label> 
                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}">
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12">
                                        <button class="btn bg-gradient-info w-100 my-4 mb-2 p-3" type="submit" data-target="successToast">Submit</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

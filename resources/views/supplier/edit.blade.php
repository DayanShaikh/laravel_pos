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
                                        <h6 class="text-white text-capitalize ps-3">Add Item</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('supplier.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('supplier.update', $supplier->id) }}" class="text-start">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-outline mt-3 @if($supplier->name) null is-filled @endif">
                                    <label class="form-label">Name <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ $supplier->name }}">
                                </div>
                                @error('name')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 @if($supplier->phone) null is-filled @endif">
                                    <label class="form-label">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $supplier->phone }}">
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($supplier->address) null is-filled @endif">
                                    <label class="form-label">Address </label>
                                    <input type="text" class="form-control" name="address" value="{{ $supplier->address }}">
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($supplier->balance) null is-filled @endif">
                                    <label class="form-label">Balance</label>
                                    <input type="text" class="form-control" name="balance" value="{{ $supplier->balance }}">
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-primary w-100 my-4 mb-2" type="submit" data-target="successToast">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

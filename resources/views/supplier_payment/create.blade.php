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
                                        <h6 class="text-white text-capitalize ps-3">Add Supplier Payment</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('supplier.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('supplier_payment.store') }}" class="text-start">
                                @csrf
                                <div class="input-group input-group-outline mt-3 form-select">
                                    <select name="supplier_id" id="action" class="form-control">
                                        <option value="0">Select Suppliers</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" @if($supplier_id==$supplier->id) selected @endif>{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if(old('phone')) null is-filled @endif">
                                        <label class="form-label">Phone </label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    <div class="input-group input-group-outline mt-3 @if(old('address')) null is-filled @endif">
                                        <label class="form-label">Address </label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                                    </div>
                                    <div class="input-group input-group-outline mt-3 @if(old('balance')) null is-filled @endif">
                                        <label class="form-label">Balance </label>
                                        <input type="text" class="form-control" name="balance" value="{{ old('balance')}} 0">
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

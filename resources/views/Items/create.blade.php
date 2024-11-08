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
                            <a href="{{route('item.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('item.store') }}" class="text-start">
                                @csrf
                                {{-- <div class="input-group input-group-outline is-filled form-select mt-3">
                                    <select class="form-control ps-3 py-0" name="item_category_id">
                                        <option value="">Select Item Category </option>
                                        @foreach($item_category as $item_c_id)
                                        <option value="{{$item_c_id->id}}" @if(old('config_type_id')==$item_c_id->id) selected @endif>{{$item_c_id->title}}</option>
                                @endforeach
                                </select>
                        </div> --}}
                        <div class="input-group input-group-outline mt-3 @if(old('title')) null is-filled @endif">
                            <label class="form-label">Title <span class="login-danger"> *</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                        </div>
                        @if (session()->has('error1'))
                        <strong>{{ session()->get('error1') }}</strong>
                        @endif
                        <div class="input-group input-group-outline mt-3 @if(old('unti_price')) null is-filled @endif">
                            <label class="form-label">Unit Price </label>
                            <input type="text" class="form-control" name="unit_price" value="{{ old('unit_price') }}">
                        </div>
                        @error('unit_price')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        <div class="input-group input-group-outline mt-3 @if(old('sale_price')) null is-filled @endif">
                            <label class="form-label">Sale Price </label>
                            <input type="text" class="form-control" name="sale_price" value="{{ old('sale_price') }}">
                        </div>
                        @error('sale_price')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        <div class="input-group input-group-outline mt-3 @if(old('quantity')) null is-filled @endif">
                            <label class="form-label">Quantity</label>
                            <input type="text" class="form-control" name="quantity" value="{{ old('quantity') }} 0">
                        </div>
                        @error('quantity')
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

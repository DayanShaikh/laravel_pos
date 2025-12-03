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
                                        <h6 class="text-white text-capitalize ps-3">Add Item</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('item.index')}}" class="btn bg-gradient-dark"><i class="material-icons">arrow_back</i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('item.update', $item->id) }}" class="text-start">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-outline is-filled form-select mt-3">
                                    {{-- <label class="form-label">Configuration Type</label> --}}
                                    <select class="form-control ps-3 py-0" name="item_category_id">
                                        <option value="">Select Item Category </option>
                                        @foreach($item_category as $item_c_id)
                                        <option value="{{$item_c_id->id}}" @if($item->item_category_id==$item_c_id->id) selected @endif>{{$item_c_id->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($item->title) null is-filled @endif">
                                    <label class="form-label">Title <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ $item->title }}">
                                </div>
                                @error('title')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 @if($item->unit_price) null is-filled @endif">
                                    <label class="form-label">Unit Price </label>
                                    <input type="text" class="form-control" name="unit_price" value="{{ $item->unit_price }}">
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($item->sale_price) null is-filled @endif">
                                    <label class="form-label">Sale Price </label>
                                    <input type="text" class="form-control" name="sale_price" value="{{ $item->sale_price }}">
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($item->quantity) null is-filled @endif">
                                    <label class="form-label">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" value="{{ $item->quantity>0?$item->quantity:'' }}">
                                </div>
                                <div class="input-group input-group-outline mt-3 @if($item->barcode) null is-filled @endif">
                                    <label class="form-label">Barcode</label>
                                    <input type="text" class="form-control" name="barcode" value="{{ $item->barcode }}">
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

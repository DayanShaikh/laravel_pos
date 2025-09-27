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
                                        <h6 class="text-white text-capitalize ps-3">Add Item</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('item.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('expense_category.update',$detail->id) }}" class="text-start">
                                @csrf
                                @method("PUT")
                                {{-- <div class="input-group input-group-outline is-filled form-select mt-3">
                                    <select class="form-control ps-3 py-0" name="item_category_id">
                                        <option value="">Select Item Category </option>
                                        @foreach($item_category as $item_c_id)
                                        <option value="{{$item_c_id->id}}" @if(old('config_type_id')==$item_c_id->id) selected @endif>{{$item_c_id->title}}</option>
                                @endforeach
                                </select>
                        </div> --}}
                        <div class="input-group input-group-outline mt-3 @if($detail->title) null is-filled @endif">
                            <label class="form-label">Title <span class="login-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ $detail->title }}">
                        </div>
                        @error('title')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        
                        <div class="col-lg-1 col-sm-6 col-12">
                            <button class="btn bg-gradient-info w-100 my-4 mb-2 p-3" type="submit" data-target="successToast">Submit</button>
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

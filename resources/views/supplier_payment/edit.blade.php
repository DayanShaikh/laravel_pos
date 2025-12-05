<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">Add Supplier Payment</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('supplier.index')}}" class="btn bg-gradient-dark"><i class="material-icons">arrow_back</i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('supplier_payment.update',$detail->id) }}" class="text-start">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-outline mt-3">
                                    <select name="supplier_id" class="form-select select2">
                                        <option value="0">Select Suppliers</option>
                                        @foreach($supplier as $suppliers)
                                            <option value="{{$suppliers->id}}" @if ($suppliers->id==$detail->supplier_id) selected @endif>{{$suppliers->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if($detail->date) null is-filled @endif">
                                        <label for="datepicker" class="form-label">Date </label>
                                        <input type="date" class="form-control" name="date" value="{{ $detail->date }}" autocomplete="off" onclick="this.showPicker()">
                                    </div>
                                    @error('date')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if($detail->payment) null is-filled @endif">
                                        <label class="form-label">Payment </label>
                                        <input type="number" class="form-control" name="payment" value="{{$detail->payment }}">
                                        <br>
                                    </div>
                                    @error('payment')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3 @if($detail->details) null is-filled @endif">
                                        <label class="form-label">Detail </label>
                                        <textarea rows="5" class="form-control" name="detail" value="{{$detail->details}}"></textarea>
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

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
                                        <h6 class="text-white text-capitalize ps-3">Add Supplier Payment</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('supplier_payment.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{  route('supplier_payment.store') }}" class="text-start">
                                @csrf
                                <div class="form-group">
                                    <select name="supplier_id" class="form-select select2">
                                        <option value="0">Select Suppliers</option>
                                        @foreach($supplier as $suppliers)
                                            <option value="{{$suppliers->id}}">{{$suppliers->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('supplier_id')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 null is-filled ">
                                    <label for="datepicker" class="form-label">Date </label>
                                    <input type="date" class="form-control" name="date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" onclick="this.showPicker()">
                                </div>
                                @error('date')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3">
                                    <label class="form-label">Payment </label>
                                    <input type="number" class="form-control" name="payment" value="{{ old('payment') }}">
                                    <br>
                                </div>
                                @error('payment')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 null is-filled">
                                    <label class="form-label">Detail </label>
                                    <textarea name="detail" class="form-control border px-2" rows="5"></textarea>
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

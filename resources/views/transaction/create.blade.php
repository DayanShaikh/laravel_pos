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
                                        <h6 class="text-white text-capitalize ps-3">Add New Transaction</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{ route('transaction.index') }}" class="btn bg-gradient-dark"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{ route('transaction.store') }}"
                                class="text-start">
                                @csrf
                                <div class="input-group input-group-outline mt-3 form-select">
                                    <select name="account_id" id="action" class="form-control">
                                        <option value="">Account To </option>
                                        @foreach ($account as $accounts)
                                            <option value="{{ $accounts->id }}">{{ $accounts->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('account_id')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="input-group input-group-outline mt-3 form-select">
                                    <select name="refrence_id" id="action" class="form-control">
                                        <option value="">Account From </option>
                                        @foreach ($account as $accounts)
                                            <option value="{{ $accounts->id }}">{{ $accounts->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('refrence_id')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                
                                


                                <div class="input-group input-group-outline mt-3 null is-filled ">
                                    <label for="datepicker" class="form-label">Date </label>
                                    <input type="text" id="datepicker" class="form-control" name="date" value="" autocomplete="off">
                                </div>
                                @error('date')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div
                                    class="input-group input-group-outline mt-3 @if (old('amount')) null is-filled @endif">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount"
                                        value="{{ old('amount') }}">
                                </div>
                                @error('amount')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div
                                    class="input-group input-group-outline mt-3 @if (old('detail')) null is-filled @endif">
                                    <label class="form-label">Detail </label>
                                    <input type="text" class="form-control" name="detail"
                                        value="{{ old('detail') }}">
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-info w-100 my-4 mb-2 p-3" type="submit"
                                        data-target="successToast">Submit</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
</x-layout>

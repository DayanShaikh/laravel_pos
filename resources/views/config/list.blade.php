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
                                        <h6 class="text-white text-capitalize ps-3">Configuration</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            {{-- <strong>This Is testing</strong> --}}
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                    <div class="card-body p-0 px-3">
                        @foreach($config_type as $config_types)
                        <h4 class="mt-3">{{$config_types->title}}</h4>
                            <form method="POST" action="{{ route('config.store' , $config_types->id) }}" enctype="multipart/form-data">
                                @csrf
                                @foreach($config as $configs)
                                    @if($configs->type == 'file')
                                        <div class="input-group input-group-outline mt-3 null is-filled">
                                            <label class="form-label">{{$configs->title}}</label>
                                            <input type="{{$configs->type}}" name="{{$configs->type."_".$configs->id}}" class="form-control">
                                        </div>
                                        @error($configs->type)
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    @elseif($configs->type == 'textarea')
                                        <div class=" mt-3 null ">
                                            <label class="form-label">{{$configs->title}}</label>
                                            <textarea name="{{$configs->type."_".$configs->id}}" class="form-control border px-2" cols="10" rows="5">{{ $configs->value }}</textarea>
                                        </div>
                                        @error($configs->type)
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    @elseif($configs->type != 'textarea' && $configs->type != 'file')
                                        <div class="input-group input-group-outline @if($configs->value) null is-filled @endif mt-3 null ">
                                            <label class="form-label">{{$configs->title}}</label>
                                            <input type="{{$configs->type}}" class="form-control" name="{{$configs->type."_".$configs->id}}" value="{{ $configs->value }}">
                                        </div>
                                        @error($configs->type)
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    @endif
                                @endforeach
                                <div class="col-lg-1 col-sm-6 col-12">
                                    <button class="btn bg-gradient-primary w-100 my-4 mb-2" type="submit" data-target="successToast">Submit</button>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
</x-layout>

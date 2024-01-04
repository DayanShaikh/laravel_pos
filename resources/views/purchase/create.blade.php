<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4" ng-app="purchase" ng-controller="purchaseController" id="purchaseController">
            <div class="row">
                <div class="col-12 ">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">Add Purchase</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a href="{{route('item.index')}}" class="btn bg-gradient-dark"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body p-0 px-3">
                            <form role="form" method="POST" action="{{route('item.store') }}" class="text-start">
                                @csrf
                                <div class="input-group input-group-outline mt-3 datepicker-container null is-filled">
                                    <label for="datepicker" class="form-label">date <span class="login-danger"> *</span></label>
                                    <input type="text" class="form-control" id="datepicker" name="date">
                                </div>
                                <div class="input-group input-group-outline is-filled form-select mt-3">
                                    {{-- <label class="form-label">Configuration Type</label> --}}
                                    <select class="form-control ps-3 py-0" name="supplier">
                                        <option value="">Select Supplier </option>
                                        @foreach($supplier as $suppliers)
                                        <option value="{{$suppliers->id}}">{{$suppliers->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-5">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="panel-body table-responsive">
                                                <table class="table table-hover list">
                                                    <thead>
                                                        <tr>
                                                            <th width="2%" class="text-center">S.No</th>
                                                            <th width="25%"></th>
                                                            <th class="text-right"></th>
                                                            <th class="text-right"></th>
                                                            <th class="text-right"></th>
                                                            <th class="text-right"></th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="item in purchase.items">
                                                            <td class="text-center serial_number"></td>
                                                            <td>
                                                                <div class="input-group input-group-outline is-filled form-select">
                                                                    {{-- <label class="form-label">Configuration Type</label> --}}
                                                                    <select class="form-control ps-3 py-0" name="item_category_id">
                                                                        <option value="">Select Items </option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="input-group input-group-outline null is-filled">
                                                                    <label class="form-label">Unit Purchase Price<span class="login-danger">*</span></label>
                                                                    <input type="text" class="form-control" ng-model="purchase.items[ $index ].purchase_price" ng-change="update_total( $index )" />
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="input-group input-group-outline null is-filled">
                                                                    <label class="form-label">Unit Sale Price<span class="login-danger">*</span></label>
                                                                    <input type="text" class="form-control" ng-model="purchase.items[ $index ].sale_price" ng-change="update_total( $index )" />
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="input-group input-group-outline null is-filled">
                                                                    <label class="form-label">Quantity<span class="login-danger">*</span></label>
                                                                    <input type="text" class="form-control" ng-model="purchase.items[ $index ].quantity" ng-change="update_total( $index )" />
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="input-group input-group-outline null is-filled">
                                                                    <label class="form-label">Total Price<span class="login-danger">*</span></label>
                                                                    <input type="text" class="form-control" ng-model="purchase.items[ $index ].total" ng-change="update_total( $index )" />
                                                                </div>
                                                            </td>
                                                            <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a><span ng-show="purchase.items[ $index ].id>0"> -
                                                                    <a href="" target="_blank">Print</a></span>
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #7a7a7a4a">
                                                            <div class="input-group input-group-outline">
                                                                <th colspan="5" class="text-end">Total Items</th>
                                                                <th class="text-right"></th>
                                                                <th class="text-right">&nbsp;</th>
                                                            </div>
                                                        </tr>
                                                        <hr class="m-0">
                                                        <tr style="border-bottom:1px solid #7a7a7a4a">
                                                            <th colspan="5" class="text-end">Total</th>
                                                            <th class="text-right"><input type="text" class="form-control" ng-model="purchase.total" ng-change='update_net_total()' /></th>
                                                            <th class="text-right">&nbsp;</th>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #7a7a7a4a">
                                                            <th colspan="5" class="text-end">Discount</th>
                                                            <th class="text-right"><input type="text" class="form-control" id="discount" ng-model="purchase.discount" ng-change='update_net_total()' /></th>
                                                            <th class="text-right">&nbsp;</th>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #7a7a7a4a">
                                                            <th colspan="5" class="text-end">Net Total</th>
                                                            <th class="text-right"><input type="text" class="form-control" id="total" ng-model="purchase.net_total" /></th>
                                                            <th class="text-right">&nbsp;</th>
                                                        </tr>
                                                        <tr style="border:1px solid #7a7a7a4a">
                                                            <th class="text-right" colspan="1">Notes</th>
                                                            <th class="text-right" colspan="6"><textarea class="form-control" ng-model="purchase.notes"></textarea></th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

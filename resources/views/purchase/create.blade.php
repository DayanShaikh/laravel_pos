<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4" ng-app="purchase" ng-controller="purchaseController" id="purchaseController">
            <div class="row">
                <div class="col-12 ">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">@{{get_action()}} Purchase</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            <a ng-if="purchase_id" class="btn bg-gradient-dark" href="{{ route("purchase.create")}}" title="Add Purchase"><i class="material-icons text-sm">add</i></a>
                            <a href="{{route('purchase.index')}}" class="btn bg-gradient-dark" title="Back to Purchase list"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        @if(isset($_GET['message']))
                        <div class="alert alert-success alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>@php echo $_GET['message'] @endphp</strong>
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class="card-body p-0 px-3">
                            <div class="input-group input-group-outline mt-3 datepicker-container null is-filled">
                                <label for="datepicker" class="form-label">date <span class="login-danger"> *</span></label>
                                <input type="text" ng-model="purchase.date" data-controllerid="purchaseController" class="form-control" id="datepicker">
                            </div>
                            <div class="input-group input-group-outline is-filled form-select mt-3">
                                <select class="form-control ps-3 py-0" ng-model="purchase.supplier_id" ng-options="supplier.id as supplier.name for supplier in suppliers">
                                    <option value="">Select Supplier </option>
                                    <option ng-repeat="supplier in suppliers" value="@{{ supplier.id }}">@{{ supplier.name }}</option>
                                </select>
                            </div>
                            <div class="input-group input-group-outline is-filled form-select mt-3">
                                <select class="form-control ps-3 py-0" ng-model="purchase.is_return" convert-to-number>
                                    <option value="0">Purchase</option>
                                    <option value="1">Purchase Return</option>
                                </select>
                            </div>
                            <div class="form-group mt-5">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel-body table-responsive">
                                            <table class="table list">
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
                                                        <td class="text-center serial_number">@{{ $index+1 }}</td>
                                                        {{-- <td class="text-center serial_number"></td> --}}
                                                        <td>
                                                            <div class="input-group input-group-outline is-filled form-select">
                                                                {{-- <label class="form-label">Configuration Type</label> --}}
                                                                <select class="form-control ps-3 py-0" ng-model="purchase.items[ $index ].item_id" ng-change="getItems(purchase.items[ $index ].item_id, item)" chosen convert-to-number>
                                                                    <option value="">Select Items </option>
                                                                    <option ng-repeat="item in items" value="@{{ item.id }}">@{{ item.title }} (@{{item.quantity}})</option>
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
                                                            <th class="text-right">@{{ purchase.quantity }}</th>
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
                            <div ng-show="errors" class="alert alert-danger alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                                <strong>@{{errors}}</strong>
                                <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-12">
                                <button class="btn bg-gradient-info w-100 my-4 mb-2" type="submit" data-target="successToast" ng-disabled="processing" ng-click="save_purchase()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i>Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

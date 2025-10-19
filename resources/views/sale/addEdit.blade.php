<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container-fluid py-4" ng-app='saleApp' ng-controller="SaleController">
            <div class="row" ng-init="initSaleData({{ isset($sale) ? $sale : 'null' }})">
                <div class="col-12">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-3">
                                <div class="row">
                                    <div class="col my-xl-2">
                                        <h6 class="text-white text-capitalize ps-3">{{ isset($sale) ? 'Edit sale' : 'Add sale' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mx-3 mt-3 text-end">
                            @if(isset($sale))
                            <a ng-if="purchase_id" class="btn bg-gradient-dark" href="{{route('sale.create')}}" title="Add Sales"><i class="material-icons text-sm">add</i></a>
                            @endif
                            <a href="{{route('sales.index')}}" class="btn bg-gradient-dark" title="Back to Purchase list"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <select class="form-select" select2 ng-model="sale.customer" ng-options="customer.id as customer.name for customer in customers">
                                    <option value="" disabled selected>Select Customer</option>
                                </select>
                                <div class="text-danger" ng-if="error.customer">Please select customer</div>
                            </div>

                            <div class="input-group input-group-outline mt-3 datepicker-container null is-filled">
                                <label for="date" class="form-label">date <span class="login-danger"> *</span></label>
                                <input type="date" name="date" class="form-control" id="date" ng-model="sale.date" onclick="this.showPicker()" />
                                <div class="text-danger" ng-if="error.date">Please select date</div>
                            </div>

                            <div class="form-group mt-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="panel-body table-responsive">
                                            <table class="table list">
                                                <thead>
                                                    <tr>
                                                        <th width="2%" class="text-center">S.No</th>
                                                        <th class="text-right">
                                                            <div class="input-group input-group-outline">
                                                                <label class="form-label">Barcode</label>
                                                                <input type="text" class="form-control" ng-model="item_id" ng-keypress="barcodeScan($event)">
                                                            </div>
                                                            <div class="text-danger" ng-if="ItemError">Item Not found</div>
                                                        </th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="item in sale.items">
                                                        <td>
                                                            @{{$index + 1}}
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-outline is-filled">
                                                                <select class="form-control" select2 ng-options="fetchItem.id as fetchItem.title for fetchItem in items" ng-change="getPrice(item.item_id, $index, true)" ng-model="item.item_id">
                                                                    <option value="" disabled>Select item</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <label class="form-label">Quantity<span class="login-danger">*</span></label>
                                                                <input type="number" id="quantity-input-@{{$index}}" class="form-control" placeholder="Quantity" ng-model="item.quantity" ng-change="item_amount($index)">
                                                            </div>  
                                                            <span ng-if="!quantityAlerterrorMessage[$index] && item.item_id" class="badge m-2" ng-class="{'badge-success': availableQuantity[$index].quantity > 0,'badge-danger': availableQuantity[$index].quantity <= 0}">
                                                                Available Stock: @{{ availableQuantity[$index].quantity}}
                                                            </span>
                                                            <div id="quantity-row-@{{$index}}">
                                                                <div class="text-danger" ng-if="quantityAlerterrorMessage[$index]">Stock not Available</div>
                                                                <div class="text-danger" ng-if="error['items.' + $index + '.quantity']">Please select quantity</div>
                                                                <div class="text-danger" ng-if="quantityErrors">@{{ quantityErrors[$index] }}</div>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <label class="form-label">Price<span class="login-danger">*</span></label>
                                                                <input type="number" class="form-control" ng-disabled="quantityAlerterrorMessage[$index]" placeholder="Price" ng-model="item.price" ng-change="item_amount($index)">
                                                                <div class="text-danger" ng-if="error['items.' + $index + '.price']">Please select price</div>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <label class="form-label">Discount<span class="login-danger">*</span></label>
                                                                <input type="number" class="form-control" ng-disabled="quantityAlerterrorMessage[$index]" placeholder="Discount" ng-model="item.discount" ng-change="item_amount($index)">
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <label class="form-label">Amount<span class="login-danger">*</span></label>
                                                                <input type="number" class="form-control" readonly placeholder="Amount" ng-model="item.amount" ng-change="item_amount($index)">
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <input class="form-check-input" type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="item.is_return" style="height: 1.5em; width: 1.5em;" ng-change="totalPrice()">
                                                            </div>
                                                        </td>
                                                         <td class="text-center">
                                                            <a href=""  ng-click="addItem()">Add</a> - <a href="" ng-click="removeItem($index)">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <div class="input-group input-group-outline">
                                                            <th colspan="5" class="text-end">Sale Total:</th>
                                                            <th class="text-right">@{{ getSaleTotal() }}</th>
                                                            <th class="text-right">&nbsp;</th>
                                                        </div>
                                                    </tr>
                                                    <hr class="m-0">
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="5" class="text-end">Return Total:</th>
                                                        <th class="text-right">
                                                            @{{ getReturnTotal() }}
                                                        </th>
                                                        {{-- <th class="text-right">&nbsp;</th> --}}
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="5" class="text-end">Total Quantity:</th>
                                                        <th class="text-right">@{{totalQuantity()}}</th>
                                                        <th class="text-right">&nbsp;</th>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="5" class="text-end">Discount</th>
                                                        <th class="text-right">
                                                            <input type="number" style="border: 1px solid #c3c3c3; padding: 0 5px; width: 60px;" ng-model="sale.totalDiscount" ng-change="netTotal();">
                                                        </th>
                                                        <th class="text-right">&nbsp;</th>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="5" class="text-end">Net Total</th>
                                                        <th class="text-right">
                                                            @{{sale.netTotal|number:2}} <input type="hidden" readonly class="form-control" ng-model="sale.netTotal">
                                                        </th>
                                                        <th class="text-right">&nbsp;</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="row border-bottom text-end py-2">
                                        <div class="col-12">
                                            <div class="card mt-4 flex-fill ms-2" style="width: 550px; float: inline-end">
                                                <div class="card-header">
                                                    <h5 class="card-title text-center">Payment Details</h5>
                                                </div>
                                                <div class="card-body">
                                                    {{-- <div class="col-12 d-flex flex-wrap justify-content-around align-items-center payment_method">
                                                @foreach($accounts as $account)
                                                <span ng-click="is_active({{$account->id}})" ng-class="sale.payment_method=={{$account->id}}?'toggle_active':''" style="padding:5px 10px; margin:5px;">
                                                    {{$account->title}}
                                                    </span>
                                                    @endforeach
                                                </div> --}}
                                                <b class="mt-3 d-block">Net Total Price</b>
                                                <input type="number" readonly class="form-control" value="@{{sale.netTotal}}" style="width: 100%">
                                                <b class="mt-2 d-block">Received Payment</b>
                                                <input type="number" class="form-control" ng-model="sale.recievedPayment" ng-change="returnPayment()" style="width: 100%">
                                                <b class="mt-2 d-block">Return Payment</b>
                                                <input type="number" readonly class="form-control" value="@{{sale.returnPayment}}" style="width: 100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @can('update',App\Models\Sale::class) --}}
                            <div class="form-group mt-4 text-end">
                                <button class="btn btn-primary" ng-disabled="isButtonEnable==true" ng-click="submitForm()">
                                    {{ isset($sale) ? 'Update' : 'Submit' }}
                                </button>
                            </div>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <script>
        @if(isset($customers))
        let customers = @json($customers);
        @endif
        @if(isset($items))
        let items = @json($items);
        @endif

    </script>
</x-layout>

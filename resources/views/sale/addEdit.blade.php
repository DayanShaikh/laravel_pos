<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <div class="container" ng-app='saleApp'>
            <div class="card py-4" ng-controller="SaleController" ng-init="initSaleData({{ isset($sale) ? $sale : 'null' }})">
                <div class="card-header row">
                    <div class="d-flex align-items-center col-md-6">
                        <div class="card-title">{{ isset($sale) ? 'Edit sale' : 'Add sale' }}</div>
                    </div>
                    <div class="col-md-6 text-end">
                        @if(isset($sale))
                        <a href="{{route('sale.create')}}" class="btn bg-gradient-dark mb-0">
                            <i class="fa fa-plus"></i> Add sale
                        </a>
                        @endif
                        <a href="{{route('sales.index')}}" class="btn bg-gradient-dark mb-0">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <select class="form-select" select2 ng-model="sale.customer" ng-options="customer.id as customer.name for customer in customers">
                            <option value="" disabled selected>Select Customer</option>
                        </select>
                        <div class="text-danger" ng-if="error.customer">Please select customer</div>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" id="date" ng-model="sale.date" onclick="this.showPicker()" />
                        <div class="text-danger" ng-if="error.date">Please select date</div>
                    </div>
                    <div class="container py-5 mt-4">
                        <div class="row align-items-center text-center bg-primary text-white fw-bold py-2 mb-2">
                            <div class="col-1">#</div>
                            <div class="col-2 d-flex align-items-center justify-content-center">
                                Item
                                <div>
                                    <input type="text" placeholder="Scan Barcode" style="margin-left: 20px; width:100%;" ng-model="item_id" ng-keypress="barcodeScan($event)">
                                    <div class="text-danger" ng-if="ItemError">Item Not found</div>
                                </div>
                            </div>
                            <div class="col">Quantity</div>
                            <div class="col">Price</div>
                            <div class="col">Discount</div>
                            <div class="col">Amount</div>
                            <div class="col-1">Return</div>
                            <div class="col-1">Action</div>
                        </div>
                        <div class="row text-center py-3 border-bottom" ng-repeat="item in sale.items">
                            <div class="col-1">@{{$index+1}}</div>
                            <div class="col-2 pt-2">
                                <select class="form-control" select2 ng-options="fetchItem.id as fetchItem.title for fetchItem in items" ng-change="getPrice(item.item_id, $index, true)" ng-model="item.item_id">
                                    <option value="" disabled>Select item</option>
                                </select>
                                <div class="text-danger" ng-if="error['items.' + $index + '.item_id']">Please select item</div>
                            </div>
                            <div class="col">
                                <input type="number" id="quantity-input-@{{$index}}" class="form-control" placeholder="Quantity" ng-model="item.quantity" ng-change="item_amount($index)">
                                <span ng-if="!quantityAlerterrorMessage[$index] && item.item_id" class="badge m-2" ng-class="{'badge-success': availableQuantity[$index].quantity > 0,'badge-danger': availableQuantity[$index].quantity <= 0}">
                                    Available Stock: @{{ availableQuantity[$index].quantity}}
                                </span>
                                <div id="quantity-row-@{{$index}}">
                                    <div class="text-danger" ng-if="quantityAlerterrorMessage[$index]">Stock not Available</div>
                                    <div class="text-danger" ng-if="error['items.' + $index + '.quantity']">Please select quantity</div>
                                    <div class="text-danger" ng-if="quantityErrors">@{{ quantityErrors[$index] }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" ng-disabled="quantityAlerterrorMessage[$index]" placeholder="Price" ng-model="item.price" ng-change="item_amount($index)">
                                <div class="text-danger" ng-if="error['items.' + $index + '.price']">Please select price</div>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" ng-disabled="quantityAlerterrorMessage[$index]" placeholder="Discount" ng-model="item.discount" ng-change="item_amount($index)">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" readonly placeholder="Amount" ng-model="item.amount" ng-change="item_amount($index)">
                            </div>
                            <div class="col-1">
                                <input class="form-check-input" type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="item.is_return" style="height: 1.5em; width: 1.5em;" ng-change="totalPrice()">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-link btn-primary btn-lg p-0" ng-click="addItem()">
                                    <i class="fas fa-plus-square" style="font-size: 25px"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-danger btn-lg p-0" ng-click="removeItem($index)" ng-if="sale.items.length > 1">
                                    <i class="fas fa-minus-square" style="font-size: 25px"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="row border-bottom text-end py-2">
                                <div class="col-10 text-end p-0 text-primary fw-bold">Sale Total:</div>
                                <div class="col-2 text-primary fw-bold">@{{ getSaleTotal() }}</div>
                            </div>
                            <div class="row border-bottom text-end py-2">
                                <div class="col-10 text-end p-0 text-primary fw-bold">Return Total:</div>
                                <div class="col-2 text-primary fw-bold">@{{ getReturnTotal() }}</div>
                            </div>
                            <div class="row border-bottom text-end py-2">
                                <div class="col-10 text-end p-0">Total Quantity:</div>
                                <div class="col-2">@{{totalQuantity()}}</div>
                            </div>
                            <div class="row border-bottom text-end py-2">
                                <div class="col-10 text-end p-0">Discount:</div>
                                <div class="col-2"><input type="number" style="border: 1px solid #c3c3c3; padding: 0 5px; width: 60px;" ng-model="sale.totalDiscount" ng-change="netTotal();"></div>
                            </div>
                            <div class="row border-bottom text-end py-2">
                                <div class="col-10 text-end p-0">Net Total:</div>
                                <div class="col-2">@{{sale.netTotal|number:2}} <input type="hidden" readonly class="form-control" ng-model="sale.netTotal"></div>
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
                    @can('update',App\Models\Sale::class)
                    <div class="form-group mt-4 text-end">
                        <button class="btn btn-primary" ng-disabled="isButtonEnable==true" ng-click="submitForm()">
                            {{ isset($sale) ? 'Update' : 'Submit' }}
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </main>
</x-layout>

<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="tables"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <style>
            .itemBadge {
                padding: 2px 12px;
                color: #fff;
                font-size: 12px;
                border-radius: 10px;
            }

            .modal-animate {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -45%);
                opacity: 0;
                visibility: hidden;
                /* prevents tab focus but keeps element in DOM */
                pointer-events: none;
                /* prevent clicks while hidden */
                transition: opacity 0.25s ease, transform 0.25s ease, visibility 0s linear 0.25s;
                z-index: 10001;
                width: 600px;
                max-width: 90%;
                /* background: white; */
                /* padding: 20px; */
                border-radius: 10px;
                max-height: 90%;
                overflow-y: auto;
            }

            /* visible state */
            .modal-animate.open {
                opacity: 1;
                transform: translate(-50%, -50%);
                visibility: visible;
                pointer-events: auto;
                transition: opacity 0.25s ease, transform 0.25s ease, visibility 0s;
            }

            .backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity 0.25s ease, visibility 0s linear 0.25s;
                z-index: 10000;
            }

            .backdrop.open {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transition: opacity 0.50s ease, visibility 0s;
            }

        </style>
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
                            <a ng-if="purchase_id" class="btn bg-gradient-dark" href="{{route('sales.create')}}" title="Add Sales"><i class="material-icons text-sm">add</i></a>
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
                            <div class="input-group input-group-outline mt-3 null is-filled">
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
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">
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
                                                        <th class="text-right">Return</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="item in sale.items">
                                                        <td class="text-center">
                                                            @{{$index + 1}}
                                                        </td>
                                                        <td class="text-center">
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
                                                            <span ng-if="!quantityAlerterrorMessage[$index] && item.item_id" class="itemBadge m-2" ng-class="{'bg-success': availableQuantity[$index].quantity > 0,'bg-danger': availableQuantity[$index].quantity <= 0}">
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
                                                            </div>
                                                            <div class="text-danger" ng-if="error['items.' + $index + '.price']">Please select price</div>
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
                                                            <div class="form-check check-tables">
                                                                <input class="form-check-input" type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="item.is_return" style="height: 1.5em; width: 1.5em;" ng-change="totalPrice()">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="" ng-click="addItem()">Add</a> <a href="" ng-click="removeItem($index)" ng-if="sale.items.length > 1">- Delete</a>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <div class="input-group input-group-outline">
                                                            <th colspan="6" class="text-end">Sale Total:</th>
                                                            <th class="text-right">@{{ getSaleTotal() }}</th>
                                                            <th class="text-right">&nbsp;</th>
                                                        </div>
                                                    </tr>
                                                    <hr class="m-0">
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="6" class="text-end">Return Total:</th>
                                                        <th class="text-right">
                                                            @{{ getReturnTotal() }}
                                                        </th>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="6" class="text-end">Total Quantity:</th>
                                                        <th class="text-right">@{{totalQuantity()}}</th>
                                                        <th class="text-right">&nbsp;</th>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="6" class="text-end">Discount</th>
                                                        <th class="text-right">
                                                            <div class="input-group input-group-outline null is-filled">
                                                                <label class="form-label">Discount<span class="login-danger">*</span></label>
                                                                <input type="number" class="form-control" placeholder="Discount" ng-model="sale.totalDiscount" ng-change="netTotal();">
                                                            </div>
                                                            {{-- <input type="number" style="border: 1px solid #c3c3c3; padding: 0 5px; width: 60px;" ng-model="sale.totalDiscount" ng-change="netTotal();"> --}}
                                                        </th>
                                                        <th class="text-right">&nbsp;</th>
                                                    </tr>
                                                    <tr style="border-bottom:1px solid #7a7a7a4a">
                                                        <th colspan="6" class="text-end">Net Total</th>
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
                            </div>
                            {{-- @can('update',App\Models\Sale::class) --}}
                            <div class="form-group mt-4">
                                {{-- <button class="btn bg-gradient-info" ng-disabled="isButtonEnable==true" ng-click="submitForm()"> --}}
                                <button class="btn bg-gradient-info" ng-disabled="isButtonEnable==true" ng-click="openPopup()">
                                    {{ isset($sale) ? 'Update' : 'Submit' }}
                                </button>
                            </div>
                            {{-- @endcan --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="backdrop" ng-class="{'open': popupVisible}" ng-show="popupVisible"></div>
            <div class="row modal-animate" ng-class="{'open': popupVisible}" role="dialog" aria-modal="true">
                <div class="col-12">
                    <div class="card mt-4 flex-fill ms-2" style="width: 550px; float: inline-end">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Payment Details</h5>
                            <div class="" ng-click="closePopup()"><i class="fa-solid fa-x"></i></div>
                        </div>
                        <div class="card-body">
                            <div class="payment_method">
                                {{-- @foreach($accounts as $account)
                                    <span ng-click="is_active({{$account->id}})" ng-class="sale.payment_method=={{$account->id}}?'toggle_active':''" style="padding:5px 10px; margin:5px;">
                                {{$account->title}}
                                </span>
                                @endforeach --}}
                            </div>
                            <div class="mt-3 input-group input-group-outline null is-filled">
                                <label class="form-label">Net Total Price</label>
                                <input type="number" class="form-control" placeholder="Net Total Price" value="@{{sale.netTotal}}" readonly>
                            </div>
                            <div class="mt-3 input-group input-group-outline null is-filled">
                                <label class="form-label">Received Payment</label>
                                <input type="number" class="form-control" placeholder="Received Payment" ng-model="sale.recievedPayment" ng-change="returnPayment()">
                            </div>
                            <div class="mt-3 input-group input-group-outline null is-filled">
                                <label class="form-label">Return Payment</label>
                                <input type="number" class="form-control" placeholder="Return Payment" value="@{{sale.returnPayment}}" readonly>
                            </div>
                        </div>
                        <div class="card-header d-flex align-items-center">
                            <button class="btn bg-gradient-info me-2" ng-click="submitForm()">
                                Submit
                                <img src="/assets/img/tube-spinner.svg" ng-if="isLoading" alt="" width="25px">
                            </button>
                            <button class="btn btn-danger" ng-click="submitForm()">
                                Skip
                                <img src="/assets/img/tube-spinner.svg" ng-if="isLoading" alt="" width="25px">
                            </button>
                        </div>
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

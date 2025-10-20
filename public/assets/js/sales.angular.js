let saleApp = angular.module('saleApp', []);
saleApp.directive('select2', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element) {
            $timeout(function () {
                $(element).select2();
            });
        }
    };
});
saleApp.controller('SaleController', function ($scope, $http) {
    $scope.error = {};
    $scope.customers = customers;
    $scope.items = items;
    $scope.item_id = '';
    $scope.insertedQuantity = [];
    // $scope.message = '';
    $scope.quantityErrors = {};
    $scope.quantityAlerterrorMessage = [];
    $scope.isButtonEnable = false;
    $scope.isLoading = false;

    // Initialize purchase data
    $scope.initSaleData = function (saleData) {
        $scope.SaleData = saleData;
        if (saleData) {
            $scope.availableQuantity = saleData.sale_item.map(function (item) {
                return {
                    id: item.item_id,
                    quantity: item.quantity,
                }
            }),
                $scope.sale = {
                    id: saleData.id,
                    customer: saleData.customer_id,
                    date: new Date(saleData.date),
                    totalQuantity: saleData.total_quantity,
                    totalPrice: saleData.total_price,
                    netTotal: saleData.net_total,
                    discount: saleData.total_discount,
                    payment_method: saleData.sale_payment.account_id,
                    returnPayment: saleData.sale_payment.return_payment,
                    recievedPayment: saleData.sale_payment.recieved_payment,
                    items: saleData.sale_item.map(function (item) {
                        return {
                            item_id: item.item_id,
                            quantity: item.quantity,
                            price: item.price,
                            amount: item.quantity * item.price,
                            discount: item.discount,
                            is_return: item.is_return
                        };
                    })
                };
        } else {
            // If creating a new sale
            $scope.quantityerror = null,
                $scope.availableQuantity = [{
                    id: null,
                    quantity: null,
                }],
                $scope.sale = {
                    totalPrice: 0,
                    totalQuantity: 0,
                    totalDiscount: 0.00,
                    netTotal: 0,
                    recievedPayment: 0,
                    returnPayment: 0,
                    customer: null,
                    payment_method: 0,
                    date: new Date,
                    items: [
                        {
                            item_id: null,
                            price: '',
                            quantity: '',
                            amount: null,
                            discount: null,
                            is_return: 0,
                        }
                    ]
                };
        }
    };

    $scope.getPrice = function (item_id, index, isSelect = false) {
        const foundItem = items.find(item => item.id === item_id);
        if (index != -1 && index != 0) {
            const duplicateIndex = $scope.old_sale_item.findIndex((item, i) => item.item_id === item_id);
            if (isSelect && duplicateIndex != -1) {
                $scope.sale.items[duplicateIndex].quantity++;
                if (foundItem.quantity <= $scope.sale.items[duplicateIndex].quantity) {
                    $scope.isButtonEnable = true;
                    $scope.quantityAlerterrorMessage[duplicateIndex] = true;
                    $scope.removeItem(index);
                    return;
                } else {
                    $scope.quantityAlerterrorMessage[duplicateIndex] = false;
                }
                $scope.removeItem(index);
            } else {
                $scope.sale.items[index]['quantity'] = 1;
                setTimeout(() => {
                    if (foundItem.quantity <= $scope.sale.items[index]['quantity']) {
                        $scope.isButtonEnable = true;
                        $scope.quantityAlerterrorMessage[index] = true;
                        return;
                    } else {
                        $scope.quantityAlerterrorMessage[index] = false;
                    }
                }, 0);
            }
        } else {
            $scope.sale.items[index].quantity = 1;
        }

        let availableQuantity = $scope.availableQuantity.find(items => items.id === item_id);
        $scope.sale.items[index]['price'] = foundItem.sale_price;
        if (foundItem.quantity <= 0) {
            $scope.availableQuantity[index] = {
                id: null,
                quantity: null
            };
            $scope.isButtonEnable = true;
            return $scope.quantityAlerterrorMessage[index] = true;
        } else {
            $scope.availableQuantity[index] = {
                id: foundItem.id,
                quantity: foundItem.quantity
            };
            $scope.isButtonEnable = false;
            $scope.quantityAlerterrorMessage[index] = false;
        }

        if (availableQuantity !== undefined) {
            $scope.availableQuantity[index] = {
                id: availableQuantity.id,
                quantity: availableQuantity.quantity
            };
        } else {
            $scope.availableQuantity[index] = {
                id: foundItem.id,
                quantity: foundItem.quantity
            };
        }
        $scope.item_amount(index);
        $scope.netTotal();
    };

    $scope.barcodeScan = function ($event) {
        if ($event.which == 13 && $scope.item_id != '') {
            $scope.itemFilter();
        }
    }

    $scope.itemFilter = function () {
        const itemFind = $scope.items.find(rec => rec.id == $scope.item_id);
        if (!itemFind) {
            $scope.item_id = '';
            return $scope.ItemError = true;
        }
        $scope.ItemError = false;
        const duplicateIndex = $scope.sale.items.findIndex(rec => rec.item_id == itemFind.id);
        if (duplicateIndex !== -1) {
            if ($scope.sale.items[duplicateIndex].quantity < itemFind.quantity) {
                $scope.sale.items[duplicateIndex].quantity += 1;
                if ($scope.sale.items[duplicateIndex].quantity == itemFind.quantity) {
                    $scope.sale.items[duplicateIndex].quantity -= 1;
                    $scope.item_id = '';
                    $scope.isButtonEnable = true;
                    return $scope.quantityAlerterrorMessage[duplicateIndex] = true;
                }
                // $scope.quantityAlerterrorMessage[duplicateIndex] = false;
            } else {
                $scope.item_id = '';
                $scope.isButtonEnable = true;
                return $scope.quantityAlerterrorMessage[duplicateIndex] = true;
            }
            $scope.item_amount(duplicateIndex);
            $scope.getPrice(itemFind.id, duplicateIndex);
        } else if ($scope.sale.items.length == 1 && $scope.sale.items[0].item_id == null) {
            $scope.sale.items[0].item_id = itemFind.id;
            if ($scope.sale.items[0].quantity < itemFind.quantity) {
                $scope.sale.items[0].quantity++;
                // $scope.quantityAlerterrorMessage[0] = false;
            } else {
                $scope.item_id = '';
                $scope.isButtonEnable = true;
                return $scope.quantityAlerterrorMessage[0] = true;
            }
            $scope.item_id = '';
            setTimeout(function () {
                $('select[select2]').trigger('change.select2');
            });

            $scope.getPrice(itemFind.id, 0);
            $scope.item_amount(0);
        }
        else {
            $scope.addItem();
            const index = $scope.sale.items.findIndex(rec => console.log(rec['item_id']));
            // console.log($scope.sale.items, itemFind.id, index);
            const lastindex = $scope.sale.items.length - 1;
            $scope.sale.items[lastindex].item_id = itemFind.id;
            if ($scope.sale.items[lastindex].quantity < itemFind.quantity) {
                $scope.sale.items[lastindex].quantity++;
                // $scope.quantityAlerterrorMessage[lastindex] = false;
            } else {
                $scope.item_id = '';
                $scope.isButtonEnable = true;
                return $scope.quantityAlerterrorMessage[lastindex] = true;
            }
            $scope.item_amount(lastindex);
            $scope.getPrice(itemFind.id, lastindex);
        }
        $scope.item_id = '';
    }

    $scope.onChangeItemsValidation = function (index) {
        const itemFind = $scope.items.find(rec => rec.id == $scope.item_id);
    }
    $scope.stockAlert = function (quantity, index, item_id) {
        const foundItem = items.find(item => item.id === item_id);
        if (foundItem.quantity < quantity) {
            $scope.sale.items[index]['quantity'] = foundItem.quantity;
        }
    }

    $scope.SelectedAccount = function () {
        if ($scope.sale.cashPayment) {
            $scope.sale.cashPayment = 1;
            return $scope.sale.cashPayment;
            console.log($scope.sale.cashPayment);
        }
        if ($scope.sale.onlinePayment) {
            $scope.sale.onlinePayment = 0;
            return $scope.sale.onlinePayment;
        }

    }

    $scope.is_active = function (value) {
        $scope.sale.payment_method = value;
    }
    $scope.addItem = function () {
        $scope.old_sale_item = angular.copy($scope.sale.items);
        $scope.sale.items.push({ item_id: null, price: '', quantity: '', amount: null, discount: null, is_return: 0 });
    };

    $scope.removeItem = function (index) {
        $scope.sale.items.splice(index, 1);
        // $scope.item_amount(index);
    };
    $scope.previousQuantities = [];

    $scope.item_amount = function (index) {
        let currentQuantity = parseInt($scope.sale.items[index].quantity) || 0;
        let prevQuantity = $scope.previousQuantities[index] || 0;

        if (currentQuantity < prevQuantity) {
            $scope.isButtonEnable = true;
            $scope.quantityAlerterrorMessage[index] = true;
            return;
        } else {
            $scope.isButtonEnable = false;
            $scope.quantityAlerterrorMessage[index] = false;
        }
        let diff = currentQuantity - prevQuantity;

        // Stock Update
        if ($scope.availableQuantity[index] && $scope.availableQuantity[index].quantity != null) {
            $scope.availableQuantity[index].quantity -= diff;
        }

        // Save current quantity for next change
        $scope.previousQuantities[index] = currentQuantity;

        // Highlight updated field
        let rowElement = document.querySelector(`#quantity-row-${index}`);
        if (rowElement) {
            rowElement.classList.add('highlight');
            setTimeout(() => {
                rowElement.classList.remove('highlight');
            }, 1000);
        }

        // Amount calculation
        let price = parseFloat($scope.sale.items[index].price || 0);
        let discount = parseFloat($scope.sale.items[index].discount || 0);
        $scope.sale.items[index].amount = (price * currentQuantity) - discount;

        // 👇 Update totals
        $scope.totalQuantity();
        $scope.totalPrice();
        $scope.netTotal();
        $scope.returnPayment();

        return $scope.sale.items[index].amount;
    };



    $scope.totalQuantity = function () {
        if (!$scope.sale || !$scope.sale.items) {
            return 0.00;
        }

        // Calculate the total quantity of items (both return and non-return)
        const allItems = $scope.sale.items.reduce((total, item) => {
            const quantity = parseInt(item.quantity) || 0;
            return total + quantity;
        }, 0);

        // Calculate the total quantity of return items
        const returnQuantity = $scope.sale.items
            .filter(item => item.is_return === 1)
            .reduce((returnTotal, item) => {
                const quantity = parseInt(item.quantity) || 0;
                return returnTotal + quantity;
            }, 0);

        // Store the calculated total quantity
        $scope.sale.totalQuantity = allItems - returnQuantity;

        return ($scope.sale.totalQuantity).toFixed(2);
    };

    $scope.totalPrice = function () {
        if (!$scope.sale || !$scope.sale.items) {
            return 0;
        }

        // Calculate the total amount of all items
        const totalAmount = $scope.sale.items.reduce((total, item) => {
            // const price = parseFloat(item.price) || 0;
            // const quantity = parseInt(item.quantity) || 0;
            const amount = parseInt(item.amount) || 0;
            return total + amount;
        }, 0);

        // Calculate the total amount of return items
        const returnAmount = $scope.sale.items.reduce((returnTotal, item) => {
            const isReturn = item.is_return || 0;
            if (isReturn === 1) {
                // const price = parseFloat(item.price) || 0;
                // const quantity = parseInt(item.quantity) || 0;
                const amount = parseInt(item.amount) || 0;
                return returnTotal + amount;
            }
            return returnTotal;
        }, 0);

        // Subtract the return amount from the total amount
        $scope.sale.totalPrice = (totalAmount - returnAmount).toFixed(2);
        return $scope.sale.totalPrice;
    };
    $scope.returnPayment = function () {
        if ($scope.sale.recievedPayment == 0 || $scope.sale.recievedPayment == null) {
            $scope.sale.returnPayment = 0;
        }
        else {

            $scope.sale.returnPayment = ($scope.sale.recievedPayment || 0) - ($scope.sale.netTotal || 0);
        }
        return $scope.returnPayment;

    }
    $scope.netTotal = function () {
        if ($scope.sale.totalDiscount == 0) {
            $scope.sale.totalDiscount = 0.00;
        }
        $scope.sale.netTotal = ($scope.sale.totalPrice || 0) - ($scope.sale.totalDiscount || 0);
        return $scope.netTotal;
    }

    $scope.submitForm = function () {

        var saleData = angular.copy($scope.sale);

        if (saleData.date) {
            // Ensure date is in YYYY-MM-DD format
            var date = new Date(saleData.date);
            saleData.date = date.getFullYear() + '-' +
                ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                ('0' + date.getDate()).slice(-2);
        }
        let url = saleData.id ? '/sales/' + saleData.id : '/sales';
        let method = saleData.id ? 'PUT' : 'POST';
        $scope.isLoading = true;
        $http({
            method: method,
            url: url,
            data: saleData
        })
            .then(function (response) {
                if (response.data.status == true) {
                    $scope.isLoading = false;
                    $scope.message = response.data.message;
                    // window.location.href = 'http://localhost:8000/sale/' + response.data.sale_id + '/edit';
                    window.open('http://localhost:8000/sale_print/' + response.data.sale_id, '_blank', 'width=800,height=600').print();
                }
                if (response.data.status == false) {
                    // Reset all previous errors

                    if (response.data.message && typeof response.data.message === 'object') {
                        // Directly assign errors
                        $scope.quantityErrors = response.data.message;
                    }
                }
            })
            .catch(function (error) {
                $scope.error = error.data.errors;
                $scope.isLoading = false;
            });
    };
    $scope.getReturnTotal = function () {
        return $scope.sale.items
            .filter(item => item.is_return === 1)
            .reduce((total, item) => total + (parseFloat(item.amount) || 0), 0)
            .toFixed(2);
    };

    $scope.getSaleTotal = function () {
        return $scope.sale.items
            .filter(item => item.is_return !== 1)
            .reduce((total, item) => total + (parseFloat(item.amount) || 0), 0)
            .toFixed(2);
    };

});
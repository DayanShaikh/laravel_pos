angular.module('purchase', ['ngAnimate']).controller('purchaseController',
	function ($scope, $http, $interval, $filter, $location) {
		$scope.categories = [];
		$scope.suppliers = [];
		$scope.items = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.item_number = "";
		$scope.accounts = [];
		$scope.purchase_id = 0;
		$scope.numberMask = "";
		$scope.purchase = {
			date: '',
			supplier_id: 0,
			items: [],
			quantity: 0,
			total: 0,
			sale_price: 0,
			discount: 0,
			net_total: 0,
			notes: ''
		};
		$scope.item = {
			"item_id": undefined,
			"item_category_id": 0,
			"purchase_price": 0,
			"sale_price": 0,
			"purchase_quantity": 0,
			"quantity": 0,
			"total": 0,

		};

		$scope.fetchData = function () {
			$http.get('/api/get_data').then(function (response) {
				$scope.suppliers = response.data.suppliers;
				$scope.items = response.data.items;
				$scope.purchase.date = response.data.date;
			}, function (error) {
				console.error("Error fetching data", error);
			});
		};
		$scope.fetchData();

		function getPurchaseIdFromUrl() {
			var pathSegments = $location.absUrl().split('/');
			var purchaseIndex = pathSegments.indexOf('edit');
			if (purchaseIndex > -1 && pathSegments[purchaseIndex + 1]) {
				return pathSegments[purchaseIndex + 1];
			}
			return null;
		}
		$scope.purchase_id = getPurchaseIdFromUrl() || 0;

		angular.element(document).ready(function () {
			if ($scope.purchase_id > 0) {
				$http.get('/api/purchase/show/' + $scope.purchase_id).then(function (response) {
					$scope.purchase = response.data.purchase;
					$scope.purchase.date = new Date($scope.purchase.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'numeric', year: 'numeric' });
				}, function (error) {
					console.error("Error fetching data", error);
				});
			}
			else {
				$scope.wctAJAX({ action: 'get_datetime' }, function (response) {
					$scope.purchase.date = JSON.parse(response);
				});
				$scope.wctAJAX({ action: 'get_date' }, function (response) {
					$scope.purchase.date_added = JSON.parse(response);
				});
				$scope.purchase.items.push(angular.copy($scope.item));
			}
			setTimeout(function () { init_date_picker(); }, 200);
		});

		$scope.get_action = function () {
			if ($scope.purchase_id > 0) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}

		$scope.add = function (position) {
			$scope.purchase.items.splice(position + 1, 0, angular.copy($scope.item));
			$scope.update_grand_total();
			setTimeout(function () { init_date_picker(); }, 200);
		}

		$scope.remove = function (position) {
			if ($scope.purchase.items.length > 1) {
				$scope.purchase.items.splice(position, 1);
			}
			else {
				$scope.purchase.items = [];
				$scope.purchase.items.push(angular.copy($scope.item));
			}
			$scope.update_grand_total();
		}
		$scope.getItems = function (item, index) {
			var foundItem = $filter('filter')($scope.items, { id: item }, true)[0];
			console.log(foundItem);
			index.purchase_price = foundItem.unit_price;
		}

		$scope.update_total = function (position) {
			$scope.purchase.items[position].total = parseFloat($scope.purchase.items[position].purchase_price) * parseFloat($scope.purchase.items[position].quantity);
			$scope.update_grand_total();
		}

		$scope.update_grand_total = function () {
			total = 0;
			quantity = 0;
			for (i = 0; i < $scope.purchase.items.length; i++) {
				total += parseFloat($scope.purchase.items[i].total);
				quantity += parseFloat($scope.purchase.items[i].quantity);
			}
			$scope.purchase.total = total;
			$scope.purchase.quantity = quantity;
			$scope.update_net_total();
		}

		$scope.update_net_total = function () {
			$scope.purchase.net_total = parseFloat($scope.purchase.total) - parseFloat($scope.purchase.discount);
		}

		$scope.purchase_return = function (position) {
			if ($scope.purchase.items[position].return == true) {
				$scope.purchase.items[position].quantity = (0 - $scope.purchase.items[position].quantity)
			}
			else {
				$scope.purchase.items[position].quantity = (0 - $scope.purchase.items[position].quantity)
			}
			$scope.update_total(position);
		}
		if ($scope.purchase_id > 0) {
			$scope.wctAJAX = function (wctData, wctCallback) {
				wctRequest = {
					method: 'POST',
					url: '/api/purchase/update/'+ $scope.purchase_id,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					transformRequest: function (obj) {
						var str = [];
						for (var p in obj) {
							str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
						}
						return str.join("&");
					},
					data: wctData
				}
				$http(wctRequest).then(function (wctResponse) {
					wctCallback(wctResponse.data);
				}, function () {
					console.log("Error in fetching data");
				});
			}

			$scope.save_purchase = function () {
				$scope.errors = [];
				if (!$scope.processing) {
					$scope.processing = true;
					data = { action: '/api/purchase/update/'+ $scope.purchase_id, purchase: JSON.stringify($scope.purchase) };
					$scope.wctAJAX(data, function (response) {
						$scope.processing = false;
						if (response.status == 1) {
							window.location.reload(true);
							$scope.successMessage = response.message;
						}
						else {
							$scope.errors = response.error;
							// $scope.errorMessage = response.data.message;
						}
					});
				}
			}
		} else {
			$scope.wctAJAX = function (wctData, wctCallback) {
				wctRequest = {
					method: 'POST',
					url: '/api/purchase/store',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					transformRequest: function (obj) {
						var str = [];
						for (var p in obj) {
							str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
						}
						return str.join("&");
					},
					data: wctData
				}
				$http(wctRequest).then(function (wctResponse) {
					wctCallback(wctResponse.data);
				}, function () {
					console.log("Error in fetching data");
				});
			}

			$scope.save_purchase = function () {
				$scope.errors = [];
				if (!$scope.processing) {
					$scope.processing = true;
					data = { action: '/api/purchase/store', purchase: JSON.stringify($scope.purchase) };
					$scope.wctAJAX(data, function (response) {
						$scope.processing = false;
						if (response.status == 1) {
							window.history.back();
							$scope.successMessage = response.message;
						}
						else {
							$scope.errors = response.error;
							// $scope.errorMessage = response.data.message;
						}
					});
				}
			}
		}
		$scope.print_barcode = function (id) {
			$("<iframe>")
				.hide()
				.attr("src", "index.php?tab=print_receipt&id=" + id)
				.appendTo("body");
		}

		$scope.$watch('purchase.supplier_id', function (newValue, oldValue) {
			if (newValue == "") {
				$scope.purchase.supplier.name = "";
			} else {
				console.log(newValue);
				var supplier = $filter('filter')($scope.suppliers, { id: newValue }, true);
				if (supplier.length > 0) {
					$scope.purchase.supplier.name = supplier[0].name;
				} else {
					$scope.purchase.supplier.name = "";
				}
			}
		});
	}
).directive('convertToNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function (val) {
				return val != null ? parseInt(val, 10) : null;
			});
			ngModel.$formatters.push(function (val) {
				return val != null ? '' + val : null;
			});
		}
	};
});
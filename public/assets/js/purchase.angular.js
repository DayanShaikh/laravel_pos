let purchaseApp = angular.module('purchase', ['ngAnimate']);
purchaseApp.directive('select2', function ($timeout) {
	return {
		restrict: 'A',
		link: function (scope, element) {
			$timeout(function () {
				$(element).select2();
			});
		}
	};
});
purchaseApp.controller('purchaseController',
	function ($scope, $http, $interval, $filter, $location) {
		$scope.categories = [];
		$scope.suppliers = [];
		$scope.items = [];
		$scope.processing = false;
		$scope.item_number = "";
		$scope.accounts = [];
		$scope.purchase_id = 0;
		$scope.numberMask = "";
		$scope.purchase = {
			date: new Date(),
			supplier_id: 0,
			items: [],
			quantity: 0,
			total: 0,
			sale_price: 0,
			discount: 0,
			net_total: 0,
			notes: '',
			is_return: 0,
		};
		$scope.item = {
			"item_id": undefined,
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
			}, function (error) {
				console.error("Error fetching data", error);
			});
		};
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
			$scope.fetchData();
			if ($scope.purchase_id > 0) {
				$http.get('/api/purchase/show/' + $scope.purchase_id).then(function (response) {
					$scope.purchase = response.data.purchase;
					$scope.purchase.date = new Date($scope.purchase.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'numeric', year: 'numeric' });
				}, function (error) {
					console.error("Error fetching data", error);
				});
			}
			else {
				$scope.purchase.items.push(angular.copy($scope.item));
			}
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

		$scope.save_purchase = function () {
			$scope.processing = true;
			if ($scope.purchase_id > 0) {
				$http.post('/api/purchase/update/' + $scope.purchase_id, $scope.purchase).then(function (response) {
					$scope.processing = false;
					if (response.status == 1) {
						window.location.reload() + encodeURIComponent(response.message);
					}
					else {
						$scope.errors = response.error;
					}
				}, function (error) {
					$scope.errors = error.data.errors;
					$scope.isLoading = false;
					$scope.processing = false;
				});
				$scope.processing = true;
			} else {
				$http.post('/api/purchase/store', $scope.purchase).then(function (response) {
					$scope.processing = false;
					if (response.data.status == 1) {
						window.location.href = '/purchase/edit/' + response.data.id + '/?message=' + encodeURIComponent(response.data.message);
					}
					else {
						$scope.errors = response.data.error;
					}
				}, function (error) {
					$scope.errors = error.data.errors;
					$scope.isLoading = false;
					$scope.processing = false;
				});
			}
		}

		$scope.print_barcode = function (id) {
			$("<iframe>").hide().attr("src", "index.php?tab=print_receipt&id=" + id).appendTo("body");
		}
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
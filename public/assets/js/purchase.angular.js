angular.module('purchase', ['ngAnimate']).controller('purchaseController', 

	function ($scope, $http, $interval, $filter) {

		$scope.categories = [];

		$scope.suppliers = [];

		$scope.items=[];

		$scope.errors = [];

		$scope.processing = false;

		$scope.item_number = "";

		$scope.accounts = [];

		$scope.purchase_id = 0;

		$scope.numberMask= "";
		$scope.purchase = {
			id: 0,
			datetime_added: '',
			supplier: angular.copy($scope.supplier),
			items: [],
			quantity: 0,
			total: 0,
			sale_price:0, 
			discount: 0,
			bilty: 0,
			brokery: 0,
			net_total: 0,
			notes: ''
		};

		$scope.item = {
			"id": 0,
			"item_id": undefined,
			"item_category_id": 0,
			"purchase_price": 0,
			"sale_price": 0,
			"purchase_quantity": 0,
			"quantity": 0,
			"total": 0,
			"unit_id": ''
			// "return": 0

		};

		$scope.item_name_mask = "99";

		$scope.updateDate = function(){

			$scope.purchase.datetime_added = $(".angular-datetimepicker").val();

			$scope.$apply();

		}

		angular.element(document).ready(function () {

			$scope.wctAJAX( {action: 'get_accounts'}, function( response ){

				$scope.accounts = response;

			});

			$scope.wctAJAX( {action: 'get_categories'}, function( response ){

				$scope.categories = response;

			});

			$scope.wctAJAX( {action: 'get_suppliers'}, function( response ){

				$scope.suppliers = response;

			});

			

			$scope.wctAJAX( {action: 'get_items'}, function( response ){

				$scope.items = response;

			});

			if( $scope.purchase_id > 0 ) {

				$scope.wctAJAX( {action: 'get_purchase', id: $scope.purchase_id}, function( response ){

					$scope.purchase = response;

				});

			}

			else {

				$scope.wctAJAX( {action: 'get_datetime'}, function( response ){

					$scope.purchase.datetime_added = JSON.parse( response );

				});

				$scope.wctAJAX( {action: 'get_date'}, function( response ){

					$scope.purchase.date_added = JSON.parse( response );

				});

				$scope.purchase.items.push( angular.copy( $scope.item ) );

			}

			setTimeout(function(){init_date_picker();}, 200);



		});

		

		$scope.get_action = function(){

			if( $scope.purchase_id > 0 ) {

				return 'Edit';

			}

			else {

				return 'Add New';

			}

		}

		

		$scope.add = function( position ){

			$scope.purchase.items.splice(position+1, 0, angular.copy( $scope.item ) );

			$scope.update_grand_total();

			setTimeout(function(){init_date_picker();}, 200);

		}

		

		$scope.remove = function( position ){

			if( $scope.purchase.items.length > 1 ){

				$scope.purchase.items.splice( position, 1 );

			}

			else {

				$scope.purchase.items = [];

				$scope.purchase.items.push( angular.copy( $scope.item ) );

			}

			$scope.update_grand_total();

		}		

		$scope.getItems = function( item, index ) {

			var foundItem = $filter('filter')($scope.items, { id: item }, true)[0];

			console.log(foundItem);

			index.purchase_price = foundItem.unit_price;

		}

		$scope.update_total = function( position ) {

			$scope.purchase.items[ position ].total = parseFloat( $scope.purchase.items[ position ].purchase_price ) * parseFloat( $scope.purchase.items[ position ].quantity );

			$scope.update_grand_total();

		}

		$scope.update_grand_total = function(){

			total = 0;

			quantity = 0;

			for( i = 0; i < $scope.purchase.items.length; i++ ) {

				total += parseFloat( $scope.purchase.items[ i ].total );

				quantity += parseFloat( $scope.purchase.items[ i ].quantity );

			}

			$scope.purchase.total = total;

			$scope.purchase.quantity = quantity;

			$scope.update_net_total();

		}

		$scope.update_net_total = function(){

			$scope.purchase.net_total = parseFloat( $scope.purchase.total ) - parseFloat( $scope.purchase.discount ) - parseFloat( $scope.purchase.bilty) + parseFloat( $scope.purchase.brokery );

		}

		$scope.purchase_return = function( position ) {

			if($scope.purchase.items[ position ].return == true){

				$scope.purchase.items[ position ].quantity = (0-$scope.purchase.items[ position ].quantity)

			}

			else{

				$scope.purchase.items[ position ].quantity = (0-$scope.purchase.items[ position ].quantity)

			}

			$scope.update_total(position);

		}

		$scope.wctAJAX = function( wctData, wctCallback ) {

			wctData.tab = 'addedit';

			wctRequest = {

				method: 'POST',

				url: 'purchase_manage.php',

				headers: {'Content-Type': 'application/x-www-form-urlencoded'},

				transformRequest: function(obj) {

					var str = [];

					for(var p in obj){

						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));

					}

					return str.join("&");

				},

				data: wctData

			}

			$http(wctRequest).then(function(wctResponse){

				wctCallback(wctResponse.data);

			}, function () {

				console.log("Error in fetching data");

			});

		}

		$scope.save_purchase = function () {

			$scope.errors = [];

			if( $scope.processing == false ){

				$scope.processing = true;

				data = {action: 'save_purchase', purchase: JSON.stringify( $scope.purchase )};

				$scope.wctAJAX( data, function( response ){

					$scope.processing = false;

					if( response.status == 1 ) {

						window.location.href='purchase_manage.php?tab=list=';

					}

					else{

						$scope.errors = response.error;

					}

				});

			}

		}

		

		$scope.print_barcode = function( id ) {

			$("<iframe>")

				.hide()

				.attr("src", "index.php?tab=print_receipt&id="+id)

				.appendTo("body"); 

		}

		$scope.$watch( 'purchase.supplier.id', function( newValue, oldValue ){

			if( newValue == "" ) {

				$scope.purchase.supplier.name = angular.copy($scope.supplier.name);

				$scope.purchase.supplier.supplier_code = angular.copy($scope.supplier.supplier_code);

				$scope.purchase.supplier.address = angular.copy($scope.supplier.address);

				$scope.purchase.supplier.phone = angular.copy($scope.supplier.phone);

				//$scope.purchase.purchase_price_mask = '9999999';

			}

			else {

				var supplier = $filter('filter')($scope.suppliers, {id: newValue}, true );

				if( supplier.length > 0 ) {

					supplier = supplier[0];

					$scope.purchase.supplier.name = supplier.name;

					$scope.purchase.supplier.supplier_code = supplier.supplier_code;

					$scope.purchase.supplier.address = supplier.address;

					$scope.purchase.supplier.phone = supplier.phone;

				}

			}

		})

	}

).directive('convertToNumber', function() {

	return {

		require: 'ngModel',

		link: function(scope, element, attrs, ngModel) {

			ngModel.$parsers.push(function(val) {

				return val != null ? parseInt(val, 10) : null;

			});

			ngModel.$formatters.push(function(val) {

				return val != null ? '' + val : null;

			});

		}

	};

});
<?php 
	include 'header.php';
?>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 col-md-2 sidebar">
		<form role="search">
			<div class="form-group">
				<!-- <input type="text" class="form-control" placeholder="Search"> -->
			</div>
		</form>
		<ul class="nav menu">
			<li class="active">
				<a href="#">
					<svg class="glyph stroked dashboard-dial">
						<use xlink:href="#stroked-dashboard-dial"></use>
					</svg>
					Dashboard
				</a>
			</li>
			<li class="parent ">
				<a href="#">
					<span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></use></svg></span> Transaction 
				</a>
				<ul class="children collapse" id="sub-item-1">
					<li>
						<a class="" href="reservation">
							<svg class="glyph stroked eye">
								<use xlink:href="#stroked-eye"/>
							</svg>
							Reservations
						</a>
					</li>
					<li>
						<a class="" href="new">
							<svg class="glyph stroked plus sign">
								<use xlink:href="#stroked-plus-sign"/>
							</svg>
							New
						</a>
					</li>
					<li>
						<a class="" href="borrow">
							<svg class="glyph stroked download">
								<use xlink:href="#stroked-download"/>
							</svg>
							Borrowed Items
						</a>
					</li>
					<li>
						<a class="" href="return">
							<svg class="glyph stroked checkmark">
								<use xlink:href="#stroked-checkmark"/>
							</svg>
							Returned Items
						</a>
					</li>
				</ul>
			</li>
			<?php if($_SESSION['admin_type'] == 1){ ?>
			<li>
				<a href="items">
					<svg class="glyph stroked desktop">
						<use xlink:href="#stroked-desktop"/>
					</svg>
					Item
				</a>
			</li>
			<li>
				<a href="members">
					<svg class="glyph stroked male user ">
						<use xlink:href="#stroked-male-user"/>
					</svg>
					Borrower
				</a>
			</li>
			<li>
				<a href="room">
					<svg class="glyph stroked app-window">
						<use xlink:href="#stroked-app-window"></use>
					</svg>
					Room
				</a>
			</li>
			<li>
				<a href="inventory">
					<svg class="glyph stroked clipboard with paper">
						<use xlink:href="#stroked-clipboard-with-paper"/>
					</svg>
					Inventory
				</a>
			</li>
			<li>
				<a href="report">
					<svg class="glyph stroked line-graph">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-line-graph"/>
					</svg>
					Graph
				</a>
			</li>
			<li>
				<a href="user">
					<svg class="glyph stroked female user">
						<use xlink:href="#stroked-female-user"/>
					</svg>
					User
				</a>
			</li>
			<?php 
				}
				($_SESSION['admin_type'] == 1) ? include('include_history.php') : false;
			 ?>
			 <!-- <li>
				<a href="setting">
					<svg class="glyph stroked gear">
						<use xlink:href="#stroked-gear"></use>
					</svg>
					Setting
				</a>
			</li> -->
		</ul>
	</div><!--/.sidebar-->
	<div class="row-fluid">
		<div class="col-md-12 main">
			<div class="col-sm-9 col-lg-10 col-md-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-3">
				
				
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Dashboard</h1>
					</div>
				</div><!--/.row-->

				<!-- <div class="row">
					<div class="col-xs-12 col-md-6 col-lg-3">
						<div class="panel panel-blue panel-widget ">
							<div class="row no-padding">
								<div class="col-sm-3 col-lg-5 widget-left">
									<i class="fa fa-hourglass-half fa-3x"></i>
								</div>
								<div class="col-sm-9 col-lg-7 widget-right">
									<div class="large peding_val">120</div>
									<div class="text-muted">Pending reservation</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-3">
						<div class="panel panel-orange panel-widget">
							<div class="row no-padding">
								<div class="col-sm-3 col-lg-5 widget-left">
									<i class="fa fa-thumbs-up fa-3x"></i>
								</div>
								<div class="col-sm-9 col-lg-7 widget-right">
									<div class="large accept_val">52</div>
									<div class="text-muted">Accepted reservation</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-3">
						<div class="panel panel-teal panel-widget">
							<div class="row no-padding">
								<div class="col-sm-3 col-lg-5 widget-left">
									<i class="fa fa-ban fa-3x"></i>
								</div>
								<div class="col-sm-9 col-lg-7 widget-right">
									<div class="large cancel_val">24</div>
									<div class="text-muted">Cancelled Reservation</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-3">
						<div class="panel panel-red panel-widget">
							<div class="row no-padding">
								<div class="col-sm-3 col-lg-5 widget-left">
									<i class="fa fa-user fa-3x"></i>
								</div>
								<div class="col-sm-9 col-lg-7 widget-right">
									<div class="large active_user">25.2k</div>
									<div class="text-muted">Number of active clients</div>
								</div>
							</div>
						</div>
					</div>
				</div><!--/.row-->

				<!-- <hr/>
				<div class="row">

					<div class="col-md-12 col-xs-12 col-sm-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="text-white">Inventory item</h4>
							</div>
							<div class="panel-body">
								<div class="col-md-12" id="inventory" style="height: 500px;"></div>
							</div>
						</div>
					</div>

				<hr/>
					<div class="col-md-12 col-xs-12 col-sm-12">
						<div class="panel panel-blue">
							<div class="panel-heading dark-overlay">
								History Logs
							</div>
							<div class="panel-body">
								<ul class="todo-list">
								
								</ul>
							</div>
						</div>
					</div> -->

				<!-- /div> -->
				<div class="row">
					<div class="col-sm-offset-3 col-sm-6">
						<h2 style="text-align:center;">Calendar of Reservation</h2>
						<div id="calendar"></div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<table class="table table_dashboard">
						<thead>
							<tr>
								<td><strong>Model</strong></td>
								<td><strong>Category</strong></td>
								<td><strong>Brand</strong></td>
								<td><strong>Description</strong></td>
								<td><strong>Quantity</strong></td>
								<td><strong>Status</strong></td>
								
								
							</tr>
						</thead>
					</table>
				</div>

<hr/>
			</div>
		</div>
		
	</div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#calendar').fullCalendar({
	      header: {
	        left: 'prev,next today',
	        center: 'title',
	        right: 'month,agendaWeek,agendaDay'
	      },
	      buttonText: {
	        today: 'today',
	        month: 'month',
	        week: 'week',
	        day: 'day'
	      },
	      events: {
	      	url: '../class/display/display',
	      	type: "POST",
	      	data: {
	      		key: "load_reservations_json"
	      	}
	      },
	      editable: false,
	      droppable: false
	    });
	});
</script>
<script>

$.ajax({
	type: "POST",
	url: "../class/display/display",
	data: {
			key: "chart_inventory"
		}
	})
.done(function(data){
	console.log(data);
	var provider = JSON.parse(data);
	var chart = AmCharts.makeChart("inventory", {
  "type": "pie",
  "startDuration": 0,
   "theme": "light",
  "addClassNames": true,
  "legend":{
   	"position":"right",
    "marginRight":100,
    "autoMargins":false
  },
  "innerRadius": "30%",
  "defs": {
    "filter": [{
      "id": "shadow",
      "width": "200%",
      "height": "200%",
      "feOffset": {
        "result": "offOut",
        "in": "SourceAlpha",
        "dx": 0,
        "dy": 0
      },
      "feGaussianBlur": {
        "result": "blurOut",
        "in": "offOut",
        "stdDeviation": 5
      },
      "feBlend": {
        "in": "SourceGraphic",
        "in2": "blurOut",
        "mode": "normal"
      }
    }]
  },
  "dataProvider": provider ,
  "valueField": "litres",
  "titleField": "country",
  "export": {
    "enabled": true
  }
});

chart.addListener("init", handleInit);

chart.addListener("rollOverSlice", function(e) {
  handleRollOver(e);
});

function handleInit(){
  chart.legend.addListener("rollOverItem", handleRollOver);
}

function handleRollOver(e){
  var wedge = e.dataItem.wedge.node;
  wedge.parentNode.appendChild(wedge);
}
});

</script>

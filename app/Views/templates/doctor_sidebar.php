<style type="text/css">
	
	.names1, .names2, .names3{
		margin-top: 10px;
	}
</style>



<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="#">
          <span class="align-middle">Pawsome Furiends</span>
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-header" style="font-size:18px;">
						Veterinarian
					</li>
				
					<li class="sidebar-item active" id="dashboard">
						<a class="sidebar-link" href="<?= base_url('dashboard'); ?>">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>
					<p></p>

					  <div class="names1">
					  	<p style="margin-left: 20px; color: lightgray;">PATIENT CARE</p>
					  </div>


						<div class="divider"></div>
					<li class="sidebar-header dropBtn" data-id="invoice">
						<i class="align-middle awIcon" data-feather="briefcase"></i> <span style="font-size:14px;">Appointments</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="invoice">

				
					<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('appointment/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Appointments</span>
			            </a>
					</li>
				

					</div>
					
			



                    	<div class="divider"></div>
					<li class="sidebar-header dropBtn" data-id="slots">
						<i class="align-middle awIcon" data-feather="clock"></i> <span style="font-size:14px;">Time Slots</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="slots">

					
					<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('slot/add'); ?>">
			              <i class="align-middle" data-feather="edit"></i> <span class="align-middle">Add/All Slots</span>
			            </a>
					</li>
					
			
					</div>




						<div class="divider"></div>
					<li class="sidebar-header dropBtn" data-id="purchases" style="display: none;">
						<i class="align-middle awIcon" data-feather="user"></i> <span style="font-size:14px;">Pet Owners</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="purchases">
	                <li class="sidebar-item" style="display: none;">
						<a class="sidebar-link" href="<?= site_url('doctor/patient/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Pet Owners</span>
			            </a>
					</li>
					<li class="sidebar-item" style="display: none;">
						<a class="sidebar-link" href="<?= site_url('doctor/patient/all'); ?>">
			              <i class="align-middle" data-feather="user"></i> <span class="align-middle">All Pet Owners</span>
			            </a>
					</li>



					</div>
					
					<div class="divider"></div>
					<li class="sidebar-header dropBtn" data-id="records" >
					<i class="align-middle awIcon" data-feather="folder"></i> <span style="font-size:14px;">Records</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>

					<div class="awDrop" id="records" >
							<!-- <li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('records/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Records</span>
			            </a>
					</li> -->
						
							<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('records/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Records</span>
			            </a>
					</li>
				</div>


						<div class="divider"></div>
					<li class="sidebar-header dropBtn" data-id="expenses">
						<i class="align-middle awIcon" data-feather="trending-up"></i> <span style="font-size:14px;">Services</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="expenses">

					
				
					<div class="divider"></div>
					<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('service/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Services</span>
			            </a>
					</li>
						<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('service/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Services</span>
			            </a>
					</li>
					</div>



					  <div class="names2">
					  	<p style="margin-left: 20px; color: lightgray;">FACILITY</p>
					  </div>

					  <p></p>

					<div class="divider"></div>

					<li class="sidebar-header dropBtn" data-id="confinement">
					<i class="align-middle awIcon" data-feather="home"></i> <span style="font-size:14px;">Confinement</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="confinement">
					<li class="sidebar-item drop">
						<a class="sidebar-link" href="<?= base_url('confinement/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Confinement</span>
			            </a>
					</li>

					
					</li>
					<li class="sidebar-item drop">
						<a class="sidebar-link" href="<?= base_url('confinement/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Confinement</span>
			            </a>
					</li>
					
					</div>

					

					<div class="divider"></div>

					<li class="sidebar-header dropBtn" data-id="client">
					<i class="align-middle awIcon" data-feather="plus"></i> <span style="font-size:14px;">Inventory</span> <i class="align-middle" data-feather="chevron-down"></i>
					</li>

					<div class="awDrop" id="client">

					<li class="sidebar-item drop ">
						<a class="sidebar-link" href="<?= site_url('medicne/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Items</span>
			            </a>
					</li>

					<li class="sidebar-item drop ">
						<a class="sidebar-link" href="<?= site_url('medicne/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Items</span>
			            </a>
					</li>
					
					</div>


					
					 <div class="names3">
					  	<p style="margin-left: 20px; color: lightgray;">BUSINESS</p>
					  </div>
										
					<p></p>


					
					<div class="divider"></div>

					<li class="sidebar-header dropBtn" data-id="account">
					<i class="align-middle awIcon" data-feather="credit-card"></i> <span style="font-size:14px;">Invoice</span><i class="align-middle" data-feather="chevron-down"></i>
					</li>
					<div class="awDrop" id="account">
					<li class="sidebar-item drop">
						<a class="sidebar-link" href="<?= base_url('invoice/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Add Invoice</span>
			            </a>
					</li>

					
					</li>
					<li class="sidebar-item drop">
						<a class="sidebar-link" href="<?= base_url('invoice/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Invoice</span>
			            </a>
					</li>
					
				
					</div>

		

				
					<div class="divider"></div>

						<li class="sidebar-header dropBtn" data-id="sales">
					<i class="align-middle awIcon" data-feather="truck"></i> <span style="font-size:14px;">Sales</span> <i class="align-middle" data-feather="chevron-down"></i>
					</li>

					<div class="awDrop" id="sales" >
							<li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('sales/add'); ?>">
			              <i class="align-middle" data-feather="plus"></i> <span class="align-middle">Generate Sales</span>
			            </a>
					</li>
						
							<!-- <li class="sidebar-item">
						<a class="sidebar-link" href="<?= site_url('sales/all'); ?>">
			              <i class="align-middle" data-feather="list"></i> <span class="align-middle">All Sales</span>
			            </a>
					</li> -->
			
				
				</div>
				
					
			
                    
                

					<div class="divider"></div>
			

				</ul>

				<div class="sidebar-cta">
					
				</div>
			</div>
		</nav>

		<style>
		.dropBtn{
	    cursor: pointer;
	    padding: 1rem 1.5rem 1rem;position: relative;
		}
		.awDrop{display: none}
		.divider {
    width: calc(100% - 2rem);
    margin: auto;
    border-top: 1px solid #313c48;
}
.dropBtn:hover,.aw-active{
	background: linear-gradient(90deg,rgba(59,125,221,.1),rgba(59,125,221,.088) 50%,transparent);
	border-left:3px solid #3b7ddd;
}

 .dropBtn .feather{
    position: absolute;
    right: 20px;
}
.awIcon{position: relative !important;margin-left: 1.5rem}

		</style>

<script>
	$(document).ready(function(){
		$(".sidebar-item").on("click", function(){
			$(".sidebar-item").removeClass('active');
			$(this).addClass('active');

		});
	

		
		$(".dropBtn").click(function(){
			var id=$(this).data('id');
			$("#"+id).slideToggle();
		});

		$('.sidebar-item').click(function(){
			
			localStorage.ids=$(this).parent().attr('id');

		});

		if(localStorage.ids != 'undefined'){
			$('#'+localStorage.ids).show();
			$('li').removeClass('active');
			
			$('[data-id='+localStorage.ids+']').addClass('aw-active');}
			else{
				$('#dashboard').addClass('active');

			}


	});
</script>
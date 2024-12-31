<style>
	#legal{
		margin-left: 20px;
		color: gray;
		font-weight: 1px solid gray;
	}
</style>
</main>
			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>Pawsome Furiends</strong></a> &copy; <b></b>
								<a href="<?= base_url('admin/terms/view_all') ?>" id="legal">Legal Documents</a>

							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	
	<script src="<?= BASEURL ?>public/js/app.js"></script>
    
    <script>
    
    
		$(document).ready(function () {
		    
             $('#mytable').DataTable({responsive: true});
		});

		
	</script>
	
	
    
</body>


</html>
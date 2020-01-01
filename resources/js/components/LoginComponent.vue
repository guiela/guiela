<template>
  <div class="container mt-5 pt-5">
    <div class="row justify-content-center mt-5">
			<div class="col-md-8">
				<form method="POST" action="#">
					<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

							<div class="col-md-6">
									<input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus>

									<span class="invalid-feedback" role="alert">
											<strong></strong>
									</span>
							</div>
					</div>

					<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

							<div class="col-md-6">
									<input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">

									<span class="invalid-feedback" role="alert">
											<strong></strong>
									</span>
							</div>
					</div>

					<div class="form-group row">
							<div class="col-md-6 offset-md-4">
									<div class="form-check">
											<input class="form-check-input" type="checkbox" name="remember" id="remember">

											<label class="form-check-label" for="remember">
													Remember Me
											</label>
									</div>
							</div>
					</div>

					<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
									<button type="submit" class="btn btn-primary">
											Login
									</button>
							</div>
					</div>
				</form>
			</div>
    </div>
</div>
</template>

<script>
	export default {
		name: 'login-component'
	}
</script>

<style>

</style>
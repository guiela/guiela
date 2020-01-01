<template>
  <div class="container mt-5 pt-5 mb-5">
    <div class="row justify-content-center mt-5">
			<div class="col-md-9">
				<form method="POST" @submit.prevent="onSubmit">

						<div class="form-group row">
								<label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

								<div class="col-md-6">
										<input id="name" type="text" class="form-control" v-model="form.name" required autocomplete="name" autofocus>

										<span class="invalid-feedback" role="alert">
											<strong></strong>
										</span>
								</div>
						</div>

						<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

								<div class="col-md-6">
										<input id="email" type="email" class="form-control" v-model="form.email" value="" required autocomplete="email">

								</div>
						</div>

						<div class="form-group row">
								<label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

								<div class="col-md-6">
										<input id="password" type="password" class="form-control" v-model="form.password" required autocomplete="new-password">

								</div>
						</div>

						<div class="form-group row">
								<label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

								<div class="col-md-6">
										<input id="password-confirm" type="password" class="form-control" v-model="form.password_confirmation" required autocomplete="new-password">
								</div>
						</div>

						<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
										<button type="submit" class="btn btn-primary">
											Register
										</button>
								</div>
						</div>
				</form>
			</div>
    </div>
</div>
</template>

<script>
	import Form from '../helpers/Form'

	export default {
		name: 'register-component',
		data() {
			return {
				form: new Form({
					name: null,
					email: null,
					password: null,
					password_confirmation: null
				})
			}
		},

		methods: {
			onSubmit()
			{
				this.form.post('/api/register').then(data => {
					// Display an info toast with no title
					window.toastr.success('Your logged in!')
					this.$router.push({ name: 'dashboard'})
				})
			}
		}
	}
</script>

<style>

</style>
<template>
  <div>
		<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top border-bottom shadow-sm">
			<router-link class="navbar-brand" :to="{ name: 'home' }">
				<img src="/img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
				Guiela
			</router-link>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">

				<ul class="navbar-nav mr-auto"></ul>

				<ul class="navbar-nav my-2 my-lg-0">
					<li class="nav-item">
						<router-link :to="{ name: 'home' }" exact class="nav-link">Home</router-link>
					</li>
					<li class="nav-item">
						<router-link :to="{ name: 'dashboard' }" class="nav-link">Dashboard</router-link>
					</li>
					<li class="nav-item">
						<router-link :to="{ name: 'howTo' }" class="nav-link">How To</router-link>
					</li>
					<!-- <li class="nav-item">
						<router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
					</li>
					<li class="nav-item">
						<router-link :to="{ name: 'register' }" class="nav-link">Register</router-link>
					</li> -->
				</ul>
			</div>
		</nav>

		<router-view></router-view>

		<footer class="text-muted bg-white fixed-bottom">
			<div class="container-fluid">
				<hr />
				<p class="float-right">
					<a href="#">Back to top</a>
				</p>
				<p>Developed and maintained with <span style="color: red"><i class="fas fa-heart"></i></span> by <a href="https://www.facebook.com/groups/DevCOuagadougou" title="Facebook Developer Circle, Ouagadougou" target="_blank">Facebook Developer Circle, Ouagadougou</a> and the good folks of <a href="#" title="+226 54778644"><strong>Nascent Africa</strong></a>, Burkina Faso.</p>
			</div>
		</footer>
  </div>
</template>

<script>
	export default {
		name: 'app-component'
	}
</script>

<style>
	footer {
		padding-top: 0;
		padding-bottom: 1rem;
	}

	footer p {
		margin-bottom: .25rem;
	}
</style>
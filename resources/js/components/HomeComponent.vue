<template>
  <!-- Full Page Image Header with Vertically Centered Content -->
	<header class="masthead">
		<div class="container h-100">
			<div class="row h-100 align-items-center">
				<div class="col-12 text-center">
					<img src="/img/logo.jpeg" width="160px" height="auto" />
					<h1 class="font-weight-bold">Guiela</h1>
					<p class="lead">Opensource project for automated bank reconciliations.</p>
				</div>
			</div>
		</div>
	</header>
</template>

<script>
	export default {
		name: 'home-component'
	}
</script>

<style scoped>
	.masthead {
		height: 78vh;
		min-height: 500px;
		background-image: url('/img/wallpaper.jpg');
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
	}
</style>
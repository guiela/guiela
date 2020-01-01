<template>
	<div class="d-flex justify-content-center pt-5">
		<div :class="`fa-${iconSize}x`">
			<i class="fas fa-cog fa-spin"></i>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'page-spinner-component',
		props: ['iconSize']
	}
</script>

<style>

</style>
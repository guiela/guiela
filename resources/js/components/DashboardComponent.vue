<template>
  <div class="container-fluid" style="padding-bottom: 100px">
		<div class="row mb-5">
			<div class="col-12 pt-3">
				<form @submit.prevent="onSubmit">
					<b-tabs content-class="mt-3 mb-3">
						<b-tab title="Upload" active>
							<div class="row">
								<file-component name="Domestic File" @file-converted="onConvertedDomesticFile">
									<template v-slot:help>
										Please select a file to upload for the domestic input.
									</template>
								</file-component>

								<file-component name="Foreign File" @file-converted="onConvertedForeignFile">
									<template v-slot:help>
										Please select a file to upload for the foreign input.
									</template>
								</file-component>

							</div>
						</b-tab>
						<b-tab title="Constraints">
							<div class="row">
								<div class="col-md-6">
									<label for="inputPassword">Identifier</label>
									<v-select multiple v-model="form.identifiers" :options="matchable" :disabled="!isMatchable" />
									<small id="passwordHelpBlock" class="form-text text-muted">
										An identifier is like an ID will be used to differentiate each row of the file.
									</small>

									<label for="inputPassword">Value Pairs</label>
									<v-select multiple v-model="form.pairs" :options="pairable" :disabled="!isMatchable" />
									<small id="passwordHelpBlock" class="form-text text-muted">
										The value pairs enables the system identify a row based on the value of its identifier.
									</small>
								</div>

								<div class="col-md-6">
									<label for="inputPassword">Matchers</label>
									<v-select v-model="form.matcher" :options="matchable" :disabled="!isMatchable" />
									<small id="passwordHelpBlock" class="form-text text-muted">
										A matcher is the record to be compared.
									</small>
								</div>
							</div>
						</b-tab>
					</b-tabs>

					<button type="submit" class="btn btn-success btn-block" :disabled="isSubmitting">
						<i v-if="isSubmitting" class="fas fa-cog fa-spin"></i>
						<i v-else class="fas fa-paper-plane"></i> Submit</button>
				</form>
			</div>
		</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<button class="btn btn-secondary btn-sm mr-2" @click="onClearFiles">Clear Files</button>
		</div>

		<div class="col-md-6">
			<div class="d-flex justify-content-end">

				<div class="custom-control custom-radio custom-control-inline">
					<input type="radio" id="grid-view" value="6" v-model="view" class="custom-control-input" checked>
					<label class="custom-control-label" for="grid-view"><i class="fas fa-th-large" title="Grid view"></i> Grid</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input type="radio" id="stacked-view" value="12" v-model="view" class="custom-control-input">
					<label class="custom-control-label" for="stacked-view"><i class="fas fa-bars" title="Stacked view"></i> Stack</label>
				</div>
			</div>
		</div>
	</div>

		<section class="row">
			<div :class="`col-md-${view}`">
				<table-component v-if="getDomestic" :csv="getDomestic" width="'450px'" 
					@delete-record="onDomesticRecordDelete"></table-component>
			</div>

			<div :class="`col-md-${view}`">
				<table-component v-if="getForeign" :csv="getForeign" width="'450px'"
					@delete-record="onForeignRecordDelete"></table-component>
			</div>
		</section>
	</div>
</template>

<script>
	import Errors from '../helpers/Errors'

	export default {
		name: 'dashboard-component',

		data() {
			return {
				view: '6',

				errors: new Errors(),

				isSubmitting: false,

				form: {
					identifiers: [],
					pairs: [],
					domestic: null,
					foreign: null,
					matcher: null
				}
			}
		},

		methods: {

			/**
			 * 
			 */
			onConvertedDomesticFile(value) {
				this.form.domestic = value
				this.updateDomesticAction(value)
			},

			/**
			 * 
			 */
			onConvertedForeignFile(value) {
				this.form.foreign = value
				this.updateForeignAction(value)
			},

			/**
			 * 
			 */
			onSubmit() {

				this.isSubmitting = true

				axios.post('/api/compare/csv', this.form)
					.then(response => {

						this.updateDomesticAction(response.data.domestic)

						this.updateForeignAction(response.data.foreign)

						this.isSubmitting = false
					
					}).catch((error) => {

						this.errors.record(error.response.data.errors);

						this.isSubmitting = false

					})
			},

			/**
			 * 
			 */
			isInvalid(field)
			{
				return this.errors.has(field) ? ' is-invalid' : ''
			},

			/**
			 * 
			 */
			onDomesticRecordDelete(uuid) {
				this.deleteDomesticRecordAction(uuid)
			},

			/**
			 * 
			 */
			onForeignRecordDelete(uuid) {
				this.deleteForeignRecordAction(uuid)
			},

			/**
			 * 
			 */
			onClearFiles()
			{
				this.updateDomesticAction(null)

				this.updateForeignAction(null)
			}
		},

		computed: {

			/**
			 * This filters through the headers and return the 
			 * values that are present in both headers.
			 * 
			 * @returns array
			 */
			matchable() {

				if (this.form.domestic && this.form.foreign) {
					return _.intersection(this.form.domestic.header, this.form.foreign.header);
				}

				return [];
			},

			/**
			 * 
			 */
			isMatchable() {
				return Boolean (this.form.domestic && this.form.foreign) && this.matchable.length >= 1
			},

			/**
			 * 
			 */
			pairable() {
				var domestic = []
				var matchable = this.form.identifiers
				var pairable = []

				if (matchable.length > 0 ) {
					for(var a = 0; a < matchable.length; a++) {
						for(var b = 0; b < this.form.domestic.records.length; b++) {
							
							var c = this.form.domestic.records[b]

							pairable = [...pairable, c[matchable[a].toLowerCase()]]
						}
					}
				}

				return _.uniq(pairable)
			}
		},
	}
</script>

<style>

</style>
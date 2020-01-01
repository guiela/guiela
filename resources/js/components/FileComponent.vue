<template>
  <div class="col-md-6">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Delimiter</label>
					<select v-model="form.delimiter" :class="`custom-select custom-select-sm${isInvalid('delimiter')}`">
						<option :value="null" selected>Select delimiter</option>
						<option value=",">Comma ( , )</option>
						<option value="|">Pipe ( | )</option>
						<option value=";">Semicolon ( ; )</option>
					</select>

					<small v-if="errors.has('delimiter')" class="invalid-feedback" v-text="errors.get('delimiter')"></small>

					<small id="fileHelp" class="form-text text-muted">
						Select a delimeter.
					</small>
				</div>
			</div>

			<div class="col-md-9">
				<div class="form-group">
					<label :for="`${name}File`" v-text="name"></label>
				
					<div class="custom-file">
						<input type="file" @change="onUpload" :class="`custom-file-input form-control-sm${isInvalid('file')}`" 
							:disabled="! form.delimiter" :id="`${name}File`">

						<label class="custom-file-label" :for="`${name}File`">Choose file</label>
						<small v-if="errors.has('file')" class="invalid-feedback" v-text="errors.get('file')"></small>
					</div>


					<small id="emailHelp" class="form-text text-muted">
						<slot name="help"></slot>
					</small>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Errors from '../helpers/Errors'

	export default {
		name: 'file-component',

		props: ['name'],

		data() {
			return {
				errors: new Errors(),
				form: {
					delimiter: null,
					file: null
				}
			}
		},

		methods: {
			/**
			 * 
			 */
			onUpload()
			{				
				var files = event.target.files || event.dataTransfer.files;
				if (! files.length ) return;

				this.form.file = files[0]

				this.convertFile()
			},

			/**
			 * 
			 */
			convertFile()
			{
				var form = new FormData();

				form.append('file', this.form.file);
				form.append('delimiter', this.form.delimiter);

				axios.post('/api/convert/csv', form, {headers: {'Content-Type': 'multipart/form-data'}}).then((response) => {

					this.$emit('file-converted', response.data);
					
				}).catch((error) => {

        	this.errors.record(error.response.data.errors);

				})
			},

			/**
			 * 
			 */
			isInvalid(field)
			{
				return this.errors.has(field) ? ' is-invalid' : ''
			}
		}
	}
</script>

<style>

</style>
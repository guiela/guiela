<template>
  <div class="container" style="padding-bottom: 100px">
		<div class="row mb-5">
			<div class="col-12 pt-5">
				<div class="card">
					<div class="card-body">
						<h3>Upload</h3>
						<hr />

						<h4>
							Intro
						</h4>

						<span class="text-info">If you simply want to test the application with a file containing dummy data, you can download <a href="/download/file-1.csv"><strong>file 1</strong></a> and <a href="/download/file-2.csv"><strong>file 2</strong></a> to help you get started.</span>
						<p>
							Guiela does not require a login or registration before use, all you need are the two CSVs you wish to compare.
						</p>

						<h4> Delimiter </h4>
						<p>
							Visit the dashboard page, you will see two file input fields, this however does not get disabled unless you select a delimiter. <strong>Delimiter</strong> refers to what is used to demarcate your CSV conent.
							In most cases this will be a "comma (,)" but in a situation where its not, the delimiter field allows you to specify which is the accurate delimiter.
						</p>

						<h4>File</h4>
						<p>
							After you select a delimiter, the file field will be enabled and selecting a file is a consept we all know too well but you have to keep a few things in mind.
							The first file input field is labeled "Domestic", and to ensure accurate result it is imperative you upload your internal CSV records with the domestic input field and the external with the "Foreign" input field.
						</p>

						<h3>Constraints</h3>
						<hr />

						<h4>Identifiers</h4>
						<p>
							The <strong>identifier</strong> is the column used by Guiela to find a record on each of the CSV and compare their values.
						</p>

						<h4>Matchers</h4>
						<p>
							The <strong>matcher</strong> is the column that should be equal on both CSVs.
						</p>

						<h4>Value pairs</h4>
						<p>
							This is used to specify explicitly which record is to be matched. Only the row with the values selected will be matched. You can match one or more columns if you want.

						</p>
					</div>
				</div>
			</div>
		</div>
  </div>
</template>

<script>
	export default {

	}
</script>

<style>

</style>
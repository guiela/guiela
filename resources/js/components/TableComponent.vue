<template>
	<div>
		<ul class="list-group mb-2">
			<li class="list-group-item d-flex justify-content-between align-items-center">
				Number of Headers
				<span class="text-muted">{{ csv.meta['header']['count'] }}</span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center">
				Number of Records
				<span class="text-muted">{{ csv.meta['records']['count'] }}</span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center">
				Uploaded
				<span class="text-muted">{{ timeAgo }}</span>
			</li>
		</ul>

		<div class="table-responsive" :style="width">
			<table class="table table-hover table-bordered table-sm">

					<caption v-text="csv.meta['name']"></caption>

				<thead class="thead-light">
					<tr>
						<th scope="col" style="width: 5%">#</th>
						<th v-for="(header, index) in csv.header" :key="index">
							<div class="custom-control custom-checkbox custom-control-inline">
								<input type="checkbox" :id="header" :name="header" class="custom-control-input" checked>
								<label class="custom-control-label" :for="header" v-text="header"></label>
							</div>
						</th>
						<th scope="col" style="width: 12px"></th>
						<th scope="col" style="width: 12px"></th>
					</tr>
				</thead>
				<tbody class="bg-white">
					<tr v-for="(record, index) in csv.records" :class="record.hasOwnProperty('meta') ? record.meta.css_style : null" :key="record.uuid">
						<th scope="row" v-text="(index + 1)"></th>
						<td v-for="(item, index) in record.original_data" :key="index" v-text="item"></td>

						<td v-if="record.hasOwnProperty('meta')">
							<b-button size="sm" @click="$bvModal.show(record.uuid)"><i class="fas fa-chart-bar"></i></b-button>

							<b-modal size="xl" :id="record.uuid" title="Matching Row">
								<table class="table table-hover table-bordered table-sm">
									<thead class="thead-dark">
										<tr>
											<th scope="col" style="width: 5%">#</th>
											<th v-for="(header, index) in record.meta.match.header" :key="index">
												<div class="custom-control custom-checkbox custom-control-inline">
													<input type="checkbox" :id="header" :name="header" class="custom-control-input" checked>
													<label class="custom-control-label" :for="header" v-text="header"></label>
												</div>
											</th>
										</tr>
									</thead>
									<tbody class="bg-white">
										<tr>
											<th scope="row" v-text="1"></th>
											<td v-for="(item, index) in record.meta.match.record.original_data" :key="index">
												<span v-if="typeof item === 'string'">
												{{ item }}
												</span>
											</td>
										</tr>
									</tbody>
								</table>
							</b-modal>
						</td>
						<td v-else></td>
						
						<td>
							<button class="btn btn-danger btn-sm" @click="$emit('delete-record', record.uuid)"><i class="fas fa-times"></i></button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	var moment = require('moment');
	moment().format();

	export default {
		name: 'table-component',
		props: ['csv', 'width'],
		computed: {
			timeAgo()
			{
				return moment(this.csv.meta['uploaded_at']).fromNow()
			}
		}
	}
</script>

<style scope>
	.list-group-item {
		position: relative;
		display: block;
		padding: 0.25rem 1.25rem;
	}
</style>
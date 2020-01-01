export default {
	state: {
		domestic: null,

		foreign: null
	},
  
	mutations: {
		updateDomestic: (state, data) => {
			state.domestic = data;
		},

		updateForeign: (state, data) => {
			state.foreign = data;
		},

		deleteDomesticRecord: (state, uuid) => {
			var index = state.domestic.records.findIndex(data => data.uuid === uuid);

			state.domestic.records.splice(index, 1);

			// Display an info toast with no title
			window.toastr.success('Domestic record removed!')
		},

		deleteForeignRecord: (state, uuid) => {
			var index = state.foreign.records.findIndex(data => data.uuid === uuid);

			state.foreign.records.splice(index, 1);

			// Display an info toast with no title
			window.toastr.success('Foreign record removed!')
		}
	},

	actions: {
		updateDomesticAction({ commit }, data) {
			commit('updateDomestic', data)
		},

		updateForeignAction({ commit }, data) {
			commit('updateForeign', data)
		},

		deleteDomesticRecordAction({ commit }, uuid) {
			commit('deleteDomesticRecord', uuid)
		},

		deleteForeignRecordAction({ commit }, uuid) {
			commit('deleteForeignRecord', uuid)
		}
	},

	getters: {
		getDomestic: state => state.domestic,

		getForeign: state => state.foreign
	}
}
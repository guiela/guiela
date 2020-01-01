import { mapActions, mapGetters } from 'vuex'

const mixin = {
	methods: {
		...mapActions([
			'updateDomesticAction', 'updateForeignAction', 
			'deleteDomesticRecordAction', 'deleteForeignRecordAction'
		]),
	},

	computed: {
		...mapGetters(['getDomestic', 'getForeign']),
	}
}

export default mixin
<template>
	<lit-base-field :field="field" :model="model">
		<template v-if="!field.readonly">
			<vue-ctk-date-time-picker
				:id="`${field.id}-${makeid(10)}`"
				:value="value"
				:label="field.label"
				:format="format"
				:no-label="true"
				:inline="field.inline"
				:formatted="field.formatted"
				:only-date="field.only_date"
				:only-time="field.only_time"
		                :right="field.right"
				color="var(--primary)"
				v-on:input="$emit('input', $event)"
			/>
		</template>
		<template v-else>
			<b-input class="form-control" :value="value" type="text" readonly />
		</template>
	</lit-base-field>
</template>

<script>
export default {
	name: 'FieldDateTime',
	props: {
		field: {
			required: true,
			type: Object,
		},
		model: {
			required: true,
			type: Object,
		},
		value: {
			required: true,
		},
	},
	data() {
		return {
			datetimeString: '',
		};
	},
	computed: {
		format() {
			if (this.field.only_time) {
				return 'HH:mm:ss';
			}

			return 'YYYY-MM-DD HH:mm:ss';
		},
	},
	methods: {
		makeid(length) {
			var result = '';
			var characters =
				'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			var charactersLength = characters.length;
			for (var i = 0; i < length; i++) {
				result += characters.charAt(
					Math.floor(Math.random() * charactersLength)
				);
			}
			return result;
		},
	},
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
@import '~vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

.date-time-picker {
	input {
		display: inline-block !important;
		width: 100% !important;
		height: 2.5rem !important;
		min-height: 2.5rem !important;
		padding: $input-padding-y $input-padding-x !important;
		font-size: $input-font-size !important;
		font-weight: 400 !important;
		line-height: 1.6 !important;
		color: $input-color !important;
		vertical-align: middle !important;
		background-color: $input-bg !important;
		border: $input-border-width solid $input-border-color !important;
		border-radius: $input-border-radius !important;
		-webkit-appearance: none !important;
	}
	.datepicker-buttons-container {
		.datepicker-button {
			&.now {
				.datepicker-button-effect {
					background: $primary;
				}
				.datepicker-button-content {
					color: $primary;
				}
			}
			.datepicker-button-effect {
				background: $success;
			}
			svg {
				fill: $success;
			}
		}
	}
}
</style>

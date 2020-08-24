<script>
export default {
	name: 'Blade',
	render(createElement) {
		const render = {
			template: this.getTemplate(createElement),
			props: Object.keys(this.$attrs),
		};
		return createElement(render, {
			props: { ...this.$attrs },
		});
	},
	props: {
		view: {
			required: true,
			type: String,
		},
		wrapper: {},
	},
	methods: {
		getTemplate(createElement) {
			if (this.wrapper) {
				return (
					'<lit-base-component :component="' +
					this.escapeHtmlSpecialChars(JSON.stringify(this.wrapper)) +
					'">' +
					this.view +
					'</lit-base-component>'
				);
			}
			return '<div class="w-100">' + this.view + '</div>';
		},
		escapeHtmlSpecialChars(str) {
			return str
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		},
	},
};
</script>

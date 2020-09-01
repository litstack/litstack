<template>
	<div class="d-inline-block">
		<b-button variant="primary" @click="visible = !visible">
			<lit-fa-icon icon="plus" />
			{{ __('lit.add_model', { model: 'Lit ' + __('lit.user') }) }}
		</b-button>
		<b-modal
			v-model="visible"
			:title="__('lit.add_model', { model: 'Lit ' + __('lit.user') })"
		>
			<b-form-group
				:label="__('lit.enter_username')"
				label-for="username"
			>
				<b-form-input
					id="username"
					v-model="user.username"
					trim
				></b-form-input>
				<b-form-invalid-feedback :state="usernameState">
					{{ error('username') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<b-form-group :label="__('base.first_name')" label-for="first_name">
				<b-form-input
					id="first_name"
					v-model="user.first_name"
					trim
				></b-form-input>
				<b-form-invalid-feedback :state="first_name">
					{{ error('first_name') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<b-form-group :label="__('base.last_name')" label-for="last_name">
				<b-form-input
					id="last_name"
					v-model="user.last_name"
					trim
				></b-form-input>
				<b-form-invalid-feedback :state="lastnameState">
					{{ error('last_name') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<b-form-group
				:label="__('lit.enter_email')"
				label-for="email"
				:state="emailState"
			>
				<b-form-input
					id="email"
					v-model="user.email"
					trim
				></b-form-input>
				<b-form-invalid-feedback :state="emailState">
					{{ error('email') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<b-form-group
				:label="__('lit.enter_password')"
				label-for="password"
				:state="passwordState"
			>
				<b-input-group class="mt-3">
					<b-form-input
						id="password"
						v-model="user.password"
						trim
					></b-form-input>
					<b-input-group-append>
						<b-button
							variant="outline-secondary"
							@click="makePassword"
						>
							<lit-fa-icon icon="sync" />
						</b-button>
					</b-input-group-append>
				</b-input-group>

				<b-progress
					class="mt-2"
					height="2px"
					:value="score"
					:max="4"
					:variant="variant"
				></b-progress>
				<b-form-invalid-feedback :state="passwordState">
					{{ error('password') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<b-form-checkbox
				v-model="user.sendResetLink"
				name="check-button"
				switch
			>
				{{ __('lit.user_reset_link') }}
			</b-form-checkbox>
			<template v-slot:modal-footer>
				<div class="w-100">
					<b-button
						variant="primary"
						size="sm"
						class="float-right"
						@click="storeUser"
						:disabled="
							!passwordState ||
								!emailState ||
								!usernameState ||
								!lastnameState ||
								!firstnameState
						"
					>
						<lit-fa-icon icon="user" />
						{{
							__(`lit.create_model`, {
								model: 'Lit ' + __('lit.user'),
							})
						}}
						<b-spinner
							label="Loading..."
							small
							v-if="busy"
						></b-spinner>
					</b-button>
				</div>
			</template>
		</b-modal>
	</div>
</template>

<script>
export default {
	name: 'UserCreate',
	data() {
		return {
			visible: false,
			user: {
				username: '',
				first_name: '',
				last_name: '',
				email: '',
				password: this.keygen(20),
				sendResetLink: true,
			},
			errors: [],
			busy: false,
		};
	},
	methods: {
		async storeUser() {
			this.busy = true;
			let response = null;
			try {
				response = await axios.post('/lit/register', this.user);
			} catch (e) {
				this.errors = e.response.data.errors;
				this.busy = false;
				return;
			}

			this.$emit('userCreated', response.data);
			this.busy = false;

			this.visible = false;
			this.init();
			this.$bvToast.toast(
				this.__('lit.model_saved', { model: 'Lit User' }),
				{
					variant: 'success',
				}
			);
		},
		error(key) {
			if (this.errors.hasOwnProperty(key)) {
				return this.errors[key].join(', ');
			}
		},
		init() {
			this.user = {
				username: '',
				first_name: '',
				last_name: '',
				email: '',
				password: this.keygen(20),
				sendResetLink: false,
			};
		},
		makePassword() {
			this.user.password = this.keygen(20);
		},
		keygen(length) {
			var result = '';
			var characters =
				'!§$%&()?+-_.ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			var charactersLength = characters.length;
			for (var i = 0; i < length; i++) {
				result += characters.charAt(
					Math.floor(Math.random() * charactersLength)
				);
			}
			return result;
		},
	},
	computed: {
		score() {
			return zxcvbn(this.user.password).score;
		},
		firstnameState() {
			if (this.errors.hasOwnProperty('first_name')) {
				return false;
			}
			return this.user.first_name.length > 0;
		},
		lastnameState() {
			if (this.errors.hasOwnProperty('last_name')) {
				return false;
			}
			return this.user.last_name.length > 0;
		},
		usernameState() {
			if (this.errors.hasOwnProperty('username')) {
				return false;
			}
			return this.user.username.length > 0;
		},
		passwordState() {
			return this.score == 4;
		},
		variant() {
			switch (this.score) {
				case 0:
				case 1:
					return 'danger';
					break;
				case 2:
				case 3:
					return 'warning';
					break;
				case 4:
					return 'success';
					break;
			}
		},
		emailState() {
			let valid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(
				this.user.email
			);

			if (!valid) {
				return false;
			}
			if (this.errors.hasOwnProperty('email')) {
				return false;
			}
			return true;
		},
		email() {
			return this.user.email;
		},
		name() {
			return this.user.name;
		},
	},
	watch: {
		email(val) {
			if (this.errors.hasOwnProperty('email')) {
				Vue.delete(this.errors, 'email');
			}
		},
		name(val) {
			if (this.errors.hasOwnProperty('name')) {
				Vue.delete(this.errors, 'name');
			}
		},
	},
};
</script>

<style></style>

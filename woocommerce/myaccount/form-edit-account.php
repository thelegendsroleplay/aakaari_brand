<?php
/**
 * Edit account form - Custom Professional Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<!-- Personal Information Section -->
	<div class="form-section" style="padding-top: 0; margin-top: 0; border-top: none;">
		<h3 style="margin: 0 0 1.5rem; font-size: 1.125rem; color: #000; font-weight: 600;">Personal Information</h3>

		<div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
			<!-- First Name -->
			<div class="form-group">
				<label for="account_first_name" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					First Name <span class="required" style="color: #dc2626;">*</span>
				</label>
				<input
					type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="account_first_name"
					id="account_first_name"
					autocomplete="given-name"
					value="<?php echo esc_attr( $user->first_name ); ?>"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>

			<!-- Last Name -->
			<div class="form-group">
				<label for="account_last_name" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					Last Name <span class="required" style="color: #dc2626;">*</span>
				</label>
				<input
					type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="account_last_name"
					id="account_last_name"
					autocomplete="family-name"
					value="<?php echo esc_attr( $user->last_name ); ?>"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>

			<!-- Display Name -->
			<div class="form-group">
				<label for="account_display_name" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					Display Name <span class="required" style="color: #dc2626;">*</span>
				</label>
				<input
					type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="account_display_name"
					id="account_display_name"
					value="<?php echo esc_attr( $user->display_name ); ?>"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
				<span style="display: block; margin-top: 0.5rem; font-size: 0.75rem; color: #666;">This will be how your name will be displayed in the account section and in reviews</span>
			</div>

			<!-- Email Address -->
			<div class="form-group">
				<label for="account_email" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					Email Address <span class="required" style="color: #dc2626;">*</span>
				</label>
				<input
					type="email"
					class="woocommerce-Input woocommerce-Input--email input-text"
					name="account_email"
					id="account_email"
					autocomplete="email"
					value="<?php echo esc_attr( $user->user_email ); ?>"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>
		</div>
	</div>

	<!-- Change Password Section -->
	<div class="form-section" style="padding-top: 2rem; margin-top: 2rem; border-top: 1px solid #e5e7eb;">
		<h3 style="margin: 0 0 0.5rem; font-size: 1.125rem; color: #000; font-weight: 600;">Change Password</h3>
		<p style="margin: 0 0 1.5rem; font-size: 0.875rem; color: #666;">Leave blank to keep your current password</p>

		<div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
			<!-- Current Password -->
			<div class="form-group">
				<label for="password_current" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					Current Password
				</label>
				<input
					type="password"
					class="woocommerce-Input woocommerce-Input--password input-text"
					name="password_current"
					id="password_current"
					autocomplete="off"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>

			<!-- New Password -->
			<div class="form-group">
				<label for="password_1" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					New Password
				</label>
				<input
					type="password"
					class="woocommerce-Input woocommerce-Input--password input-text"
					name="password_1"
					id="password_1"
					autocomplete="off"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>

			<!-- Confirm New Password -->
			<div class="form-group">
				<label for="password_2" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #333;">
					Confirm New Password
				</label>
				<input
					type="password"
					class="woocommerce-Input woocommerce-Input--password input-text"
					name="password_2"
					id="password_2"
					autocomplete="off"
					style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.2s;"
					onfocus="this.style.borderColor='#000'"
					onblur="this.style.borderColor='#e5e7eb'"
				/>
			</div>
		</div>
	</div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<!-- Form Actions -->
	<div class="form-actions" style="display: flex; gap: 1rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; align-items: center;">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button
			type="submit"
			class="woocommerce-Button button"
			name="save_account_details"
			value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"
			style="padding: 0.75rem 2rem; background: #000; color: #fff; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
			onmouseover="this.style.background='#333'"
			onmouseout="this.style.background='#000'"
		>
			<?php esc_html_e( 'Save Changes', 'woocommerce' ); ?>
		</button>
		<input
			type="hidden"
			name="action"
			value="save_account_details"
		/>
		<p style="margin: 0; font-size: 0.75rem; color: #666;">
			<span class="required" style="color: #dc2626;">*</span> Required fields
		</p>
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
